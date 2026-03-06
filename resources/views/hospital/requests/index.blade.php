<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Requests - Hospital</title>
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
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-red-600 font-medium">Dashboard</a>
                    <a href="{{ route('hospital.requests.create') }}" class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">New Request</a>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-red-600 font-medium">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">My Requests</h1>
                    <p class="text-gray-600 mt-1">Manage requests created by your hospital</p>
                </div>
                <a href="{{ route('hospital.requests.create') }}" class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">Create Request</a>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Blood Type</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Quantity</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Urgency</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Status</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Matches</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Date</th>
                                    <th class="px-4 py-2 text-xs font-semibold text-gray-700 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($requests as $request)
                                    <tr>
                                        <td class="px-4 py-3">{{ $request->blood_type }}</td>
                                        <td class="px-4 py-3">{{ $request->quantity }} units</td>
                                        <td class="px-4 py-3">{{ ucfirst($request->urgency_level) }}</td>
                                        <td class="px-4 py-3">{{ ucfirst($request->status) }}</td>
                                        <td class="px-4 py-3">{{ $request->matchings->count() }}</td>
                                        <td class="px-4 py-3">{{ $request->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('hospital.requests.show', $request) }}" class="text-red-600 hover:text-red-800 mr-3" title="View"><i class="fas fa-eye"></i></a>
                                            <form action="{{ route('hospital.requests.destroy', $request) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this request?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" title="Delete"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">No requests found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($requests->hasPages())
                        <div class="mt-4">{{ $requests->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>