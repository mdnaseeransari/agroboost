@extends('layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
<div class="space-y-8">
    <!-- Top Greeting & Stats -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-sm text-gray-500">Here's what's happening on your {{ $role }} dashboard today.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ now()->format('l, M d') }}</span>
        </div>
    </div>

    <!-- Metric Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($stats as $label => $value)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 group hover:shadow-md transition">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ str_replace('_', ' ', $label) }}</p>
                <p class="text-3xl font-bold text-gray-900 group-hover:text-agro-green transition">
                    {{ is_numeric($value) && str_contains($label, 'revenue') ? '$' . number_format($value, 2) : (is_numeric($value) && str_contains($label, 'spent') ? '$' . number_format($value, 2) : $value) }}
                </p>
            </div>
        @endforeach
        
        @if($role !== 'buyer')
        <div class="bg-agro-green p-6 rounded-2xl shadow-sm flex items-center justify-between text-white">
            <div>
                <p class="text-green-100 text-xs font-bold uppercase tracking-wider mb-1">Tasks Pending</p>
                <p class="text-3xl font-bold">{{ $pendingTasks }}</p>
            </div>
            <div class="p-3 bg-white/10 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Orders (Main Content) -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-agro-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Recent Orders
                    </h3>
                    <a href="{{ route('orders.index') }}" class="text-xs font-bold text-agro-green hover:underline">View All</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentOrders as $order)
                        <div class="p-6 hover:bg-gray-50/50 transition flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                    {{ substr($role === 'buyer' ? $order->farmer->name : $order->buyer->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                                    <p class="text-xs text-gray-500">{{ $role === 'buyer' ? 'Farmer: ' . $order->farmer->name : 'Buyer: ' . $order->buyer->name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->order_status === 'delivered' ? 'bg-status-success/10 text-status-success' : 'bg-status-warning/10 text-status-warning' }}">
                                        {{ $order->order_status }}
                                    </span>
                                </div>
                                <a href="{{ route('orders.show', $order) }}" class="p-2 text-gray-400 hover:text-agro-green transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-400">
                            <p class="text-sm">No recent orders found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($role === 'admin')
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                        <h3 class="font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-agro-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Incoming Requests
                        </h3>
                        <a href="{{ route('requests.index') }}" class="text-xs font-bold text-agro-green hover:underline">View All</a>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @forelse($recentRequests as $req)
                            <div class="p-6 hover:bg-gray-50/50 transition flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">{{ $req->item_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $req->quantity }} units · {{ $req->request_type }}</p>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $req->status === 'approved' ? 'bg-blue-100 text-blue-600' : ($req->status === 'pending' ? 'bg-status-warning/10 text-status-warning' : 'bg-status-danger/10 text-status-danger') }}">
                                    {{ $req->status }}
                                </span>
                            </div>
                        @empty
                            <div class="p-12 text-center text-gray-400">
                                <p class="text-sm">No recent requests.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            @if($role === 'buyer')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($marketHighlights as $listing)
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center gap-4 group hover:shadow-md transition">
                            <div class="w-16 h-16 rounded-xl bg-agro-green/5 flex items-center justify-center text-agro-green">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-agro-green transition">{{ $listing->crop->name }}</h4>
                                <p class="text-[10px] text-gray-500">by {{ $listing->farmer->name }}</p>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-sm font-bold text-gray-900">${{ number_format($listing->price_per_unit, 2) }}</span>
                                    <a href="{{ route('marketplace.index') }}" class="text-[10px] font-bold text-agro-green uppercase hover:underline">View</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Sidebar Components -->
        <div class="space-y-8">
            <!-- Messages Widget -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 flex items-center gap-2 mb-6">
                    <svg class="w-5 h-5 text-agro-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    Recent Messages
                </h3>
                <div class="space-y-4 mb-6">
                    @forelse($messages as $msg)
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-400 shrink-0">
                                {{ substr($msg->sender->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    @if($msg->sender_id === Auth::id())
                                        <p class="text-xs font-bold text-agro-green">You <span class="text-gray-400 font-normal">to</span> {{ $msg->receiver->name }}</p>
                                    @else
                                        <p class="text-xs font-bold text-gray-900">{{ $msg->sender->name }}</p>
                                    @endif
                                    <span class="text-[8px] text-gray-400">{{ $msg->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-[11px] text-gray-600 line-clamp-1 mt-0.5">{{ $msg->content }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 text-center py-4">No recent messages.</p>
                    @endforelse
                </div>
                <form action="{{ route('messages.store') }}" method="POST" class="mt-4 space-y-3">
                    @csrf
                    @if($role === 'admin')
                        <select name="receiver_id" required class="w-full text-[10px] border-gray-200 rounded-xl focus:ring-agro-green focus:border-agro-green py-2 font-bold uppercase tracking-wider">
                            <option value="">Select Recipient</option>
                            <optgroup label="Farmers">
                                @foreach(\App\Models\User::where('role', 'farmer')->get() as $f)
                                    <option value="{{ $f->id }}" {{ $selectedRecipientId == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Buyers">
                                @foreach(\App\Models\User::where('role', 'buyer')->get() as $b)
                                    <option value="{{ $b->id }}" {{ $selectedRecipientId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    @elseif($role === 'buyer')
                         <select name="receiver_id" required class="w-full text-[10px] border-gray-200 rounded-xl focus:ring-agro-green focus:border-agro-green py-2 font-bold uppercase tracking-wider">
                            <option value="">Select Recipient</option>
                            <optgroup label="Farmers">
                                @foreach(\App\Models\User::where('role', 'farmer')->get() as $f)
                                    <option value="{{ $f->id }}" {{ $selectedRecipientId == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Administration">
                                @foreach(\App\Models\User::where('role', 'admin')->get() as $a)
                                    <option value="{{ $a->id }}" {{ $selectedRecipientId == $a->id ? 'selected' : '' }}>AgroBoost Admin ({{ $a->name }})</option>
                                @endforeach
                            </optgroup>
                        </select>
                    @elseif($role === 'farmer')
                        <select name="receiver_id" required class="w-full text-[10px] border-gray-200 rounded-xl focus:ring-agro-green focus:border-agro-green py-2 font-bold uppercase tracking-wider">
                            <option value="">Select Recipient</option>
                            <optgroup label="Administration">
                                @foreach(\App\Models\User::where('role', 'admin')->get() as $a)
                                    <option value="{{ $a->id }}" {{ $selectedRecipientId == $a->id ? 'selected' : '' }}>AgroBoost Admin ({{ $a->name }})</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="My Buyers">
                                @php
                                    $myBuyerIds = \App\Models\Order::where('farmer_id', Auth::id())->pluck('buyer_id')->unique();
                                    $myBuyers = \App\Models\User::whereIn('id', $myBuyerIds)->get();
                                @endphp
                                @foreach($myBuyers as $b)
                                    <option value="{{ $b->id }}" {{ $selectedRecipientId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    @endif

                    <div class="flex gap-2">
                        <input type="text" name="content" required placeholder="Type a message..." 
                               class="flex-1 text-xs border-gray-100 rounded-xl focus:ring-agro-green focus:border-agro-green py-2 shadow-sm">
                        <button type="submit" class="bg-agro-green text-white p-2 rounded-xl hover:bg-agro-green/90 transition shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Quick Actions -->
            @if($role !== 'farmer')
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-6">Quick Actions</h3>
                <div class="space-y-3">
                    @if($role === 'buyer')
                        <a href="{{ route('marketplace.index') }}" class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-agro-green hover:text-white transition group">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center text-agro-green group-hover:text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <span class="text-xs font-bold">Shop Marketplace</span>
                        </a>
                    @elseif($role === 'admin')
                        <a href="{{ route('tasks.create') }}" class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-agro-green hover:text-white transition group">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center text-agro-green group-hover:text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <span class="text-xs font-bold">Assign Task</span>
                        </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
