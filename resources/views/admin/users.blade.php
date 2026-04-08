@extends('layout.app')

@section('title', 'Manage Users - LifeLink')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manage Users</h1>
                <p class="text-gray-600 mt-1">View and manage all system users</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.hospitals') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-hospital mr-2"></i>Manage Hospitals
                </a>
            </div>
        </div>

        <!-- Users Table Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Registered</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50" data-testid="user-{{ $user->id }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">#{{ $user->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-user-circle text-gray-400 text-xl"></i>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block rounded-full text-sm font-medium {{ $user->role == 'admin' ? 'bg-indigo-100 text-indigo-800' : ($user->role == 'hospital' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800') }} px-3 py-1">
                                        <i class="fas fa-{{ $user->role == 'admin' ? 'user-shield' : ($user->role == 'hospital' ? 'hospital' : 'user') }} mr-1"></i>
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-block rounded-full text-sm font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-3 py-1">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.toggle', $user) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="inline-block {{ $user->is_active ? 'bg-red-100 hover:bg-red-200 text-red-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} px-3 py-2 rounded text-sm font-medium cursor-pointer" 
                                                    title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}" 
                                                    data-testid="toggle-user-{{ $user->id }}">
                                                <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }} mr-1"></i> {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-users text-gray-400 text-4xl mb-3"></i>
                                        <p class="text-gray-500 font-medium">No users found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection