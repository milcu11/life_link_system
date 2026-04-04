<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Inventory - LifeLink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('hospital.partials.nav')

    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Blood Inventory</h1>
                    <p class="text-gray-600 mt-1">Manage your hospital's blood stock</p>
                </div>
                <a href="{{ route('hospital.inventory.create') }}" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    <i class="fas fa-plus mr-2"></i>Add Blood Stock
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($lowStock->count() > 0)
                <div class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                    <strong>Low Stock Alert:</strong> {{ $lowStock->count() }} item(s) have less than 10 units.
                </div>
            @endif

            @if($expiringSoon->count() > 0)
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>Expiration Alert:</strong> {{ $expiringSoon->count() }} item(s) expire within 7 days.
                </div>
            @endif

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">Current Inventory</h2>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Blood Type</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Quantity</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Expiration Date</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Status</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($inventory as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">
                                                {{ $item->blood_type }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">{{ $item->quantity }} units</td>
                                        <td class="px-4 py-3">{{ $item->expiration_date->format('M d, Y') }}</td>
                                        <td class="px-4 py-3">
                                            @if($item->quantity < 10)
                                                <span class="text-yellow-600 font-medium">Low Stock</span>
                                            @elseif($item->expiration_date <= now()->addDays(7))
                                                <span class="text-red-600 font-medium">Expiring Soon</span>
                                            @else
                                                <span class="text-green-600 font-medium">Good</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('hospital.inventory.edit', $item) }}" class="text-gray-600 hover:text-red-600 mr-3">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('hospital.inventory.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to remove this inventory item?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-600 hover:text-red-600">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                            No inventory items found. <a href="{{ route('hospital.inventory.create') }}" class="text-red-600 hover:text-red-700">Add your first item</a>.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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