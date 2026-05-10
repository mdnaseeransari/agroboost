<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Forgot Password?</h2>
        <p class="text-gray-500 text-sm mt-2">
            {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 focus:border-agro-green focus:ring-agro-green rounded-xl shadow-sm transition" type="email" name="email" :value="old('email')" required autofocus placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-agro-green hover:bg-agro-green/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-agro-green transition transform hover:-translate-y-0.5">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
        
        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-agro-green transition">
                &larr; Back to login
            </a>
        </div>
    </form>
</x-guest-layout>
