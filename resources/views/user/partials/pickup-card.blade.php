<div x-data="{ showModal: false, zoomImage: false }">
    <!-- Card -->
    <div @click="showModal = true" class="bg-white/60 border border-white/80 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between shadow-sm hover:shadow-md transition-all cursor-pointer hover:border-emerald-200 gap-4 {{ $pickup->status === 'cancelled' || $pickup->status === 'rejected' ? 'opacity-70' : '' }}">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 bg-gradient-to-br from-emerald-50 to-white rounded-2xl shadow-sm flex items-center justify-center text-3xl border border-white shrink-0">
                <x-category-icon :category="$pickup->category" class="w-8 h-8 text-emerald-600" />
            </div>
            <div>
                <div class="font-medium text-secondary">{{ __('Pickup #') }}{{ $pickup->id }}</div>
                <div class="text-sm font-light text-secondary/60 mt-0.5">{{ $pickup->created_at->format('d M Y') }}</div>
                
                @if($pickup->status === 'pending')
                    <div class="mt-2 text-xs font-bold text-amber-600 flex items-center gap-1 bg-amber-50 w-max px-2.5 py-1 rounded-md border border-amber-100">
                        <svg class="animate-spin -ml-1 mr-1 h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        {{ __('Searching Driver') }} &bull; {{ $pickup->total_weight }} kg
                    </div>
                @elseif($pickup->status === 'on-the-way')
                    @if($pickup->cancel_requested_by)
                        <div class="mt-2 text-xs font-bold text-amber-600 flex items-center gap-1 bg-amber-50 w-max px-2.5 py-1 rounded-md border border-amber-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            {{ __('Cancel Requested') }}
                        </div>
                    @else
                        <div class="mt-2 text-xs font-bold text-blue-600 flex items-center gap-1 bg-blue-50 w-max px-2.5 py-1 rounded-md border border-blue-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            {{ __('Driver on the way') }} &bull; {{ $pickup->total_weight }} kg
                        </div>
                    @endif
                @elseif($pickup->status === 'completed')
                    <div class="mt-2 text-xs font-bold text-emerald-600 flex items-center gap-1 bg-emerald-50 w-max px-2.5 py-1 rounded-md border border-emerald-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ __('Completed') }} &bull; {{ $pickup->total_weight }} kg
                    </div>
                @elseif($pickup->status === 'rejected')
                    <div class="mt-2 text-xs font-bold text-red-500 flex items-center gap-1 bg-red-50 w-max px-2.5 py-1 rounded-md border border-red-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        {{ __('Rejected') }}
                    </div>
                @elseif($pickup->status === 'cancelled')
                    <div class="mt-2 text-xs font-bold text-red-500 flex items-center gap-1 bg-red-50 w-max px-2.5 py-1 rounded-md border border-red-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        {{ __('Cancelled') }}
                    </div>
                @endif
            </div>
        </div>

        @if($pickup->status === 'completed')
            <div class="shrink-0 sm:text-right mt-4 sm:mt-0">
                <div class="text-sm text-gray-500 font-medium">{{ __('Coins Earned') }}</div>
                <div class="text-xl font-bold text-amber-500 flex items-center gap-1 sm:justify-end">
                    +{{ number_format($pickup->total_coins) }} <svg class="w-4 h-4 text-amber-500 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal -->
    <template x-teleport="body">
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center px-4 sm:px-0">
            <!-- Backdrop -->
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-secondary/60 backdrop-blur-sm" @click="showModal = false"></div>
            
            <!-- Modal Content -->
        <div x-show="showModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative z-10 overflow-hidden">
             
            <!-- Decorative blur -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-400/10 rounded-full blur-2xl -z-10"></div>
            
            <button @click="showModal = false" class="absolute top-4 right-4 p-2 bg-gray-100 text-gray-500 hover:text-gray-800 hover:bg-gray-200 rounded-full transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <h3 class="text-2xl font-serif font-bold mb-6 text-secondary flex items-center gap-2">
                {{ __('Order #') }}{{ $pickup->id }}
            </h3>

            <div class="space-y-5">
                <!-- Image -->
                @if($pickup->waste_photo)
                    <div @click="zoomImage = true" class="cursor-pointer w-full h-48 bg-gray-100 rounded-2xl overflow-hidden shadow-sm border border-gray-200 relative group">
                        <img src="{{ asset('storage/' . $pickup->waste_photo) }}" alt="Waste Photo" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all flex items-center justify-center">
                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                        </div>
                    </div>
                @endif

                <!-- Details Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">{{ __('Category') }}</div>
                        <div class="font-bold text-secondary flex items-center gap-2">
                            <span>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </span> {{ __($pickup->category->name) }}
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">{{ __('Est. Weight') }}</div>
                        <div class="font-bold text-secondary">{{ $pickup->total_weight }} kg</div>
                    </div>
                </div>

                <!-- Address -->
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">{{ __('Pickup Address') }}</div>
                    <div class="font-medium text-secondary text-sm">{{ $pickup->address }}</div>
                    @if($pickup->address_notes)
                        <div class="text-sm mt-2 pt-2 border-t border-gray-200 font-medium text-gray-600">
                            <span class="text-gray-400">{{ __('Notes:') }}</span> {{ $pickup->address_notes }}
                        </div>
                    @endif
                </div>

                <!-- Status & Driver -->
                <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                    <div>
                        <div class="text-xs text-emerald-600 font-bold uppercase tracking-wider mb-1">{{ __('Status') }}</div>
                        @if($pickup->status === 'pending')
                            <div class="flex flex-col gap-1" x-data="{
                                expiresAt: new Date('{{ $pickup->created_at->addMinutes(15)->toIso8601String() }}').getTime(),
                                now: new Date().getTime(),
                                timeLeft: '',
                                init() {
                                    this.update();
                                    setInterval(() => this.update(), 1000);
                                },
                                update() {
                                    this.now = new Date().getTime();
                                    let diff = Math.max(0, this.expiresAt - this.now);
                                    let m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                    let s = Math.floor((diff % (1000 * 60)) / 1000);
                                    if (diff <= 0) {
                                        this.timeLeft = '{{ __('Cancelling...') }}';
                                    } else {
                                        this.timeLeft = 'Auto-cancel in ' + m + 'm ' + s + 's';
                                    }
                                }
                            }">
                                <span class="text-amber-600 font-bold flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    {{ __('Searching Driver') }}
                                </span>
                                <span class="text-xs text-amber-500 font-semibold" x-text="timeLeft"></span>
                            </div>
                        @elseif($pickup->status === 'on-the-way')
                            <span class="text-blue-600 font-bold flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> {{ __('On the way') }}</span>
                        @elseif($pickup->status === 'completed')
                            <span class="text-emerald-600 font-bold flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> {{ __('Completed') }}</span>
                        @elseif($pickup->status === 'rejected')
                            <span class="text-red-600 font-bold flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> {{ __('Rejected') }}</span>
                        @elseif($pickup->status === 'cancelled')
                            <span class="text-gray-600 font-bold flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg> {{ __('Cancelled') }}</span>
                        @endif
                    </div>
                    @if($pickup->driver)
                        <div class="text-right">
                            <div class="text-xs text-emerald-600 font-bold uppercase tracking-wider mb-1">{{ __('Driver') }}</div>
                            <div class="font-bold text-secondary">{{ $pickup->driver->name }}</div>
                        </div>
                    @endif
                </div>

                <!-- Cancellation Actions (User View) -->
                @if($pickup->status === 'pending')
                    <form action="{{ route('user.pickup.cancel', $pickup->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full mt-2 py-3 bg-red-50 text-red-600 font-bold rounded-xl border border-red-100 hover:bg-red-100 transition-all text-sm">
                            {{ __('Cancel Order') }}
                        </button>
                    </form>
                @elseif($pickup->status === 'on-the-way')
                    @if($pickup->cancel_requested_by === null)
                        <form action="{{ route('user.pickup.cancel', $pickup->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full mt-2 py-3 bg-red-50 text-red-600 font-bold rounded-xl border border-red-100 hover:bg-red-100 transition-all text-sm flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                {{ __('Request Cancel to Driver') }}
                            </button>
                        </form>
                    @elseif($pickup->cancel_requested_by === 'user')
                        <div class="mt-2 py-3 bg-amber-50 text-amber-700 font-bold rounded-xl border border-amber-100 text-sm text-center flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            {{ __('Waiting for Driver to approve cancellation...') }}
                        </div>
                    @elseif($pickup->cancel_requested_by === 'driver')
                        <div class="mt-2 p-4 bg-red-50 rounded-2xl border border-red-200">
                            <p class="text-sm font-bold text-red-700 text-center mb-3 flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ __('Driver requested to cancel this order.') }}
                            </p>
                            <div class="flex gap-2">
                                <form action="{{ route('user.pickup.approve-cancel', $pickup->id) }}" method="POST" class="w-1/2">
                                    @csrf
                                    <button type="submit" class="w-full py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all text-sm">
                                        {{ __('Approve Cancel') }}
                                    </button>
                                </form>
                                <form action="{{ route('user.pickup.reject-cancel', $pickup->id) }}" method="POST" class="w-1/2">
                                    @csrf
                                    <button type="submit" class="w-full py-2 bg-white text-gray-700 font-bold rounded-xl border border-gray-300 hover:bg-gray-50 transition-all text-sm">
                                        {{ __('Reject') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </template>

    <!-- Fullscreen Image Zoom -->
    <template x-teleport="body">
        <div x-show="zoomImage" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <!-- Darker Backdrop for Zoom -->
            <div x-show="zoomImage" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-md" @click="zoomImage = false"></div>
            
            <div x-show="zoomImage"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="relative z-10 max-w-5xl w-full max-h-[90vh] flex flex-col items-center justify-center">
                 
                <button @click="zoomImage = false" class="absolute -top-12 right-0 sm:-right-8 p-2 text-white/50 hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                @if($pickup->waste_photo)
                    <img src="{{ asset('storage/' . $pickup->waste_photo) }}" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10">
                @endif
            </div>
        </div>
    </template>
</div>
