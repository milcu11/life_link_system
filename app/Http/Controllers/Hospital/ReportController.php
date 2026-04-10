<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Matching;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('hospital.profile.complete');
    }

    public function index(Request $request)
    {
        $hospital = $request->user();

        // Request Fulfillment Rates (last 12 months)
        $fulfillmentData = $this->getFulfillmentRates($hospital->id);

        // Donor Response Times (average days from match to completion)
        $responseTimeData = $this->getResponseTimes($hospital->id);

        // Seasonal Trends (requests by month for last 12 months)
        $seasonalData = $this->getSeasonalTrends($hospital->id);

        // Success Metrics
        $successMetrics = $this->getSuccessMetrics($hospital->id);

        return view('hospital.reports.index', compact(
            'fulfillmentData',
            'responseTimeData',
            'seasonalData',
            'successMetrics'
        ));
    }

    public function export(Request $request)
    {
        $hospital = $request->user();
        $type = $request->get('type', 'csv');

        if ($type === 'csv') {
            return $this->exportCsv($hospital->id);
        }

        // For PDF, would need additional setup
        return redirect()->back()->with('error', 'Export type not supported yet.');
    }

    private function getFulfillmentRates($hospitalId)
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M Y');

            $total = BloodRequest::where('hospital_id', $hospitalId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $fulfilled = BloodRequest::where('hospital_id', $hospitalId)
                ->where('status', 'fulfilled')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $rate = $total > 0 ? round(($fulfilled / $total) * 100, 1) : 0;

            $data['labels'][] = $month;
            $data['rates'][] = $rate;
        }

        return $data;
    }

    private function getResponseTimes($hospitalId)
    {
        // Average response time in days from match creation to donation completion
        $avgTime = DB::table('matches')
            ->join('donations', function($join) {
                $join->on('matches.request_id', '=', 'donations.request_id')
                     ->on('matches.donor_id', '=', 'donations.donor_id');
            })
            ->join('blood_requests', 'matches.request_id', '=', 'blood_requests.id')
            ->where('blood_requests.hospital_id', $hospitalId)
            ->where('donations.status', 'completed')
            ->selectRaw('AVG(DATEDIFF(donations.updated_at, matches.created_at)) as avg_days')
            ->first();

        return [
            'average_days' => round($avgTime->avg_days ?? 0, 1)
        ];
    }

    private function getSeasonalTrends($hospitalId)
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('M');

            $requests = BloodRequest::where('hospital_id', $hospitalId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $donations = Donation::whereHas('request', function($q) use ($hospitalId) {
                $q->where('hospital_id', $hospitalId);
            })
            ->where('status', 'completed')
            ->whereYear('donation_date', $date->year)
            ->whereMonth('donation_date', $date->month)
            ->count();

            $data['labels'][] = $month;
            $data['requests'][] = $requests;
            $data['donations'][] = $donations;
        }

        return $data;
    }

    private function getSuccessMetrics($hospitalId)
    {
        $totalRequests = BloodRequest::where('hospital_id', $hospitalId)->count();
        $fulfilledRequests = BloodRequest::where('hospital_id', $hospitalId)->where('status', 'fulfilled')->count();
        $totalMatches = Matching::whereHas('request', function($q) use ($hospitalId) {
            $q->where('hospital_id', $hospitalId);
        })->count();
        $completedDonations = Donation::whereHas('request', function($q) use ($hospitalId) {
            $q->where('hospital_id', $hospitalId);
        })->where('status', 'completed')->count();

        $fulfillmentRate = $totalRequests > 0 ? round(($fulfilledRequests / $totalRequests) * 100, 1) : 0;
        $matchRate = $totalRequests > 0 ? round(($totalMatches / $totalRequests) * 100, 1) : 0;
        $donationRate = $totalMatches > 0 ? round(($completedDonations / $totalMatches) * 100, 1) : 0;

        return [
            'total_requests' => $totalRequests,
            'fulfilled_requests' => $fulfilledRequests,
            'total_matches' => $totalMatches,
            'completed_donations' => $completedDonations,
            'fulfillment_rate' => $fulfillmentRate,
            'match_rate' => $matchRate,
            'donation_rate' => $donationRate,
        ];
    }

    private function exportCsv($hospitalId)
    {
        $filename = 'hospital_report_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($hospitalId) {
            $file = fopen('php://output', 'w');

            // Success Metrics
            fputcsv($file, ['Success Metrics']);
            $metrics = $this->getSuccessMetrics($hospitalId);
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Total Requests', $metrics['total_requests']]);
            fputcsv($file, ['Fulfilled Requests', $metrics['fulfilled_requests']]);
            fputcsv($file, ['Total Matches', $metrics['total_matches']]);
            fputcsv($file, ['Completed Donations', $metrics['completed_donations']]);
            fputcsv($file, ['Fulfillment Rate (%)', $metrics['fulfillment_rate']]);
            fputcsv($file, ['Match Rate (%)', $metrics['match_rate']]);
            fputcsv($file, ['Donation Rate (%)', $metrics['donation_rate']]);

            fputcsv($file, []); // Empty row

            // Fulfillment Rates
            fputcsv($file, ['Monthly Fulfillment Rates']);
            fputcsv($file, ['Month', 'Fulfillment Rate (%)']);
            $fulfillment = $this->getFulfillmentRates($hospitalId);
            foreach ($fulfillment['labels'] as $index => $label) {
                fputcsv($file, [$label, $fulfillment['rates'][$index]]);
            }

            fputcsv($file, []); // Empty row

            // Seasonal Trends
            fputcsv($file, ['Seasonal Trends']);
            fputcsv($file, ['Month', 'Requests', 'Donations']);
            $seasonal = $this->getSeasonalTrends($hospitalId);
            foreach ($seasonal['labels'] as $index => $label) {
                fputcsv($file, [$label, $seasonal['requests'][$index], $seasonal['donations'][$index]]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
