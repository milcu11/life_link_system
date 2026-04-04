@extends('layout.app')

@section('title', 'Report')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Report: {{ ucfirst($reportType) }}</h1>
            <p class="text-sm text-gray-600">
                From {{ \Illuminate\Support\Carbon::parse($dateFrom)->toDayDateTimeString() }}
                to {{ \Illuminate\Support\Carbon::parse($dateTo)->toDayDateTimeString() }}
            </p>
        </div>
        <div class="space-x-2">
            <a href="{{ route('admin.reports.generate', array_merge(request()->all(), ['format' => 'csv'])) }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                <i class="fas fa-file-csv mr-2"></i> Download CSV
            </a>
        </div>
    </div>

    <div class="report-content">
        @if($reportType === 'requests')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white shadow rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-gray-700">Total Requests</h4>
                    <p class="text-2xl font-bold text-gray-900">{{ $data['total'] ?? 0 }}</p>
                </div>
            </div>

            <h3 class="mt-8 mb-2 text-xl font-semibold text-gray-800">Status Breakdown</h3>
            @if(!empty($data['by_status']) && $data['by_status'] instanceof Illuminate\Support\Collection)
                <div class="overflow-x-auto bg-white shadow rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($data['by_status'] as $row)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $row->status ?? ($row['status'] ?? 'N/A') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $row->count ?? ($row['count'] ?? 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No status data available.</p>
            @endif

            <h3 class="mt-8 mb-2 text-xl font-semibold text-gray-800">Urgency Breakdown</h3>
            @if(!empty($data['by_urgency']) && $data['by_urgency'] instanceof Illuminate\Support\Collection)
                <div class="overflow-x-auto bg-white shadow rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgency</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($data['by_urgency'] as $row)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $row->urgency_level ?? ($row['urgency_level'] ?? 'N/A') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $row->count ?? ($row['count'] ?? 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No urgency data available.</p>
            @endif

        @elseif($reportType === 'donors')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white shadow rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-gray-700">Total Donors</h4>
                    <p class="text-2xl font-bold text-gray-900">{{ $data['total'] ?? 0 }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-gray-700">Available</h4>
                    <p class="text-2xl font-bold text-gray-900">{{ $data['available'] ?? 0 }}</p>
                </div>
            </div>

            <h3 class="mt-8 mb-2 text-xl font-semibold text-gray-800">By Blood Type</h3>
            @if(!empty($data['by_blood_type']) && $data['by_blood_type'] instanceof Illuminate\Support\Collection)
                <div class="overflow-x-auto bg-white shadow rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($data['by_blood_type'] as $row)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $row->blood_type ?? ($row['blood_type'] ?? 'N/A') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $row->count ?? ($row['count'] ?? 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No blood type data available.</p>
            @endif

        @elseif($reportType === 'donations')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white shadow rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-gray-700">Total Donations</h4>
                    <p class="text-2xl font-bold text-gray-900">{{ $data['total'] ?? 0 }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p=4">
                    <h4 class="text-lg font-semibold text-gray-700">Completed</h4>
                    <p class="text-2xl font-bold text-gray-900">{{ $data['completed'] ?? 0 }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-gray-700">Total Units</h4>
                    <p class="text-2xl font-bold text-gray-900">{{ $data['total_units'] ?? 0 }}</p>
                </div>
            </div>

        @else
            <h3 class="mt-6 mb-2 text-xl font-semibold text-gray-800">Summary Report</h3>
            <div class="space-y-8">
                <div>
                    <h4 class="text-lg font-semibold text-gray-700">Donor Overview</h4>
                    @php $sub = $data['donors'] ?? [] @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="bg-white shadow rounded-lg p-4">
                            <p>Total donors: {{ $sub['total'] ?? 0 }}</p>
                        </div>
                        <div class="bg-white shadow rounded-lg p-4">
                            <p>Available: {{ $sub['available'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-700">Request Overview</h4>
                    @php $sub = $data['requests'] ?? [] @endphp
                    <div class="bg-white shadow rounded-lg p-4">
                        <p>Total requests: {{ $sub['total'] ?? 0 }}</p>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-700">Donation Overview</h4>
                    @php $sub = $data['donations'] ?? [] @endphp
                    <div class="bg-white shadow rounded-lg p-4">
                        <p>Total donations: {{ $sub['total'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
