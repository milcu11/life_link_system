@extends('layout.app')

@section('title', 'Notifications')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
                <p class="text-gray-600 mt-2">Stay updated with system alerts and messages</p>
            </div>
            @if($notifications->count() > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition">
                        <i class="fas fa-check-double mr-2"></i>Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div class="bg-white rounded-lg shadow-sm border-l-4 {{ $notification->read_at ? 'border-gray-300' : 'border-red-600' }} p-6 hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            @if($notification->type === 'emergency')
                                <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-lg">
                                    <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                                </div>
                            @elseif($notification->type === 'sms')
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                                    <i class="fas fa-sms text-blue-600 text-lg"></i>
                                </div>
                            @elseif($notification->type === 'email')
                                <div class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-lg">
                                    <i class="fas fa-envelope text-orange-600 text-lg"></i>
                                </div>
                            @else
                                <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-lg">
                                    <i class="fas fa-bell text-gray-600 text-lg"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $notification->title }}</h3>
                                    <p class="text-gray-600 mt-1">{{ $notification->message }}</p>
                                    <div class="flex items-center gap-4 mt-3">
                                        <span class="text-sm text-gray-500">
                                            <i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                        </span>
                                        @if($notification->type === 'emergency')
                                            <span class="text-sm px-3 py-1 bg-red-100 text-red-700 rounded-full font-medium">
                                                <i class="fas fa-circle-exclamation mr-1"></i>Emergency
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action -->
                                @if(!$notification->read_at)
                                    <form action="{{ route('notifications.read', $notification) }}" method="POST" class="ml-4">
                                        @csrf
                                        <button type="submit" class="px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition font-medium">
                                            <i class="fas fa-check mr-1"></i>Mark Read
                                        </button>
                                    </form>
                                @else
                                    <div class="ml-4 px-3 py-2 text-sm text-green-600 font-medium">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <i class="fas fa-bell-slash text-4xl text-gray-300 mb-4 block"></i>
                    <h3 class="text-xl font-semibold text-gray-900">No Notifications</h3>
                    <p class="text-gray-600 mt-2">You're all caught up! Check back later for updates.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection