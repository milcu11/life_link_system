<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Matching;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatchingController extends Controller
{
    public function findMatches(BloodRequest $request)
    {
        // Get compatible blood types
        $compatibleTypes = $request->getCompatibleDonorBloodTypes();
        
        // Find available donors with compatible blood types
        $donors = Donor::whereIn('blood_type', $compatibleTypes)
            ->where('is_available', true)
            ->whereHas('user', function($query) {
                $query->where('is_active', true);
            })
            ->get();

        $matchesCreated = 0;

        foreach ($donors as $donor) {
            // Check if donor can donate (56 days rule)
            if (!$donor->canDonate()) {
                continue;
            }

            // Calculate distance using Haversine formula
            $distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $donor->latitude,
                $donor->longitude
            );

            // Calculate compatibility score (0-100)
            $compatibilityScore = $this->calculateCompatibilityScore(
                $request,
                $donor,
                $distance
            );

            // Create match
            $match = Matching::create([
                'request_id' => $request->id,
                'donor_id' => $donor->id,
                'compatibility_score' => $compatibilityScore,
                'distance' => $distance,
                'status' => 'pending',
                'notified_at' => now(),
            ]);

            // Create notification for donor
            Notification::create([
                'user_id' => $donor->user_id,
                'type' => $request->isUrgent() ? 'emergency' : 'in_app',
                'title' => 'New Blood Donation Request',
                'message' => "A {$request->urgency_level} priority request for {$request->blood_type} blood is available near you.",
                'request_id' => $request->id,
            ]);

            $matchesCreated++;
        }

        return redirect()->back()->with('success', "Found and notified {$matchesCreated} compatible donors!");
    }

    public function viewMatches(BloodRequest $request)
    {
        $matches = $request->matchings()
            ->with('donor.user')
            ->orderBy('compatibility_score', 'desc')
            ->get();
        
        return view('hospital.matches', compact('request', 'matches'));
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        if (is_null($lat2) || is_null($lon2)) {
            return 9999; // Return large distance if coordinates not available
        }

        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }

    private function calculateCompatibilityScore(BloodRequest $request, Donor $donor, $distance)
    {
        $score = 100;

        // Exact blood type match gets bonus
        if ($request->blood_type === $donor->blood_type) {
            $score += 20;
        }

        // Distance penalty (closer is better)
        if ($distance < 5) {
            $score += 15;
        } elseif ($distance < 10) {
            $score += 10;
        } elseif ($distance < 20) {
            $score += 5;
        } else {
            $score -= ($distance / 2);
        }

        // Recent donation history bonus
        if ($donor->donations()->where('status', 'completed')->count() > 5) {
            $score += 10;
        }

        // Urgency multiplier
        if ($request->urgency_level === 'critical') {
            $score *= 1.2;
        }

        return min(100, max(0, round($score)));
    }
}