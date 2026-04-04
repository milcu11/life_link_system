<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Requests - Blood Donation System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                    <img src="{{ asset('images/lifelink-logo.svg') }}" alt="LifeLink Logo" class="lifelink-logo-nav" />
                </a>
                <div class="flex items-center gap-4">
                    <a href="{{ route('donor.messages.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium px-3 py-2 rounded-md hover:bg-gray-100 transition">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <div style="width: 1px; height: 24px; background-color: #999;"></div>
                    <div class="relative pl-0">
                        <button id="profileDropdown" class="text-gray-600 hover:text-red-600 font-medium flex items-center gap-2 focus:outline-none cursor-pointer">
                            <i class="fas fa-user-circle"></i>{{ auth()->user()->name }}
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="profileMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 hidden">
                            <a href="{{ route('donor.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-user-edit mr-2"></i>Edit Profile
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
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Available Blood Requests</h1>
                <p class="text-gray-600 mt-1">Respond to urgent blood donation requests near you</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($matches as $match)
                    <div class="bg-white rounded-lg shadow p-4 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">{{ $match->request->blood_type }}</span>
                                    <span class="text-xs text-gray-500">{{ $match->request->quantity }} units</span>
                                </div>
                                <span class="text-xs font-semibold uppercase px-2 py-1 rounded {{
                                    $match->request->urgency_level == 'critical' ? 'bg-red-100 text-red-700' :
                                    ($match->request->urgency_level == 'high' ? 'bg-orange-100 text-orange-700' :
                                    ($match->request->urgency_level == 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700'))
                                }}">{{ ucfirst($match->request->urgency_level) }}</span>
                            </div>

                            <h3 class="font-semibold text-gray-900 mb-1">{{ $match->request->hospital->name }}</h3>
                            <p class="text-sm text-gray-600 mb-2"><i class="fas fa-map-marker-alt text-red-600 mr-2"></i>{{ $match->request->location }}</p>
                            <p class="text-sm text-gray-600 mb-2"><i class="fas fa-road text-red-600 mr-2"></i>{{ number_format($match->distance, 1) }} km away</p>
                            @if($match->request->needed_by)
                                <p class="text-sm text-gray-600 mb-2"><i class="fas fa-clock text-red-600 mr-2"></i>Needed by {{ \Carbon\Carbon::parse($match->request->needed_by)->format('M d, Y') }}</p>
                            @endif
                            <p class="text-sm text-gray-600 mb-2"><i class="fas fa-user text-red-600 mr-2"></i>{{ $match->request->contact_person }}</p>
                            <p class="text-sm text-gray-600 mb-4"><i class="fas fa-phone text-red-600 mr-2"></i>{{ $match->request->contact_phone }}</p>

                            @if($match->request->notes)
                                <div class="bg-gray-50 p-3 rounded mb-3 text-sm text-gray-700">{{ $match->request->notes }}</div>
                            @endif
                        </div>

                        <div class="mt-4 flex gap-2">
                            <form action="{{ route('donor.respond', $match) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="response" value="accepted">
                                <button type="submit" class="w-full px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-medium">
                                    <i class="fas fa-check mr-1"></i>Accept
                                </button>
                            </form>
                            <form action="{{ route('donor.respond', $match) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="response" value="rejected">
                                <button type="submit" class="w-full px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm font-medium">
                                    <i class="fas fa-times mr-1"></i>Decline
                                </button>
                            </form>
                        </div>

                        <div class="text-xs text-gray-400 mt-3">Posted {{ $match->request->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-lg shadow p-6 text-center text-gray-600">
                        <i class="fas fa-inbox text-3xl text-gray-400 mb-3"></i>
                        <h3 class="text-lg font-semibold">No Requests Available</h3>
                        <p class="mt-2">There are currently no blood requests matching your profile. Check back later.</p>
                    </div>
                @endforelse
            </div>

            @if($matches->hasPages())
                <div class="mt-6">
                    {{ $matches->links() }}
                </div>
            @endif
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


