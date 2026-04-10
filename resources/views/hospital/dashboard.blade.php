<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Dashboard - Blood Donation System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        form[action*="logout"] button {
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:opacity-80 transition" title="LifeLink - Blood Donation System">
                        <img src="{{ asset('images/lifelink-logo.svg') }}" alt="LifeLink Logo" class="lifelink-logo-nav" />
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('hospital.messages.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium px-3 py-2 rounded-md hover:bg-gray-100 transition">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <div style="width: 1px; height: 24px; background-color: #999;"></div>
                    <div class="relative pl-0">
                        <button id="menuDropdown" class="text-gray-600 hover:text-red-600 font-medium flex items-center gap-2 focus:outline-none cursor-pointer">
                            <i class="fas fa-bars"></i>Menu
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="menuDropdownContent" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 hidden">
                            @php
                                $profileComplete = auth()->user()->location && auth()->user()->latitude && auth()->user()->longitude;
                            @endphp
                            
                            @if(!$profileComplete)
                                <div class="px-4 py-2 text-xs text-amber-700 bg-amber-50 border-b border-amber-200">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Complete your profile first
                                </div>
                            @endif
                            
                            <a href="{{ route('hospital.requests.index') }}" class="block px-4 py-2 text-sm {{ !$profileComplete ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer' }}" {{ !$profileComplete ? 'onclick="event.preventDefault();"' : '' }}>
                                <i class="fas fa-list mr-2"></i>My Requests
                            </a>
                            <a href="{{ route('hospital.requests.create') }}" class="block px-4 py-2 text-sm {{ !$profileComplete ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer' }}" {{ !$profileComplete ? 'onclick="event.preventDefault();"' : '' }}>
                                <i class="fas fa-plus-circle mr-2"></i>New Request
                            </a>
                            <a href="{{ route('hospital.inventory.index') }}" class="block px-4 py-2 text-sm {{ !$profileComplete ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer' }}" {{ !$profileComplete ? 'onclick="event.preventDefault();"' : '' }}>
                                <i class="fas fa-boxes mr-2"></i>Blood Inventory
                            </a>
                            <a href="{{ route('hospital.drives.index') }}" class="block px-4 py-2 text-sm {{ !$profileComplete ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer' }}" {{ !$profileComplete ? 'onclick="event.preventDefault();"' : '' }}>
                                <i class="fas fa-calendar-alt mr-2"></i>Blood Drives
                            </a>
                            <a href="{{ route('hospital.reports.index') }}" class="block px-4 py-2 text-sm {{ !$profileComplete ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer' }}" {{ !$profileComplete ? 'onclick="event.preventDefault();"' : '' }}>
                                <i class="fas fa-chart-bar mr-2"></i>Analytics & Reports
                            </a>
                            <hr class="border-gray-200">
                            <form action="{{ route('logout') }}" method="POST" style="display: block;">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">
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
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Hospital Dashboard</h1>
                    <p class="text-gray-600 mt-1">{{ auth()->user()->name }}</p>
                </div>
            </div>



            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-orange-100">
                            <i class="fas fa-clock text-orange-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Active Requests</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['active_requests'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-green-100">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Fulfilled Requests</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['fulfilled_requests'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-red-100">
                            <i class="fas fa-users text-red-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Total Matches</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_matches'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-blue-100">
                            <i class="fas fa-boxes text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Blood Inventory</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_inventory'] }} units</h3>
                            @if($stats['low_stock_items'] > 0 || $stats['expiring_soon_items'] > 0)
                                <p class="text-xs text-red-600 mt-1">
                                    @if($stats['low_stock_items'] > 0) {{ $stats['low_stock_items'] }} low @endif
                                    @if($stats['expiring_soon_items'] > 0) {{ $stats['expiring_soon_items'] }} expiring @endif
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($urgentRequests->count() > 0)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-red-700 mb-3"><i class="fas fa-exclamation-triangle mr-2"></i>Urgent Requests</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($urgentRequests as $request)
                            <div class="bg-white rounded-lg shadow p-4 flex justify-between items-center">
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <div class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">{{ $request->blood_type }}</div>
                                        <div class="text-sm text-gray-600">{{ $request->quantity }} units • {{ ucfirst($request->urgency_level) }}</div>
                                    </div>
                                    <p class="text-sm text-gray-700">{{ $request->hospital->name }} · <span class="text-gray-500">{{ $request->location }}</span></p>
                                </div>
                                <a href="{{ route('hospital.requests.show', $request) }}" class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">View</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900"><i class="fas fa-list text-red-600 mr-2"></i>Recent Requests</h2>
                    <a href="{{ route('hospital.requests.index') }}" class="text-red-600 hover:text-red-700 text-sm font-medium">View All <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Blood Type</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Quantity</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Urgency</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Status</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Matches</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Date</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($recentRequests as $request)
                                    <tr>
                                        <td class="px-4 py-3">{{ $request->blood_type }}</td>
                                        <td class="px-4 py-3">{{ $request->quantity }} units</td>
                                        <td class="px-4 py-3">{{ ucfirst($request->urgency_level) }}</td>
                                        <td class="px-4 py-3">{{ ucfirst($request->status) }}</td>
                                        <td class="px-4 py-3">{{ $request->matchings->count() }} donors</td>
                                        <td class="px-4 py-3">{{ $request->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('hospital.requests.show', $request) }}" class="text-gray-600 hover:text-red-600"><i class="fas fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">No requests found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mb-12 mt-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Quick Access</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                    <a href="{{ route('hospital.requests.index') }}" class="border border-gray-200 bg-white rounded-lg p-4 hover:shadow-xl transition">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-100 text-red-600">
                                <i class="fas fa-list"></i>
                            </span>
                            <div>
                                <p class="text-xs text-gray-500">Requests</p>
                                <p class="text-lg font-bold text-gray-900">{{ $stats['active_requests'] }} active</p>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-red-600 font-semibold">View all</p>
                    </a>

                    <a href="{{ route('hospital.requests.create') }}" class="border border-gray-200 bg-white rounded-lg p-4 hover:shadow-xl transition">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                                <i class="fas fa-plus-circle"></i>
                            </span>
                            <div>
                                <p class="text-xs text-gray-500">New Request</p>
                                <p class="text-lg font-bold text-gray-900">Create</p>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-orange-600 font-semibold">Open form</p>
                    </a>

                    <a href="{{ route('hospital.inventory.index') }}" class="border border-gray-200 bg-white rounded-lg p-4 hover:shadow-xl transition">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                <i class="fas fa-boxes"></i>
                            </span>
                            <div>
                                <p class="text-xs text-gray-500">Inventory</p>
                                <p class="text-lg font-bold text-gray-900">{{ $stats['total_inventory'] ?? 0 }} units</p>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-blue-600 font-semibold">Manage stock</p>
                    </a>

                    <a href="{{ route('hospital.drives.index') }}" class="border border-gray-200 bg-white rounded-lg p-4 hover:shadow-xl transition">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <div>
                                <p class="text-xs text-gray-500">Blood Drives</p>
                                <p class="text-lg font-bold text-gray-900">Manage</p>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-indigo-600 font-semibold">Schedule events</p>
                    </a>

                    <a href="{{ route('hospital.reports.index') }}" class="border border-gray-200 bg-white rounded-lg p-4 hover:shadow-xl transition">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-green-100 text-green-600">
                                <i class="fas fa-chart-bar"></i>
                            </span>
                            <div>
                                <p class="text-xs text-gray-500">Analytics</p>
                                <p class="text-lg font-bold text-gray-900">View</p>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-green-600 font-semibold">View trends</p>
                    </a>

                    <a href="{{ route('hospital.messages.index') }}" class="border border-gray-200 bg-white rounded-lg p-4 hover:shadow-xl transition">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-100 text-red-600">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <div>
                                <p class="text-xs text-gray-500">Messages</p>
                                <p class="text-lg font-bold text-gray-900">Open</p>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-red-600 font-semibold">Communicate</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dropdown toggle functionality
        const menuDropdown = document.getElementById('menuDropdown');
        const menuDropdownContent = document.getElementById('menuDropdownContent');

        menuDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            menuDropdownContent.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!menuDropdown.contains(e.target) && !menuDropdownContent.contains(e.target)) {
                menuDropdownContent.classList.add('hidden');
            }
        });

        // Close dropdown on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                menuDropdownContent.classList.add('hidden');
            }
        });
    </script>
</body>
</html>


