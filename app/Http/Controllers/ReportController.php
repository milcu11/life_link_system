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
            'report_type' => 'nullable|in:donors,requests,donations,summary',
            'report_types' => 'nullable|array',
            'report_types.*' => 'in:donors,requests,donations,matching,usage',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $dateFrom = $request->date_from ?? now()->subMonth();
        $dateTo = $request->date_to ?? now();

        // Handle custom reports with multiple types
        if ($request->has('report_types') && !empty($request->report_types)) {
            $data = [];
            foreach ($request->report_types as $type) {
                $data[$type] = match($type) {
                    'donors' => $this->getDonorReport($dateFrom, $dateTo),
                    'requests' => $this->getRequestReport($dateFrom, $dateTo),
                    'donations' => $this->getDonationReport($dateFrom, $dateTo),
                    'matching' => $this->getMatchingReport($dateFrom, $dateTo),
                    'usage' => $this->getUsageReport($dateFrom, $dateTo),
                };
            }

            // if the user has requested a CSV download, stream the file instead of rendering HTML
            if ($request->query('format') === 'csv') {
                return $this->streamCustomCsv($data, $dateFrom, $dateTo);
            }

            return view('admin.reports.custom', [
                'reportTypes' => $request->report_types,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'data' => $data,
            ]);
        }

        // Handle single report type (backward compatibility)
        $data = match($request->report_type) {
            'donors' => $this->getDonorReport($dateFrom, $dateTo),
            'requests' => $this->getRequestReport($dateFrom, $dateTo),
            'donations' => $this->getDonationReport($dateFrom, $dateTo),
            'summary' => $this->getSummaryReport($dateFrom, $dateTo),
        };

        // if the user has requested a CSV download, stream the file instead of rendering HTML
        if ($request->query('format') === 'csv') {
            return $this->streamCsv($request->report_type, $data, $dateFrom, $dateTo);
        }

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

    private function getMatchingReport($from, $to)
    {
        // This would need to be implemented based on your Matching model
        // For now, return a placeholder
        return [
            'total_matches' => 0, // Placeholder - implement based on your matching logic
            'success_rate' => 0,
            'average_response_time' => 0,
        ];
    }

    private function getUsageReport($from, $to)
    {
        // This would need to be implemented based on your usage tracking
        // For now, return a placeholder based on available schema fields
        return [
            'total_users' => \App\Models\User::whereBetween('created_at', [$from, $to])->count(),
            'active_users' => \App\Models\User::whereBetween('updated_at', [$from, $to])->count(),
            'total_logins' => 0, // Placeholder - would need login tracking
            'system_alerts' => 0, // Placeholder - would need alert tracking
        ];
    }

    private function streamCsv(string $type, array $data, $from, $to)
    {
        $fromStr = \Illuminate\Support\Carbon::parse($from)->format('Ymd');
        $toStr   = \Illuminate\Support\Carbon::parse($to)->format('Ymd');
        $filename = "{$type}_report_{$fromStr}_{$toStr}.csv";

        $callback = function() use ($type, $data) {
            $out = fopen('php://output', 'w');

            if ($type === 'donors') {
                fputcsv($out, ['Blood Type', 'Count']);
                foreach ($data['by_blood_type'] ?? [] as $row) {
                    $blood = is_array($row) ? ($row['blood_type'] ?? '') : ($row->blood_type ?? '');
                    $count = is_array($row) ? ($row['count'] ?? 0) : ($row->count ?? 0);
                    fputcsv($out, [$blood, $count]);
                }
            } elseif ($type === 'requests') {
                fputcsv($out, ['Status/Urgency', 'Count']);
                foreach ($data['by_status'] ?? [] as $row) {
                    $status = is_array($row) ? ($row['status'] ?? '') : ($row->status ?? '');
                    $count = is_array($row) ? ($row['count'] ?? 0) : ($row->count ?? 0);
                    fputcsv($out, ["status: $status", $count]);
                }
                fputcsv($out, []);
                foreach ($data['by_urgency'] ?? [] as $row) {
                    $urgency = is_array($row) ? ($row['urgency_level'] ?? '') : ($row->urgency_level ?? '');
                    $count = is_array($row) ? ($row['count'] ?? 0) : ($row->count ?? 0);
                    fputcsv($out, ["urgency: $urgency", $count]);
                }
            } elseif ($type === 'donations') {
                fputcsv($out, ['Metric', 'Value']);
                fputcsv($out, ['Total', $data['total'] ?? 0]);
                fputcsv($out, ['Completed', $data['completed'] ?? 0]);
                fputcsv($out, ['Total Units', $data['total_units'] ?? 0]);
            }

            fclose($out);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function streamCustomCsv(array $data, $from, $to)
    {
        $fromStr = \Illuminate\Support\Carbon::parse($from)->format('Ymd');
        $toStr   = \Illuminate\Support\Carbon::parse($to)->format('Ymd');
        $filename = "custom_report_{$fromStr}_{$toStr}.csv";

        $callback = function() use ($data) {
            $out = fopen('php://output', 'w');

            foreach ($data as $type => $typeData) {
                fputcsv($out, ["--- " . ucfirst($type) . " Report ---"]);
                fputcsv($out, []);

                if ($type === 'donors') {
                    fputcsv($out, ['Blood Type', 'Count']);
                    foreach ($typeData['by_blood_type'] ?? [] as $row) {
                        $blood = is_array($row) ? ($row['blood_type'] ?? '') : ($row->blood_type ?? '');
                        $count = is_array($row) ? ($row['count'] ?? 0) : ($row->count ?? 0);
                        fputcsv($out, [$blood, $count]);
                    }
                    fputcsv($out, ['Total Donors', $typeData['total'] ?? 0]);
                    fputcsv($out, ['Available Donors', $typeData['available'] ?? 0]);
                } elseif ($type === 'requests') {
                    fputcsv($out, ['Status', 'Count']);
                    foreach ($typeData['by_status'] ?? [] as $row) {
                        $status = is_array($row) ? ($row['status'] ?? '') : ($row->status ?? '');
                        $count = is_array($row) ? ($row['count'] ?? 0) : ($row->count ?? 0);
                        fputcsv($out, [$status, $count]);
                    }
                    fputcsv($out, []);
                    fputcsv($out, ['Urgency Level', 'Count']);
                    foreach ($typeData['by_urgency'] ?? [] as $row) {
                        $urgency = is_array($row) ? ($row['urgency_level'] ?? '') : ($row->urgency_level ?? '');
                        $count = is_array($row) ? ($row['count'] ?? 0) : ($row->count ?? 0);
                        fputcsv($out, [$urgency, $count]);
                    }
                } elseif ($type === 'donations') {
                    fputcsv($out, ['Metric', 'Value']);
                    fputcsv($out, ['Total Donations', $typeData['total'] ?? 0]);
                    fputcsv($out, ['Completed Donations', $typeData['completed'] ?? 0]);
                    fputcsv($out, ['Total Units', $typeData['total_units'] ?? 0]);
                }

                fputcsv($out, []);
            }

            fclose($out);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}