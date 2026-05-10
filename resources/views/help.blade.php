@extends('layouts.app')
@section('title', 'Help & Support')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <!-- Help Hero -->
    <div class="bg-agro-green rounded-3xl p-8 text-white relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <h1 class="text-3xl font-bold font-poppins mb-2">How can we help you today?</h1>
            <p class="text-agro-gold font-medium mb-6">Search our knowledge base or contact our farm experts.</p>
            <div class="relative">
                <input type="text" placeholder="Search for answers..." class="w-full bg-white/10 border border-white/20 rounded-xl py-4 pl-12 pr-4 text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-agro-gold focus:border-transparent">
                <svg class="w-6 h-6 absolute left-4 top-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>
        <!-- Decorative leaf SVG -->
        <svg class="absolute -right-10 -bottom-10 w-64 h-64 text-white/5" fill="currentColor" viewBox="0 0 24 24"><path d="M17,8C8,10 5.9,16.17 3.82,21.34L5.71,22L6.66,19.7C7.14,19.87 7.64,20 8.13,20C11,20 13.56,18.06 14.43,15.4C16.85,15.4 19,13.43 19,11C19,10.13 18.75,9.3 18.3,8.59L21,6.58L19.7,5.13L17,7.14C16.3,6.7 15.47,6.43 14.6,6.43C12.17,6.43 10.2,8.53 10.2,11C10.2,11.5 10.28,12 10.42,12.47C8.44,12.87 7,14.63 7,16.74C7,17.43 7.15,18.07 7.42,18.66C8.8,15.4 10.6,12.1 14.6,10.6C14.1,12.1 13,14 11,15.4C12.8,14.6 14.6,14.6 16.4,15.4C15.9,13.9 14.8,12 12.8,10.6C14.6,11.4 16.4,11.4 18.2,12.2C17.7,10.7 16.6,8.8 14.6,7.4C16.4,8.2 18.2,8.2 20,9C19.5,7.5 18.4,5.6 16.4,4.2C18.2,5 20,5 21.8,5.8C21.3,4.3 20.2,2.4 18.2,1L17,2.25L18.2,3.5C16.2,2.1 14.1,1.4 12,1.4C9.5,1.4 7.4,2.2 5.7,3.6L7,4.85L5.8,6.1C4.1,4.7 2,4 0,4C0,4 0,4 0,4C0,6.1 0.7,8.2 2.1,10.2L3.35,9L4.6,10.2C3.2,8.5 2.5,6.4 2.5,4.3C4.6,4.3 6.7,5 8.4,6.4L7.15,7.65L8.4,8.9C6.7,7.5 4.6,6.8 2.5,6.8C2.5,8.9 3.2,11 4.6,12.7L5.85,11.45L7.1,12.7C5.7,11 5,8.9 5,6.8C7.1,6.8 9.2,7.5 10.9,8.9L9.65,10.15L10.9,11.4C9.2,10 7.1,9.3 5,9.3C5,11.4 5.7,13.5 7.1,15.2L8.35,13.95L9.6,15.2C8.2,13.5 7.5,11.4 7.5,9.3C9.6,9.3 11.7,10 13.4,11.4L12.15,12.65L13.4,13.9C11.7,12.5 9.6,11.8 7.5,11.8C7.5,13.9 8.2,16 9.6,17.7L10.85,16.45L12.1,17.7C10.7,16 10,13.9 10,11.8C12.1,11.8 14.2,12.5 15.9,13.9L14.65,15.15L15.9,16.4C14.2,15 12.1,14.3 10,14.3C10,16.4 10.7,18.5 12.1,20.2L13.35,18.95L14.6,20.2C13.2,18.5 12.5,16.4 12.5,14.3C14.6,14.3 16.7,15 18.4,16.4L17.15,17.65L18.4,18.9C16.7,17.5 14.6,16.8 12.5,16.8C12.5,18.9 13.2,21 14.6,22.7L15.85,21.45L17.1,22.7C15.7,21 15,18.9 15,16.8C17.1,16.8 19.2,17.5 20.9,18.9L19.65,20.15L20.9,21.4C19.2,20 17.1,19.3 15,19.3C15,21.4 15.7,23.5 17.1,25.2L18.35,23.95L19.6,25.2C18.2,23.5 17.5,21.4 17.5,19.3C19.6,19.3 21.7,20 23.4,21.4L22.15,22.65L23.4,23.9C21.7,22.5 19.6,21.8 17.5,21.8C17.5,23.9 18.2,26 19.6,27.7L20.85,26.45L22.1,27.7C20.7,26 20,23.9 20,21.8C22.1,21.8 24.2,22.5 25.9,23.9L24.65,25.15L25.9,26.4C24.2,25 22.1,24.3 20,24.3"></path></svg>
    </div>

    <!-- Help Sections -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-agro-gold/10 rounded-xl flex items-center justify-center text-agro-gold mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h3 class="font-bold text-gray-900 mb-2">Knowledge Base</h3>
            <p class="text-sm text-gray-600 mb-4">Detailed guides on using every feature of AgroBoost.</p>
            <a href="#" class="text-agro-green font-semibold text-sm hover:underline">Read Guides &rarr;</a>
        </div>
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-status-success/10 rounded-xl flex items-center justify-center text-status-success mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="font-bold text-gray-900 mb-2">Video Tutorials</h3>
            <p class="text-sm text-gray-600 mb-4">Watch step-by-step videos of common farm workflows.</p>
            <a href="#" class="text-agro-green font-semibold text-sm hover:underline">Watch Now &rarr;</a>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
            <div class="w-12 h-12 bg-status-info/10 rounded-xl flex items-center justify-center text-status-info mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            </div>
            <h3 class="font-bold text-gray-900 mb-2">Live Support</h3>
            <p class="text-sm text-gray-600 mb-4">Chat with our agricultural tech support team.</p>
            <a href="#" class="text-agro-green font-semibold text-sm hover:underline">Start Chat &rarr;</a>
        </div>
    </div>

    <!-- FAQ -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-bold font-poppins text-gray-900">Frequently Asked Questions</h2>
        </div>
        <div class="p-6 space-y-4" x-data="{ active: null }">
            <div class="border border-gray-100 rounded-xl overflow-hidden">
                <button @click="active = active === 0 ? null : 0" class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition">
                    <span class="font-bold text-gray-900">How do I add a new crop?</span>
                    <svg class="w-5 h-5 transition-transform" :class="active === 0 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="active === 0" x-collapse class="p-4 bg-gray-50 text-sm text-gray-600">
                    To add a crop, navigate to the "Crops" module in the sidebar and click the "Add Crop" button. Enter your planting date and expected harvest date to start tracking growth.
                </div>
            </div>

            <div class="border border-gray-100 rounded-xl overflow-hidden">
                <button @click="active = active === 1 ? null : 1" class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition">
                    <span class="font-bold text-gray-900">What is the Farm Health Score?</span>
                    <svg class="w-5 h-5 transition-transform" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="active === 1" x-collapse class="p-4 bg-gray-50 text-sm text-gray-600">
                    The health score is a calculation based on task completion rates, inventory stock levels, and crop growth progress. Aim for a score above 80 for optimal farm performance.
                </div>
            </div>

            <div class="border border-gray-100 rounded-xl overflow-hidden">
                <button @click="active = active === 2 ? null : 2" class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition">
                    <span class="font-bold text-gray-900">How do I invite team members?</span>
                    <svg class="w-5 h-5 transition-transform" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="active === 2" x-collapse class="p-4 bg-gray-50 text-sm text-gray-600">
                    Only Admins can invite team members. Go to Settings -> Team Management and click "Invite Member". Enter their email and assign a role (Farmer or Viewer).
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <div class="max-w-2xl mx-auto text-center mb-8">
            <h2 class="text-2xl font-bold font-poppins text-gray-900 mb-2">Still have questions?</h2>
            <p class="text-gray-600">Fill out the form below and our team will get back to you within 24 hours.</p>
        </div>
        <form action="#" method="POST" class="max-w-2xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="Auth::user()->name" required />
            </div>
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="Auth::user()->email" required />
            </div>
            <div class="md:col-span-2">
                <x-input-label for="subject" :value="__('Subject')" />
                <x-text-input id="subject" class="mt-1 block w-full" type="text" name="subject" required />
            </div>
            <div class="md:col-span-2">
                <x-input-label for="message" :value="__('Message')" />
                <textarea id="message" name="message" rows="4" class="mt-1 block w-full border-gray-200 focus:border-agro-green focus:ring-agro-green/50 rounded-xl shadow-sm transition duration-200" required></textarea>
            </div>
            <div class="md:col-span-2 flex justify-center">
                <x-primary-button class="w-full sm:w-auto px-12">Send Message</x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection
