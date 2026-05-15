@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="space-y-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Order Management</h2>
            <p class="text-sm text-gray-500">Track and manage your crop purchases and sales</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 bg-gray-100 rounded-full text-xs font-bold text-gray-600">Total: {{ $orders->total() }}</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">{{ Auth::user()->isBuyer() ? 'Farmer' : 'Buyer' }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-900">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-agro-green/10 flex items-center justify-center text-[10px] text-agro-green font-bold">
                                        {{ substr(Auth::user()->isBuyer() ? $order->farmer->name : $order->buyer->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs font-medium text-gray-700">{{ Auth::user()->isBuyer() ? $order->farmer->name : $order->buyer->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($order->payment_status === 'paid')
                                    <span class="px-2 py-1 bg-status-success/10 text-status-success rounded text-[10px] font-bold uppercase">Paid</span>
                                @else
                                    <span class="px-2 py-1 bg-status-warning/10 text-status-warning rounded text-[10px] font-bold uppercase">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-gray-100 text-gray-600',
                                        'accepted' => 'bg-blue-100 text-blue-600',
                                        'packed' => 'bg-purple-100 text-purple-600',
                                        'shipped' => 'bg-agro-gold/20 text-agro-gold',
                                        'delivered' => 'bg-status-success/10 text-status-success',
                                    ];
                                    $class = $statusClasses[$order->order_status] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="px-2 py-1 {{ $class }} rounded text-[10px] font-bold uppercase">
                                    {{ $order->order_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('orders.show', $order) }}" class="p-2 text-gray-400 hover:text-agro-green transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    
                                    @if(Auth::user()->isBuyer() && $order->payment_status === 'pending')
                                        <form action="{{ route('orders.pay', $order) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-agro-green text-white rounded-lg text-[10px] font-bold hover:bg-agro-green/90 transition shadow-sm">
                                                PAY
                                            </button>
                                        </form>
                                    @endif

                                    @if((Auth::user()->isFarmer() || Auth::user()->isAdmin()) && $order->order_status !== 'delivered')
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" class="p-2 text-gray-400 hover:text-agro-gold transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                            </button>
                                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden">
                                                @foreach(['pending', 'accepted', 'packed', 'shipped', 'delivered'] as $status)
                                                    @if($order->order_status !== $status)
                                                        <form action="{{ route('orders.status.update', $order) }}" method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="{{ $status }}">
                                                            <button type="submit" class="w-full text-left px-4 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 transition uppercase tracking-wider">
                                                                Mark as {{ $status }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center">
                                <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                <p class="text-gray-500 font-medium">No orders found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $orders->links() }}
    </div>
</div>
@endsection
