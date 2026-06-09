<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-semibold text-2xl text-secondary leading-tight">
            {{ __('Redeem Center') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8 grid md:grid-cols-3 gap-8">

            <!-- Redeem Form -->
            <div class="md:col-span-2">
                <div class="glass-panel rounded-3xl p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-amber-400/10 rounded-full blur-3xl -z-10"></div>
                    <h3 class="text-3xl font-serif font-semibold mb-2 text-secondary tracking-tight">{{ __('Redeem Coins') }}</h3>
                    <p class="text-sm font-medium text-gray-500 mb-6">{{ __('Exchange your collected coins for e-wallet balance!') }}</p>

                    <div class="mb-6 p-4 rounded-2xl bg-white/60 border border-white/80 flex items-center gap-4 shadow-sm">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-secondary text-sm">{{ __('Exchange Rate Info') }}</div>
                            <div class="text-xs text-gray-600 font-medium">{{ __('Every') }} <strong class="text-amber-600">{{ __('10 Coins') }}</strong> {{ __('is equal to') }} <strong class="text-emerald-600">{{ __('Rp 1.000') }}</strong> {{ __('e-wallet balance.') }}</div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 bg-emerald-100 text-emerald-700 px-4 py-3 rounded-xl">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded-xl">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div
                        class="mb-8 p-4 bg-amber-50 rounded-xl border border-amber-200 flex justify-between items-center">
                        <div>
                            <div class="text-amber-800 font-semibold text-sm">{{ __('Available Balance') }}</div>
                            <div class="text-3xl font-bold text-amber-600 flex items-center gap-1.5">{{ number_format($driver->coin_balance) }}
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg></div>
                        </div>
                        <div class="text-amber-500 bg-amber-100 p-3 rounded-2xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
                        </div>
                    </div>

                    <form action="{{ route('driver.redeem.store') }}" method="POST" x-data="{ amount: '', max: {{ $driver->coin_balance }}, accountNumber: '{{ $driver->payment_number }}', savedNumber: '{{ $driver->payment_number }}', confirmedNumber: false }">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('E-Wallet Provider') }}</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <input type="radio" name="provider" id="dana" value="Dana" class="peer hidden"
                                        required>
                                    <label for="dana"
                                        class="block text-center cursor-pointer bg-white/50 border border-white/60 shadow-sm rounded-2xl p-4 hover:border-blue-300 peer-checked:border-2 peer-checked:border-blue-500 peer-checked:bg-blue-50/80 peer-checked:shadow-md transition-all">
                                        <div class="font-bold text-blue-600 text-lg">DANA</div>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" name="provider" id="gopay" value="GoPay" class="peer hidden"
                                        required>
                                    <label for="gopay"
                                        class="block text-center cursor-pointer bg-white/50 border border-white/60 shadow-sm rounded-2xl p-4 hover:border-green-300 peer-checked:border-2 peer-checked:border-green-500 peer-checked:bg-green-50/80 peer-checked:shadow-md transition-all">
                                        <div class="font-bold text-green-600 text-lg">GoPay</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-10">
                            <label for="amount" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Coin Amount') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-amber-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
                                </div>
                                <input type="number" name="amount" id="amount" min="100" max="{{ $driver->coin_balance }}" x-model.number="amount"
                                    class="w-full pl-14 rounded-2xl border shadow-sm focus:ring-4 text-lg font-bold py-4 bg-white/70 transition-all"
                                    :class="amount > max ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20' : 'border-gray-200 focus:border-primary focus:ring-primary/20'"
                                    required placeholder="{{ __('Min. 100') }}">
                            </div>
                            <div class="mt-2 text-red-500 text-sm font-medium" x-show="amount > max" x-cloak>
                                {{ __('Coin amount exceeds your available balance.') }}
                            </div>
                            <div class="mt-3 text-sm font-semibold text-gray-600 bg-emerald-50 px-4 py-3 rounded-xl border border-emerald-100 flex items-center justify-between transition-all" x-show="amount >= 100" x-cloak x-transition>
                                <span>{{ __('Estimated Money:') }}</span>
                                <span class="text-emerald-700 font-bold text-lg">Rp <span x-text="(amount * 100).toLocaleString('id-ID')"></span></span>
                            </div>
                        </div>

                        <div class="mb-10">
                            <label for="account_number" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Phone Number / Target Account') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="text" name="account_number" id="account_number" x-model="accountNumber"
                                    class="w-full pl-14 rounded-2xl border border-gray-200 shadow-sm focus:border-primary focus:ring-primary focus:ring-4 focus:ring-primary/20 text-lg font-bold py-4 bg-white/70 transition-all"
                                    required placeholder="{{ __('e.g., 081234567890') }}">
                            </div>
                            
                            <div class="mt-4 p-4 bg-red-50 rounded-xl border border-red-200 flex items-start gap-3" x-show="accountNumber !== savedNumber && savedNumber !== '' && accountNumber !== ''" x-cloak x-transition>
                                <input type="checkbox" id="confirm_number" x-model="confirmedNumber" class="mt-1 w-4 h-4 text-primary bg-white border-red-300 rounded focus:ring-primary focus:ring-2 cursor-pointer">
                                <label for="confirm_number" class="text-sm text-red-800 font-medium cursor-pointer">
                                    {{ __('Nomor ini berbeda dengan nomor di profil. Saya mengkonfirmasi nomor ini sudah sesuai.') }}
                                </label>
                            </div>
                            <div class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-200 flex items-start gap-3" x-show="savedNumber === '' && accountNumber !== ''" x-cloak x-transition>
                                <input type="checkbox" id="confirm_new_number" x-model="confirmedNumber" class="mt-1 w-4 h-4 text-primary bg-white border-amber-300 rounded focus:ring-primary focus:ring-2 cursor-pointer">
                                <label for="confirm_new_number" class="text-sm text-amber-800 font-medium cursor-pointer">
                                    {{ __('Belum ada nomor yang tersimpan di profil. Saya mengkonfirmasi nomor ini sudah sesuai.') }}
                                </label>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full px-8 py-5 bg-gradient-to-r from-primary to-emerald-500 text-white font-bold text-lg rounded-full btn-premium disabled:opacity-50 disabled:cursor-not-allowed"
                            x-bind:disabled="amount > max || amount < 100 || (accountNumber !== savedNumber && !confirmedNumber) || (savedNumber === '' && !confirmedNumber)"
                            @if($driver->coin_balance < 100) disabled @endif>
                            {{ __('Withdraw Now') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- History -->
            <div>
                <div class="glass-panel rounded-3xl p-6">
                    <h3 class="text-xl font-serif font-semibold mb-4 text-secondary flex items-center justify-between">
                        {{ __('Riwayat Penukaran') }}
                    </h3>

                    @if($redemptions->isEmpty())
                        <div class="text-center py-8">
                            <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">{{ __('Belum ada riwayat penukaran koin.') }}</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($redemptions->take(3) as $redemption)
                                @include('partials.redemption-card', ['redemption' => $redemption])
                            @endforeach
                        </div>
                        <div class="mt-6 flex justify-center">
                            <a href="{{ route('driver.history') }}" class="inline-block px-6 py-2.5 bg-gray-50 text-gray-600 font-bold rounded-full border border-gray-200 hover:bg-gray-100 transition-colors text-sm text-center w-full">
                                {{ __('Lihat Semua Riwayat') }} &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
