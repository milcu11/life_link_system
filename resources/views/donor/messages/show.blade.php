<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation - LifeLink</title>
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
            <div class="mb-6 md:mb-8">
                <div class="flex items-center gap-4 mb-4">
                    <a href="{{ route('donor.messages.index') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Messages
                    </a>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Conversation with {{ $hospital->name }}</h1>
                    <p class="text-gray-600 mt-1 text-sm md:text-base">Coordinate your donation</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 md:mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 md:mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Related Requests -->
            @if($requests->count() > 0)
                <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
                    <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900">Related Donation Requests</h2>
                    </div>
                    <div class="p-4 md:p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($requests as $request)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-gray-900">{{ $request->blood_type }}</span>
                                        <span class="px-2 py-1 rounded text-xs font-medium
                                            @if($request->urgency_level === 'critical') bg-red-100 text-red-800
                                            @elseif($request->urgency_level === 'high') bg-orange-100 text-orange-800
                                            @elseif($request->urgency_level === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($request->urgency_level) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">{{ $request->description }}</p>
                                    <p class="text-xs text-gray-500">
                                        Requested: {{ $request->created_at->format('M j, Y') }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Messages -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">Messages</h2>
                </div>
                <div class="p-4 md:p-6">
                    <div id="messagesContainer" class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                        @forelse($messages as $message)
                            <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-xs lg:max-w-md xl:max-w-lg {{ $message->sender_id === auth()->id() ? 'order-2' : 'order-1' }}">
                                    <div class="bg-{{ $message->sender_id === auth()->id() ? 'blue' : 'gray' }}-100 border border-{{ $message->sender_id === auth()->id() ? 'blue' : 'gray' }}-200 rounded-lg p-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="font-semibold text-sm text-gray-900">
                                                {{ $message->sender_id === auth()->id() ? 'You' : $hospital->name }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ $message->created_at->format('M j, g:i A') }}
                                            </span>
                                        </div>
                                        <h4 class="font-medium text-gray-900 mb-2">{{ $message->subject }}</h4>
                                        <p class="text-gray-700 whitespace-pre-wrap">{{ $message->message }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-comments text-gray-300 text-4xl mb-4"></i>
                                <p class="text-gray-500">No messages in this conversation yet.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Reply Form -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Send Reply</h3>
                        <form action="{{ route('donor.messages.store', $hospital) }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="message">
                            @if($requests->count() > 0)
                                <input type="hidden" name="request_id" value="{{ $requests->first()->id }}">
                            @endif
                            <div class="mb-4">
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                                <textarea id="message" name="message" rows="4" required
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Type your message here...">{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <i class="fas fa-paper-plane mr-2"></i>Send Message
                                </button>
                            </div>
                        </form>
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

        // Auto-scroll to bottom of messages
        const messagesContainer = document.getElementById('messagesContainer');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    </script>
</body>
</html>