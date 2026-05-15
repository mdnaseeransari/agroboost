@extends('layouts.app')
@section('title', 'Task Management')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <!-- Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 hide-scrollbar">
        @php $status = request('status'); @endphp
        <a href="{{ route('tasks.index') }}" class="px-4 py-2 {{ !$status ? 'bg-agro-green text-white shadow-sm' : 'bg-white text-gray-600 hover:text-agro-green border border-gray-200' }} rounded-xl font-semibold text-sm whitespace-nowrap transition">All Tasks</a>
        <a href="{{ route('tasks.index', ['status' => 'pending']) }}" class="px-4 py-2 {{ $status === 'pending' ? 'bg-agro-green text-white shadow-sm' : 'bg-white text-gray-600 hover:text-agro-green border border-gray-200' }} rounded-xl font-semibold text-sm whitespace-nowrap transition">Pending</a>
        <a href="{{ route('tasks.index', ['status' => 'overdue']) }}" class="px-4 py-2 {{ $status === 'overdue' ? 'bg-status-danger text-white shadow-sm' : 'bg-white text-gray-600 hover:text-status-danger border border-gray-200' }} rounded-xl font-semibold text-sm whitespace-nowrap transition">Overdue</a>
        <a href="{{ route('tasks.index', ['status' => 'completed']) }}" class="px-4 py-2 {{ $status === 'completed' ? 'bg-status-info text-white shadow-sm' : 'bg-white text-gray-600 hover:text-status-info border border-gray-200' }} rounded-xl font-semibold text-sm whitespace-nowrap transition">Completed</a>
    </div>
    
    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'farmer')
    <a href="{{ route('tasks.create') }}" class="inline-flex justify-center items-center gap-2 bg-agro-green text-white px-5 py-2.5 rounded-xl font-semibold shadow-sm hover:shadow-md hover:bg-agro-green/90 transition whitespace-nowrap">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Add Task
    </a>
    @endif
</div>

@if($tasks->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tasks as $task)
            @php
                $isOverdue = !$task->completed && $task->due_date < now();
                $isCompleted = $task->completed;
                $statusColor = $isCompleted ? 'status-info' : ($isOverdue ? 'status-danger' : 'status-success');
            @endphp
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col group hover:shadow-lg transition duration-300 relative overflow-hidden">
                <!-- Color bar on the left -->
                <div class="absolute top-0 left-0 w-1.5 h-full bg-{{ $statusColor }}"></div>
                
                <div class="flex justify-between items-start mb-4 pl-2">
                    <div class="flex items-center gap-3">
                        @if(Auth::user()->role !== 'viewer')
                        <form action="{{ route('tasks.update', $task) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="completed" value="{{ $task->completed ? 0 : 1 }}">
                            <button type="submit" class="w-6 h-6 rounded-md border-2 {{ $isCompleted ? 'bg-status-info border-status-info text-white' : 'border-gray-300 text-transparent hover:border-agro-green hover:text-agro-green' }} flex items-center justify-center transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        </form>
                        @else
                        <div class="w-6 h-6 rounded-md border-2 {{ $isCompleted ? 'bg-status-info border-status-info text-white' : 'border-gray-300 text-transparent opacity-50' }} flex items-center justify-center">
                            @if($isCompleted)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </div>
                        @endif
                        <h3 class="text-lg font-bold text-gray-900 {{ $isCompleted ? 'line-through text-gray-400' : '' }}">{{ $task->title }}</h3>
                    </div>
                    
                    @if($isOverdue)
                        <svg class="w-6 h-6 text-status-danger animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                </div>

                @if($task->description)
                    <p class="text-sm text-gray-600 mb-4 pl-11 line-clamp-2 {{ $isCompleted ? 'opacity-50' : '' }}">{{ $task->description }}</p>
                @endif

                <div class="pl-11 mb-4">
                    @if($task->assignee)
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-agro-gold/20 flex items-center justify-center text-agro-green font-bold text-[10px]">
                                {{ substr($task->assignee->name, 0, 1) }}
                            </div>
                            <span class="text-xs text-gray-500 font-medium">Assigned to {{ $task->assignee->name }}</span>
                        </div>
                    @else
                        <span class="text-xs text-gray-400 italic">Unassigned</span>
                    @endif
                </div>

                <div class="mt-auto pl-11 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 {{ $isOverdue ? 'text-status-danger' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-xs font-semibold {{ $isOverdue ? 'text-status-danger' : 'text-gray-500' }}">
                            {{ $task->due_date->format('M d, Y') }}
                        </span>
                    </div>
                    
                    @if(Auth::user()->role !== 'viewer')
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                        <a href="{{ route('tasks.edit', $task) }}" class="p-1.5 text-gray-400 hover:text-agro-gold rounded-lg transition" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        @if(Auth::user()->role === 'admin')
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-status-danger rounded-lg transition" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
        {{ $tasks->links() }}
    </div>
@else
    <x-empty-state 
        icon='<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>'
        title="No tasks yet"
        message="Keep your farm organized by creating tasks. You can assign due dates and track completion."
        @if(Auth::user()->role !== 'viewer')
        actionText="Add Task"
        actionUrl="{{ route('tasks.create') }}"
        @endif
    />
@endif
@endsection
