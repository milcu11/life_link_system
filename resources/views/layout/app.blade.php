<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LifeLink - Blood Donation System')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Navigation Bar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                        <svg class="h-8 w-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                        <span class="text-xl font-bold text-gray-900">LifeLink</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center gap-8">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium transition {{ request()->routeIs('admin.dashboard') ? 'text-red-600' : '' }}">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                            <a href="{{ route('admin.donors') }}" class="flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium transition {{ request()->routeIs('admin.donors') ? 'text-red-600' : '' }}">
                                <i class="fas fa-users"></i> Donors
                            </a>
                            <a href="{{ route('admin.requests') }}" class="flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium transition {{ request()->routeIs('admin.requests') ? 'text-red-600' : '' }}">
                                <i class="fas fa-hand-holding-medical"></i> Requests
                            </a>
                            <a href="{{ route('admin.map') }}" class="flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium transition {{ request()->routeIs('admin.map') ? 'text-red-600' : '' }}">
                                <i class="fas fa-map-marked-alt"></i> Map
                            </a>
                            <a href="{{ route('admin.reports') }}" class="flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium transition {{ request()->routeIs('admin.reports') ? 'text-red-600' : '' }}">
                                <i class="fas fa-file-alt"></i> Reports
                            </a>
                        @elseif(auth()->user()->isHospital())
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium transition {{ request()->routeIs('dashboard') ? 'text-red-600' : '' }}">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                            <a href="{{ route('hospital.requests.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium transition {{ request()->routeIs('hospital.requests.*') ? 'text-red-600' : '' }}">
                                <i class="fas fa-hand-holding-medical"></i> My Requests
                            </a>
                            <a href="{{ route('hospital.requests.create') }}" class="flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium transition">
                                <i class="fas fa-plus-circle"></i> New Request
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium transition {{ request()->routeIs('dashboard') ? 'text-red-600' : '' }}">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                            <a href="{{ route('donor.profile') }}" class="flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium transition {{ request()->routeIs('donor.profile') ? 'text-red-600' : '' }}">
                                <i class="fas fa-user"></i> Profile
                            </a>
                            <a href="{{ route('donor.requests') }}" class="flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium transition {{ request()->routeIs('donor.requests') ? 'text-red-600' : '' }}">
                                <i class="fas fa-bell"></i> Requests
                            </a>
                            <a href="{{ route('donor.history') }}" class="flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium transition {{ request()->routeIs('donor.history') ? 'text-red-600' : '' }}">
                                <i class="fas fa-history"></i> History
                            </a>
                        @endif
                    @endauth
                </div>

                <!-- User Menu -->
                @auth
                    <div class="flex items-center gap-4">
                        <button onclick="toggleUserMenu()" class="flex items-center gap-2 text-gray-700 hover:text-red-600 transition">
                            <i class="fas fa-user-circle text-xl"></i>
                            <span class="text-sm font-medium hidden sm:inline">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="userDropdown" class="hidden absolute top-16 right-4 bg-white rounded-lg shadow-lg py-2 w-48">
                            <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 text-sm">
                                <i class="fas fa-bell text-red-600 mr-2"></i> Notifications
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 text-sm border-t border-gray-200">
                                    <i class="fas fa-sign-out-alt text-red-600 mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800 max-w-7xl mx-auto">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800 max-w-7xl mx-auto">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg text-blue-800 max-w-7xl mx-auto">
                <i class="fas fa-info-circle mr-2"></i>
                {{ session('info') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const userBtn = event.target.closest('button');
            if (!userBtn && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    @stack('styles')
    @stack('scripts')
</body>
</html>