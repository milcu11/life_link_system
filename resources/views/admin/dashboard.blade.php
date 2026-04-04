<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Blood Donation System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .quick-action-btn {
            transition: all 0.3s ease;
        }
        .quick-action-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .btn-red {
            background-color: #ef4444;
            color: white;
        }
        .btn-red:hover {
            background-color: #dc2626;
        }
        .btn-orange {
            background-color: #f97316;
            color: white;
        }
        .btn-orange:hover {
            background-color: #ea580c;
        }
        .btn-yellow {
            background-color: #eab308;
            color: white;
        }
        .btn-yellow:hover {
            background-color: #ca8a04;
        }
        .btn-indigo {
            background-color: #6366f1;
            color: white;
        }
        .btn-indigo:hover {
            background-color: #4f46e5;
        }
        .btn-green {
            background-color: #22c55e;
            color: white;
        }
        .btn-green:hover {
            background-color: #16a34a;
        }
        .btn-gray {
            background-color: #4b5563;
            color: white;
        }
        .btn-gray:hover {
            background-color: #374151;
        }
        .btn-pink {
            background-color: #ec4899;
            color: white;
        }
        .btn-pink:hover {
            background-color: #be185d;
        }        form[action*="logout"] button {
            cursor: pointer;
        }    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 hover:opacity-80 transition" title="LifeLink - Blood Donation System">
                    <img src="{{ asset('images/lifelink-logo.svg') }}" alt="LifeLink Logo" class="lifelink-logo-nav" />
                </a>
                <div class="flex items-center gap-4">
                    <a href="{{ route('notifications.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium transition" title="Notifications">
                        <i class="fas fa-bell"></i>
                    </a>
                    <div style="width: 1px; height: 24px; background-color: #999;"></div>
                    <div class="relative">
                        <button id="menuDropdown" class="text-gray-600 hover:text-red-600 font-medium flex items-center gap-2 focus:outline-none cursor-pointer">
                            <i class="fas fa-bars"></i>Menu
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="menuDropdownContent" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 hidden">
                            <a href="{{ route('admin.donors') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-users mr-2"></i>Manage Donors
                            </a>
                            <a href="{{ route('admin.requests') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-hand-holding-medical mr-2"></i>Manage Requests
                            </a>
                            <a href="{{ route('admin.map') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-map-marker-alt mr-2"></i>View Map
                            </a>
                            <a href="{{ route('admin.reports') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-chart-bar mr-2"></i>Generate Reports
                            </a>
                            <a href="{{ route('admin.users') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-user-shield mr-2"></i>Manage Users
                            </a>
                            <a href="{{ route('admin.appeals') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-exclamation-circle mr-2"></i>Review Appeals
                            </a>
                            <a href="{{ route('admin.donations') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-droplet mr-2"></i>View Donations
                            </a>
                            <hr class="border-gray-200">
                            <form action="{{ route('logout') }}" method="POST" style="display: block;">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="text-gray-600 mt-1">Blood Donation System Overview</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-red-100">
                            <i class="fas fa-users text-red-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Total Donors</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_donors'] }}</h3>
                            <p class="text-sm text-gray-500">{{ $stats['active_donors'] }} active</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-orange-100">
                            <i class="fas fa-hand-holding-medical text-orange-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Blood Requests</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_requests'] }}</h3>
                            <p class="text-sm text-gray-500">{{ $stats['pending_requests'] }} pending</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-green-100">
                            <i class="fas fa-heart text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Total Donations</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_donations'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-indigo-100">
                            <i class="fas fa-user-shield text-indigo-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Total Users</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow overflow-hidden col-span-full">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900"><i class="fas fa-chart-line text-red-600 mr-2"></i>6-Month Trends</h2>
                    </div>
                    <div class="p-6">
                        <canvas id="sixMonthChart" style="max-height: 400px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900"><i class="fas fa-list text-red-600 mr-2"></i>Recent Blood Requests</h2>
                        <a href="{{ route('admin.requests') }}" class="text-red-600 hover:text-red-700 text-sm font-medium">View All <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($recentRequests as $request)
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">{{ $request->blood_type }}</span>
                                        <span class="text-sm text-gray-600">{{ $request->quantity }} units • {{ ucfirst($request->urgency_level) }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 mt-1">{{ $request->hospital->name }} · <span class="text-gray-500">{{ $request->location }}</span></p>
                                </div>
                                <div class="text-sm text-gray-500">{{ $request->created_at->diffForHumans() }}</div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500">No recent requests</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900"><i class="fas fa-droplet text-red-600 mr-2"></i>Recent Donations</h2>
                        <a href="{{ route('admin.donations') }}" class="text-red-600 hover:text-red-700 text-sm font-medium">View All <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($recentDonations as $donation)
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm text-gray-700"><strong>{{ $donation->donor->user->name }}</strong></p>
                                    <p class="text-sm text-gray-600">{{ $donation->donor->blood_type }} • {{ $donation->request ? $donation->request->quantity . ' units' : $donation->quantity . ' units' }}</p>
                                </div>
                                <div class="text-sm text-gray-500">{{ $donation->donation_date?->format('M d, Y') ?? $donation->created_at->format('M d, Y') }}</div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500">No recent donations</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-6">Quick Actions</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.donors') }}" class="quick-action-btn btn-red flex items-center justify-center h-16 w-full rounded-lg font-medium text-sm focus:outline-none focus:ring-2">
                        <i class="fas fa-users mr-2"></i>Manage Donors
                    </a>
                    <a href="{{ route('admin.requests') }}" class="quick-action-btn btn-orange flex items-center justify-center h-16 w-full rounded-lg font-medium text-sm focus:outline-none focus:ring-2">
                        <i class="fas fa-hand-holding-medical mr-2"></i>Manage Requests
                    </a>
                    <a href="{{ route('admin.appeals') }}" class="quick-action-btn btn-yellow flex items-center justify-center h-16 w-full rounded-lg font-medium text-sm focus:outline-none focus:ring-2">
                        <i class="fas fa-exclamation-circle mr-2"></i>Review Appeals
                    </a>
                    <a href="{{ route('admin.map') }}" class="quick-action-btn btn-indigo flex items-center justify-center h-16 w-full rounded-lg font-medium text-sm focus:outline-none focus:ring-2">
                        <i class="fas fa-map-marker-alt mr-2"></i>View Map
                    </a>
                    <a href="{{ route('admin.reports') }}" class="quick-action-btn btn-green flex items-center justify-center h-16 w-full rounded-lg font-medium text-sm focus:outline-none focus:ring-2">
                        <i class="fas fa-chart-bar mr-2"></i>Generate Reports
                    </a>
                    <a href="{{ route('admin.users') }}" class="quick-action-btn btn-gray flex items-center justify-center h-16 w-full rounded-lg font-medium text-sm focus:outline-none focus:ring-2">
                        <i class="fas fa-user-shield mr-2"></i>Manage Users
                    </a>
                    <a href="{{ route('admin.donations') }}" class="quick-action-btn btn-pink col-span-full flex items-center justify-center h-16 w-full rounded-lg font-medium text-sm focus:outline-none focus:ring-2">
                        <i class="fas fa-droplet mr-2"></i>View Donations
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuDropdown = document.getElementById('menuDropdown');
            const menuDropdownContent = document.getElementById('menuDropdownContent');

            menuDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
                menuDropdownContent.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                menuDropdownContent.classList.add('hidden');
            });

            // Prevent dropdown from closing when clicking inside it
            menuDropdownContent.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            // Initialize Chart.js
            const ctx = document.getElementById('sixMonthChart');
            if (ctx) {
                const chartData = @json($chartData);
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData.months,
                        datasets: [
                            {
                                label: 'Donors',
                                data: chartData.donors,
                                borderColor: '#22c55e',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#22c55e',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                            },
                            {
                                label: 'Requests',
                                data: chartData.requests,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#3b82f6',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                            },
                            {
                                label: 'Matches',
                                data: chartData.matches,
                                borderColor: '#eab308',
                                backgroundColor: 'rgba(234, 179, 8, 0.1)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#eab308',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    },
                                    padding: 20,
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: {
                                    size: 13,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 12
                                },
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.parsed.y;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Count'
                                },
                                ticks: {
                                    stepSize: 1
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    drawBorder: false
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month'
                                },
                                grid: {
                                    display: false,
                                    drawBorder: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>


