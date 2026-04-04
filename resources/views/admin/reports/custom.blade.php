@extends('layout.app')

@section('title', 'Custom Report - LifeLink')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Custom Report</h1>
                    <p class="text-gray-600 mt-1">
                        Generated on {{ now()->format('M j, Y') }} |
                        Period: {{ \Carbon\Carbon::parse($dateFrom)->format('M j, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('M j, Y') }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.reports') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Reports
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['format' => 'csv']) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        <i class="fas fa-download mr-2"></i> Download CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Report Sections -->
        <div class="space-y-8">
            @foreach($reportTypes as $type)
                @php
                    $typeData = $data[$type] ?? [];
                    $sectionTitle = match($type) {
                        'donors' => 'Donor Report',
                        'requests' => 'Blood Request Report',
                        'donations' => 'Donation Report',
                        'matching' => 'Matching Report',
                        'usage' => 'System Usage Report',
                        default => ucfirst($type) . ' Report'
                    };
                @endphp

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">{{ $sectionTitle }}</h2>
                    </div>
                    <div class="p-6">
                        @if($type === 'donors')
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-users text-blue-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-blue-600 font-medium">Total Donors</p>
                                            <p class="text-2xl font-bold text-blue-900">{{ number_format($typeData['total'] ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-green-600 font-medium">Available</p>
                                            <p class="text-2xl font-bold text-green-900">{{ number_format($typeData['available'] ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Blood Type Distribution</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @php $total = collect($typeData['by_blood_type'] ?? [])->sum('count') @endphp
                                        @foreach($typeData['by_blood_type'] ?? [] as $row)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $row['blood_type'] ?? $row->blood_type }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($row['count'] ?? $row->count) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $total > 0 ? round((($row['count'] ?? $row->count) / $total) * 100, 1) : 0 }}%
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        @elseif($type === 'requests')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="bg-orange-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-hand-holding-medical text-orange-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-orange-600 font-medium">Total Requests</p>
                                            <p class="text-2xl font-bold text-orange-900">{{ number_format($typeData['total'] ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Breakdown</h3>
                                    <div class="space-y-3">
                                        @foreach($typeData['by_status'] ?? [] as $row)
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-600">{{ ucfirst($row['status'] ?? $row->status) }}</span>
                                                <span class="text-sm font-medium text-gray-900">{{ number_format($row['count'] ?? $row->count) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Urgency Levels</h3>
                                    <div class="space-y-3">
                                        @foreach($typeData['by_urgency'] ?? [] as $row)
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-600">{{ ucfirst($row['urgency_level'] ?? $row->urgency_level) }}</span>
                                                <span class="text-sm font-medium text-gray-900">{{ number_format($row['count'] ?? $row->count) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        @elseif($type === 'donations')
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-heart text-green-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-green-600 font-medium">Total Donations</p>
                                            <p class="text-2xl font-bold text-green-900">{{ number_format($typeData['total'] ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-check text-blue-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-blue-600 font-medium">Completed</p>
                                            <p class="text-2xl font-bold text-blue-900">{{ number_format($typeData['completed'] ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-tint text-purple-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-purple-600 font-medium">Total Units</p>
                                            <p class="text-2xl font-bold text-purple-900">{{ number_format($typeData['total_units'] ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @elseif($type === 'matching')
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div class="bg-indigo-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-link text-indigo-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-indigo-600 font-medium">Total Matches</p>
                                            <p class="text-2xl font-bold text-indigo-900">{{ number_format($typeData['total_matches'] ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-percentage text-green-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-green-600 font-medium">Success Rate</p>
                                            <p class="text-2xl font-bold text-green-900">{{ number_format($typeData['success_rate'] ?? 0, 1) }}%</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-yellow-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock text-yellow-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-yellow-600 font-medium">Avg Response Time</p>
                                            <p class="text-2xl font-bold text-yellow-900">{{ number_format($typeData['average_response_time'] ?? 0, 1) }}h</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @elseif($type === 'usage')
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-plus text-blue-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-blue-600 font-medium">New Users</p>
                                            <p class="text-2xl font-bold text-blue-900">{{ number_format($typeData['total_users'] ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-check text-green-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-green-600 font-medium">Active Users</p>
                                            <p class="text-2xl font-bold text-green-900">{{ number_format($typeData['active_users'] ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-sign-in-alt text-purple-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-purple-600 font-medium">Total Logins</p>
                                            <p class="text-2xl font-bold text-purple-900">{{ number_format($typeData['total_logins'] ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-red-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl mr-3"></i>
                                        <div>
                                            <p class="text-sm text-red-600 font-medium">System Alerts</p>
                                            <p class="text-2xl font-bold text-red-900">{{ number_format($typeData['system_alerts'] ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection