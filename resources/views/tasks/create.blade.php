@extends('layouts.app')
@section('title', 'Add Task')

@section('content')
<div class="max-w-3xl mx-auto">
    <x-card title="Task Details" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>'>
        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="title">Task Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm" placeholder="e.g. Inspect irrigation system">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="description">Description (Optional)</label>
                    <textarea name="description" id="description" rows="3" 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm" placeholder="Add any extra details...">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Due Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="due_date">Due Date</label>
                    <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date') }}" required 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                    @error('due_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Related Crop (Optional) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="crop_id">Related Crop (Optional)</label>
                    <select name="crop_id" id="crop_id" class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                        <option value="">None</option>
                        @foreach(\App\Models\Crop::where('farm_id', Auth::user()->farm_id)->get() as $crop)
                            <option value="{{ $crop->id }}" {{ old('crop_id') == $crop->id ? 'selected' : '' }}>{{ $crop->name }}</option>
                        @endforeach
                    </select>
                    @error('crop_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Assigned To (Admin only) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="assigned_to">Assign To</label>
                    <select name="assigned_to" id="assigned_to" class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                        <option value="">Unassigned</option>
                        @foreach(\App\Models\User::where('farm_id', Auth::user()->farm_id)->get() as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', Auth::id()) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->role }})
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('tasks.index') }}" class="px-5 py-2.5 text-gray-600 font-medium hover:text-gray-900 transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-agro-green text-white font-bold rounded-xl shadow-sm hover:bg-agro-green/90 hover:shadow-md transition">
                    Save Task
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
