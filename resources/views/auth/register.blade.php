<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Hidden Role (Default to User) -->
        <input type="hidden" name="role" value="user">

        <!-- Name -->
        <div class="mb-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-2 w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary focus:ring-primary bg-white text-gray-900 transition-colors shadow-sm placeholder:text-gray-400" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-2 w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary focus:ring-primary bg-white text-gray-900 transition-colors shadow-sm placeholder:text-gray-400" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-2 w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary focus:ring-primary bg-white text-gray-900 transition-colors shadow-sm placeholder:text-gray-400"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-2 w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-primary focus:ring-primary bg-white text-gray-900 transition-colors shadow-sm placeholder:text-gray-400"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-8">
            <a class="text-sm font-medium text-gray-500 hover:text-primary transition-colors" href="{{ route('login') }}">
                {{ __('Sudah punya akun?') }}
            </a>

            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-primary to-emerald-500 text-white font-bold rounded-xl hover:-translate-y-0.5 transition-transform shadow-lg shadow-emerald-500/30">
                {{ __('Daftar Sekarang') }}
            </button>
        </div>
    </form>
</x-guest-layout>
