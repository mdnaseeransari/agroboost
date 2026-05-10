@extends('layouts.app')
@section('title', 'Edit Inventory: ' . $inventoryItem->name)

@section('content')
<div class="max-w-3xl mx-auto">
    <x-card title="Item Details" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>'>
        <form action="{{ route('inventory.update', $inventoryItem) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="name">Item Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $inventoryItem->name) }}" required 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Type -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="type">Category</label>
                    <select name="type" id="type" class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                        <option value="seed" {{ old('type', $inventoryItem->type) === 'seed' ? 'selected' : '' }}>Seed</option>
                        <option value="fertilizer" {{ old('type', $inventoryItem->type) === 'fertilizer' ? 'selected' : '' }}>Fertilizer</option>
                        <option value="chemical" {{ old('type', $inventoryItem->type) === 'chemical' ? 'selected' : '' }}>Chemical</option>
                        <option value="equipment" {{ old('type', $inventoryItem->type) === 'equipment' ? 'selected' : '' }}>Equipment</option>
                        <option value="other" {{ old('type', $inventoryItem->type) === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-gray-100 my-6">
            
            <h4 class="font-bold text-gray-900 mb-4">Stock Levels</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="quantity">Current Quantity</label>
                    <input type="number" step="0.01" name="quantity" id="quantity" value="{{ old('quantity', $inventoryItem->quantity) }}" required 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                    @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Unit -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="unit">Unit</label>
                    <input type="text" name="unit" id="unit" value="{{ old('unit', $inventoryItem->unit) }}" required 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                    @error('unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Threshold -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="threshold_alert">Low Stock Alert At</label>
                    <input type="number" step="0.01" name="threshold_alert" id="threshold_alert" value="{{ old('threshold_alert', $inventoryItem->threshold_alert) }}" required 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                    @error('threshold_alert') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('inventory.index') }}" class="px-5 py-2.5 text-gray-600 font-medium hover:text-gray-900 transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-agro-green text-white font-bold rounded-xl shadow-sm hover:bg-agro-green/90 hover:shadow-md transition">
                    Update Item
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
