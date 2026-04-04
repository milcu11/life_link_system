<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\User;
use App\Models\Matching;
use Illuminate\Http\Request;
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
                    \Illuminate\Support\Facades\Mail::send(new \App\Mail\DonorVerificationStatus($appeal->donor, true));
                } catch (\Exception $e) {}
            }
        } else {
            $appeal->status = 'rejected';
            if ($appeal->donor) {
                try {
                    \Illuminate\Support\Facades\Mail::send(new \App\Mail\DonorVerificationStatus($appeal->donor, false));
                } catch (\Exception $e) {}
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
        $donor->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        try {
            \Illuminate\Support\Facades\Mail::send(new \App\Mail\DonorVerificationStatus($donor, true));
        } catch (\Exception $e) {
            // mail not configured
        }

        return redirect()->back()->with('success', 'Donor approved and verification email sent.');
    }

    public function rejectDonor(Request $request, Donor $donor)
    {
        $request->validate(['reason' => 'nullable|string|max:1000']);

        $donor->update([
            'is_verified' => false,
            'rejection_reason' => $request->input('reason'),
        ]);

        try {
            \Illuminate\Support\Facades\Mail::send(new \App\Mail\DonorVerificationStatus($donor, false));
        } catch (\Exception $e) {
            // mail not configured
        }

        return redirect()->back()->with('success', 'Donor rejected and notification email sent.');
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
}
