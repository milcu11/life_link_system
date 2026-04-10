<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests - Hospital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/lifelink-logo.svg') }}" alt="LifeLink Logo" class="lifelink-logo-nav" />
                </a>
                <div class="flex items-center gap-4">
                    <a href="{{ route('hospital.messages.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-red-600 font-medium px-3 py-2 rounded-md hover:bg-gray-100 transition">
                        <i class="fas fa-envelope"></i>
                    </a>
                    <div style="width: 1px; height: 24px; background-color: #999;"></div>
                    <div class="relative pl-0">
                        <button id="menuDropdown" class="text-gray-600 hover:text-red-600 font-medium flex items-center gap-2 focus:outline-none cursor-pointer">
                            <i class="fas fa-bars"></i>Menu
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="menuDropdownContent" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 hidden">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                            </a>
                            <a href="{{ route('hospital.requests.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-plus-circle mr-2"></i>New Request
                            </a>
                            <a href="{{ route('hospital.inventory.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-boxes mr-2"></i>Blood Inventory
                            </a>
                            <a href="{{ route('hospital.drives.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-calendar-alt mr-2"></i>Blood Drives
                            </a>
                            <a href="{{ route('hospital.reports.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-chart-bar mr-2"></i>Analytics & Reports
                            </a>
                            <hr class="border-gray-200">
                            <form action="{{ route('logout') }}" method="POST" style="display: block;">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-3 md:px-4 lg:px-8">
            <div class="mb-4">
                <button onclick="window.history.back()" class="back-button text-red-600 hover:text-red-500 inline-flex items-center cursor-pointer transition duration-200 hover:scale-110">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>
            <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900">My Requests</h1>
                    <p class="text-sm md:text-base text-gray-600 mt-1">Manage requests created by your hospital</p>
                </div>
                <a href="{{ route('hospital.requests.create') }}" class="px-3 py-2 bg-red-600 text-white text-sm md:text-base rounded hover:bg-red-700 whitespace-nowrap">Create Request</a>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-3 md:p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs md:text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-2 md:px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Blood Type</th>
                                    <th class="px-2 md:px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Quantity</th>
                                    <th class="px-2 md:px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Urgency</th>
                                    <th class="px-2 md:px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Status</th>
                                    <th class="px-2 md:px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Matches</th>
                                    <th class="px-2 md:px-4 py-2 text-xs font-semibold text-gray-700 uppercase hidden md:table-cell">Date</th>
                                    <th class="px-2 md:px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($requests as $request)
                                    <tr>
                                        <td class="px-2 md:px-4 py-2 md:py-3 font-medium">{{ $request->blood_type }}</td>
                                        <td class="px-2 md:px-4 py-2 md:py-3">{{ $request->quantity }}</td>
                                        <td class="px-2 md:px-4 py-2 md:py-3 text-xs md:text-sm">{{ ucfirst($request->urgency_level) }}</td>
                                        <td class="px-2 md:px-4 py-2 md:py-3 text-xs md:text-sm">{{ ucfirst($request->status) }}</td>
                                        <td class="px-2 md:px-4 py-2 md:py-3 font-medium">{{ $request->matchings->count() }}</td>
                                        <td class="px-2 md:px-4 py-2 md:py-3 text-xs md:text-sm hidden md:table-cell">{{ $request->created_at->format('M d, Y') }}</td>
                                        <td class="px-2 md:px-4 py-2 md:py-3">
                                            <div class="flex gap-2 md:gap-3">
                                                <a href="{{ route('hospital.requests.show', $request) }}" class="text-red-600 hover:text-red-800" title="View"><i class="fas fa-eye"></i></a>
                                                <form action="{{ route('match.find', $request) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="text-blue-600 hover:text-blue-800" title="Find Matches"><i class="fas fa-search"></i></button>
                                                </form>
                                                <form action="{{ route('hospital.requests.destroy', $request) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this request?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-3 md:px-4 py-6 text-center text-gray-500 text-sm">No requests found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($requests->hasPages())
                        <div class="mt-3 md:mt-4 text-sm">{{ $requests->links() }}</div>
                    @endif
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

        function goBack() {
            window.history.back();
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                menuDropdownContent.classList.add('hidden');
            }
        });
    </script>
</body>
</html>


