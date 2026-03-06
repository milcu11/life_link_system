@extends('layout.app')

@section('title', 'Manage Donations - LifeLink')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Manage Donations</h1>
            <p class="text-gray-600 mt-1">View all completed and pending blood donations</p>
        </div>

        <!-- Donations Table Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Donor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Blood Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Request ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Donation Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($donations as $donation)
                            <tr class="hover:bg-gray-50" data-testid="donation-{{ $donation->id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">#{{ $donation->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-user-circle text-gray-400 text-xl"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $donation->donor->user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $donation->donor->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">
                                        {{ $donation->donor->blood_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $donation->request->quantity }} units</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="#" class="text-red-600 hover:text-red-700 font-medium">#{{ $donation->request_id }}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $donation->donation_date?->format('M d, Y') ?? 'Not set' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block rounded-full text-sm font-medium {{ $donation->status == 'completed' ? 'bg-green-100 text-green-800' : ($donation->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }} px-3 py-1">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $donation->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-heart text-gray-400 text-4xl mb-3"></i>
                                        <p class="text-gray-500 font-medium">No donations found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($donations->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $donations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection