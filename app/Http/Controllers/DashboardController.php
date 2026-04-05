<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Matching;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        \Log::info('Dashboard access', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'is_admin' => $user->isAdmin(),
            'is_hospital' => $user->isHospital(),
            'is_donor' => $user->isDonor(),
            'has_donor_relation' => $user->donor ? true : false,
        ]);
        
        if ($user->isAdmin()) {
            \Log::info('Redirecting admin to admin dashboard');
            return redirect()->route('admin.dashboard');
        } elseif ($user->isHospital()) {
            \Log::info('Showing hospital dashboard');
            return $this->hospitalDashboard($user);
        } elseif ($user->isDonor() || $user->donor) {
            \Log::info('Showing donor dashboard');
            return $this->donorDashboard($user);
        } else {
            \Log::info('User has no valid role, redirecting to login');
            Auth::logout();
            return redirect('/login')->with('error', 'Your account is not properly configured. Please contact support.');
        }
    }
    
    private function donorDashboard($user)
    {
        $donor = $user->donor;
        
        if (!$donor) {
            return redirect()->route('donor.profile')->with('info', 'Please complete your donor profile first.');
        }
        
        // Check if donor is verified
        if (!$donor->is_verified && !$donor->rejection_reason) {
            return redirect()->route('donor.denied')->with('info', 'Your registration is pending admin approval. Please wait or check back later.');
        }
        
        // Check if donor was rejected
        if ($donor->rejection_reason) {
            return redirect()->route('donor.denied');
        }
        
        $stats = [
            'total_donations' => $donor->donations()->where('status', 'completed')->count(),
            'pending_matches' => $donor->matchings()->where('status', 'pending')->count(),
            'can_donate' => $donor->canDonate(),
            'last_donation' => $donor->last_donation_date?->format('M d, Y') ?? 'Never',
        ];
        
        // only include donations with an existing blood request to prevent null-relations
        $recentDonations = $donor->donations()
            ->whereHas('request')
            ->with('request.hospital') // eager load hospital too since view needs it
            ->latest()
            ->take(5)
            ->get();
        
        $pendingMatches = $donor->matchings()
            ->with('request.hospital')
            ->where('status', 'pending')
            ->latest()
            ->get();
        
        return view('donor.dashboard', compact('donor', 'stats', 'recentDonations', 'pendingMatches'));
    }
    
    private function hospitalDashboard($user)
    {
        $stats = [
            'active_requests' => BloodRequest::where('hospital_id', $user->id)
                ->where('status', 'pending')
                ->count(),
            'fulfilled_requests' => BloodRequest::where('hospital_id', $user->id)
                ->where('status', 'fulfilled')
                ->count(),
            'total_matches' => Matching::whereHas('request', function($q) use ($user) {
                $q->where('hospital_id', $user->id);
            })->count(),
            'total_inventory' => \App\Models\BloodInventory::where('hospital_id', $user->id)->sum('quantity'),
            'low_stock_items' => \App\Models\BloodInventory::where('hospital_id', $user->id)->lowStock()->count(),
            'expiring_soon_items' => \App\Models\BloodInventory::where('hospital_id', $user->id)->expiringSoon()->count(),
        ];
        
        $recentRequests = BloodRequest::where('hospital_id', $user->id)
            ->with('matchings')
            ->latest()
            ->take(5)
            ->get();
        
        $urgentRequests = BloodRequest::where('hospital_id', $user->id)
            ->whereIn('urgency_level', ['high', 'critical'])
            ->where('status', 'pending')
            ->latest()
            ->get();
        
        return view('hospital.dashboard', compact('stats', 'recentRequests', 'urgentRequests'));
    }
}