@extends('layouts.app')

@section('title', 'Dashboard Overview')

@section('content')

@if($role === 'viewer')
    <x-alert-banner type="info" message="You are viewing the dashboard in Read-Only mode. You cannot modify records." />
@endif

<!-- Admin Section (Team & System Metrics) -->
@if($role === 'admin')
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-800">System Overview (Admin)</h2>
        <a href="#" class="text-sm font-semibold text-agro-green hover:underline">Manage Team & Settings</a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-1 gap-6">
        <div class="bg-agro-green text-white rounded-2xl p-6 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-green-100 font-medium mb-1">Total Team Members</p>
                <p class="text-3xl font-bold">{{ $teamCount }}</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('tasks.create') }}" class="px-4 py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl text-sm font-bold transition">
                    + Assign Task
                </a>
                <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Main Metric Cards (Visible based on role) -->
<h2 class="text-xl font-bold text-gray-800 mb-4">{{ $role === 'farmer' ? 'My Work' : 'Farm Status' }}</h2>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @if($role === 'admin' || $role === 'viewer')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col group hover:shadow-md transition">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-status-success/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition duration-300">
                <svg class="w-6 h-6 text-status-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
            </div>
            <x-stat-badge type="healthy" label="Healthy" />
        </div>
        <h3 class="text-gray-500 font-medium text-sm">Active Crops</h3>
        <p class="text-3xl font-bold font-poppins text-gray-900 mt-1">{{ $activeCrops }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col group hover:shadow-md transition">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-status-warning/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition duration-300">
                <svg class="w-6 h-6 text-status-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            @if($lowStockItems > 0)
                <x-stat-badge type="warning" label="Needs Attention" />
            @else
                <x-stat-badge type="healthy" label="All Good" />
            @endif
        </div>
        <h3 class="text-gray-500 font-medium text-sm">Low Stock Items</h3>
        <p class="text-3xl font-bold font-poppins {{ $lowStockItems > 0 ? 'text-status-warning' : 'text-gray-900' }} mt-1">{{ $lowStockItems }}</p>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col group hover:shadow-md transition {{ ($role === 'admin' || $role === 'viewer') ? '' : 'sm:col-span-1 lg:col-span-2' }}">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-status-info/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition duration-300">
                <svg class="w-6 h-6 text-status-info" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
        </div>
        <h3 class="text-gray-500 font-medium text-sm">{{ $role === 'farmer' ? 'My Pending Tasks' : 'Pending Tasks' }}</h3>
        <p class="text-3xl font-bold font-poppins text-gray-900 mt-1">{{ $pendingTasks }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col group hover:shadow-md transition {{ ($role === 'admin' || $role === 'viewer') ? '' : 'sm:col-span-1 lg:col-span-2' }}">
        <div class="flex justify-between items-start mb-4">
            <div class="w-12 h-12 bg-status-danger/10 rounded-xl flex items-center justify-center group-hover:scale-110 transition duration-300">
                <svg class="w-6 h-6 text-status-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            @if($overdueTasks > 0)
                <x-stat-badge type="danger" label="Urgent" />
            @endif
        </div>
        <h3 class="text-gray-500 font-medium text-sm">{{ $role === 'farmer' ? 'My Overdue Tasks' : 'Overdue Tasks' }}</h3>
        <p class="text-3xl font-bold font-poppins text-status-danger mt-1">{{ $overdueTasks }}</p>
    </div>
</div>

<div class="grid grid-cols-1 {{ $role === 'farmer' ? 'lg:grid-cols-2' : 'lg:grid-cols-3' }} gap-8">
    
    <!-- Tasks List -->
    <div class="{{ $role === 'farmer' ? '' : 'lg:col-span-2' }}">
        <x-card title="Assigned Tasks" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>'>
            <x-slot name="action">
                <a href="{{ route('tasks.index') }}" class="text-sm font-semibold text-agro-green hover:text-agro-gold transition">View All</a>
            </x-slot>

            @if($recentTasks->count() > 0)
                <div class="space-y-4">
                    @foreach($recentTasks as $task)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-agro-green/30 hover:bg-agro-green/5 transition group">
                            <div class="flex items-center gap-4">
                                @if($role !== 'viewer')
                                <!-- Interactive Checkbox for Admin/Farmer -->
                                <form action="{{ route('tasks.update', $task) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="completed" value="1">
                                    <button type="submit" class="w-6 h-6 rounded-md border-2 border-gray-300 flex items-center justify-center text-transparent hover:border-agro-green hover:text-agro-green transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </form>
                                @else
                                <!-- Disabled Checkbox for Viewer -->
                                <div class="w-6 h-6 rounded-md border-2 border-gray-300 opacity-50 flex items-center justify-center"></div>
                                @endif
                                
                                <div>
                                    <h4 class="font-semibold text-gray-900 group-hover:text-agro-green transition">{{ $task->title }}</h4>
                                    <div class="flex items-center gap-3 mt-1">
                                        <p class="text-[10px] text-gray-500 flex items-center gap-1">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $task->due_date->format('M d') }}
                                        </p>
                                        @if($task->assignee)
                                            <p class="text-[10px] text-agro-green font-bold flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                {{ $task->assignee->name }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                @if($task->due_date < now())
                                    <x-stat-badge type="overdue" label="Overdue" />
                                @else
                                    <x-stat-badge type="info" label="Pending" />
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-10">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <p class="text-gray-500 text-sm">You have no pending tasks!</p>
                </div>
            @endif
        </x-card>

        @if($role === 'admin' || $role === 'viewer')
        <!-- Low Stock Items Alert (New) -->
        <div class="mt-8">
            <x-card title="Inventory Alerts" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>'>
                <x-slot name="action">
                    <a href="{{ route('inventory.index') }}" class="text-sm font-semibold text-agro-green hover:text-agro-gold transition">Manage Stock</a>
                </x-slot>

                @if($lowStockList->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($lowStockList as $item)
                            <div class="p-4 bg-status-danger/5 border border-status-danger/10 rounded-xl flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-status-danger rounded-full animate-pulse"></div>
                                    <div>
                                        <h5 class="font-bold text-gray-900 text-sm">{{ $item->name }}</h5>
                                        <p class="text-xs text-status-danger font-medium">{{ $item->quantity }} {{ $item->unit }} remaining</p>
                                    </div>
                                </div>
                                <a href="{{ route('inventory.edit', $item) }}" class="text-[10px] bg-status-danger text-white px-2 py-1 rounded font-bold uppercase tracking-wider">Order</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-6">
                        <div class="w-12 h-12 bg-status-success/10 rounded-full flex items-center justify-center text-status-success mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">All items healthy - no alerts</p>
                    </div>
                @endif
            </x-card>
        </div>
        @endif
    </div>

    </div>

    <!-- Right Sidebar / Secondary Grid Item -->
    <div class="{{ $role === 'farmer' ? '' : 'lg:col-span-1' }}">
        @if($role === 'admin' || $role === 'viewer')
        <x-card title="Priority Crops" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>'>
            <x-slot name="action">
                <a href="{{ route('crops.index') }}" class="text-sm text-agro-green font-medium hover:underline">See All</a>
            </x-slot>

            <div class="space-y-4">
                @forelse($topCrops as $crop)
                    <div class="p-4 border border-gray-100 rounded-xl bg-white shadow-sm hover:border-agro-green/30 transition">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-bold text-gray-900">{{ $crop->name }}</h4>
                            <span class="text-xs font-semibold text-agro-green">{{ $crop->daysRemaining() }} days left</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-status-success h-full rounded-full" style="width: {{ $crop->progressPercentage() }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 italic text-center py-4">No active crops.</p>
                @endforelse
            </div>
            
            <a href="{{ route('crops.create') }}" class="mt-4 block w-full py-2.5 bg-gray-50 text-gray-700 font-semibold rounded-xl text-center hover:bg-gray-100 border border-gray-200 border-dashed transition">
                + Add New Crop
            </a>
        </x-card>

        @if($role === 'admin')
        <!-- Team Members (New Admin Section) -->
        <div class="mt-8">
            <x-card title="Team Status" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>'>
                <x-slot name="action">
                    <a href="{{ route('settings.team') }}" class="text-sm font-semibold text-agro-green hover:underline">Manage</a>
                </x-slot>
                
                <div class="space-y-4">
                    @foreach($teamMembers as $member)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-agro-gold/20 flex items-center justify-center text-agro-green font-bold text-xs">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-900">{{ $member->name }}</p>
                                    <p class="text-[10px] text-gray-500 capitalize">{{ $member->role }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 bg-status-success rounded-full"></div>
                                <span class="text-[10px] text-gray-400">Online</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>
        </div>
        @endif
        @endif

        <!-- Messaging System (Available to All, but positioned differently for Farmers) -->
        <div class="{{ $role === 'farmer' ? '' : 'mt-8' }}">
            <x-card title="Messages" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>'>
                <div class="space-y-4 max-h-[300px] overflow-y-auto mb-4 pr-2">
                    @forelse($messages as $msg)
                        <div class="p-3 rounded-xl text-xs {{ $msg->sender_id === Auth::id() ? 'bg-agro-green/10 ml-6 border border-agro-green/20' : 'bg-gray-100 mr-6 border border-gray-200' }}">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-bold {{ $msg->sender_id === Auth::id() ? 'text-agro-green' : 'text-gray-900' }}">
                                    {{ $msg->sender->name }}
                                </span>
                                <span class="text-[10px] text-gray-400">{{ $msg->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-700 text-[11px] leading-relaxed">{{ $msg->content }}</p>
                        </div>
                    @empty
                        <p class="text-center text-gray-400 text-xs py-4">No messages yet.</p>
                    @endforelse
                </div>

                <form action="{{ route('messages.store') }}" method="POST" class="space-y-3">
                    @csrf
                    @if($role === 'admin')
                        <select name="receiver_id" required class="w-full text-xs border-gray-200 rounded-lg focus:ring-agro-green focus:border-agro-green">
                            <option value="">Select Farmer to Reply</option>
                            @foreach(\App\Models\User::where('farm_id', Auth::user()->farm_id)->where('role', 'farmer')->get() as $farmer)
                                <option value="{{ $farmer->id }}">{{ $farmer->name }}</option>
                            @endforeach
                        </select>
                    @endif
                    <div class="flex gap-2">
                        <input type="text" name="content" required placeholder="{{ $role === 'admin' ? 'Type a reply...' : 'Message Admin...' }}" 
                            class="flex-1 text-xs border-gray-200 rounded-lg focus:ring-agro-green focus:border-agro-green shadow-sm">
                        <button type="submit" class="bg-agro-green text-white p-2 rounded-lg hover:bg-agro-green/90 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</div>

@endsection
