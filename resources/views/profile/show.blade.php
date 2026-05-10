@extends('layouts.app')
@section('title', 'User Profile')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Profile Header -->
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
        <!-- Decorative background -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-agro-green/5 rounded-full -mr-16 -mt-16"></div>
        
        <div class="w-32 h-32 rounded-full bg-agro-gold flex items-center justify-center text-agro-green text-5xl font-bold shadow-lg border-4 border-white shrink-0">
            {{ substr($user->name, 0, 1) }}
        </div>
        
        <div class="flex-1 text-center md:text-left">
            <h1 class="text-3xl font-bold text-gray-900 font-poppins">{{ $user->name }}</h1>
            <p class="text-gray-500 font-medium mt-1">{{ $user->email }}</p>
            <div class="mt-4 flex flex-wrap justify-center md:justify-start gap-3">
                <span class="px-3 py-1 bg-agro-green text-white rounded-full text-xs font-bold uppercase tracking-wider">{{ $user->role }}</span>
                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-bold uppercase tracking-wider">{{ $user->farm->name }}</span>
            </div>
        </div>

        <div class="shrink-0">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 text-white rounded-xl font-semibold text-sm hover:bg-gray-800 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Profile
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-2">Tasks Completed</p>
            <p class="text-3xl font-black text-agro-green font-poppins">{{ $stats['tasks_completed'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-2">Crops Managed</p>
            <p class="text-3xl font-black text-agro-gold font-poppins">{{ $stats['crops_managed'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
            <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-2">Member Since</p>
            <p class="text-xl font-bold text-gray-900 font-poppins h-9 flex items-center justify-center">{{ $stats['joined_date'] }}</p>
        </div>
    </div>

    <!-- Farm Info -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center gap-3">
            <svg class="w-6 h-6 text-agro-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <h2 class="text-xl font-bold font-poppins text-gray-900">Farm Information</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Farm Name</label>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $user->farm->name }}</p>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Location</label>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $user->farm->location }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
