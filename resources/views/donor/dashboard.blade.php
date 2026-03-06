<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard - Blood Donation System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <svg class="h-8 w-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                    <span class="text-xl font-bold text-gray-900">LifeLink</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('donor.profile') }}" class="text-gray-600 hover:text-red-600 font-medium">
                        <i class="fas fa-user-circle mr-2"></i>{{ auth()->user()->name }}
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-red-600 font-medium">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}!</h1>
                    <p class="text-gray-600 mt-2">Track your donations and help save lives</p>
                </div>
                <div class="flex items-center gap-3 bg-white p-4 rounded-lg shadow">
                    <label for="availability" class="font-medium text-gray-700">Availability</label>
                    <form action="{{ route('donor.availability') }}" method="POST" id="availabilityForm">
                        @csrf
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_available" {{ $donor->is_available ? 'checked' : '' }} onchange="document.getElementById('availabilityForm').submit()" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                        </label>
                    </form>
                    <span class="text-sm font-semibold {{ $donor->is_available ? 'text-green-600' : 'text-gray-600' }}">
                        {{ $donor->is_available ? 'Available' : 'Unavailable' }}
                    </span>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-red-100">
                            <i class="fas fa-heart text-red-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Total Donations</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_donations'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-orange-100">
                            <i class="fas fa-bell text-orange-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Pending Requests</p>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $stats['pending_matches'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg bg-pink-100">
                            <i class="fas fa-calendar-check text-pink-600 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Last Donation</p>
                            <h3 class="text-xl font-bold text-gray-900">{{ $stats['last_donation'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-lg {{ $stats['can_donate'] ? 'bg-green-100' : 'bg-gray-100' }}">
                            <i class="fas fa-{{ $stats['can_donate'] ? 'check-circle text-green-600' : 'clock text-gray-600' }} text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Status</p>
                            <h3 class="text-xl font-bold {{ $stats['can_donate'] ? 'text-green-600' : 'text-gray-600' }}">
                                {{ $stats['can_donate'] ? 'Can Donate' : 'Wait Period' }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Pending Matches -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900">
                            <i class="fas fa-hand-holding-medical text-red-600 mr-2"></i>Pending Requests
                        </h2>
                        <a href="{{ route('donor.requests') }}" class="text-red-600 hover:text-red-700 text-sm font-medium">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="p-6">
                        @forelse($pendingMatches as $match)
                            <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:border-red-200 hover:shadow transition">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">
                                            {{ $match->request->blood_type }}
                                        </span>
                                        <span class="inline-block ml-2 px-2 py-1 rounded text-xs font-semibold uppercase {{ 
                                            $match->request->urgency_level == 'critical' ? 'bg-red-100 text-red-700 animate-pulse' :
                                            ($match->request->urgency_level == 'high' ? 'bg-orange-100 text-orange-700' :
                                            ($match->request->urgency_level == 'medium' ? 'bg-yellow-100 text-yellow-700' :
                                            'bg-green-100 text-green-700'))
                                        }}">
                                            {{ ucfirst($match->request->urgency_level) }}
                                        </span>
                                    </div>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2">{{ $match->request->hospital->name }}</h4>
                                <div class="text-sm text-gray-600 space-y-1 mb-4">
                                    <p><i class="fas fa-map-marker-alt text-red-600 w-4"></i> {{ $match->request->location }}</p>
                                    <p><i class="fas fa-road text-red-600 w-4"></i> {{ number_format($match->distance, 1) }} km away</p>
                                    <p><i class="fas fa-clock text-red-600 w-4"></i> {{ $match->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <form action="{{ route('donor.respond', $match) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="response" value="accepted">
                                        <button type="submit" class="flex-1 px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-medium">
                                            <i class="fas fa-check mr-1"></i>Accept
                                        </button>
                                    </form>
                                    <form action="{{ route('donor.respond', $match) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="response" value="rejected">
                                        <button type="submit" class="flex-1 px-3 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 text-sm font-medium">
                                            <i class="fas fa-times mr-1"></i>Decline
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2 block"></i>
                                <p>No pending requests at the moment</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Donor Profile Summary -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">
                            <i class="fas fa-user-circle text-red-600 mr-2"></i>Your Profile
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Blood Type</p>
                            <div class="inline-block bg-red-100 text-red-800 px-4 py-2 rounded-lg text-lg font-bold">
                                {{ $donor->blood_type }}
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Phone</p>
                            <p class="text-gray-900 font-medium">{{ $donor->phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Gender</p>
                            <p class="text-gray-900 font-medium">{{ ucfirst($donor->gender ?? 'Not provided') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Address</p>
                            <p class="text-gray-900 text-sm">{{ $donor->address ?? 'Not provided' }}</p>
                        </div>
                        <a href="{{ route('donor.profile') }}" class="block w-full mt-6 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-center font-medium transition">
                            <i class="fas fa-edit mr-2"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Donations -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">
                        <i class="fas fa-history text-red-600 mr-2"></i>Recent Donations
                    </h2>
                    <a href="{{ route('donor.history') }}" class="text-red-600 hover:text-red-700 text-sm font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Blood Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Hospital</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentDonations as $donation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">
                                            {{ $donation->request->blood_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $donation->request->hospital->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $donation->donation_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ 
                                            $donation->status == 'completed' ? 'bg-green-100 text-green-700' :
                                            ($donation->status == 'accepted' ? 'bg-blue-100 text-blue-700' :
                                            'bg-gray-100 text-gray-700')
                                        }}">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-heart text-2xl mb-2 block"></i>
                                        <p>No donations yet. Start saving lives today!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>