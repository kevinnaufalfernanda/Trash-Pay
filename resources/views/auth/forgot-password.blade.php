<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-serif font-bold text-secondary mb-2">{{ __('Forgot Password?') }}</h2>
        <p class="text-sm text-gray-500">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-2 w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary focus:ring-primary bg-white text-gray-900 transition-colors shadow-sm placeholder:text-gray-400" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-8">
            <a class="text-sm font-medium text-gray-500 hover:text-primary transition-colors" href="{{ route('login') }}">
                {{ __('Back to Login') }}
            </a>

            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary to-emerald-500 text-white font-bold rounded-xl hover:-translate-y-0.5 transition-transform shadow-lg shadow-emerald-500/30">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>
</x-guest-layout>
