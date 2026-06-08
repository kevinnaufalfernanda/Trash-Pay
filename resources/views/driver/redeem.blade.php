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
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-xl">
                            💡
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
                            <div class="text-3xl font-bold text-amber-600">{{ number_format($driver->coin_balance) }}
                                <span class="text-lg">🪙</span></div>
                        </div>
                        <div class="text-2xl">💰</div>
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
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <span class="text-xl">🪙</span>
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
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <span class="text-xl">📱</span>
                                </div>
                                <input type="text" name="account_number" id="account_number" x-model="accountNumber"
                                    class="w-full pl-14 rounded-2xl border border-gray-200 shadow-sm focus:border-primary focus:ring-primary focus:ring-4 focus:ring-primary/20 text-lg font-bold py-4 bg-white/70 transition-all"
                                    required placeholder="{{ __('e.g., 081234567890') }}">
                            </div>
                            
                            <div class="mt-4 p-4 bg-red-50 rounded-xl border border-red-200 flex items-start gap-3" x-show="accountNumber !== savedNumber && savedNumber !== '' && accountNumber !== ''" x-cloak x-transition>
                                <input type="checkbox" id="confirm_number" x-model="confirmedNumber" class="mt-1 w-4 h-4 text-red-600 bg-white border-red-300 rounded focus:ring-red-500 focus:ring-2">
                                <label for="confirm_number" class="text-sm text-red-800 font-medium">
                                    {{ __('The number entered is different from your saved payment number. I confirm this number is correct.') }}
                                </label>
                            </div>
                            <div class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-200 flex items-start gap-3" x-show="savedNumber === '' && accountNumber !== ''" x-cloak x-transition>
                                <input type="checkbox" id="confirm_new_number" x-model="confirmedNumber" class="mt-1 w-4 h-4 text-amber-600 bg-white border-amber-300 rounded focus:ring-amber-500 focus:ring-2">
                                <label for="confirm_new_number" class="text-sm text-amber-800 font-medium">
                                    {{ __('You do not have a saved payment number. I confirm this number is correct.') }}
                                </label>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full px-8 py-5 bg-gradient-to-r from-primary to-emerald-500 text-white font-bold text-lg rounded-full btn-premium disabled:opacity-50 disabled:cursor-not-allowed"
                            x-bind:disabled="amount > max || amount < 100 || (accountNumber !== savedNumber && !confirmedNumber) || (savedNumber === '' && !confirmedNumber)"
                            @if($driver->coin_balance < 100) disabled @endif>
                            {{ __('🚀 Withdraw Now') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- History -->
            <div>
                <div class="glass-panel rounded-3xl p-6">
                    <h3 class="text-xl font-serif font-semibold mb-4 text-secondary">{{ __('History') }}</h3>

                    @if($redemptions->isEmpty())
                        <p class="text-gray-500 text-sm">{{ __('No redemption history yet.') }}</p>
                    @else
                        <div class="space-y-4">
                            @foreach($redemptions as $redemption)
                                <div class="p-3 bg-white/40 rounded-xl border border-white/60 shadow-sm">
                                    <div class="flex justify-between items-center mb-1">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-800">{{ $redemption->provider }}</span>
                                            <span class="text-xs text-gray-500 font-medium">{{ $redemption->account_number }}</span>
                                        </div>
                                        <span class="font-bold text-amber-500">{{ $redemption->amount }} 🪙</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xs mt-2">
                                        <span class="text-gray-500">{{ $redemption->created_at->format('d M, H:i') }}</span>

                                        @if($redemption->status === 'approved')
                                            <span class="text-emerald-600 font-semibold bg-emerald-100 px-2 py-1 rounded-md">{{ __('Success') }}</span>
                                        @elseif($redemption->status === 'rejected')
                                            <span class="text-red-600 font-semibold bg-red-100 px-2 py-1 rounded-md">{{ __('Rejected') }}</span>
                                        @else
                                            <span class="text-amber-600 font-semibold bg-amber-100 px-2 py-1 rounded-md">{{ __('Processing') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
