<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation History - Blood Donation System</title>
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
                <h1 class="text-2xl font-bold text-gray-900">Donation History</h1>
                <p class="text-gray-600 mt-1">Track all your blood donations and their impact</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
                    <div class="p-3 rounded-lg bg-red-100">
                        <i class="fas fa-droplet text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Donations</p>
                        <h3 class="text-lg font-bold text-gray-900">{{ $donations->total() }}</h3>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4 flex items-center gap-4">
                    <div class="p-3 rounded-lg bg-green-100">
                        <i class="fas fa-users text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Lives Potentially Saved</p>
                        <h3 class="text-lg font-bold text-gray-900">{{ $donations->total() * 3 }}</h3>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($donations as $donation)
                    <div class="bg-white rounded-lg shadow p-4 flex justify-between items-start gap-4">
                        <div class="flex gap-4">
                            <div class="shrink-0 p-3 rounded-lg bg-red-50">
                                <i class="fas fa-droplet text-red-600 text-2xl"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">{{ $donation->request->blood_type }}</span>
                                    <span class="text-sm text-gray-600">{{ $donation->request->quantity }} units</span>
                                </div>
                                <p class="text-sm text-gray-700">{{ $donation->request->hospital->name }} · <span class="text-gray-500">{{ $donation->donation_date?->format('M d, Y') ?? 'Date N/A' }}</span></p>
                                @if($donation->notes)
                                    <p class="text-sm text-gray-600 mt-2">{{ $donation->notes }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{
                                $donation->status == 'completed' ? 'bg-green-100 text-green-700' :
                                ($donation->status == 'accepted' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700')
                            }}">{{ ucfirst($donation->status) }}</span>
                            <div class="text-xs text-gray-500 mt-2">{{ $donation->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow p-6 text-center text-gray-600">
                        <i class="fas fa-heart text-3xl text-red-600 mb-3"></i>
                        <h3 class="text-lg font-semibold">No Donation History</h3>
                        <p class="mt-2">Your donation history will appear here once you start donating.</p>
                        <a href="{{ route('donor.requests') }}" class="inline-block mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            <i class="fas fa-bell mr-2"></i>View Available Requests
                        </a>
                    </div>
                @endforelse
            </div>

            @if($donations->hasPages())
                <div class="mt-6">
                    {{ $donations->links() }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>