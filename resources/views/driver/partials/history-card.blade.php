<div x-data="{ showModal: false, zoomImage: false }">
    <!-- Card -->
    <div @click="showModal = true" class="cursor-pointer flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-white/60 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-primary/30 transition-all gap-4 {{ $order->status === 'cancelled' ? 'opacity-70' : '' }}">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 bg-gradient-to-br from-gray-100 to-white text-primary rounded-2xl flex items-center justify-center font-serif font-bold text-2xl border border-white shadow-sm shrink-0">
                {{ substr($order->user->name, 0, 1) }}
            </div>
            <div>
                <div class="font-medium text-secondary">{{ $order->user->name }}</div>
                <div class="text-sm font-light text-secondary/60 mt-0.5">Pickup #{{ $order->id }} &bull; {{ $isActive ? $order->total_weight . ' kg' : $order->created_at->format('d M Y') }}</div>
                
                @if($isActive)
                    @if($order->cancel_requested_by === 'user')
                        <div class="mt-1 text-xs font-bold text-red-600 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            {{ __('User requested cancellation!') }}
                        </div>
                    @elseif($order->cancel_requested_by === 'driver')
                        <div class="mt-1 text-xs font-bold text-amber-600 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ __('Waiting for cancellation approval...') }}
                        </div>
                    @endif
                @else
                    @if($order->status === 'completed')
                        <div class="mt-2 text-xs font-bold text-emerald-600 flex items-center gap-1 bg-emerald-50 w-max px-2.5 py-1 rounded-md border border-emerald-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ __('Completed') }} &bull; {{ $order->total_weight }} kg
                        </div>
                    @elseif($order->status === 'cancelled')
                        <div class="mt-2 text-xs font-bold text-red-500 flex items-center gap-1 bg-red-50 w-max px-2.5 py-1 rounded-md border border-red-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            {{ __('Cancelled') }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

        @if($isActive)
            <div class="flex flex-col sm:items-end gap-2 shrink-0" @click.stop>
                <div class="flex gap-2">
                    <a href="{{ route('driver.navigation', $order->id) }}" class="px-5 py-2.5 bg-white/80 border border-gray-200 text-secondary text-sm font-bold rounded-full hover:bg-white transition-colors">{{ __('Navigate') }}</a>
                    <a href="{{ route('driver.verify', $order->id) }}" class="px-5 py-2.5 bg-gradient-to-r from-primary to-emerald-500 text-white text-sm font-bold rounded-full btn-premium shadow-lg shadow-emerald-500/30">{{ __('Verify Weight') }}</a>
                </div>
                @if($order->cancel_requested_by === 'user')
                    <div class="flex gap-2 mt-2 w-full justify-end">
                        <form action="{{ route('driver.orders.approve-cancel', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-full shadow-md shadow-red-500/30 hover:bg-red-700 transition-colors">{{ __('Approve Cancel') }}</button>
                        </form>
                        <form action="{{ route('driver.orders.reject-cancel', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-white text-gray-700 text-xs font-bold rounded-full shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors">{{ __('Reject Cancel') }}</button>
                        </form>
                    </div>
                @endif
            </div>
        @else
            @if($order->status === 'completed')
                <div class="shrink-0 text-right">
                    <div class="text-sm text-gray-500 font-medium">{{ __('Coins Earned') }}</div>
                    <div class="text-xl font-bold text-amber-500 flex items-center gap-1 justify-end">
                        +{{ number_format($order->total_coins) }} <svg class="w-4 h-4 text-amber-500 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <!-- Modal Detail -->
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
                    {{ __('Order #') }}{{ $order->id }}
                </h3>

                <div class="space-y-5">
                    <!-- Image -->
                    @if($order->waste_photo)
                        <div @click="zoomImage = true" class="cursor-pointer w-full h-48 bg-gray-100 rounded-2xl overflow-hidden shadow-sm border border-gray-200 relative group">
                            <img src="{{ asset('storage/' . $order->waste_photo) }}" alt="Waste Photo" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
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
                                </span> {{ __($order->category->name) }}
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                            <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">{{ __('Est. Weight') }}</div>
                            <div class="font-bold text-secondary">{{ $order->total_weight }} kg</div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">{{ __('Pickup Address') }}</div>
                        <div class="font-medium text-secondary text-sm">{{ $order->address }}</div>
                        @if($order->address_notes)
                            <div class="text-sm mt-2 pt-2 border-t border-gray-200 font-medium text-gray-600">
                                <span class="text-gray-400">{{ __('Notes:') }}</span> {{ $order->address_notes }}
                            </div>
                        @endif
                    </div>

                    <!-- Status -->
                    <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                        <div>
                            <div class="text-xs text-emerald-600 font-bold uppercase tracking-wider mb-1">{{ __('Status') }}</div>
                            @if($order->status === 'on-the-way')
                                <span class="text-blue-600 font-bold flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> {{ __('On the way') }}</span>
                            @elseif($order->status === 'completed')
                                <span class="text-emerald-600 font-bold flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> {{ __('Completed') }}</span>
                            @elseif($order->status === 'cancelled')
                                <span class="text-gray-600 font-bold flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg> {{ __('Cancelled') }}</span>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-emerald-600 font-bold uppercase tracking-wider mb-1">{{ __('Customer') }}</div>
                            <div class="font-bold text-secondary">{{ $order->user->name }}</div>
                        </div>
                    </div>

                    @if($isActive)
                        <div class="flex gap-2 pt-2">
                            <a href="{{ route('driver.navigation', $order->id) }}" class="flex-1 text-center py-3 bg-white border-2 border-gray-200 text-secondary font-bold rounded-xl hover:bg-gray-50 transition-all text-sm">
                                {{ __('Open Navigation') }}
                            </a>
                            <a href="{{ route('driver.verify', $order->id) }}" class="flex-1 text-center py-3 bg-primary text-white font-bold rounded-xl hover:bg-emerald-600 shadow-md shadow-emerald-500/30 transition-all text-sm">
                                {{ __('Verify Weight') }}
                            </a>
                        </div>
                    @endif
                </div>
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
                 class="relative z-10 max-w-5xl w-full flex flex-col items-center justify-center">
                 
                <button @click="zoomImage = false" class="absolute -top-12 right-0 sm:-right-8 p-2 text-white/50 hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                @if($order->waste_photo)
                    <img src="{{ asset('storage/' . $order->waste_photo) }}" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10">
                @endif
            </div>
        </div>
    </template>
</div>
