<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation History - Blood Donation System</title>
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
            <div class="mb-4">
                <button onclick="window.history.back()" class="back-button text-red-600 hover:text-red-500 inline-flex items-center cursor-pointer transition duration-200 hover:scale-110">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Donation History</h1>
                <p class="text-gray-600 mt-1">Track all your blood donations and their impact</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
                    <div class="p-3 rounded-lg bg-red-100">
                        <i class="fas fa-droplet text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Donations</p>
                        <h3 class="text-lg font-bold text-gray-900">{{ $donations->total() }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
                    <div class="p-3 rounded-lg bg-green-100">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Lives Potentially Saved</p>
                        <h3 class="text-lg font-bold text-gray-900">{{ $donations->total() * 3 }}</h3>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($donations as $donation)
                    <div class="bg-white rounded-lg shadow p-4 flex justify-between items-start gap-4">
                        <div class="flex gap-4">
                            <div class="shrink-0 p-3 rounded-lg bg-red-50">
                                <i class="fas fa-droplet text-red-600 text-2xl"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">{{ $donation->request ? $donation->request->blood_type : 'N/A' }}</span>
                                    <span class="text-sm text-gray-600">{{ $donation->request ? $donation->request->quantity : 'N/A' }} units</span>
                                </div>
                                <p class="text-sm text-gray-700">{{ $donation->request && $donation->request->hospital ? $donation->request->hospital->name : 'N/A' }} · <span class="text-gray-500">{{ $donation->donation_date?->format('M d, Y') ?? 'Date N/A' }}</span></p>
                                @if($donation->notes)
                                    <p class="text-sm text-gray-600 mt-2">{{ $donation->notes }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{
                                $donation->status == 'completed' ? 'bg-green-100 text-green-700' :
                                ($donation->status == 'accepted' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700')
                            }}">{{ ucfirst($donation->status) }}</span>
                            <div class="text-xs text-gray-500 mt-2">{{ $donation->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow p-6 text-center text-gray-600">
                        <div class="flex justify-center mb-4">
                            <img src="{{ asset('images/lifelink-logo.svg') }}" alt="LifeLink Logo" class="lifelink-logo-form" />
                        </div>
                        <h3 class="text-lg font-semibold">No Donation History</h3>
                        <p class="mt-2">Your donation history will appear here once you start donating.</p>
                        <a href="{{ route('donor.requests') }}" class="inline-block mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            <i class="fas fa-bell mr-2"></i>View Available Requests
                        </a>
                    </div>
                @endforelse
            </div>

            @if($donations->hasPages())
                <div class="mt-6">
                    {{ $donations->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }

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


