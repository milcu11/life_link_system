@extends('layout.app')

@section('title', 'Report')

@section('content')
<div class="report-result-container">
    <div class="page-header">
        <h1 class="page-title">Report: {{ ucfirst($reportType) }}</h1>
        <p class="page-subtitle">
            From {{ \Illuminate\Support\Carbon::parse($dateFrom)->toDayDateTimeString() }}
            to {{ \Illuminate\Support\Carbon::parse($dateTo)->toDayDateTimeString() }}
        </p>


    <div class="report-content">
        @if($reportType === 'requests')
            <div class="stats">
                <div class="stat-card">
                    <h4>Total Requests</h4>
                    <p>{{ $data['total'] ?? 0 }}</p>
                </div>
            </div>

            <h3>Status Breakdown</h3>
            @if(!empty($data['by_status']) && $data['by_status'] instanceof Illuminate\Support\Collection)
                <table class="report-table">
                    <thead>
                        <tr><th>Status</th><th>Count</th></tr>
                    </thead>
                    <tbody>
                        @foreach($data['by_status'] as $row)
                            <tr>
                                <td>{{ $row->status ?? ($row['status'] ?? 'N/A') }}</td>
                                <td>{{ $row->count ?? ($row['count'] ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No status data available.</p>
            @endif

            <h3>Urgency Breakdown</h3>
            @if(!empty($data['by_urgency']) && $data['by_urgency'] instanceof Illuminate\Support\Collection)
                <table class="report-table">
                    <thead>
                        <tr><th>Urgency</th><th>Count</th></tr>
                    </thead>
                    <tbody>
                        @foreach($data['by_urgency'] as $row)
                            <tr>
                                <td>{{ $row->urgency_level ?? ($row['urgency_level'] ?? 'N/A') }}</td>
                                <td>{{ $row->count ?? ($row['count'] ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No urgency data available.</p>
            @endif

        @elseif($reportType === 'donors')
            <div class="stats">
                <div class="stat-card">
                    <h4>Total Donors</h4>
                    <p>{{ $data['total'] ?? 0 }}</p>
                </div>
                <div class="stat-card">
                    <h4>Available</h4>
                    <p>{{ $data['available'] ?? 0 }}</p>
                </div>
            </div>

            <h3>By Blood Type</h3>
            @if(!empty($data['by_blood_type']) && $data['by_blood_type'] instanceof Illuminate\Support\Collection)
                <table class="report-table">
                    <thead>
                        <tr><th>Blood Type</th><th>Count</th></tr>
                    </thead>
                    <tbody>
                        @foreach($data['by_blood_type'] as $row)
                            <tr>
                                <td>{{ $row->blood_type ?? ($row['blood_type'] ?? 'N/A') }}</td>
                                <td>{{ $row->count ?? ($row['count'] ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No blood type data available.</p>
            @endif

        @elseif($reportType === 'donations')
            <div class="stats">
                <div class="stat-card">
                    <h4>Total Donations</h4>
                    <p>{{ $data['total'] ?? 0 }}</p>
                </div>
                <div class="stat-card">
                    <h4>Completed</h4>
                    <p>{{ $data['completed'] ?? 0 }}</p>
                </div>
                <div class="stat-card">
                    <h4>Total Units</h4>
                    <p>{{ $data['total_units'] ?? 0 }}</p>
                </div>
            </div>

        @else
            <h3>Report Data</h3>
            <pre>{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
        @endif
    </div>
</div>
@endsection
