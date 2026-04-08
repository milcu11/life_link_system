<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\User;
use App\Models\Matching;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_donors' => Donor::count(),
            'active_donors' => Donor::where('is_available', true)->count(),
            'total_requests' => BloodRequest::count(),
            'pending_requests' => BloodRequest::where('status', 'pending')->count(),
            'total_donations' => Donation::where('status', 'completed')->count(),
            'total_users' => User::count(),
        ];

        $recentRequests = BloodRequest::with('hospital')
            ->latest()
            ->take(5)
            ->get();

        $recentDonations = Donation::with('donor.user')
            ->latest()
            ->take(5)
            ->get();

        // Get 6-month data for chart
        $chartData = $this->getSixMonthChartData();

        return view('admin.dashboard', compact('stats', 'recentRequests', 'recentDonations', 'chartData'));
    }

    private function getSixMonthChartData()
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();
        $now = Carbon::now()->endOfMonth();

        // Initialize months array
        $months = [];
        $currentDate = $sixMonthsAgo->copy();
        while ($currentDate <= $now) {
            $months[$currentDate->format('Y-m')] = $currentDate->format('M');
            $currentDate->addMonth();
        }

        // Get donors data grouped by month
        $donorsData = Donor::whereBetween('created_at', [$sixMonthsAgo, $now])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Get requests data grouped by month
        $requestsData = BloodRequest::whereBetween('created_at', [$sixMonthsAgo, $now])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Get matches data grouped by month
        $matchesData = Matching::whereBetween('created_at', [$sixMonthsAgo, $now])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill in missing months with 0
        $donors = [];
        $requests = [];
        $matches = [];

        foreach ($months as $monthKey => $monthLabel) {
            $donors[] = $donorsData[$monthKey] ?? 0;
            $requests[] = $requestsData[$monthKey] ?? 0;
            $matches[] = $matchesData[$monthKey] ?? 0;
        }

        return [
            'months' => array_values($months),
            'donors' => $donors,
            'requests' => $requests,
            'matches' => $matches,
        ];
    }

    public function donors()
    {
        $donors = Donor::with('user')
            ->latest()
            ->paginate(20);
        
        return view('admin.donors', compact('donors'));
    }

    public function requests()
    {
        $requests = BloodRequest::with('hospital')
            ->latest()
            ->paginate(20);
        
        return view('admin.requests', compact('requests'));
    }

    public function donations()
    {
        $donations = Donation::with(['donor.user', 'request'])
            ->latest()
            ->paginate(20);
        
        return view('admin.donations', compact('donations'));
    }

    public function users()
    {
        $users = User::latest()->paginate(20);
        
        return view('admin.users', compact('users'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User has been {$status}.");
    }

    public function map()
    {
        $donors = Donor::with('user')
            ->where('is_available', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $requests = BloodRequest::with('hospital')
            ->where('status', 'pending')
            ->get();

        return view('admin.map', compact('donors', 'requests'));
    }

    // Appeals management
    public function appeals()
    {
        $appeals = \App\Models\DonorAppeal::with('donor.user')->latest()->paginate(20);
        return view('admin.appeals.index', compact('appeals'));
    }

    public function appealShow(\App\Models\DonorAppeal $appeal)
    {
        $appeal->load('donor.user');
        return view('admin.appeals.show', compact('appeal'));
    }

    public function appealDownload(\App\Models\DonorAppeal $appeal)
    {
        if (!$appeal->attachment_path || !file_exists(storage_path('app/' . $appeal->attachment_path))) {
            abort(404, 'File not found.');
        }

        return response()->download(storage_path('app/' . $appeal->attachment_path));
    }

    public function appealReview(\Illuminate\Http\Request $request, \App\Models\DonorAppeal $appeal)
    {
        $request->validate(['admin_note' => 'nullable|string']);

        $action = $request->input('action');
        $appeal->admin_note = $request->input('admin_note');

        if ($action === 'approve') {
            $appeal->status = 'approved';
            // mark donor as verified if applicable
            if ($appeal->donor) {
                $appeal->donor->update(['is_verified' => true, 'verified_at' => now()]);
                try {
                    \Illuminate\Support\Facades\Mail::queue(new \App\Mail\DonorVerificationStatus($appeal->donor, true));
                } catch (\Exception $e) {
                    Log::error('Appeal approval email queue failed: ' . $e->getMessage(), ['appeal_id' => $appeal->id]);
                }
            }
        } else {
            $appeal->status = 'rejected';
            if ($appeal->donor) {
                try {
                    \Illuminate\Support\Facades\Mail::queue(new \App\Mail\DonorVerificationStatus($appeal->donor, false));
                } catch (\Exception $e) {
                    Log::error('Appeal rejection email queue failed: ' . $e->getMessage(), ['appeal_id' => $appeal->id]);
                }
            }
        }

        $appeal->save();

        return redirect()->route('admin.appeals')->with('success', 'Appeal reviewed.');
    }

    // Donor verification/approval methods
    public function donorShow(Donor $donor)
    {
        $donor->load('user');
        return view('admin.donor', compact('donor'));
    }

    public function approveDonor(Donor $donor)
    {
        try {
            $donor->update([
                'is_verified' => true,
                'verified_at' => now(),
            ]);

            if ($donor->user) {
                try {
                    Log::info('Queueing approval email to: ' . $donor->user->email);
                    Mail::queue(new \App\Mail\DonorVerificationStatus($donor, true));
                    Log::info('Approval email queued successfully for: ' . $donor->user->email);
                } catch (\Throwable $e) {
                    Log::error('Approval email queue failed: ' . $e->getMessage(), ['donor_id' => $donor->id, 'email' => $donor->user->email]);
                    return redirect()->back()->with('error', 'Donor approved, but email could not be queued.');
                }
            }

            return redirect()->back()->with('success', 'Donor approved successfully.');
        } catch (\Exception $e) {
            Log::error('Error approving donor: ' . $e->getMessage(), ['donor_id' => $donor->id]);
            return redirect()->back()->with('error', 'Error approving donor: ' . $e->getMessage());
        }
    }

    public function rejectDonor(Request $request, Donor $donor)
    {
        try {
            $request->validate(['reason' => 'nullable|string|max:1000']);

            $donor->update([
                'is_verified' => false,
                'rejection_reason' => $request->input('reason'),
            ]);

            if ($donor->user) {
                try {
                    Log::info('Queueing rejection email to: ' . $donor->user->email);
                    Mail::queue(new \App\Mail\DonorVerificationStatus($donor, false));
                    Log::info('Rejection email queued successfully for: ' . $donor->user->email);
                } catch (\Throwable $e) {
                    Log::error('Rejection email queue failed: ' . $e->getMessage(), ['donor_id' => $donor->id, 'email' => $donor->user->email]);
                    return redirect()->back()->with('error', 'Donor rejected, but email could not be queued.');
                }
            }

            return redirect()->back()->with('success', 'Donor rejected successfully.');
        } catch (\Exception $e) {
            Log::error('Error rejecting donor: ' . $e->getMessage(), ['donor_id' => $donor->id]);
            return redirect()->back()->with('error', 'Error rejecting donor: ' . $e->getMessage());
        }
    }

    public function downloadVerificationDocument(Donor $donor)
    {
        if (!$donor->verification_document_path) {
            return redirect()->back()->with('error', 'No document to download.');
        }

        $path = \Illuminate\Support\Facades\Storage::disk('private')->path($donor->verification_document_path);
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return response()->download($path);
    }

    // Hospital management methods
    public function hospitals()
    {
        $hospitals = User::where('role', 'hospital')
            ->withCount(['bloodRequests', 'bloodInventory'])
            ->latest()
            ->paginate(20);

        return view('admin.hospitals.index', compact('hospitals'));
    }

    public function createHospital()
    {
        return view('admin.hospitals.create');
    }

    public function storeHospital(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'hospital',
            'phone' => $request->phone,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_active' => true,
        ]);

        return redirect()->route('admin.hospitals.show', $user)->with('success', 'Hospital created successfully.');
    }

    public function showHospital(User $hospital)
    {
        if ($hospital->role !== 'hospital') {
            abort(404);
        }

        $hospital->load(['bloodRequests' => function($query) {
            $query->latest()->take(5);
        }, 'bloodInventory' => function($query) {
            $query->latest()->take(10);
        }]);

        $stats = [
            'total_requests' => $hospital->bloodRequests()->count(),
            'pending_requests' => $hospital->bloodRequests()->where('status', 'pending')->count(),
            'fulfilled_requests' => $hospital->bloodRequests()->where('status', 'fulfilled')->count(),
            'total_inventory' => $hospital->bloodInventory()->sum('quantity'),
        ];

        return view('admin.hospitals.show', compact('hospital', 'stats'));
    }

    public function editHospital(User $hospital)
    {
        if ($hospital->role !== 'hospital') {
            abort(404);
        }

        return view('admin.hospitals.edit', compact('hospital'));
    }

    public function updateHospital(Request $request, User $hospital)
    {
        if ($hospital->role !== 'hospital') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $hospital->id,
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        $hospital->update($request->only([
            'name', 'email', 'phone', 'location', 'latitude', 'longitude', 'is_active'
        ]));

        return redirect()->route('admin.hospitals.show', $hospital)->with('success', 'Hospital updated successfully.');
    }

    public function deleteHospital(User $hospital)
    {
        if ($hospital->role !== 'hospital') {
            abort(404);
        }

        // Check if hospital has active requests or inventory
        $hasRequests = $hospital->bloodRequests()->exists();
        $hasInventory = $hospital->bloodInventory()->exists();

        if ($hasRequests || $hasInventory) {
            return redirect()->back()->with('error', 'Cannot delete hospital with existing requests or inventory. Please archive instead.');
        }

        $hospital->delete();

        return redirect()->route('admin.hospitals')->with('success', 'Hospital deleted successfully.');
    }
}
