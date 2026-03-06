<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Requests - Blood Donation System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <svg class="h-8 w-8 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <span class="text-xl font-bold text-gray-900">LifeLink</span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('donor.profile') }}" class="text-gray-600 hover:text-red-600 font-medium">
                        <i class="fas fa-user-circle mr-2"></i>{{ auth()->user()->name }}
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-red-600 font-medium">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Available Blood Requests</h1>
                <p class="text-gray-600 mt-1">Respond to urgent blood donation requests near you</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($matches as $match)
                    <div class="bg-white rounded-lg shadow p-4 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">{{ $match->request->blood_type }}</span>
                                    <span class="text-xs text-gray-500">{{ $match->request->quantity }} units</span>
                                </div>
                                <span class="text-xs font-semibold uppercase px-2 py-1 rounded {{
                                    $match->request->urgency_level == 'critical' ? 'bg-red-100 text-red-700' :
                                    ($match->request->urgency_level == 'high' ? 'bg-orange-100 text-orange-700' :
                                    ($match->request->urgency_level == 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700'))
                                }}">{{ ucfirst($match->request->urgency_level) }}</span>
                            </div>

                            <h3 class="font-semibold text-gray-900 mb-1">{{ $match->request->hospital->name }}</h3>
                            <p class="text-sm text-gray-600 mb-2"><i class="fas fa-map-marker-alt text-red-600 mr-2"></i>{{ $match->request->location }}</p>
                            <p class="text-sm text-gray-600 mb-2"><i class="fas fa-road text-red-600 mr-2"></i>{{ number_format($match->distance, 1) }} km away</p>
                            @if($match->request->needed_by)
                                <p class="text-sm text-gray-600 mb-2"><i class="fas fa-clock text-red-600 mr-2"></i>Needed by {{ \Carbon\Carbon::parse($match->request->needed_by)->format('M d, Y') }}</p>
                            @endif
                            <p class="text-sm text-gray-600 mb-2"><i class="fas fa-user text-red-600 mr-2"></i>{{ $match->request->contact_person }}</p>
                            <p class="text-sm text-gray-600 mb-4"><i class="fas fa-phone text-red-600 mr-2"></i>{{ $match->request->contact_phone }}</p>

                            @if($match->request->notes)
                                <div class="bg-gray-50 p-3 rounded mb-3 text-sm text-gray-700">{{ $match->request->notes }}</div>
                            @endif
                        </div>

                        <div class="mt-4 flex gap-2">
                            <form action="{{ route('donor.respond', $match) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="response" value="accepted">
                                <button type="submit" class="w-full px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-medium">
                                    <i class="fas fa-check mr-1"></i>Accept
                                </button>
                            </form>
                            <form action="{{ route('donor.respond', $match) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="response" value="rejected">
                                <button type="submit" class="w-full px-3 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm font-medium">
                                    <i class="fas fa-times mr-1"></i>Decline
                                </button>
                            </form>
                        </div>

                        <div class="text-xs text-gray-400 mt-3">Posted {{ $match->request->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-lg shadow p-6 text-center text-gray-600">
                        <i class="fas fa-inbox text-3xl text-gray-400 mb-3"></i>
                        <h3 class="text-lg font-semibold">No Requests Available</h3>
                        <p class="mt-2">There are currently no blood requests matching your profile. Check back later.</p>
                    </div>
                @endforelse
            </div>

            @if($matches->hasPages())
                <div class="mt-6">
                    {{ $matches->links() }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>