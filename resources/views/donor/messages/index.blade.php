<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - LifeLink</title>
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
            <div class="mb-6 md:mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Messages</h1>
                    <p class="text-gray-600 mt-1 text-sm md:text-base">Communicate with hospitals</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 md:mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">Conversations</h2>
                </div>
                <div class="p-4 md:p-6">
                    <div class="space-y-4">
                        @forelse($conversations as $conversation)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $conversation['hospital']->name }}</h3>
                                            @if($conversation['unread_count'] > 0)
                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">
                                                    {{ $conversation['unread_count'] }} unread
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-600 mb-2">
                                            @foreach($conversation['requests'] as $request)
                                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs mr-2">
                                                    {{ $request->blood_type }} - {{ ucfirst($request->urgency_level) }}
                                                </span>
                                            @endforeach
                                        </div>
                                        @if($conversation['latest_message'])
                                            <p class="text-gray-700 text-sm">
                                                <strong>{{ $conversation['latest_message']->subject }}:</strong>
                                                {{ Str::limit($conversation['latest_message']->message, 100) }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $conversation['latest_message']->created_at->diffForHumans() }}
                                            </p>
                                        @else
                                            <p class="text-gray-500 text-sm italic">No messages yet</p>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('donor.messages.show', $conversation['hospital']) }}" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                                            <i class="fas fa-eye mr-2"></i>View Conversation
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-envelope text-gray-300 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No conversations yet</h3>
                                <p class="text-gray-500">Once hospitals contact you about donation requests, you'll see messages here.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Profile dropdown functionality
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