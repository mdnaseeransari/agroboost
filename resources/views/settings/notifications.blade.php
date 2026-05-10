@extends('settings.layout')

@section('settings_content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-xl font-bold font-poppins text-gray-900">Notification Preferences</h2>
        <p class="text-sm text-gray-500 mt-1">Control how and when you receive updates about your farm.</p>
    </div>

    <form method="POST" action="{{ route('settings.notifications.update') }}" class="p-6 space-y-8">
        @csrf
        @method('PATCH')
        <!-- Email Notifications -->
        <div>
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Email Notifications</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Harvest Alerts</p>
                        <p class="text-xs text-gray-500">Receive an email 7 days before an expected harvest.</p>
                    </div>
                    <button type="button" class="w-11 h-6 rounded-full bg-agro-green relative transition duration-200">
                        <span class="absolute right-1 top-1 w-4 h-4 rounded-full bg-white transition"></span>
                    </button>
                </div>
                
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Low Stock Alerts</p>
                        <p class="text-xs text-gray-500">Get notified when inventory items fall below threshold.</p>
                    </div>
                    <button type="button" class="w-11 h-6 rounded-full bg-agro-green relative transition duration-200">
                        <span class="absolute right-1 top-1 w-4 h-4 rounded-full bg-white transition"></span>
                    </button>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Task Assignments</p>
                        <p class="text-xs text-gray-500">When someone assigns you a new task.</p>
                    </div>
                    <button type="button" class="w-11 h-6 rounded-full bg-gray-200 relative transition duration-200">
                        <span class="absolute left-1 top-1 w-4 h-4 rounded-full bg-white transition"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Push Notifications -->
        <div class="pt-8 border-t border-gray-100">
            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">In-App Notifications</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Show Header Badge</p>
                        <p class="text-xs text-gray-500">Display a red dot on the bell icon for unread items.</p>
                    </div>
                    <button type="button" class="w-11 h-6 rounded-full bg-agro-green relative transition duration-200">
                        <span class="absolute right-1 top-1 w-4 h-4 rounded-full bg-white transition"></span>
                    </button>
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-gray-50">
            <x-primary-button>{{ __('Save Preferences') }}</x-primary-button>
        </div>
    </form>
</div>
@endsection
