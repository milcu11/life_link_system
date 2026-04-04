<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total_donors' => Donor::count(),
            'lives_saved' => Donation::where('status', 'completed')->count(),
            'partner_hospitals' => User::where('role', 'hospital')->count(),
            'emergency_support' => '24/7', // This is always available
        ];

        return view('welcome', compact('stats'));
    }
}