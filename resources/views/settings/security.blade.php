@extends('settings.layout')

@section('settings_content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-xl font-bold font-poppins text-gray-900">Security Settings</h2>
        <p class="text-sm text-gray-500 mt-1">Keep your account secure with these advanced features.</p>
    </div>

    <div class="p-12 flex flex-col items-center justify-center text-center">
        <div class="w-16 h-16 bg-agro-green/10 rounded-full flex items-center justify-center text-agro-green mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Security is active</h3>
        <p class="text-sm text-gray-500 max-w-sm">Your account is currently protected by standard encryption and secure password hashing. Advanced features are currently being updated.</p>
    </div>
</div>
@endsection
