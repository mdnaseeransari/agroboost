@extends('layouts.app')
@section('title', 'Edit Task')

@section('content')
<div class="max-w-3xl mx-auto">
    <x-card title="Task Details" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>'>
        <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="title">Task Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" required 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="description">Description (Optional)</label>
                    <textarea name="description" id="description" rows="3" 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">{{ old('description', $task->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Due Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="due_date">Due Date</label>
                    <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date', $task->due_date->format('Y-m-d\TH:i')) }}" required 
                        class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                    @error('due_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Related Crop (Optional) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="crop_id">Related Crop (Optional)</label>
                    <select name="crop_id" id="crop_id" class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                        <option value="">None</option>
                        @foreach(\App\Models\Crop::where('farm_id', Auth::user()->farm_id)->get() as $crop)
                            <option value="{{ $crop->id }}" {{ old('crop_id', $task->crop_id) == $crop->id ? 'selected' : '' }}>{{ $crop->name }}</option>
                        @endforeach
                    </select>
                    @error('crop_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Assigned To -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1" for="assigned_to">Assign To</label>
                    <select name="assigned_to" id="assigned_to" class="w-full border-gray-300 rounded-xl focus:border-agro-green focus:ring-agro-green shadow-sm">
                        <option value="">Unassigned</option>
                        @foreach(\App\Models\User::where('farm_id', Auth::user()->farm_id)->get() as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->role }})
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2 mt-2">
                     <label for="completed" class="inline-flex items-center group cursor-pointer">
                        <input id="completed" type="checkbox" value="1" {{ $task->completed ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-agro-green shadow-sm focus:ring-agro-green transition" name="completed">
                        <span class="ms-3 text-sm font-semibold text-gray-700 group-hover:text-gray-900 transition">Mark as completed</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('tasks.index') }}" class="px-5 py-2.5 text-gray-600 font-medium hover:text-gray-900 transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-agro-green text-white font-bold rounded-xl shadow-sm hover:bg-agro-green/90 hover:shadow-md transition">
                    Update Task
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
