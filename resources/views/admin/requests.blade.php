@extends('layout.app')

@section('title', 'Manage Blood Requests - LifeLink')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Manage Blood Requests</h1>
            <p class="text-gray-600 mt-1">View and monitor all blood donation requests</p>
        </div>

        <!-- Requests Table Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Hospital</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Blood Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Urgency</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Needed By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($requests as $request)
                            <tr class="hover:bg-gray-50" data-testid="request-{{ $request->id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">#{{ $request->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-hospital text-orange-600"></i>
                                        <span class="text-gray-900">{{ $request->hospital->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-bold">
                                        {{ $request->blood_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->quantity }} units</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block rounded-full text-sm font-medium {{ $request->urgency_level == 'critical' ? 'bg-red-100 text-red-800' : ($request->urgency_level == 'high' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }} px-3 py-1">
                                        {{ ucfirst($request->urgency_level) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span title="{{ $request->location }}">{{ Str::limit($request->location, 30) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block rounded-full text-sm font-medium {{ $request->status == 'fulfilled' ? 'bg-green-100 text-green-800' : ($request->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }} px-3 py-1">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->needed_by ? \Carbon\Carbon::parse($request->needed_by)->format('M d, Y') : 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $request->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-inbox text-gray-400 text-4xl mb-3"></i>
                                        <p class="text-gray-500 font-medium">No requests found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($requests->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection