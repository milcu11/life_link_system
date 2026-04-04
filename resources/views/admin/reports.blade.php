@extends('layout.app')

@section('title', 'Generate Reports - LifeLink')

@push('styles')
<style>
    button[data-testid*="generate-"]:hover,
    button[data-testid="open-custom-report"]:hover {
        cursor: pointer !important;
    }
    button[data-testid*="generate-"],
    button[data-testid="open-custom-report"] {
        cursor: pointer !important;
    }
</style>
@endpush

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
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded transition cursor-pointer" style="cursor: pointer;" data-testid="generate-donor-report">
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
                        <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded transition cursor-pointer" style="cursor: pointer;" data-testid="generate-request-report">
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
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition cursor-pointer" style="cursor: pointer;" data-testid="generate-donation-report">
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
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded transition cursor-pointer" style="cursor: pointer;" data-testid="generate-matching-report">
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
                        <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white font-medium py-2 px-4 rounded transition cursor-pointer" style="cursor: pointer;" data-testid="generate-usage-report">
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
                    <button type="button" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-medium py-2 px-4 rounded transition cursor-pointer" style="cursor: pointer;" onclick="openCustomReportModal()" data-testid="open-custom-report">
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
    document.getElementById('customReportModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeCustomReportModal() {
    document.getElementById('customReportModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('customReportModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCustomReportModal();
        }
    });
});
</script>
@endpush

<!-- Custom Report Modal -->
<div id="customReportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Configure Custom Report</h3>
                <button onclick="closeCustomReportModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form action="{{ route('admin.reports.generate') }}" method="GET" class="space-y-6">
                @csrf

                <!-- Date Range Section -->
                <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-3">Date Range</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" id="date_from" name="date_from" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" value="{{ now()->subMonth()->format('Y-m-d') }}">
                        </div>
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" id="date_to" name="date_to" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" value="{{ now()->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <!-- Report Types Section -->
                <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-3">Report Types</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="report_types[]" value="donors" class="rounded border-gray-300 text-red-600 focus:ring-red-500" checked>
                                <span class="ml-2 text-sm text-gray-700">Donor Report</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="report_types[]" value="requests" class="rounded border-gray-300 text-red-600 focus:ring-red-500" checked>
                                <span class="ml-2 text-sm text-gray-700">Blood Request Report</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="report_types[]" value="donations" class="rounded border-gray-300 text-red-600 focus:ring-red-500" checked>
                                <span class="ml-2 text-sm text-gray-700">Donation Report</span>
                            </label>
                        </div>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="report_types[]" value="matching" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <span class="ml-2 text-sm text-gray-700">Matching Report</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="report_types[]" value="usage" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <span class="ml-2 text-sm text-gray-700">System Usage Report</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Export Format Section -->
                <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-3">Export Format</h4>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="format" value="html" class="border-gray-300 text-red-600 focus:ring-red-500" checked>
                            <span class="ml-2 text-sm text-gray-700">View in Browser (HTML)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="format" value="csv" class="border-gray-300 text-red-600 focus:ring-red-500">
                            <span class="ml-2 text-sm text-gray-700">Download as CSV</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="format" value="pdf" class="border-gray-300 text-red-600 focus:ring-red-500" disabled>
                            <span class="ml-2 text-sm text-gray-700">Download as PDF (Coming Soon)</span>
                        </label>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" onclick="closeCustomReportModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        <i class="fas fa-download mr-2"></i> Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection