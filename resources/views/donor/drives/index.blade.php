<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Blood Drives</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:opacity-80 transition" title="LifeLink - Blood Donation System">
                        <img src="{{ asset('images/lifelink-logo.svg') }}" alt="LifeLink Logo" class="lifelink-logo-nav" />
                    </a>
                </div>
                <div class="flex items-center gap-4">
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

    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Available Blood Drives</h1>
                    <p class="text-gray-600 mt-1">Book a slot and schedule your donation.</p>
                </div>
                <a href="{{ route('donor.messages.index') }}" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Messages</a>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                @forelse($availableDrives as $drive)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow">
                        <div class="flex justify-between items-start gap-2">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">{{ $drive->title }}</h2>
                                <p class="text-gray-600 text-sm">{{ Str::limit($drive->description, 100) }}</p>
                            </div>
                            <span class="text-xs {{ $drive->status === 'scheduled' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }} px-2 py-1 rounded">
                                {{ ucfirst($drive->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">{{ $drive->location }}</p>
                        <p class="text-sm text-gray-500">{{ $drive->start_time->format('M d, Y h:i A') }} - {{ $drive->end_time->format('M d, Y h:i A') }}</p>
                        <p class="text-sm text-gray-500">Capacity: {{ $drive->capacity }} · Booked: {{ $drive->confirmed_appointments_count }}</p>

                        <form action="{{ route('donor.drives.book', $drive) }}" method="POST" class="mt-3">
                            @csrf
                            <div class="grid grid-cols-1 gap-2">
                                <label class="text-sm text-gray-700">Choose slot</label>
                                <input type="datetime-local" name="slot_time" value="{{ old('slot_time', $drive->start_time->format('Y-m-d\TH:i')) }}" min="{{ $drive->start_time->format('Y-m-d\TH:i') }}" max="{{ $drive->end_time->format('Y-m-d\TH:i') }}" required class="w-full border border-gray-300 rounded px-2 py-2" />
                                <textarea name="notes" rows="2" class="w-full border border-gray-300 rounded px-2 py-2" placeholder="Notes or conditions (optional)">{{ old('notes') }}</textarea>
                                <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Book Appointment</button>
                            </div>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-500">No available drives right now.</p>
                @endforelse
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">My Appointments</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2">Drive</th>
                                <th class="px-4 py-2">Slot</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Notes</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($myAppointments as $appointment)
                                <tr>
                                    <td class="px-4 py-2">{{ $appointment->bloodDrive->title }}</td>
                                    <td class="px-4 py-2">{{ $appointment->slot_time->format('M d, Y h:i A') }}</td>
                                    <td class="px-4 py-2">{{ ucfirst($appointment->status) }}</td>
                                    <td class="px-4 py-2">{{ $appointment->notes ?? '—' }}</td>
                                    <td class="px-4 py-2">
                                        @if($appointment->status !== 'cancelled')
                                            <form action="{{ route('donor.appointments.cancel', $appointment) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="px-3 py-1 text-sm bg-gray-600 text-white rounded hover:bg-gray-700">Cancel</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-500">No actions</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">No appointments yet</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const profileDropdown = document.getElementById('profileDropdown');
        const profileMenu = document.getElementById('profileMenu');

        profileDropdown.addEventListener('click', (event) => {
            event.stopPropagation();
            profileMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', (event) => {
            if (!profileDropdown.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.classList.add('hidden');
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                profileMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>