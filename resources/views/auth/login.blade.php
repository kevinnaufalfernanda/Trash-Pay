<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div x-data="{ role: 'user' }" class="mb-6">
        <!-- Role Tabs -->
        <div class="flex p-1 bg-white/50 backdrop-blur-sm border border-white/60 rounded-2xl mb-6 shadow-inner">
            <button @click="role = 'user'" 
                    :class="{'bg-white shadow-md text-primary font-bold': role === 'user', 'text-gray-500 hover:text-gray-700 hover:bg-white/40': role !== 'user'}" 
                    type="button" class="flex-1 py-3 text-sm rounded-xl transition-all text-center focus:outline-none">
                Warga
            </button>
            <button @click="role = 'driver'" 
                    :class="{'bg-gradient-to-r from-primary to-emerald-500 shadow-md text-white font-bold': role === 'driver', 'text-gray-500 hover:text-gray-700 hover:bg-white/40': role !== 'driver'}" 
                    type="button" class="flex-1 py-3 text-sm rounded-xl transition-all text-center focus:outline-none">
                Eco-Driver
            </button>
        </div>

        <div class="text-center mb-6">
            <h2 class="text-2xl font-serif font-bold text-secondary" x-text="role === 'user' ? 'Selamat Datang, Warga!' : 'Selamat Datang, Driver!'"></h2>
            <p class="text-sm text-gray-500 mt-1" x-text="role === 'user' ? 'Login untuk kelola sampah & tukar koin.' : 'Login untuk melihat pesanan penjemputan.'"></p>
        </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary bg-white/70" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary bg-white/70"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-8">
            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-gray-500 hover:text-primary transition-colors" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif

            <button class="px-6 py-3 bg-gradient-to-r from-primary to-emerald-500 text-white font-bold rounded-xl hover:-translate-y-0.5 transition-transform shadow-lg shadow-emerald-500/30">
                {{ __('Masuk') }}
            </button>
        </div>
        
        <div class="mt-8 text-center text-sm font-medium">
            <span class="text-gray-500">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="text-primary font-bold hover:underline ml-1">Daftar sekarang</a>
        </div>
    </form>
    </div>
</x-guest-layout>
