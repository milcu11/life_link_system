<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Blood Donation System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <svg class="h-8 w-8 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <span class="text-xl font-bold text-gray-900">LifeLink Admin</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-red-600 font-medium">Dashboard</a>
                    <a href="{{ route('admin.users') }}" class="text-gray-600 hover:text-red-600 font-medium">Users</a>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-red-600 font-medium">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
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
                                    <p class="text-sm text-gray-600">{{ $donation->donor->blood_type }} • {{ $donation->request->quantity }} units</p>
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
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.donors') }}" class="px-4 py-3 bg-red-600 text-white rounded-lg text-center">Manage Donors</a>
                    <a href="{{ route('admin.requests') }}" class="px-4 py-3 bg-orange-500 text-white rounded-lg text-center">Manage Requests</a>
                    <a href="{{ route('admin.map') }}" class="px-4 py-3 bg-indigo-600 text-white rounded-lg text-center">View Map</a>
                    <a href="{{ route('admin.reports') }}" class="px-4 py-3 bg-green-600 text-white rounded-lg text-center">Generate Reports</a>
                    <a href="{{ route('admin.users') }}" class="px-4 py-3 bg-gray-700 text-white rounded-lg text-center">Manage Users</a>
                    <a href="{{ route('admin.donations') }}" class="px-4 py-3 bg-pink-600 text-white rounded-lg text-center">View Donations</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>