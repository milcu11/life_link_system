<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message {{ $donor->name }} - LifeLink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('hospital.partials.nav')

    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <button onclick="window.history.back()" class="back-button text-red-600 hover:text-red-500 inline-flex items-center cursor-pointer transition duration-200 hover:scale-110">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Message {{ $donor->name }}</h1>
                    <p class="text-gray-600 mt-1">Communicate with this matched donor</p>
                </div>
                <a href="{{ route('hospital.messages.index') }}" class="px-4 py-2 text-gray-600 hover:text-red-600">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Messages
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Related Requests -->
            @if($relatedRequests->count() > 0)
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">Related Requests:</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($relatedRequests as $request)
                            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                {{ $request->blood_type }} - {{ ucfirst($request->urgency_level) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Messages -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">Conversation</h2>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    @forelse($messages as $message)
                        <div class="mb-4 {{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                            <div class="inline-block max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-900' }}">
                                <div class="text-xs font-medium mb-1 {{ $message->sender_id === auth()->id() ? 'text-red-100' : 'text-gray-600' }}">
                                    {{ $message->subject }}
                                    @if($message->type !== 'message')
                                        <span class="ml-2 px-2 py-1 rounded text-xs {{ $message->type === 'urgent' ? 'bg-red-500 text-white' : 'bg-blue-500 text-white' }}">
                                            {{ ucfirst($message->type) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="text-sm">{{ $message->message }}</div>
                                <div class="text-xs mt-1 {{ $message->sender_id === auth()->id() ? 'text-red-100' : 'text-gray-500' }}">
                                    {{ $message->created_at->format('M d, H:i') }}
                                    @if($message->sender_id !== auth()->id() && $message->isRead())
                                        <i class="fas fa-check ml-1"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-comments text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">No messages yet. Start the conversation below.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Send Message Form -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Send Message</h3>
                <form action="{{ route('hospital.messages.store', $donor) }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Message Type</label>
                            <select id="type" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500" required>
                                <option value="message" {{ old('type') == 'message' ? 'selected' : '' }}>General Message</option>
                                <option value="reminder" {{ old('type') == 'reminder' ? 'selected' : '' }}>Reminder</option>
                                <option value="coordination" {{ old('type') == 'coordination' ? 'selected' : '' }}>Coordination</option>
                                <option value="urgent" {{ old('type') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <div>
                            <label for="request_id" class="block text-sm font-medium text-gray-700 mb-2">Related Request (Optional)</label>
                            <select id="request_id" name="request_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500">
                                <option value="">Select request</option>
                                @foreach($relatedRequests as $request)
                                    <option value="{{ $request->id }}" {{ old('request_id') == $request->id ? 'selected' : '' }}>
                                        {{ $request->blood_type }} - {{ ucfirst($request->urgency_level) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <input type="text" id="subject" name="subject" value="{{ old('subject') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500" required>
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea id="message" name="message" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500" required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            <i class="fas fa-paper-plane mr-2"></i>Send Message
                        </button>
                    </div>
                </form>
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

        // Auto-scroll to bottom of messages
        const messagesContainer = document.querySelector('.max-h-96');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>