@extends('layouts.app')
@section('title', 'Inventory Management')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="relative">
            <select class="appearance-none bg-white border border-gray-200 text-gray-700 py-2.5 pl-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-agro-green/50 focus:border-agro-green shadow-sm text-sm font-medium">
                <option>All Types</option>
                <option>Seed</option>
                <option>Fertilizer</option>
                <option>Equipment</option>
                <option>Other</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
        <div class="relative">
            <select class="appearance-none bg-white border border-gray-200 text-gray-700 py-2.5 pl-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-agro-green/50 focus:border-agro-green shadow-sm text-sm font-medium">
                <option>All Stock Levels</option>
                <option>Low Stock</option>
                <option>Healthy Stock</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </div>
    
    @if(Auth::user()->role !== 'buyer')
    <a href="{{ route('inventory.create') }}" class="inline-flex justify-center items-center gap-2 bg-agro-green text-white px-5 py-2.5 rounded-xl font-semibold shadow-sm hover:shadow-md hover:bg-agro-green/90 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add Item
    </a>
    @endif
</div>

@php
    $lowStockItems = $inventoryItems->filter(fn($item) => $item->quantity <= $item->threshold_alert);
@endphp

@if($lowStockItems->count() > 0)
    <x-alert-banner 
        type="warning" 
        message="{{ $lowStockItems->count() }} items are running low. Please reorder soon to avoid disruption." 
    />
@endif

@if($inventoryItems->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($inventoryItems as $item)
            @php
                $isLow = $item->quantity <= $item->threshold_alert;
                $isMedium = $item->quantity <= ($item->threshold_alert * 1.5);
                $statusColor = $isLow ? 'status-danger' : ($isMedium ? 'status-warning' : 'status-success');
                $statusLabel = $isLow ? 'Low Stock' : ($isMedium ? 'Medium' : 'Healthy');
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col group hover:shadow-lg transition duration-300 relative overflow-hidden">
                <!-- Status Top Bar -->
                <div class="absolute top-0 left-0 w-full h-1 bg-{{ $statusColor }}"></div>
                
                <div class="flex justify-between items-start mb-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 uppercase tracking-wider">
                        {{ $item->type }}
                    </span>
                    <x-stat-badge type="{{ $isLow ? 'danger' : ($isMedium ? 'warning' : 'healthy') }}" label="{{ $statusLabel }}" />
                </div>

                <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-agro-green transition">{{ $item->name }}</h3>
                <p class="text-xs text-gray-500 mb-4">Last updated {{ $item->updated_at->diffForHumans() }}</p>

                <div class="mt-auto">
                    <div class="flex items-end justify-between mb-2">
                        <div>
                            <span class="text-2xl font-bold text-gray-900">{{ number_format($item->quantity, 1) }}</span>
                            <span class="text-sm font-medium text-gray-500 ml-1">{{ $item->unit }}</span>
                        </div>
                        <span class="text-[10px] text-gray-400 font-medium">Alert at {{ $item->threshold_alert }} {{ $item->unit }}</span>
                    </div>
                    
                    <!-- Progress bar for stock level -->
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mb-6 overflow-hidden">
                        @php
                            $percentage = min(100, ($item->quantity / ($item->threshold_alert * 2)) * 100);
                        @endphp
                        <div class="bg-{{ $statusColor }} h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>

                    @if(Auth::user()->role !== 'buyer')
                    <div class="flex items-center gap-2 pt-4 border-t border-gray-50">
                        <a href="{{ route('inventory.edit', $item) }}" class="flex-1 inline-flex justify-center items-center py-2 bg-gray-50 text-gray-700 font-semibold rounded-xl text-sm hover:bg-gray-100 transition border border-gray-100">
                            Edit
                        </a>
                        @if(Auth::user()->role === 'admin')
                        <form action="{{ route('inventory.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Delete this item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-status-danger rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="mt-8">
        {{ $inventoryItems->links() }}
    </div>
@else
    <x-empty-state 
        icon='<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>'
        title="Inventory is empty"
        message="Keep track of your seeds, fertilizers, and tools. Add your first item to start receiving low-stock alerts."
        @if(Auth::user()->role !== 'buyer')
        actionText="Add Item"
        actionUrl="{{ route('inventory.create') }}"
        @endif
    />
@endif

@if(Auth::user()->role === 'farmer' && isset($adminInventory) && $adminInventory->count() > 0)
    <div class="mt-12 pt-12 border-t border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Request from Admin</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($adminInventory as $item)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col hover:border-agro-green/30 transition">
                    <div class="flex justify-between items-start mb-4">
                        <h4 class="font-bold text-gray-900">{{ $item->name }}</h4>
                        <span class="text-sm font-semibold text-gray-500">{{ $item->quantity }} {{ $item->unit }} available</span>
                    </div>
                    <form action="{{ route('inventory-requests.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <div class="flex gap-2">
                            <input type="number" name="quantity" step="0.01" max="{{ $item->quantity }}" required 
                                class="flex-1 text-sm border-gray-200 rounded-lg focus:ring-agro-green focus:border-agro-green" placeholder="Qty">
                            <button type="submit" class="bg-agro-green text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-agro-green/90 transition">
                                Request
                            </button>
                        </div>
                        <input type="text" name="notes" placeholder="Optional notes..." 
                            class="w-full text-xs border-gray-200 rounded-lg focus:ring-agro-green focus:border-agro-green">
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection
