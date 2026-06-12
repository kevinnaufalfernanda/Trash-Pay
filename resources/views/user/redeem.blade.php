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
                            <div class="text-3xl font-bold text-amber-600 flex items-center gap-1.5">{{ number_format($user->coin_balance) }}
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg></div>
                        </div>
                        <div class="text-amber-500 bg-amber-100 p-3 rounded-2xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
                        </div>
                    </div>

                    <form action="{{ route('user.redeem.store') }}" method="POST" novalidate
                          @submit.prevent="
                              if ($el.checkValidity()) {
                                  if ((accountNumber !== savedNumber && !confirmedNumber) || (savedNumber === '' && !confirmedNumber)) {
                                      triggerToast('{{ __('Please confirm your phone number by checking the box.') }}');
                                      let confirmBox = document.getElementById('confirm_number') || document.getElementById('confirm_new_number');
                                      if (confirmBox) confirmBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                      return;
                                  }
                                  $el.submit();
                              } else {
                                  let firstInvalid = $el.querySelector(':invalid');
                                  if (firstInvalid) {
                                      let msg = '{{ __('Please complete all required fields.') }}';
                                      if (firstInvalid.type === 'radio') msg = '{{ __('Please select an e-wallet provider.') }}';
                                      if (firstInvalid.type === 'number') msg = '{{ __('Please enter a valid coin amount (min 100).') }}';
                                      if (firstInvalid.type === 'tel') msg = '{{ __('Please enter your phone number or target account.') }}';
                                      
                                      triggerToast(msg);
                                      
                                      let targetToScroll = firstInvalid;
                                      if (firstInvalid.classList.contains('hidden') && firstInvalid.nextElementSibling) {
                                          targetToScroll = firstInvalid.nextElementSibling;
                                      }
                                      targetToScroll.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                  }
                              }
                          "
                          x-data="{ 
                              amount: '', max: {{ $user->coin_balance }}, accountNumber: '{{ $user->payment_number }}', savedNumber: '{{ $user->payment_number }}', confirmedNumber: false,
                              toastMessage: '',
                              showToast: false,
                              triggerToast(msg) {
                                  this.toastMessage = msg;
                                  this.showToast = true;
                                  setTimeout(() => this.showToast = false, 4000);
                              }
                          }">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('E-Wallet Provider') }}</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <input type="radio" name="provider" id="dana" value="Dana" class="peer hidden"
                                        required>
                                    <label for="dana"
                                        class="block text-center cursor-pointer bg-white/50 border border-white/60 shadow-sm rounded-2xl p-4 hover:border-blue-300 peer-checked:border-2 peer-checked:border-blue-500 peer-checked:bg-blue-50/80 peer-checked:shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg active:scale-95">
                                        <div class="font-bold text-blue-600 text-lg">DANA</div>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" name="provider" id="gopay" value="GoPay" class="peer hidden"
                                        required>
                                    <label for="gopay"
                                        class="block text-center cursor-pointer bg-white/50 border border-white/60 shadow-sm rounded-2xl p-4 hover:border-green-300 peer-checked:border-2 peer-checked:border-green-500 peer-checked:bg-green-50/80 peer-checked:shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg active:scale-95">
                                        <div class="font-bold text-green-600 text-lg">GoPay</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-10">
                            <label for="amount" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Coin Amount') }}</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-amber-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
                                    </div>
                                    <input type="number" name="amount" id="amount" min="100" max="{{ $user->coin_balance }}" x-model.number="amount"
                                        @input="if(amount > max) amount = max; if(amount < 0) amount = ''"
                                        class="w-full h-full pl-14 rounded-2xl border shadow-sm focus:ring-4 text-lg font-bold py-4 bg-white/70 transition-all border-gray-200 focus:border-primary focus:ring-primary/20"
                                        required placeholder="{{ __('Min. 100') }}">
                                </div>
                                <div class="h-full text-sm font-semibold text-emerald-800 bg-gradient-to-r from-emerald-50 to-emerald-100/50 px-5 py-4 rounded-2xl border border-emerald-200 flex items-center justify-between transition-all">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
                                        {{ __('Estimated Money:') }}
                                    </span>
                                    <span class="font-bold text-2xl text-emerald-600">Rp <span x-text="Math.round((amount || 0) * 100).toLocaleString('id-ID')"></span></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-10">
                            <label for="account_number" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Phone Number / Target Account') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <input type="tel" name="account_number" id="account_number" x-model="accountNumber"
                                    @input="accountNumber = $event.target.value.replace(/[^\d+]/g, '').replace(/(?!^)\+/g, '')"
                                    class="w-full pl-14 rounded-2xl border border-gray-200 shadow-sm focus:border-primary focus:ring-primary focus:ring-4 focus:ring-primary/20 text-lg font-bold py-4 bg-white/70 transition-all"
                                    required placeholder="{{ __('e.g., 081234567890') }}">
                            </div>
                            
                            <div class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-200 flex items-start gap-3" x-show="accountNumber !== savedNumber && savedNumber !== '' && accountNumber !== ''" x-cloak x-transition>
                                <input type="checkbox" id="confirm_number" x-model="confirmedNumber" class="mt-1 w-4 h-4 text-primary bg-white border-amber-300 rounded focus:ring-primary focus:ring-2 cursor-pointer">
                                <label for="confirm_number" class="text-sm text-amber-800 font-medium cursor-pointer">
                                    {{ __("This number is different from your profile's saved number. I confirm this number is correct.") }}
                                </label>
                            </div>
                            <div class="mt-4 p-4 bg-amber-50 rounded-xl border border-amber-200 flex items-start gap-3" x-show="savedNumber === '' && accountNumber !== ''" x-cloak x-transition>
                                <input type="checkbox" id="confirm_new_number" x-model="confirmedNumber" class="mt-1 w-4 h-4 text-primary bg-white border-amber-300 rounded focus:ring-primary focus:ring-2 cursor-pointer">
                                <label for="confirm_new_number" class="text-sm text-amber-800 font-medium cursor-pointer">
                                    {{ __("There is no saved number in your profile. I confirm this number is correct.") }}
                                </label>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full px-8 py-5 bg-gradient-to-r from-primary to-emerald-500 text-white font-bold text-lg rounded-full btn-premium active:scale-[0.98] transition-all duration-300 hover:shadow-xl hover:shadow-primary/40 hover:-translate-y-1">
                            {{ __('Withdraw Now') }}
                        </button>
                        
                        <template x-teleport="body">
                            <div x-show="showToast"
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0 -translate-y-20 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-20 scale-95"
                                 class="fixed top-8 left-1/2 -translate-x-1/2 z-[99999] w-[90%] max-w-md bg-red-600 rounded-2xl shadow-2xl border border-red-400 p-5 flex items-start gap-4">
                                <div class="flex-shrink-0 text-white mt-0.5">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div class="flex-1 text-white">
                                    <h3 class="font-bold text-lg mb-1">{{ __('Attention') }}</h3>
                                    <p class="text-sm font-medium text-red-100" x-text="toastMessage"></p>
                                </div>
                                <button @click="showToast = false" type="button" class="text-red-200 hover:text-white transition-colors p-1">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </template>
                    </form>
                </div>
            </div>

            <!-- History -->
            <div>
                <div class="glass-panel rounded-3xl p-6">
                    <h3 class="text-xl font-serif font-semibold mb-4 text-secondary flex items-center justify-between">
                        {{ __('Redemption History') }}
                    </h3>

                    @if($redemptions->isEmpty())
                        <div class="text-center py-8">
                            <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">{{ __('No redemption history yet.') }}</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($redemptions->take(3) as $redemption)
                                @include('partials.redemption-card', ['redemption' => $redemption])
                            @endforeach
                        </div>
                        <div class="mt-6 flex justify-center">
                            <a href="{{ route('user.history') }}" class="inline-block px-6 py-2.5 bg-gray-50 text-gray-600 font-bold rounded-full border border-gray-200 hover:bg-gray-100 transition-all duration-200 text-sm text-center w-full active:scale-95 hover:scale-[1.02]">
                                {{ __('View All History') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
    @if(session('redemption_data'))
        <template x-teleport="body">
            <div x-data="{ open: true }" x-show="open" class="fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-xl" x-cloak>
                <div @click.away="window.history.back()" class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative overflow-hidden"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-400/20 rounded-full blur-3xl -z-10"></div>
                
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-serif font-bold text-secondary">{{ __('Redemption Details') }}</h3>
                    <button @click="window.history.back()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="flex items-center gap-4 p-5 bg-gray-50 rounded-2xl mb-4 border border-gray-100">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-amber-500 shadow-sm border border-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">{{ session('redemption_data')->provider }}</h4>
                        <p class="text-gray-500 text-sm font-medium">{{ session('redemption_data')->account_number }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col justify-center">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Redeemed TrashCoins') }}</div>
                        <div class="text-xl font-bold text-amber-500 flex items-center gap-1">
                            {{ number_format(session('redemption_data')->amount) }}
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"></path></svg>
                        </div>
                    </div>
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col justify-center">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Rupiah Value') }}</div>
                        <div class="text-xl font-bold text-gray-900">
                            Rp {{ number_format(session('redemption_data')->amount * 100, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="p-5 bg-gray-50 rounded-2xl mb-4 space-y-3 border border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">{{ __('Transaction ID') }}</span>
                        <span class="text-sm font-bold text-gray-900">#TXN-{{ str_pad(session('redemption_data')->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">{{ __('Date') }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ session('redemption_data')->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>

                <div class="p-4 bg-amber-50 rounded-2xl mb-6 flex justify-between items-center border border-amber-100">
                    <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">{{ __('Status') }}</span>
                    <div class="flex items-center gap-1.5 text-amber-600 font-bold text-sm bg-amber-100/50 px-3 py-1.5 rounded-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ ucfirst(session('redemption_data')->status) == 'Pending' ? 'Processing' : ucfirst(session('redemption_data')->status) }}</span>
                    </div>
                </div>
                
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 mb-6 text-center">
                    <p class="text-sm text-emerald-800 font-medium mb-4">{{ __('Please proceed with the payment link for validation.') }}</p>
                    <a href="https://app.sandbox.midtrans.com/payment-links/17bf84e8-125b-442a-baf5-bef194cc1ddf-qClh4lkY" target="_blank"
                       class="inline-block w-full px-6 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-200">
                        {{ __('Proceed to Midtrans') }}
                    </a>
                </div>
            </div>
        </div>
        </template>
    @endif
</x-app-layout>
