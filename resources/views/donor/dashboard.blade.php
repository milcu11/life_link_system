<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard - Blood Donation System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        form[action*="logout"] button {
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-3 md:px-4 lg:px-8">
            <div class="flex justify-between items-center h-14 md:h-16">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                    <img src="{{ asset('images/lifelink-logo.svg') }}" alt="LifeLink Logo" class="lifelink-logo-nav" />
                </a>
                <div class="flex items-center gap-2 md:gap-4">
                    <a href="{{ route('donor.messages.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium px-3 py-2 rounded-md hover:bg-gray-100 transition text-xs md:text-base">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <div style="width: 1px; height: 24px; background-color: #999;"></div>
                    <div class="relative pl-0">
                        <button id="profileDropdown" class="text-gray-600 hover:text-red-600 font-medium flex items-center gap-1 md:gap-2 focus:outline-none text-xs md:text-base cursor-pointer">
                            <i class="fas fa-user-circle"></i>
                            <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="profileMenu" class="absolute right-0 mt-2 w-40 md:w-48 bg-white rounded-md shadow-lg z-50 hidden">
                            <a href="{{ route('donor.profile') }}" class="block px-3 md:px-4 py-2 text-xs md:text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-user-edit mr-2"></i>Edit Profile
                            </a>
                            <a href="{{ route('donor.drives.index') }}" class="block px-3 md:px-4 py-2 text-xs md:text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-calendar-check mr-2"></i>Blood Drives
                            </a>
                            <hr class="border-gray-200">
                            <form action="{{ route('logout') }}" method="POST" style="display: block;">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 md:px-4 py-2 text-xs md:text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="min-h-screen py-4 md:py-8">
        <div class="max-w-7xl mx-auto px-3 md:px-4 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Welcome, {{ auth()->user()->name }}!</h1>
                    <p class="text-gray-600 mt-2 text-sm md:text-base">Track your donations and help save lives</p>
                </div>
                <div class="flex items-center gap-3 bg-white p-3 md:p-4 rounded-lg shadow w-full md:w-auto">
                    <label for="availability" class="font-medium text-gray-700 text-sm md:text-base">Availability</label>
                    <form action="{{ route('donor.availability') }}" method="POST" id="availabilityForm">
                        @csrf
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_available" {{ $donor->is_available ? 'checked' : '' }} onchange="document.getElementById('availabilityForm').submit()" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                        </label>
                    </form>
                    <span class="text-xs md:text-sm font-semibold {{ $donor->is_available ? 'text-green-600' : 'text-gray-600' }}">
                        {{ $donor->is_available ? 'Available' : 'Unavailable' }}
                    </span>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-3 md:p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:gap-4">
                        <div class="p-2 md:p-3 rounded-lg bg-red-100 w-fit">
                            <i class="fas fa-heart text-red-600 text-lg md:text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs md:text-sm font-medium">Total Donations</p>
                            <h3 class="text-xl md:text-2xl font-bold text-gray-900">{{ $stats['total_donations'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-3 md:p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:gap-4">
                        <div class="p-2 md:p-3 rounded-lg bg-orange-100 w-fit">
                            <i class="fas fa-bell text-orange-600 text-lg md:text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs md:text-sm font-medium">Pending Requests</p>
                            <h3 class="text-xl md:text-2xl font-bold text-gray-900">{{ $stats['pending_matches'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-3 md:p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:gap-4">
                        <div class="p-2 md:p-3 rounded-lg bg-pink-100 w-fit">
                            <i class="fas fa-calendar-check text-pink-600 text-lg md:text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs md:text-sm font-medium">Last Donation</p>
                            <h3 class="text-lg md:text-xl font-bold text-gray-900">{{ $stats['last_donation'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-3 md:p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:gap-4">
                        <div class="p-2 md:p-3 rounded-lg {{ $stats['can_donate'] ? 'bg-green-100' : 'bg-gray-100' }} w-fit">
                            <i class="fas fa-{{ $stats['can_donate'] ? 'check-circle text-green-600' : 'clock text-gray-600' }} text-lg md:text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs md:text-sm font-medium">Status</p>
                            <h3 class="text-lg md:text-xl font-bold {{ $stats['can_donate'] ? 'text-green-600' : 'text-gray-600' }}">
                                {{ $stats['can_donate'] ? 'Can Donate' : 'Wait Period' }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
                <!-- Pending Matches -->
                <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                        <h2 class="text-base md:text-lg font-bold text-gray-900">
                            <i class="fas fa-hand-holding-medical text-red-600 mr-2"></i>Pending Requests
                        </h2>
                        <a href="{{ route('donor.requests') }}" class="text-red-600 hover:text-red-700 text-xs md:text-sm font-medium">
                            View All <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="p-3 md:p-6">
                        @forelse($pendingMatches as $match)
                            <div class="border border-gray-200 rounded-lg p-3 md:p-4 mb-4 hover:border-red-200 hover:shadow transition">
                                <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-3 gap-2">
                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-block bg-red-100 text-red-800 px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-bold">
                                            {{ $match->request->blood_type }}
                                        </span>
                                        <span class="inline-block px-2 py-1 rounded text-xs font-semibold uppercase {{ 
                                            $match->request->urgency_level == 'critical' ? 'bg-red-100 text-red-700 animate-pulse' :
                                            ($match->request->urgency_level == 'high' ? 'bg-orange-100 text-orange-700' :
                                            ($match->request->urgency_level == 'medium' ? 'bg-yellow-100 text-yellow-700' :
                                            'bg-green-100 text-green-700'))
                                        }}">
                                            {{ ucfirst($match->request->urgency_level) }}
                                        </span>
                                    </div>
                                </div>
                                <h4 class="font-semibold text-gray-900 mb-2 text-sm md:text-base">{{ $match->request->hospital->name }}</h4>
                                <div class="text-xs md:text-sm text-gray-600 space-y-1 mb-4">
                                    <p><i class="fas fa-map-marker-alt text-red-600 w-4"></i> {{ $match->request->location }}</p>
                                    <p><i class="fas fa-road text-red-600 w-4"></i> {{ number_format($match->distance, 1) }} km away</p>
                                    <p><i class="fas fa-clock text-red-600 w-4"></i> {{ $match->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex flex-col md:flex-row gap-2">
                                    <form action="{{ route('donor.respond', $match) }}" method="POST" style="display: inline;" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="response" value="accepted">
                                        <button type="submit" class="w-full px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-xs md:text-sm font-medium">
                                            <i class="fas fa-check mr-1"></i>Accept
                                        </button>
                                    </form>
                                    <form action="{{ route('donor.respond', $match) }}" method="POST" style="display: inline;" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="response" value="rejected">
                                        <button type="submit" class="w-full px-3 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 text-xs md:text-sm font-medium">
                                            <i class="fas fa-times mr-1"></i>Decline
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-inbox text-2xl md:text-3xl mb-2 block"></i>
                                <p class="text-sm md:text-base">No pending requests at the moment</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Donor Profile Summary -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
                        <h2 class="text-base md:text-lg font-bold text-gray-900">
                            <i class="fas fa-user-circle text-red-600 mr-2"></i>Your Profile
                        </h2>
                    </div>
                    <div class="p-4 md:p-6 space-y-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Blood Type</p>
                            <div class="inline-block bg-red-100 text-red-800 px-3 md:px-4 py-2 rounded-lg text-base md:text-lg font-bold">
                                {{ $donor->blood_type }}
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Phone</p>
                            <p class="text-gray-900 font-medium text-sm md:text-base">{{ $donor->phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Gender</p>
                            <p class="text-gray-900 font-medium text-sm md:text-base">{{ ucfirst($donor->gender ?? 'Not provided') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Address</p>
                            <p class="text-gray-900 text-xs md:text-sm">{{ $donor->address ?? 'Not provided' }}</p>
                        </div>
                        <a href="{{ route('donor.profile') }}" class="block w-full mt-6 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-center font-medium transition text-sm md:text-base">
                            <i class="fas fa-edit mr-2"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Donations -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-4 md:px-6 py-3 md:py-4 border-b border-gray-200 flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <h2 class="text-base md:text-lg font-bold text-gray-900">
                        <i class="fas fa-history text-red-600 mr-2"></i>Recent Donations
                    </h2>
                    <a href="{{ route('donor.history') }}" class="text-red-600 hover:text-red-700 text-xs md:text-sm font-medium">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-max md:min-w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-semibold text-gray-700 uppercase">Blood Type</th>
                                <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-semibold text-gray-700 uppercase">Hospital</th>
                                <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date</th>
                                <th class="px-3 md:px-6 py-2 md:py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentDonations as $donation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 md:px-6 py-2 md:py-4">
                                        <span class="inline-block bg-red-100 text-red-800 px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-bold">
                                            {{ optional($donation->request)->blood_type ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-3 md:px-6 py-2 md:py-4 text-xs md:text-sm text-gray-900">
                                        {{ optional($donation->request->hospital)->name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-3 md:px-6 py-2 md:py-4 text-xs md:text-sm text-gray-600">{{ $donation->donation_date->format('M d, Y') }}</td>
                                    <td class="px-3 md:px-6 py-2 md:py-4">
                                        <span class="inline-block px-2 md:px-3 py-1 rounded-full text-xs font-semibold {{ 
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
                                    <td colspan="4" class="px-3 md:px-6 py-4 md:py-8 text-center text-gray-500">
                                        <i class="fas fa-heart text-xl md:text-2xl mb-2 block"></i>
                                        <p class="text-xs md:text-sm">No donations yet. Start saving lives today!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dropdown toggle functionality
        const profileDropdown = document.getElementById('profileDropdown');
        const profileMenu = document.getElementById('profileMenu');

        profileDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileDropdown.contains(e.target) && !profileMenu.contains(e.target)) {
                profileMenu.classList.add('hidden');
            }
        });

        // Close dropdown on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                profileMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>


