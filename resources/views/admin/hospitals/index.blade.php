@extends('layout.app')

@section('title', 'Manage Hospitals - LifeLink')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manage Hospitals</h1>
                <p class="text-gray-600 mt-1">View and manage hospital partnerships</p>
            </div>
            <a href="{{ route('admin.hospitals.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="fas fa-plus mr-2"></i>Add Hospital
            </a>
        </div>

        <!-- Hospitals Table Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Hospital Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Location</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Requests</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Inventory</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($hospitals as $hospital)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">#{{ $hospital->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-hospital text-red-500 text-xl"></i>
                                        <p class="text-sm font-medium text-gray-900">{{ $hospital->name }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $hospital->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $hospital->location ?: 'Not set' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $hospital->blood_requests_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $hospital->blood_inventory_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block rounded-full text-sm font-medium {{ $hospital->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-3 py-1">
                                        {{ $hospital->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.hospitals.show', $hospital) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                        <a href="{{ route('admin.hospitals.edit', $hospital) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-hospital text-gray-400 text-4xl mb-3"></i>
                                        <p class="text-gray-500 font-medium">No hospitals found</p>
                                        <p class="text-gray-400 text-sm mt-1">Get started by adding your first hospital partner</p>
                                        <a href="{{ route('admin.hospitals.create') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                            <i class="fas fa-plus mr-2"></i>Add First Hospital
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($hospitals->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $hospitals->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection