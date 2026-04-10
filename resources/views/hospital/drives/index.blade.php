<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Drives - Hospital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('hospital.partials.nav')

    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <button onclick="window.history.back()" class="back-button text-red-600 hover:text-red-500 inline-flex items-center cursor-pointer transition duration-200 hover:scale-110">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Blood Drives</h1>
                    <p class="text-gray-600 mt-1">Manage and schedule blood collection events.</p>
                </div>
                <a href="{{ route('hospital.drives.create') }}" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    <i class="fas fa-plus mr-2"></i>New Drive
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-4">
                        @forelse($drives as $drive)
                            <div class="border border-gray-200 rounded-lg p-4 flex flex-col md:flex-row justify-between gap-4">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900">{{ $drive->title }}</h2>
                                    <p class="text-gray-600 text-sm">{{ Str::limit($drive->description, 120) }}</p>
                                    <p class="text-sm text-gray-500 mt-1">Location: {{ $drive->location }}</p>
                                    <p class="text-sm text-gray-500">{{ $drive->start_time->format('M d, Y h:i A') }} - {{ $drive->end_time->format('M d, Y h:i A') }}</p>
                                    <p class="text-sm text-gray-500">Capacity: {{ $drive->capacity }} · Confirmed: {{ $drive->confirmed_appointments_count }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('hospital.drives.show', $drive) }}" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">View</a>
                                    <a href="{{ route('hospital.drives.edit', $drive) }}" class="px-3 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
                                    <form action="{{ route('hospital.drives.cancel', $drive) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No blood drives scheduled yet. Use "New Drive" to create one.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    function goBack() {
        window.history.back();
    }
</script>