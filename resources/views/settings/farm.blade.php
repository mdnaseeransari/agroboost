@extends('settings.layout')

@section('settings_content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <h2 class="text-xl font-bold font-poppins text-gray-900">Farm Details</h2>
        <p class="text-sm text-gray-500 mt-1">Update your farm's public profile and operational details.</p>
    </div>

    <form method="POST" action="{{ route('settings.farm.update') }}" class="p-6 space-y-6">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="name" :value="__('Farm Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $farm->name)" required :disabled="Auth::user()->role !== 'admin'" />
            </div>

            <div>
                <x-input-label for="location" :value="__('Location')" />
                <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $farm->location)" required :disabled="Auth::user()->role !== 'admin'" />
            </div>
        </div>

        @if(Auth::user()->role === 'admin')
        <div class="flex items-center gap-4 pt-4 border-t border-gray-50">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
        </div>
        @else
        <div class="p-4 bg-gray-50 rounded-xl mt-4">
            <p class="text-xs text-gray-500 italic">Only Farm Admins can update these details.</p>
        </div>
        @endif
    </form>
</div>
@endsection
