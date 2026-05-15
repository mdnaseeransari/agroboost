@extends('layouts.app')

@section('title', 'New Supply Request')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
            <h3 class="font-bold text-gray-900">Request Form</h3>
            <a href="{{ route('requests.index') }}" class="text-xs font-bold text-agro-green hover:underline">Back to List</a>
        </div>
        
        <form action="{{ route('requests.store') }}" method="POST" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Request Type</label>
                    <select name="request_type" required class="w-full rounded-xl border-gray-200 focus:border-agro-green focus:ring-agro-green py-3">
                        <option value="seeds">Seeds</option>
                        <option value="fertilizer">Fertilizer</option>
                        <option value="tools">Tools</option>
                        <option value="irrigation">Irrigation Support</option>
                        <option value="equipment">Heavy Equipment</option>
                    </select>
                    @error('request_type') <p class="text-xs text-status-danger mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Quantity</label>
                    <input type="number" name="quantity" step="0.01" required placeholder="e.g. 50" 
                           class="w-full rounded-xl border-gray-200 focus:border-agro-green focus:ring-agro-green py-3">
                    @error('quantity') <p class="text-xs text-status-danger mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Item Name / Specifics</label>
                <input type="text" name="item_name" required placeholder="e.g. Organic Nitrogen Fertilizer, Tomato Seeds..." 
                       class="w-full rounded-xl border-gray-200 focus:border-agro-green focus:ring-agro-green py-3">
                @error('item_name') <p class="text-xs text-status-danger mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Description / Reason</label>
                <textarea name="description" rows="4" placeholder="Explain why you need this item..." 
                          class="w-full rounded-xl border-gray-200 focus:border-agro-green focus:ring-agro-green"></textarea>
                @error('description') <p class="text-xs text-status-danger mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 bg-agro-green text-white rounded-xl font-bold hover:bg-agro-green/90 transition shadow-md uppercase tracking-widest text-sm">
                    Submit Request to Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
