@extends('layouts.app')

@section('title', 'Marketplace')

@section('content')
<div class="space-y-8">
    <!-- Header & Search -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Explore Marketplace</h2>
                <p class="text-sm text-gray-500">Find fresh crops directly from local farmers</p>
            </div>
            
            <form action="{{ route('marketplace.index') }}" method="GET" class="flex-1 max-w-xl flex gap-2">
                <div class="relative flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search crops..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray-200 focus:border-agro-green focus:ring-agro-green shadow-sm">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <button type="submit" class="bg-agro-green text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-agro-green/90 transition shadow-md">
                    Search
                </button>
            </form>

            @if(Auth::user()->isFarmer())
                <button x-data @click="$dispatch('open-modal', 'create-listing')" class="bg-agro-gold text-agro-green px-6 py-2.5 rounded-xl font-bold hover:bg-agro-gold/90 transition shadow-md flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    List Your Crop
                </button>
            @endif
        </div>
    </div>


    <!-- Listings Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($listings as $listing)
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-lg transition group">
                <div class="h-48 bg-gray-100 relative">
                    <!-- Placeholder Image with Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-br from-agro-green/10 to-agro-gold/10 flex items-center justify-center">
                        <svg class="w-16 h-16 text-agro-green/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                    <div class="absolute top-4 right-4 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold text-agro-green shadow-sm">
                        ${{ number_format($listing->price_per_unit, 2) }} / unit
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="font-bold text-gray-900 group-hover:text-agro-green transition">{{ $listing->crop->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $listing->crop->variety }}</p>
                        </div>
                        <span class="px-2 py-0.5 bg-gray-100 rounded text-[10px] font-bold text-gray-600 uppercase">
                            {{ $listing->quantity_available }} {{ $listing->crop->unit ?? 'kg' }} left
                        </span>
                    </div>
                    
                    <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-50">
                        <div class="w-6 h-6 rounded-full bg-agro-green flex items-center justify-center text-[10px] text-white font-bold">
                            {{ substr($listing->farmer->name, 0, 1) }}
                        </div>
                        <span class="text-xs text-gray-600 font-medium">{{ $listing->farmer->name }}</span>
                    </div>

                    @if(Auth::user()->isBuyer())
                        <div class="mt-5" x-data="{ quantity: 1 }">
                            <form action="{{ route('orders.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                                <div class="flex items-center gap-3 mb-3">
                                    <label class="text-xs font-bold text-gray-700">QTY:</label>
                                    <input type="number" name="quantity" x-model="quantity" min="0.01" step="0.01" max="{{ $listing->quantity_available }}" 
                                           class="w-full text-sm rounded-lg border-gray-200 py-1 focus:ring-agro-green">
                                </div>
                                <button type="submit" class="w-full py-2 bg-agro-green text-white rounded-xl text-sm font-bold hover:bg-agro-green/90 transition shadow-sm">
                                    Place Order
                                </button>
                            </form>
                        </div>
                    @elseif(Auth::id() === $listing->farmer_id)
                        <div class="mt-5 grid grid-cols-2 gap-2">
                            <button x-data @click="$dispatch('open-modal', 'edit-listing-{{ $listing->id }}')" class="py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-200 transition">
                                Edit
                            </button>
                            <form action="{{ route('marketplace.listings.update', $listing) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="is_active" value="{{ $listing->is_active ? 0 : 1 }}">
                                <button type="submit" class="w-full py-2 {{ $listing->is_active ? 'bg-status-danger/10 text-status-danger' : 'bg-status-success/10 text-status-success' }} rounded-xl text-xs font-bold hover:opacity-80 transition">
                                    {{ $listing->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-white rounded-2xl border border-dashed border-gray-300">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                <p class="text-gray-500 font-medium">No listings found in the marketplace.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $listings->links() }}
    </div>
</div>

<!-- Create Listing Modal -->
<div x-data="{ open: false }" x-show="open" @open-modal.window="if($event.detail == 'create-listing') open = true" @close-modal.window="if($event.detail == 'create-listing') open = false" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="bg-white rounded-2xl overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">List Your Crop</h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition">&times;</button>
            </div>
            <form action="{{ route('marketplace.listings.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Select Crop</label>
                    <select name="crop_id" required class="w-full rounded-xl border-gray-200 focus:border-agro-green focus:ring-agro-green">
                        <option value="">-- Choose Harvested Crop --</option>
                        @foreach($myCrops as $crop)
                            <option value="{{ $crop->id }}">{{ $crop->name }} ({{ $crop->variety }}) - {{ $crop->yield_kg }}kg harvested</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Quantity to List</label>
                        <input type="number" name="quantity_available" step="0.01" required 
                               class="w-full rounded-xl border-gray-200 focus:border-agro-green focus:ring-agro-green">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Price per Unit ($)</label>
                        <input type="number" name="price_per_unit" step="0.01" required 
                               class="w-full rounded-xl border-gray-200 focus:border-agro-green focus:ring-agro-green">
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" @click="open = false" class="px-6 py-2 text-sm font-bold text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-agro-green text-white rounded-xl text-sm font-bold hover:bg-agro-green/90 shadow-md">Create Listing</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
