<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Reset Password</h2>
        <p class="text-gray-500 text-sm mt-2">Enter your new password below.</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 focus:border-agro-green focus:ring-agro-green rounded-xl shadow-sm transition" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('New Password')" class="text-gray-700 font-semibold" />
            <x-text-input id="password" class="block mt-1 w-full border-gray-300 focus:border-agro-green focus:ring-agro-green rounded-xl shadow-sm transition"
                            type="password"
                            name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-gray-700 font-semibold" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full border-gray-300 focus:border-agro-green focus:ring-agro-green rounded-xl shadow-sm transition"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-agro-green hover:bg-agro-green/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-agro-green transition transform hover:-translate-y-0.5">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>
