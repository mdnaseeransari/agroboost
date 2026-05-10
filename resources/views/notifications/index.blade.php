@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-poppins">Your Notifications</h1>
            <p class="text-sm text-gray-500">Stay updated with your farm's activity and alerts.</p>
        </div>
        @if(Auth::user()->unreadNotifications->count() > 0)
        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
            @csrf
            <button type="submit" class="text-sm font-semibold text-agro-green hover:text-agro-gold transition">
                Mark all as read
            </button>
        </form>
        @endif
    </div>

    @if($notifications->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="divide-y divide-gray-100">
                @foreach($notifications as $notification)
                    <div class="p-4 sm:p-6 flex items-start gap-4 hover:bg-gray-50 transition {{ $notification->unread() ? 'bg-agro-green/5' : '' }}">
                        <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center {{ $notification->unread() ? 'bg-agro-green text-white' : 'bg-gray-100 text-gray-400' }}">
                            @if(isset($notification->data['type']) && $notification->data['type'] === 'task')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            @elseif(isset($notification->data['type']) && $notification->data['type'] === 'inventory')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <h3 class="text-sm font-bold text-gray-900 truncate">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </h3>
                                <span class="text-[10px] text-gray-400 whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $notification->data['message'] ?? '' }}</p>
                            
                            <div class="mt-3 flex items-center gap-3">
                                @if($notification->unread())
                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs font-semibold text-agro-green hover:underline">Mark as read</button>
                                </form>
                                @endif
                                <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Delete this notification?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-status-danger hover:underline">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @else
        <x-empty-state 
            icon='<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>'
            title="No notifications yet"
            message="When there's activity on your farm, we'll let you know here."
        />
    @endif
</div>
@endsection
