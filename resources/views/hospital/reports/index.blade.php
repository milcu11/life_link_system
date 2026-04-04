<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics & Reports - LifeLink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }
    </style>
<body class="bg-gray-50">
    @include('hospital.partials.nav')

    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Analytics & Reports</h1>
                    <p class="text-gray-600 mt-1">Comprehensive insights into your hospital's blood donation operations</p>
                </div>
            </div>

            <!-- Success Metrics -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Success Metrics</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="text-sm text-gray-500">Total Requests</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $successMetrics['total_requests'] }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="text-sm text-gray-500">Fulfillment Rate</div>
                        <div class="text-2xl font-bold text-green-600">{{ $successMetrics['fulfillment_rate'] }}%</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="text-sm text-gray-500">Match Rate</div>
                        <div class="text-2xl font-bold text-blue-600">{{ $successMetrics['match_rate'] }}%</div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="text-sm text-gray-500">Donation Completion Rate</div>
                        <div class="text-2xl font-bold text-purple-600">{{ $successMetrics['donation_rate'] }}%</div>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Request Fulfillment Rates -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Request Fulfillment Rates (Last 12 Months)</h3>
                    <div class="chart-container">
                        <canvas id="fulfillmentChart"></canvas>
                    </div>
                </div>

                <!-- Seasonal Trends -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Seasonal Trends (Last 12 Months)</h3>
                    <div class="chart-container">
                        <canvas id="seasonalChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Response Time -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Donor Response Time</h3>
                <div class="text-center">
                    <div class="text-4xl font-bold text-red-600">{{ $responseTimeData['average_days'] }}</div>
                    <div class="text-gray-600">Average days from match to donation completion</div>
                </div>
            </div>

            <!-- Detailed Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Detailed Statistics</h3>
                    <a href="{{ route('hospital.reports.export', ['type' => 'csv']) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        <i class="fas fa-download mr-2"></i>Export CSV
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Metric</th>
                                <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Value</th>
                                <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Description</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-3">Total Requests</td>
                                <td class="px-4 py-3 font-semibold">{{ $successMetrics['total_requests'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">Total blood requests made by your hospital</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Fulfilled Requests</td>
                                <td class="px-4 py-3 font-semibold">{{ $successMetrics['fulfilled_requests'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">Requests that were successfully completed</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Total Matches</td>
                                <td class="px-4 py-3 font-semibold">{{ $successMetrics['total_matches'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">Total donor matches found for your requests</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Completed Donations</td>
                                <td class="px-4 py-3 font-semibold">{{ $successMetrics['completed_donations'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">Donations that were successfully completed</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fulfillment Rates Chart
            const fulfillmentCtx = document.getElementById('fulfillmentChart');
            if (fulfillmentCtx) {
                new Chart(fulfillmentCtx, {
                    type: 'line',
                    data: {
                        labels: @json($fulfillmentData['labels']),
                        datasets: [{
                            label: 'Fulfillment Rate (%)',
                            data: @json($fulfillmentData['rates']),
                            borderColor: 'rgb(220, 38, 38)',
                            backgroundColor: 'rgba(220, 38, 38, 0.1)',
                            tension: 0.1,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        }
                    }
                });
            }

            // Seasonal Trends Chart
            const seasonalCtx = document.getElementById('seasonalChart');
            if (seasonalCtx) {
                new Chart(seasonalCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($seasonalData['labels']),
                        datasets: [{
                            label: 'Requests',
                            data: @json($seasonalData['requests']),
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1
                        }, {
                            label: 'Donations',
                            data: @json($seasonalData['donations']),
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderColor: 'rgb(16, 185, 129)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>