@extends('layouts.app')
@section('title', 'Crop Management')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="relative">
            <select class="appearance-none bg-white border border-gray-200 text-gray-700 py-2.5 pl-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-agro-green/50 focus:border-agro-green shadow-sm text-sm font-medium">
                <option>All Statuses</option>
                <option>Growing</option>
                <option>Harvested</option>
                <option>Failed</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
        <div class="relative">
            <select class="appearance-none bg-white border border-gray-200 text-gray-700 py-2.5 pl-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-agro-green/50 focus:border-agro-green shadow-sm text-sm font-medium">
                <option>Sort by Date</option>
                <option>Harvesting Soon</option>
                <option>Recently Planted</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </div>
    
    @if(Auth::user()->role !== 'viewer')
    <a href="{{ route('crops.create') }}" class="inline-flex justify-center items-center gap-2 bg-agro-green text-white px-5 py-2.5 rounded-xl font-semibold shadow-sm hover:shadow-md hover:bg-agro-green/90 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add Crop
    </a>
    @endif
</div>

@if($crops->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($crops as $crop)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300 group flex flex-col relative">
                
                <!-- Color coded border line -->
                <div class="absolute top-0 left-0 w-1.5 h-full 
                    {{ $crop->status === 'growing' ? 'bg-status-success' : ($crop->status === 'harvested' ? 'bg-status-info' : 'bg-status-danger') }}">
                </div>

                <div class="p-6 pl-8 flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                {{ $crop->name }}
                            </h3>
                            @if($crop->variety)
                                <p class="text-sm text-gray-500 mt-1">{{ $crop->variety }}</p>
                            @endif
                        </div>
                        <x-stat-badge type="{{ $crop->status }}" label="{{ ucfirst($crop->status) }}" />
                    </div>

                    <div class="mt-6 mb-4">
                        <div class="flex justify-between text-xs font-medium mb-1">
                            <span class="text-gray-500">Planted: {{ $crop->planting_date->format('M d') }}</span>
                            <span class="text-gray-900">Harvest: {{ $crop->expected_harvest_date->format('M d, Y') }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden relative">
                            <div class="h-full rounded-full transition-all duration-1000 ease-out {{ $crop->status === 'harvested' ? 'bg-status-info' : 'bg-status-success' }}" 
                                 style="width: {{ $crop->progressPercentage() }}%">
                            </div>
                        </div>
                        @if($crop->status === 'growing')
                            <p class="text-xs text-right mt-1 font-semibold text-agro-green">
                                {{ $crop->daysRemaining() }} days left
                            </p>
                        @elseif($crop->status === 'harvested')
                            <p class="text-xs text-right mt-1 font-semibold text-status-info">
                                Yield: {{ number_format($crop->yield_kg) }} kg
                            </p>
                        @endif
                    </div>
                </div>

                @if(Auth::user()->role !== 'viewer')
                <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex justify-end gap-2 ml-1.5">
                    <a href="{{ route('crops.edit', $crop) }}" class="p-2 text-gray-400 hover:text-agro-gold hover:bg-white rounded-lg transition" title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </a>
                    
                    @if(Auth::user()->role === 'admin')
                    <form action="{{ route('crops.destroy', $crop) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this crop?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-gray-400 hover:text-status-danger hover:bg-white rounded-lg transition" title="Delete">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                    @endif
                </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $crops->links() }}
    </div>
@else
    <x-empty-state 
        icon='<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>'
        title="No crops planted yet"
        message="Start tracking your farm's growth by adding your first crop. You can monitor its progress from planting to harvest."
        @if(Auth::user()->role !== 'viewer')
        actionText="Add First Crop"
        actionUrl="{{ route('crops.create') }}"
        @endif
    />
@endif
@endsection
