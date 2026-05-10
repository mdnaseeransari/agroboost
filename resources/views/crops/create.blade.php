@extends('layouts.app')
@section('title', 'Plant New Crop')

@section('content')
<div class="max-w-3xl mx-auto">
    <x-card title="Crop Details" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>'>
        <form action="{{ route('crops.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="name">Crop Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm" placeholder="e.g. Corn, Wheat, Tomatoes">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Variety -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="variety">Variety (Optional)</label>
                    <input type="text" name="variety" id="variety" value="{{ old('variety') }}" 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm" placeholder="e.g. Golden Bantam">
                    @error('variety') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-gray-100 my-6">
            
            <h4 class="font-bold text-gray-900 mb-4">Growth Timeline</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Planting Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="planting_date">Planting Date</label>
                    <input type="date" name="planting_date" id="planting_date" value="{{ old('planting_date', date('Y-m-d')) }}" required 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                    @error('planting_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Expected Harvest -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="expected_harvest_date">Expected Harvest Date</label>
                    <input type="date" name="expected_harvest_date" id="expected_harvest_date" value="{{ old('expected_harvest_date') }}" required 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                    @error('expected_harvest_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <input type="hidden" name="status" value="growing">

            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('crops.index') }}" class="px-5 py-2.5 text-gray-600 font-medium hover:text-gray-900 transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-agro-green text-white font-bold rounded-xl shadow-sm hover:bg-agro-green/90 hover:shadow-md transition">
                    Plant Crop
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
