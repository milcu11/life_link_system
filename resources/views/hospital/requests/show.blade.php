<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details</title>
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
                            <a href="{{ route('hospital.requests.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-list mr-2"></i>My Requests
                            </a>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
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
        <div class="max-w-4xl mx-auto px-3 md:px-4 lg:px-8">
            <div class="mb-4">
                <button onclick="window.history.back()" class="back-button text-red-600 hover:text-red-500 inline-flex items-center cursor-pointer transition duration-200 hover:scale-110">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>
            <div class="mb-4 md:mb-6">
                <h1 class="text-xl md:text-2xl font-bold text-gray-900">Request Details</h1>
                <p class="text-sm md:text-base text-gray-600 mt-1">Overview and matched donors</p>
            </div>
            <div class="bg-white rounded-lg shadow p-4 md:p-6 mb-4 md:mb-6">
                <div class="flex flex-col md:flex-row items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-2">
                            <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs md:text-sm font-bold">{{ $request->blood_type }}</span>
                            <span class="text-xs md:text-sm text-gray-600">{{ $request->quantity }} units • {{ ucfirst($request->urgency_level) }}</span>
                        </div>
                        <h2 class="text-base md:text-lg font-semibold text-gray-900">{{ $request->hospital->name }}</h2>
                        <p class="text-xs md:text-sm text-gray-700 mt-1">{{ $request->location }}</p>
                        @if($request->needed_by)
                            <p class="text-xs md:text-sm text-gray-600 mt-1">Needed by {{ \Carbon\Carbon::parse($request->needed_by)->format('M d, Y') }}</p>
                        @endif
                        @if($request->notes)
                            <div class="mt-2 md:mt-3 bg-gray-50 p-2 md:p-3 rounded text-xs md:text-sm text-gray-700">{{ $request->notes }}</div>
                        @endif
                    </div>
                    <div class="text-left md:text-right">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $request->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">{{ ucfirst($request->status) }}</span>
                        <div class="text-xs text-gray-500 mt-1 md:mt-2">Posted {{ $request->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 md:p-6 mb-4 md:mb-6">
                <h3 class="text-base md:text-lg font-semibold mb-3 md:mb-4">Blood Request Summary</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4">
                    <div class="bg-blue-50 p-2 md:p-4 rounded">
                        <p class="text-xs md:text-sm text-gray-600">Total Matches</p>
                        <p class="text-lg md:text-2xl font-bold text-blue-600">{{ $request->matchings->count() }}</p>
                    </div>
                    <div class="bg-green-50 p-2 md:p-4 rounded">
                        <p class="text-xs md:text-sm text-gray-600">Accepted</p>
                        <p class="text-lg md:text-2xl font-bold text-green-600">{{ $request->matchings->where('status', 'accepted')->count() }}</p>
                    </div>
                    <div class="bg-yellow-50 p-2 md:p-4 rounded">
                        <p class="text-xs md:text-sm text-gray-600">Pending</p>
                        <p class="text-lg md:text-2xl font-bold text-yellow-600">{{ $request->matchings->where('status', 'pending')->count() }}</p>
                    </div>
                    <div class="bg-red-50 p-2 md:p-4 rounded">
                        <p class="text-xs md:text-sm text-gray-600">Rejected</p>
                        <p class="text-lg md:text-2xl font-bold text-red-600">{{ $request->matchings->where('status', 'rejected')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-4 md:p-6 mb-4 md:mb-6">
                <h3 class="text-base md:text-lg font-semibold mb-3 md:mb-4">Accepted Donors</h3>
                @php
                    $acceptedMatches = $request->matchings->where('status', 'accepted')
                        ->filter(fn($match) => $match->donor !== null);
                @endphp
                @if($acceptedMatches->count())
                    <div class="space-y-3 md:space-y-4">
                        @foreach($acceptedMatches as $match)
                            @php $donation = $match->donor->donations()->where('request_id', $request->id)->first(); @endphp
                            <div class="border-2 border-green-200 bg-green-50 rounded-lg p-3 md:p-4">
                                <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-2 md:mb-3 gap-2">
                                    <div>
                                        <p class="font-semibold text-base md:text-lg text-gray-900">{{ $match->donor->user->name }}</p>
                                        <p class="text-xs md:text-sm text-gray-600">{{ $match->donor->blood_type }} • {{ number_format($match->distance, 1) }} km away</p>
                                    </div>
                                    <span class="inline-block bg-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold">ACCEPTED</span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-3 mb-2 md:mb-3 text-xs md:text-sm">
                                    <div>
                                        <span class="text-gray-600">Phone:</span>
                                        <p><a href="tel:{{ $match->donor->phone }}" class="text-blue-600 hover:underline font-medium break-all">{{ $match->donor->phone }}</a></p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Email:</span>
                                        <p><a href="mailto:{{ $match->donor->user->email }}" class="text-blue-600 hover:underline font-medium break-all">{{ $match->donor->user->email }}</a></p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Compatibility:</span>
                                        <p class="font-medium text-green-600">{{ $match->compatibility_score }}%</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Quantity:</span>
                                        <p class="font-medium">{{ $donation ? $donation->quantity . ' units' : $request->quantity . ' units' }}</p>
                                    </div>
                                </div>

                                @if($donation)
                                <div class="flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-3 text-xs md:text-sm mb-2 md:mb-3 bg-white p-1 md:p-2 rounded">
                                    <span class="text-gray-600">Donation Status:</span>
                                    <span class="inline-block px-2 py-1 rounded text-xs font-semibold {{ $donation->status == 'completed' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </div>
                                @endif

                                <div class="flex flex-col md:flex-row gap-2">
                                    @if($donation && $donation->status !== 'completed')
                                    <form action="{{ route('hospital.donations.complete', $donation) }}" method="POST" style="display:flex; flex: 1;">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="flex-1 md:flex-none px-3 py-1 bg-green-600 text-white text-xs md:text-sm rounded hover:bg-green-700 transition">
                                            <i class="fas fa-check"></i> Mark as Completed
                                        </button>
                                    </form>
                                    @elseif($donation && $donation->status === 'completed')
                                    <span class="flex-1 md:flex-none px-3 py-1 bg-green-100 text-green-700 text-xs md:text-sm rounded font-medium text-center">✓ Completed</span>
                                    @endif
                                    <a href="tel:{{ $match->donor->phone }}" class="flex-1 md:flex-none px-3 py-1 bg-blue-600 text-white text-xs md:text-sm rounded hover:bg-blue-700 transition text-center">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-6">No donors have accepted yet</div>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow p-4 md:p-6 mb-4 md:mb-6">
                <h3 class="text-base md:text-lg font-semibold mb-3 md:mb-4">Pending Matches</h3>
                @php $pendingMatches = $request->matchings->where('status', 'pending'); @endphp
                @if($pendingMatches->count())
                    <div class="space-y-2 md:space-y-3">
                        @foreach($pendingMatches as $match)
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between p-2 md:p-3 border border-yellow-200 bg-yellow-50 rounded gap-2">
                                <div>
                                    <p class="font-medium text-sm md:text-base text-gray-900">{{ $match->donor->user->name }}</p>
                                    <p class="text-xs md:text-sm text-gray-600">{{ $match->donor->blood_type }} • {{ number_format($match->distance, 1) }} km • {{ $match->compatibility_score }}%</p>
                                </div>
                                <span class="inline-block bg-yellow-600 text-white px-3 py-1 rounded-full text-xs font-semibold self-start md:self-center whitespace-nowrap">PENDING</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-4 md:py-6 text-sm">No pending matches</div>
                @endif
            </div>
            <div class="flex flex-col md:flex-row gap-2 md:gap-3 pt-2 md:pt-4">
                <form action="{{ route('match.find', $request) }}" method="POST" style="display:flex; flex: 1;">
                    @csrf
                    <button type="submit" class="flex-1 px-4 md:px-6 py-2 bg-blue-600 text-white text-sm md:text-base rounded-md hover:bg-blue-700 transition font-medium cursor-pointer">
                        <i class="fas fa-search"></i> Find Matches
                    </button>
                </form>
                <a href="{{ route('hospital.requests.index') }}" class="flex-1 px-4 md:px-6 py-2 border border-gray-300 rounded-md text-gray-700 text-sm md:text-base hover:bg-gray-50 transition font-medium text-center">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
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

        function goBack() {
            window.history.back();
        }
    </script>
</body>
</html>


