<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <svg class="h-8 w-8 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    <span class="text-xl font-bold text-gray-900">LifeLink</span>
                <div class="flex items-center gap-4">
                    <a href="{{ route('hospital.requests.index') }}" class="text-gray-600 hover:text-red-600 font-medium">My Requests</a>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">@csrf<button type="submit" class="text-gray-600 hover:text-red-600">Logout</button></form>
            </div>
        </div>
    </nav>
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-4">
                <h1 class="text-2xl font-bold text-gray-900">Request Details</h1>
                <p class="text-gray-600 mt-1">Overview and matched donors</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">{{ $request->blood_type }}</span>
                            <span class="text-sm text-gray-600">{{ $request->quantity }} units • {{ ucfirst($request->urgency_level) }}</span>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $request->hospital->name }}</h2>
                        <p class="text-sm text-gray-700 mt-1">{{ $request->location }}</p>
                        @if($request->needed_by)
                            <p class="text-sm text-gray-600 mt-1">Needed by {{ \Carbon\Carbon::parse($request->needed_by)->format('M d, Y') }}</p>
                        @endif
                        @if($request->notes)
                            <div class="mt-3 bg-gray-50 p-3 rounded text-sm text-gray-700">{{ $request->notes }}</div>
                        @endif
                    </div>
                    <div class="text-right">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $request->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">{{ ucfirst($request->status) }}</span>
                        <div class="text-xs text-gray-500 mt-2">Posted {{ $request->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Matched Donors</h3>
                @if($request->matchings && $request->matchings->count())
                    <div class="space-y-4">
                        @foreach($request->matchings as $match)
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $match->donor->user->name ?? 'Donor' }}</p>
                                    <p class="text-sm text-gray-600">{{ $match->donor->blood_type ?? '' }} • {{ number_format($match->distance,1) }} km away</p>
                                </div>
                                <div class="text-sm text-gray-500">{{ $match->created_at->diffForHumans() }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500">No matched donors yet.</div>
                @endif
            </div>
            <div class="flex gap-3 pt-4">
                <a href="{{ route('hospital.requests.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition font-medium">
                    <i class="fas fa-arrow-left"></i> Back to Requests
                </a>
            </div>
        </div>
    </div>
</body>
</html>