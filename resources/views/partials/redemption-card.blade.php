<div x-data="{ showRedemptionModal: false }">
    <!-- Card -->
    <div @click="showRedemptionModal = true" class="cursor-pointer flex items-center justify-between p-5 bg-white/60 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all hover:border-amber-200">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-amber-50 to-white text-amber-500 rounded-2xl flex items-center justify-center font-serif font-bold text-2xl border border-white shadow-sm shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
            </div>
            <div>
                <div class="font-bold text-secondary">{{ __('Coin Redemption') }}</div>
                <div class="text-sm text-gray-500 font-medium">{{ $redemption->provider }} &bull; {{ $redemption->created_at->format('d M Y') }}</div>
                
                @if($redemption->status === 'pending')
                    <div class="mt-1 text-xs font-bold text-amber-600 flex items-center gap-1 bg-amber-50 w-max px-2.5 py-1 rounded-md border border-amber-100">
                        <svg class="animate-spin -ml-1 mr-1 h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        {{ __('Processing') }}
                    </div>
                @elseif($redemption->status === 'approved')
                    <div class="mt-1 text-xs font-bold text-emerald-600 flex items-center gap-1 bg-emerald-50 w-max px-2.5 py-1 rounded-md border border-emerald-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ __('Success') }}
                    </div>
                @elseif($redemption->status === 'rejected')
                    <div class="mt-1 text-xs font-bold text-red-500 flex items-center gap-1 bg-red-50 w-max px-2.5 py-1 rounded-md border border-red-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        {{ __('Rejected') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="shrink-0 text-right">
            <div class="text-sm text-gray-500 font-medium">{{ __('Amount') }}</div>
            <div class="text-xl font-bold text-secondary flex items-center justify-end">
                -{{ number_format($redemption->amount) }} <svg class="w-4 h-4 opacity-80 text-amber-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
            </div>
        </div>
    </div>

    <!-- Modal Detail Penukaran -->
    <template x-teleport="body">
        <div x-show="showRedemptionModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4 sm:px-0">
            <!-- Backdrop -->
            <div x-show="showRedemptionModal" x-transition.opacity class="fixed inset-0 bg-secondary/60 backdrop-blur-sm" @click="showRedemptionModal = false"></div>
            
            <!-- Modal Content -->
            <div x-show="showRedemptionModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative z-10 overflow-hidden">
                 
                <!-- Decorative blur -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-amber-400/10 rounded-full blur-2xl -z-10"></div>
                
                <button @click="showRedemptionModal = false" class="absolute top-4 right-4 p-2 bg-gray-100 text-gray-500 hover:text-gray-800 hover:bg-gray-200 rounded-full transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                <h3 class="text-2xl font-serif font-bold mb-6 text-secondary flex items-center gap-2">
                    {{ __('Redemption Details') }}
                </h3>

                <div class="space-y-5">
                    <!-- Provider Info -->
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 flex items-center gap-4">
                        <div class="w-12 h-12 bg-white text-amber-500 rounded-xl flex items-center justify-center font-bold text-xl shadow-sm border border-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-secondary text-lg">{{ $redemption->provider }}</div>
                            <div class="text-sm text-gray-500">{{ $redemption->account_number }}</div>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                            <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">{{ __('Redeemed Coins') }}</div>
                            <div class="font-bold text-amber-500 flex items-center gap-1 text-lg">
                                {{ number_format($redemption->amount) }} <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                            <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">{{ __('Rupiah Value') }}</div>
                            <div class="font-bold text-secondary text-lg">Rp {{ number_format($redemption->amount * 100, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <!-- Additional Details -->
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 space-y-3">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 font-medium">{{ __('Transaction ID') }}</span>
                            <span class="font-bold text-secondary">#TXN-{{ str_pad($redemption->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500 font-medium">{{ __('Date') }}</span>
                            <span class="font-bold text-secondary">{{ $redemption->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    <!-- Status -->
                    @php
                        $statusBg = 'bg-amber-50 border-amber-100';
                        $statusLabelColor = 'text-amber-600';
                        if ($redemption->status === 'approved') {
                            $statusBg = 'bg-emerald-50 border-emerald-100';
                            $statusLabelColor = 'text-emerald-600';
                        } elseif ($redemption->status === 'rejected') {
                            $statusBg = 'bg-red-50 border-red-100';
                            $statusLabelColor = 'text-red-600';
                        }
                    @endphp
                    <div class="flex items-center justify-between p-4 {{ $statusBg }} rounded-2xl border">
                        <div class="text-xs {{ $statusLabelColor }} font-bold uppercase tracking-wider">{{ __('Status') }}</div>
                        <div>
                            @if($redemption->status === 'pending')
                                <span class="text-amber-600 font-bold flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ __('Processing') }}</span>
                            @elseif($redemption->status === 'approved')
                                <span class="text-emerald-600 font-bold flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> {{ __('Success') }}</span>
                            @elseif($redemption->status === 'rejected')
                                <span class="text-red-600 font-bold flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg> {{ __('Rejected') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
