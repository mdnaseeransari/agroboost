@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('orders.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-gray-100 text-gray-400 hover:text-agro-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Order #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h2>
                <p class="text-xs text-gray-500">Placed on {{ $order->created_at->format('M d, Y at h:i A') }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            @if(Auth::user()->isBuyer() && $order->payment_status === 'pending')
                <form action="{{ route('orders.pay', $order) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2 bg-agro-green text-white rounded-xl text-sm font-bold hover:bg-agro-green/90 transition shadow-md">
                        PAY NOW
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Order Summary -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="font-bold text-gray-900">Order Items</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between pb-4 border-b border-gray-50 last:border-0 last:pb-0">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-agro-green/5 flex items-center justify-center text-agro-green">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900">{{ $item->crop->name ?? 'Deleted Crop' }}</h4>
                                        <p class="text-xs text-gray-500">{{ $item->quantity }} {{ $item->crop->unit ?? 'kg' }} x ${{ number_format($item->price, 2) }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-gray-900">${{ number_format($item->subtotal, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="p-6 bg-gray-50/50 border-t border-gray-50">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-700">Total Amount</span>
                        <span class="text-lg font-bold text-agro-green">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Order Status Timeline (Simplified) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-6">Order Status</h3>
                <div class="relative">
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-100"></div>
                    <div class="space-y-8">
                        @php
                            $statuses = ['pending', 'accepted', 'packed', 'shipped', 'delivered'];
                            $currentIndex = array_search($order->order_status, $statuses);
                        @endphp
                        @foreach($statuses as $index => $status)
                            <div class="relative flex items-center gap-6 pl-10">
                                <div class="absolute left-0 w-8 h-8 rounded-full border-4 border-white shadow-sm flex items-center justify-center {{ $index <= $currentIndex ? 'bg-agro-green text-white' : 'bg-gray-200 text-gray-400' }}">
                                    @if($index < $currentIndex || ($index === $currentIndex && $status === 'delivered'))
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold {{ $index <= $currentIndex ? 'text-gray-900' : 'text-gray-400' }} uppercase tracking-wider">{{ $status }}</p>
                                    @if($index === $currentIndex)
                                        <p class="text-xs text-agro-green font-medium">Currently here</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Sidebar Info -->
        <div class="space-y-6">
            <!-- Payment Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Payment Details</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500 font-medium">Status</span>
                        <span class="font-bold {{ $order->payment_status === 'paid' ? 'text-status-success' : 'text-status-warning' }} uppercase">{{ $order->payment_status }}</span>
                    </div>
                    @if($order->paid_at)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500 font-medium">Paid at</span>
                            <span class="text-gray-900 font-medium">{{ $order->paid_at->format('M d, Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Farmer/Buyer Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-agro-green/10 flex items-center justify-center text-xl text-agro-green font-bold mx-auto mb-4">
                    {{ substr(Auth::user()->isBuyer() ? $order->farmer->name : $order->buyer->name, 0, 1) }}
                </div>
                <h3 class="font-bold text-gray-900">{{ Auth::user()->isBuyer() ? 'Farmer Info' : 'Buyer Info' }}</h3>
                <p class="text-sm font-medium text-gray-600 mt-1">{{ Auth::user()->isBuyer() ? ($order->farmer->name ?? 'Unknown') : ($order->buyer->name ?? 'Unknown') }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ Auth::user()->isBuyer() ? ($order->farmer->email ?? '') : ($order->buyer->email ?? '') }}</p>
                
                <a href="{{ route('dashboard') }}?chat={{ Auth::user()->isBuyer() ? $order->farmer_id : $order->buyer_id }}" class="mt-6 block w-full py-2 bg-gray-50 text-agro-green rounded-xl text-xs font-bold hover:bg-agro-green/5 border border-agro-green/10 transition uppercase tracking-wider">
                    Message {{ Auth::user()->isBuyer() ? 'Farmer' : 'Buyer' }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
