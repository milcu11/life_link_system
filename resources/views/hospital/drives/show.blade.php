<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $drive->title }} - Blood Drive</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('hospital.partials.nav')

    <div class="min-h-screen py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <button onclick="window.history.back()" class="back-button text-red-600 hover:text-red-500 inline-flex items-center cursor-pointer transition duration-200 hover:scale-110">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $drive->title }}</h1>
                    <p class="text-gray-600 mt-1">{{ $drive->location }} · {{ $drive->start_time->format('M d, Y h:i A') }} - {{ $drive->end_time->format('M d, Y h:i A') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('hospital.drives.edit', $drive) }}" class="px-3 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
                    <a href="{{ route('hospital.drives.index') }}" class="px-3 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Back</a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900">Details</h2>
                <p class="text-gray-700 mt-2">{{ $drive->description }}</p>
                <p class="text-sm text-gray-500 mt-2">Capacity: {{ $drive->capacity }}</p>
                <p class="text-sm text-gray-500">Status: {{ ucfirst($drive->status) }}</p>
                <p class="text-sm text-gray-500">Confirmed appointments: {{ $drive->confirmedAppointments()->count() }}</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Donor Appointments</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2">Donor</th>
                                <th class="px-4 py-2">Slot Time</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($drive->appointments as $appointment)
                                <tr>
                                    <td class="px-4 py-3">{{ $appointment->donor->name }}</td>
                                    <td class="px-4 py-3">{{ $appointment->slot_time->format('M d, Y h:i A') }}</td>
                                    <td class="px-4 py-3">{{ ucfirst($appointment->status) }}</td>
                                    <td class="px-4 py-3">{{ $appointment->notes ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">No appointments yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>