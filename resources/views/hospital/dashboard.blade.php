<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Dashboard - Blood Donation System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <svg class="h-8 w-8 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <span class="text-xl font-bold text-gray-900">LifeLink</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('hospital.requests.index') }}" class="text-gray-600 hover:text-red-600 font-medium">
                        <i class="fas fa-list mr-2"></i>My Requests
                    </a>
                    <a href="{{ route('hospital.requests.create') }}" class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        <i class="fas fa-plus-circle mr-2"></i>New Request
                    </a>
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
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Hospital Dashboard</h1>
                    <p class="text-gray-600 mt-1">{{ auth()->user()->name }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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
        </div>
    </div>
</body>
</html>