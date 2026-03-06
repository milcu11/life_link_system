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
            <form action="{{ route('admin.reports.generate') }}" method="GET">
                <input type="hidden" name="report_type" value="donors">
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
            <form action="{{ route('admin.reports.generate') }}" method="GET">
                <input type="hidden" name="report_type" value="requests">
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
            <form action="{{ route('admin.reports.generate') }}" method="GET">
                <input type="hidden" name="report_type" value="donations">
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
            <form action="{{ route('admin.reports.generate') }}" method="GET">
                <input type="hidden" name="report_type" value="summary">
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
            <form action="{{ route('admin.reports.generate') }}" method="GET">
                <input type="hidden" name="report_type" value="summary">
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
            <button type="button" class="btn btn-primary btn-block" onclick="openCustomReportModal()" data-testid="open-custom-report">
                <i class="fas fa-cog"></i> Configure Report
            </button>
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
