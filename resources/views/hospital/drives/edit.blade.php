<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blood Drive</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('hospital.partials.nav')

    <div class="min-h-screen py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <button onclick="window.history.back()" class="back-button text-red-600 hover:text-red-500 inline-flex items-center cursor-pointer transition duration-200 hover:scale-110">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Edit Blood Drive</h1>

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('hospital.drives.update', $drive) }}" method="POST" class="bg-white p-6 rounded-lg shadow">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" value="{{ old('title', $drive->title) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('description', $drive->description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" name="location" value="{{ old('location', $drive->location) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Start Time</label>
                            <input type="datetime-local" name="start_time" value="{{ old('start_time', $drive->start_time->format('Y-m-d\TH:i')) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">End Time</label>
                            <input type="datetime-local" name="end_time" value="{{ old('end_time', $drive->end_time->format('Y-m-d\TH:i')) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Capacity</label>
                        <input type="number" name="capacity" min="1" value="{{ old('capacity', $drive->capacity) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                            <option value="scheduled" {{ old('status', $drive->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="completed" {{ old('status', $drive->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $drive->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <a href="{{ route('hospital.drives.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Update Drive</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>