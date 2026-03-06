@extends('layout.app')

@section('title', 'Notifications')

@section('content')
<div class="notifications-container">
    <div class="page-header">
        <h1 class="page-title">Notifications</h1>
        <div class="header-actions">
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary" data-testid="mark-all-read-btn">
                    <i class="fas fa-check-double"></i> Mark All as Read
                </button>
            </form>
        </div>
    </div>

    <div class="notifications-list-card">
        @forelse($notifications as $notification)
            <div class="notification-item {{ $notification->read_at ? 'read' : 'unread' }}" data-testid="notification-{{ $notification->id }}">
                <div class="notification-icon {{ $notification->type }}">
                    @if($notification->type === 'emergency')
                        <i class="fas fa-exclamation-circle"></i>
                    @elseif($notification->type === 'sms')
                        <i class="fas fa-sms"></i>
                    @elseif($notification->type === 'email')
                        <i class="fas fa-envelope"></i>
                    @else
                        <i class="fas fa-bell"></i>
                    @endif
                </div>
                <div class="notification-content">
                    <h4 class="notification-title">{{ $notification->title }}</h4>
                    <p class="notification-message">{{ $notification->message }}</p>
                    <div class="notification-meta">
                        <span class="notification-time">
                            <i class="fas fa-clock"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                        @if($notification->type === 'emergency')
                            <span class="notification-type emergency">
                                <i class="fas fa-circle-exclamation"></i> Emergency
                            </span>
                        @endif
                    </div>
                </div>
                <div class="notification-actions">
                    @if(!$notification->read_at)
                        <form action="{{ route('notifications.read', $notification) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-icon" title="Mark as read" data-testid="mark-read-{{ $notification->id }}">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                    @else
                        <span class="read-badge"><i class="fas fa-check-circle"></i></span>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state-lg">
                <i class="fas fa-bell-slash"></i>
                <h3>No Notifications</h3>
                <p>You're all caught up! Check back later for updates.</p>
            </div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
.notifications-list-card {
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow);
    overflow: hidden;
}

.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    border-bottom: 1px solid var(--gray-200);
    transition: background 0.2s;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item.unread {
    background: #fef2f2;
}

.notification-item:hover {
    background: var(--gray-50);
}

.notification-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.notification-icon.emergency {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: white;
}

.notification-icon.in_app,
.notification-icon.sms,
.notification-icon.email {
    background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
    color: white;
}

.notification-content {
    flex: 1;
}

.notification-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--gray-900);
    margin-bottom: 0.5rem;
}

.notification-message {
    color: var(--gray-700);
    margin-bottom: 0.75rem;
}

.notification-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-size: 0.875rem;
}

.notification-time {
    color: var(--gray-500);
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.notification-type.emergency {
    color: var(--primary);
    font-weight: 600;
}

.notification-actions {
    flex-shrink: 0;
}

.read-badge {
    color: var(--success);
    font-size: 1.25rem;
}
</style>
@endpush
@endsection