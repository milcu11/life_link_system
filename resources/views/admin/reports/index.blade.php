@extends('layout.app')

@section('title', 'Generate Reports')

@section('content')
<div class="reports-container">
    <div class="page-header">
        <h1 class="page-title">Generate Reports</h1>
        <p class="page-subtitle">Download system reports and analytics</p>
    </div>

    <div class="reports-grid">
        <!-- Donor Report -->
        <div class="report-card" data-testid="donor-report-card">
            <div class="report-icon" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
                <i class="fas fa-users"></i>
            </div>
            <div class="report-content">
                <h3 class="report-title">Donor Report</h3>
                <p class="report-description">Comprehensive report of all registered donors, their availability, and donation history.</p>
            </div>
            <form action="{{ route('admin.reports.generate') }}" method="GET" class="space-y-2">
                <input type="hidden" name="report_type" value="donors">
                <div class="flex gap-2">
                    <input type="date" name="date_from" class="border rounded px-2 py-1 flex-1" placeholder="From">
                    <input type="date" name="date_to" class="border rounded px-2 py-1 flex-1" placeholder="To">
                </div>
                <button type="submit" class="btn btn-primary btn-block" data-testid="generate-donor-report">
                    <i class="fas fa-download"></i> Generate Report
                </button>
            </form>
        </div>

        <!-- Blood Request Report -->
        <div class="report-card" data-testid="request-report-card">
            <div class="report-icon" style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);">
                <i class="fas fa-hand-holding-medical"></i>
            </div>
            <div class="report-content">
                <h3 class="report-title">Blood Request Report</h3>
                <p class="report-description">Detailed report of all blood requests, status, and fulfillment rates.</p>
            </div>
            <form action="{{ route('admin.reports.generate') }}" method="GET" class="space-y-2">
                <input type="hidden" name="report_type" value="requests">
                <div class="flex gap-2">
                    <input type="date" name="date_from" class="border rounded px-2 py-1 flex-1" placeholder="From">
                    <input type="date" name="date_to" class="border rounded px-2 py-1 flex-1" placeholder="To">
                </div>
                <button type="submit" class="btn btn-primary btn-block" data-testid="generate-request-report">
                    <i class="fas fa-download"></i> Generate Report
                </button>
            </form>
        </div>

        <!-- Donation Report -->
        <div class="report-card" data-testid="donation-report-card">
            <div class="report-icon" style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
                <i class="fas fa-heart"></i>
            </div>
            <div class="report-content">
                <h3 class="report-title">Donation Report</h3>
                <p class="report-description">Complete report of all completed and pending donations with timeline data.</p>
            </div>
            <form action="{{ route('admin.reports.generate') }}" method="GET" class="space-y-2">
                <input type="hidden" name="report_type" value="donations">
                <div class="flex gap-2">
                    <input type="date" name="date_from" class="border rounded px-2 py-1 flex-1" placeholder="From">
                    <input type="date" name="date_to" class="border rounded px-2 py-1 flex-1" placeholder="To">
                </div>
                <button type="submit" class="btn btn-primary btn-block" data-testid="generate-donation-report">
                    <i class="fas fa-download"></i> Generate Report
                </button>
            </form>
        </div>

        <!-- Matching Report -->
        <div class="report-card" data-testid="matching-report-card">
            <div class="report-icon" style="background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);">
                <i class="fas fa-link"></i>
            </div>
            <div class="report-content">
                <h3 class="report-title">Matching Report</h3>
                <p class="report-description">Analysis of donor-recipient matching efficiency and response times.</p>
            </div>
            <form action="{{ route('admin.reports.generate') }}" method="GET" class="space-y-2">
                <input type="hidden" name="report_type" value="summary">
                <div class="flex gap-2">
                    <input type="date" name="date_from" class="border rounded px-2 py-1 flex-1" placeholder="From">
                    <input type="date" name="date_to" class="border rounded px-2 py-1 flex-1" placeholder="To">
                </div>
                <button type="submit" class="btn btn-primary btn-block" data-testid="generate-matching-report">
                    <i class="fas fa-download"></i> Generate Report
                </button>
            </form>
        </div>

        <!-- System Usage Report -->
        <div class="report-card" data-testid="usage-report-card">
            <div class="report-icon" style="background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="report-content">
                <h3 class="report-title">System Usage Report</h3>
                <p class="report-description">Overall system performance, user activity, and operational metrics.</p>
            </div>
            <form action="{{ route('admin.reports.generate') }}" method="GET" class="space-y-2">
                <input type="hidden" name="report_type" value="summary">
                <div class="flex gap-2">
                    <input type="date" name="date_from" class="border rounded px-2 py-1 flex-1" placeholder="From">
                    <input type="date" name="date_to" class="border rounded px-2 py-1 flex-1" placeholder="To">
                </div>
                <button type="submit" class="btn btn-primary btn-block" data-testid="generate-usage-report">
                    <i class="fas fa-download"></i> Generate Report
                </button>
            </form>
        </div>

        <!-- Custom Report -->
        <div class="report-card" data-testid="custom-report-card">
            <div class="report-icon" style="background: linear-gradient(135deg, #be123c 0%, #881337 100%);">
                <i class="fas fa-sliders-h"></i>
            </div>
            <div class="report-content">
                <h3 class="report-title">Custom Report</h3>
                <p class="report-description">Create custom reports with specific date ranges and data filters.</p>
            </div>
            <button type="button" class="btn btn-primary btn-block cursor-pointer" style="cursor: pointer;" onclick="openCustomReportModal()" data-testid="open-custom-report">
                <i class="fas fa-cog"></i> Configure Report
            </button>
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

window.addEventListener('DOMContentLoaded', function () {
    document.getElementById('customReportModal').addEventListener('click', function (e) {
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
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Configure Custom Report</h3>
                <button onclick="closeCustomReportModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('admin.reports.generate') }}" method="GET" class="space-y-6">
                <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-3">Date Range</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" id="date_from" name="date_from" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" value="{{ now()->subMonth()->format('Y-m-d') }}">
                        </div>
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" id="date_to" name="date_to" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-red-500" value="{{ now()->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-3">Report Types</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <label class="flex items-center gap-2"><input type="checkbox" name="report_types[]" value="donors" checked class="h-4 w-4 text-red-600"><span class="text-gray-700">Donor Report</span></label>
                        <label class="flex items-center gap-2"><input type="checkbox" name="report_types[]" value="requests" checked class="h-4 w-4 text-red-600"><span class="text-gray-700">Blood Request Report</span></label>
                        <label class="flex items-center gap-2"><input type="checkbox" name="report_types[]" value="donations" checked class="h-4 w-4 text-red-600"><span class="text-gray-700">Donation Report</span></label>
                        <label class="flex items-center gap-2"><input type="checkbox" name="report_types[]" value="matching" class="h-4 w-4 text-red-600"><span class="text-gray-700">Matching Report</span></label>
                        <label class="flex items-center gap-2"><input type="checkbox" name="report_types[]" value="usage" class="h-4 w-4 text-red-600"><span class="text-gray-700">System Usage Report</span></label>
                    </div>
                </div>

                <div>
                    <h4 class="text-md font-semibold text-gray-800 mb-3">Export Format</h4>
                    <label class="flex items-center gap-2"><input type="radio" name="format" value="html" checked class="h-4 w-4 text-red-600"><span class="text-gray-700">View in Browser (HTML)</span></label>
                    <label class="flex items-center gap-2"><input type="radio" name="format" value="csv" class="h-4 w-4 text-red-600"><span class="text-gray-700">Download CSV</span></label>
                    <label class="flex items-center gap-2"><input type="radio" name="format" value="pdf" disabled class="h-4 w-4 text-red-600"><span class="text-gray-700">Download PDF (Coming Soon)</span></label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeCustomReportModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 cursor-pointer" style="cursor: pointer;">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 cursor-pointer" style="cursor: pointer;">Generate Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
