@extends('layouts.app')
@section('title', 'Edit Crop: ' . $crop->name)

@section('content')
<div class="max-w-3xl mx-auto">
    <x-card title="Crop Details" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>'>
        <form action="{{ route('crops.update', $crop) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="name">Crop Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $crop->name) }}" required 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Variety -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="variety">Variety (Optional)</label>
                    <input type="text" name="variety" id="variety" value="{{ old('variety', $crop->variety) }}" 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                    @error('variety') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-gray-100 my-6">
            
            <h4 class="font-bold text-gray-900 mb-4">Growth Timeline</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Planting Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="planting_date">Planting Date</label>
                    <input type="date" name="planting_date" id="planting_date" value="{{ old('planting_date', $crop->planting_date->format('Y-m-d')) }}" required 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                    @error('planting_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Expected Harvest -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="expected_harvest_date">Expected Harvest Date</label>
                    <input type="date" name="expected_harvest_date" id="expected_harvest_date" value="{{ old('expected_harvest_date', $crop->expected_harvest_date->format('Y-m-d')) }}" required 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                    @error('expected_harvest_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-gray-100 my-6">

            <h4 class="font-bold text-gray-900 mb-4">Status & Yield</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="status">Current Status</label>
                    <select name="status" id="status" class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                        <option value="growing" {{ old('status', $crop->status) === 'growing' ? 'selected' : '' }}>Growing</option>
                        <option value="harvested" {{ old('status', $crop->status) === 'harvested' ? 'selected' : '' }}>Harvested</option>
                        <option value="failed" {{ old('status', $crop->status) === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Yield (Only relevant if harvested, but keeping it simple) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="yield_kg">Final Yield (kg) - Optional</label>
                    <input type="number" step="0.01" name="yield_kg" id="yield_kg" value="{{ old('yield_kg', $crop->yield_kg) }}" 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                    @error('yield_kg') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('crops.index') }}" class="px-5 py-2.5 text-gray-600 font-medium hover:text-gray-900 transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-agro-green text-white font-bold rounded-xl shadow-sm hover:bg-agro-green/90 hover:shadow-md transition">
                    Update Crop
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
