@extends('layout.app')

@section('title', 'Hospital Details - LifeLink')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.hospitals') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Hospitals
                    </a>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.hospitals.edit', $hospital) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <i class="fas fa-hospital text-red-500 text-3xl"></i>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $hospital->name }}</h1>
                    <p class="text-gray-600 mt-1">{{ $hospital->email }}</p>
                </div>
                <span class="inline-block rounded-full text-sm font-medium {{ $hospital->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-3 py-1">
                    {{ $hospital->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clipboard-list text-blue-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Requests</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_requests'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending Requests</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_requests'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Fulfilled Requests</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['fulfilled_requests'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-boxes text-purple-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Blood Inventory</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_inventory'] }} units</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Hospital Information -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Hospital Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $hospital->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $hospital->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $hospital->phone ?: 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $hospital->location ?: 'Not provided' }}</p>
                    </div>
                    @if($hospital->latitude && $hospital->longitude)
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Coordinates</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $hospital->latitude }}, {{ $hospital->longitude }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Joined</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $hospital->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $hospital->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                </div>
                <div class="p-6">
                    @if($hospital->bloodRequests->count() > 0)
                        <div class="space-y-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Recent Blood Requests</h4>
                            @foreach($hospital->bloodRequests as $request)
                                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $request->blood_type }} - {{ $request->quantity }} units</p>
                                        <p class="text-xs text-gray-500">{{ $request->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="inline-block rounded-full text-xs font-medium px-2 py-1
                                        @if($request->status == 'fulfilled') bg-green-100 text-green-800
                                        @elseif($request->status == 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No recent requests</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Blood Inventory -->
        @if($hospital->bloodInventory->count() > 0)
        <div class="mt-8 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Blood Inventory</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiration Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($hospital->bloodInventory as $inventory)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $inventory->blood_type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inventory->quantity }} units</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $inventory->expiration_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block rounded-full text-xs font-medium px-2 py-1
                                        @if($inventory->expiration_date->isPast()) bg-red-100 text-red-800
                                        @elseif($inventory->expiration_date->diffInDays() <= 30) bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800 @endif">
                                        @if($inventory->expiration_date->isPast()) Expired
                                        @elseif($inventory->expiration_date->diffInDays() <= 30) Expiring Soon
                                        @else Good @endif
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection