@extends('layout.app')

@section('title', 'Generate Reports - LifeLink')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Generate Reports</h1>
            <p class="text-gray-600 mt-1">Download system reports and analytics</p>
        </div>

        <!-- Reports Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Donor Report -->
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition" data-testid="donor-report-card">
                <div class="h-24 bg-linear-to-r from-red-600 to-red-800 flex items-center justify-center">
                    <i class="fas fa-users text-white text-5xl"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Donor Report</h3>
                    <p class="text-gray-600 text-sm mb-4">Comprehensive report of all registered donors, their availability, and donation history.</p>
                    <ul class="space-y-2 mb-6">
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Total donors count</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Blood type distribution</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Availability status</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Donation frequency</li>
                    </ul>
                    <form action="{{ route('admin.reports.generate') }}" method="GET">
                        <input type="hidden" name="type" value="donors">
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded transition" data-testid="generate-donor-report">
                            <i class="fas fa-download mr-2"></i> Generate Report
                        </button>
                    </form>
                </div>
            </div>

            <!-- Blood Request Report -->
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition" data-testid="request-report-card">
                <div class="h-24 bg-linear-to-r from-orange-600 to-orange-800 flex items-center justify-center">
                    <i class="fas fa-hand-holding-medical text-white text-5xl"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Blood Request Report</h3>
                    <p class="text-gray-600 text-sm mb-4">Detailed report of all blood requests, status, and fulfillment rates.</p>
                    <ul class="space-y-2 mb-6">
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Total requests</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Status breakdown</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Urgency levels</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Hospital-wise data</li>
                    </ul>
                    <form action="{{ route('admin.reports.generate') }}" method="GET">
                        <input type="hidden" name="type" value="requests">
                        <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded transition" data-testid="generate-request-report">
                            <i class="fas fa-download mr-2"></i> Generate Report
                        </button>
                    </form>
                </div>
            </div>

            <!-- Donation Report -->
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition" data-testid="donation-report-card">
                <div class="h-24 bg-linear-to-r from-green-600 to-green-800 flex items-center justify-center">
                    <i class="fas fa-heart text-white text-5xl"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Donation Report</h3>
                    <p class="text-gray-600 text-sm mb-4">Complete report of all completed and pending donations with timeline data.</p>
                    <ul class="space-y-2 mb-6">
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Total donations</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Completion rates</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Monthly trends</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Donor participation</li>
                    </ul>
                    <form action="{{ route('admin.reports.generate') }}" method="GET">
                        <input type="hidden" name="type" value="donations">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition" data-testid="generate-donation-report">
                            <i class="fas fa-download mr-2"></i> Generate Report
                        </button>
                    </form>
                </div>
            </div>

            <!-- Matching Report -->
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition" data-testid="matching-report-card">
                <div class="h-24 bg-linear-to-r from-indigo-600 to-indigo-800 flex items-center justify-center">
                    <i class="fas fa-link text-white text-5xl"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Matching Report</h3>
                    <p class="text-gray-600 text-sm mb-4">Analysis of donor-recipient matching efficiency and response times.</p>
                    <ul class="space-y-2 mb-6">
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Match success rate</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Response times</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Distance analysis</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Acceptance rates</li>
                    </ul>
                    <form action="{{ route('admin.reports.generate') }}" method="GET">
                        <input type="hidden" name="type" value="matching">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded transition" data-testid="generate-matching-report">
                            <i class="fas fa-download mr-2"></i> Generate Report
                        </button>
                    </form>
                </div>
            </div>

            <!-- System Usage Report -->
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition" data-testid="usage-report-card">
                <div class="h-24 bg-linear-to-r from-cyan-600 to-cyan-800 flex items-center justify-center">
                    <i class="fas fa-chart-line text-white text-5xl"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">System Usage Report</h3>
                    <p class="text-gray-600 text-sm mb-4">Overall system performance, user activity, and operational metrics.</p>
                    <ul class="space-y-2 mb-6">
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>User registrations</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Active users</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>System alerts</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Performance metrics</li>
                    </ul>
                    <form action="{{ route('admin.reports.generate') }}" method="GET">
                        <input type="hidden" name="type" value="usage">
                        <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-medium py-2 px-4 rounded transition" data-testid="generate-usage-report">
                            <i class="fas fa-download mr-2"></i> Generate Report
                        </button>
                    </form>
                </div>
            </div>

            <!-- Custom Report -->
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition" data-testid="custom-report-card">
                <div class="h-24 bg-linear-to-r from-pink-600 to-pink-800 flex items-center justify-center">
                    <i class="fas fa-sliders-h text-white text-5xl"></i>
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Custom Report</h3>
                    <p class="text-gray-600 text-sm mb-4">Create custom reports with specific date ranges and data filters.</p>
                    <ul class="space-y-2 mb-6">
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Custom date range</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Filtered data</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Multiple metrics</li>
                        <li class="text-sm text-gray-700"><i class="fas fa-check text-green-600 mr-2"></i>Export options</li>
                    </ul>
                    <button type="button" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-medium py-2 px-4 rounded transition" onclick="openCustomReportModal()" data-testid="open-custom-report">
                        <i class="fas fa-cog mr-2"></i> Configure Report
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openCustomReportModal() {
    alert('Custom report configuration modal would open here');
}
</script>
@endpush
@endsection