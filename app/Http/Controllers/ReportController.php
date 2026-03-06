<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:donors,requests,donations,summary',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $dateFrom = $request->date_from ?? now()->subMonth();
        $dateTo = $request->date_to ?? now();

        $data = match($request->report_type) {
            'donors' => $this->getDonorReport($dateFrom, $dateTo),
            'requests' => $this->getRequestReport($dateFrom, $dateTo),
            'donations' => $this->getDonationReport($dateFrom, $dateTo),
            'summary' => $this->getSummaryReport($dateFrom, $dateTo),
        };

        return view('admin.reports.show', [
            'reportType' => $request->report_type,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'data' => $data,
        ]);
    }

    private function getDonorReport($from, $to)
    {
        return [
            'total' => Donor::whereBetween('created_at', [$from, $to])->count(),
            'by_blood_type' => Donor::whereBetween('created_at', [$from, $to])
                ->selectRaw('blood_type, count(*) as count')
                ->groupBy('blood_type')
                ->get(),
            'available' => Donor::where('is_available', true)->count(),
        ];
    }

    private function getRequestReport($from, $to)
    {
        return [
            'total' => BloodRequest::whereBetween('created_at', [$from, $to])->count(),
            'by_status' => BloodRequest::whereBetween('created_at', [$from, $to])
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get(),
            'by_urgency' => BloodRequest::whereBetween('created_at', [$from, $to])
                ->selectRaw('urgency_level, count(*) as count')
                ->groupBy('urgency_level')
                ->get(),
        ];
    }

    private function getDonationReport($from, $to)
    {
        return [
            'total' => Donation::whereBetween('donation_date', [$from, $to])->count(),
            'completed' => Donation::whereBetween('donation_date', [$from, $to])
                ->where('status', 'completed')
                ->count(),
            'total_units' => Donation::whereBetween('donation_date', [$from, $to])
                ->where('status', 'completed')
                ->sum('quantity'),
        ];
    }

    private function getSummaryReport($from, $to)
    {
        return [
            'donors' => $this->getDonorReport($from, $to),
            'requests' => $this->getRequestReport($from, $to),
            'donations' => $this->getDonationReport($from, $to),
        ];
    }
}