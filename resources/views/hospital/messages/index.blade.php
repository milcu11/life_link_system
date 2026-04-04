<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - LifeLink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('hospital.partials.nav')

    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
                    <p class="text-gray-600 mt-1">Communicate with matched donors</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">Conversations</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse($conversations as $conversation)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $conversation['donor']->name }}</h3>
                                            @if($conversation['unread_count'] > 0)
                                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">
                                                    {{ $conversation['unread_count'] }} unread
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-600 mb-2">
                                            @foreach($conversation['matchings'] as $matching)
                                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs mr-2">
                                                    {{ $matching->request->blood_type }} - {{ ucfirst($matching->request->urgency_level) }}
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
                                        <a href="{{ route('hospital.messages.show', $conversation['donor']) }}" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                            <i class="fas fa-eye mr-2"></i>View Conversation
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <i class="fas fa-envelope text-gray-300 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No conversations yet</h3>
                                <p class="text-gray-500">Once you have matched donors, you'll be able to communicate with them here.</p>
                            </div>
                        @endforelse
                    </div>
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