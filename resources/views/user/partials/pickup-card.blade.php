<div x-data="{ showModal: false, zoomImage: false }">
    <!-- Card -->
    <div @click="showModal = true" class="bg-white/60 border border-white/80 rounded-2xl p-4 flex flex-col gap-2 shadow-sm hover:shadow-md transition-all cursor-pointer hover:border-emerald-200">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-2xl">
                {{ $pickup->category->icon ?? '📦' }}
            </div>
            <div>
                <div class="font-bold text-secondary">Pickup #{{ $pickup->id }}</div>
                <div class="text-xs text-gray-500 font-medium">{{ $pickup->category->name }} • {{ $pickup->total_weight }} kg</div>
            </div>
        </div>
        <div class="flex justify-between items-center text-xs mt-2 border-t border-gray-100 pt-2">
            <span class="text-gray-500">{{ $pickup->created_at->format('d M, H:i') }}</span>
            @if($pickup->status === 'pending')
                <span class="text-amber-600 font-semibold bg-amber-100 px-2 py-1 rounded-md">{{ __('Searching Driver') }}</span>
            @elseif($pickup->status === 'on-the-way')
                <span class="text-blue-600 font-semibold bg-blue-100 px-2 py-1 rounded-md">{{ __('On the way') }}</span>
            @elseif($pickup->status === 'completed')
                <span class="text-emerald-600 font-semibold bg-emerald-100 px-2 py-1 rounded-md">{{ __('Completed') }}</span>
            @elseif($pickup->status === 'rejected')
                <span class="text-red-600 font-semibold bg-red-100 px-2 py-1 rounded-md">{{ __('Rejected') }}</span>
            @endif
        </div>
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
                Pickup #{{ $pickup->id }}
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
                            <span>{{ $pickup->category->icon ?? '📦' }}</span> {{ $pickup->category->name }}
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
                            <span class="text-gray-400">Notes:</span> {{ $pickup->address_notes }}
                        </div>
                    @endif
                </div>

                <!-- Status & Driver -->
                <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                    <div>
                        <div class="text-xs text-emerald-600 font-bold uppercase tracking-wider mb-1">{{ __('Status') }}</div>
                        @if($pickup->status === 'pending')
                            <span class="text-amber-600 font-bold flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                {{ __('Searching Driver') }}
                            </span>
                        @elseif($pickup->status === 'on-the-way')
                            <span class="text-blue-600 font-bold">🚚 {{ __('On the way') }}</span>
                        @elseif($pickup->status === 'completed')
                            <span class="text-emerald-600 font-bold">✅ {{ __('Completed') }}</span>
                        @elseif($pickup->status === 'rejected')
                            <span class="text-red-600 font-bold">❌ {{ __('Rejected') }}</span>
                        @endif
                    </div>
                    @if($pickup->driver)
                        <div class="text-right">
                            <div class="text-xs text-emerald-600 font-bold uppercase tracking-wider mb-1">{{ __('Driver') }}</div>
                            <div class="font-bold text-secondary">{{ $pickup->driver->name }}</div>
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
            <div x-show="zoomImage" x-transition.opacity class="fixed inset-0 bg-secondary/95 backdrop-blur-md" @click="zoomImage = false"></div>
            
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
