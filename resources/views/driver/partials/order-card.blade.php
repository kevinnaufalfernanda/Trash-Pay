@props(['pickup', 'currentCapacity', 'type' => 'standard'])

@php
    $isOffline = auth()->user()->driver_status === 'offline';
    $isOverCapacity = $currentCapacity + $pickup->total_weight > 10;
    $isDisabled = $isOffline || $isOverCapacity;
@endphp

<div x-data="{ 
        showPreviewModal: false, 
        zoomImage: false,
        mapInitialized: false,
        initMap() {
            if (this.mapInitialized) return;
            this.mapInitialized = true;
            
            var userLat = {{ $pickup->latitude ?? -7.942 }};
            var userLon = {{ $pickup->longitude ?? 112.62 }};
            var driverLat = userLat - 0.01; 
            var driverLon = userLon - 0.01;

            var map = L.map('map-{{ $pickup->id }}').setView([userLat, userLon], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var userIcon = L.icon({
                iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
                iconSize: [38, 38],
                iconAnchor: [19, 38]
            });

            var driverIcon = L.icon({
                iconUrl: 'https://cdn-icons-png.flaticon.com/512/75/75783.png',
                iconSize: [38, 38],
                iconAnchor: [19, 38]
            });

            L.marker([userLat, userLon], {icon: userIcon}).addTo(map)
                .bindPopup('<b>Customer: {{ addslashes($pickup->user->name) }}</b><br>{{ addslashes($pickup->address) }}')
                .openPopup();

            L.marker([driverLat, driverLon], {icon: driverIcon}).addTo(map)
                .bindPopup('Lokasi Anda saat ini');
                
            // Fix tile loading issues in modal
            setTimeout(() => { map.invalidateSize(); }, 300);
        }
    }"
    class="{{ $type === 'high' ? 'bg-gradient-to-br from-white/90 to-amber-50/90 border-amber-200 hover:border-amber-400 hover:shadow-lg hover:shadow-amber-400/30' : 'bg-white/60 border-gray-200 hover:border-primary/30 hover:shadow-lg hover:shadow-primary/10' }} rounded-3xl border p-6 shadow-sm hover:-translate-y-1.5 transition-all duration-300 group flex flex-col h-full">
    
    <div class="flex justify-between items-start mb-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 {{ $type === 'high' ? 'bg-amber-100 text-amber-600 border-amber-200' : 'bg-gray-100 text-gray-600 border-gray-200' }} rounded-2xl flex items-center justify-center font-serif font-bold text-2xl shadow-sm border group-hover:scale-110 transition-transform">
                {{ substr($pickup->user->name, 0, 1) }}
            </div>
            <div>
                <div class="font-bold text-secondary">{{ $pickup->user->name }}</div>
                <div class="text-xs font-bold {{ $type === 'high' ? 'text-amber-600' : 'text-gray-500' }} uppercase">{{ __($pickup->category->name) }}</div>
            </div>
        </div>
        <span class="text-xs font-bold {{ $type === 'high' ? 'text-white bg-amber-500' : 'text-primary bg-primary/10 border border-primary/20' }} px-3 py-1 rounded-full shadow-sm flex items-center gap-1">{{ __('Est:') }} {{ $pickup->est_coins }} <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg></span>
    </div>
    
    @if($pickup->waste_photo)
        <div @click="zoomImage = true" class="cursor-pointer overflow-hidden rounded-xl mb-4 border {{ $type === 'high' ? 'border-amber-200/50' : 'border-gray-200' }} group relative">
            <img src="{{ Storage::url($pickup->waste_photo) }}" class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-500">
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
            </div>
        </div>
    @endif

    <div class="flex-grow space-y-2 mb-6">
        <div class="flex items-start gap-2 text-sm text-gray-600">
            <span class="mt-0.5 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg></span>
            <p>{{ __('Estimated Weight:') }} <strong>{{ $pickup->total_weight }} kg</strong></p>
        </div>
        <div class="flex items-start gap-2 text-sm text-gray-600">
            <span class="mt-0.5 text-gray-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></span>
            <p>{{ __('Address:') }} <strong>{{ $pickup->address ?? __('No specific address') }}</strong></p>
        </div>
    </div>

    <div class="mt-auto">
        <div class="flex gap-2">
            <button @click="showPreviewModal = true; setTimeout(() => initMap(), 100)" class="flex-1 py-{{ $type === 'high' ? '4' : '3' }} {{ $type === 'high' ? 'bg-amber-50 text-amber-600 border-amber-200 hover:bg-amber-100' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-primary/10 hover:text-primary hover:border-primary/30' }} border font-bold rounded-2xl transition-all duration-200 active:scale-95 hover:scale-[1.02] flex justify-center items-center gap-2 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg> {{ __('Cek Peta') }}
            </button>
            <form action="{{ route('driver.orders.accept', $pickup->id) }}" method="POST" class="flex-1">
                @csrf
                <button @if($isDisabled) disabled @endif class="w-full h-full py-{{ $type === 'high' ? '4' : '3' }} {{ $type === 'high' ? 'bg-white border-2 border-amber-500 text-amber-600 ' . (!$isDisabled ? 'hover:bg-gradient-to-r hover:from-amber-400 hover:to-orange-500 hover:border-orange-500 hover:text-white hover:shadow-lg hover:shadow-orange-500/40 active:scale-[0.98] hover:-translate-y-0.5' : '') : 'bg-white border-2 border-primary text-primary ' . (!$isDisabled ? 'hover:bg-primary hover:text-white active:scale-[0.98] hover:-translate-y-0.5' : '') }} font-bold rounded-2xl transition-all duration-300 flex justify-center items-center gap-2 text-sm @if($isDisabled) opacity-60 cursor-not-allowed border-gray-300 text-gray-400 bg-gray-50 @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> 
                    {{ $isOffline ? __('Aktifkan Akun') : __('Ambil') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Preview Modal -->
    <template x-teleport="body">
        <div x-show="showPreviewModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <!-- Dark Backdrop -->
            <div x-show="showPreviewModal" x-transition.opacity class="fixed inset-0 bg-secondary/80 backdrop-blur-sm" @click="showPreviewModal = false"></div>
            
            <!-- Modal Content -->
            <div x-show="showPreviewModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl relative z-10 overflow-y-auto max-h-[90vh]">
                 
                <button @click="showPreviewModal = false" class="absolute top-4 right-4 p-2 bg-gray-100 text-gray-500 hover:text-gray-800 hover:bg-gray-200 rounded-full transition-all z-20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <h3 class="text-2xl font-serif font-bold mb-6 text-secondary flex items-center gap-2">
                    {{ __('Pesanan #') }}{{ $pickup->id }}
                </h3>

                <div class="space-y-5">
                    <!-- Map Section -->
                    <div class="w-full h-48 bg-gray-100 rounded-2xl overflow-hidden shadow-sm border border-gray-200 relative">
                        <div id="map-{{ $pickup->id }}" class="w-full h-full z-0"></div>
                    </div>

                    <!-- Address Section -->
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">{{ __('Alamat Penjemputan') }}</div>
                        <div class="font-medium text-secondary text-sm">{{ $pickup->address }}</div>
                        @if($pickup->address_notes)
                            <div class="text-sm mt-2 pt-2 border-t border-gray-200 font-medium text-gray-600">
                                <span class="text-gray-400">{{ __('Catatan:') }}</span> {{ $pickup->address_notes }}
                            </div>
                        @endif
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                            <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">{{ __('Kategori') }}</div>
                            <div class="font-bold text-secondary">{{ __($pickup->category->name) }}</div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                            <div class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">{{ __('Estimasi Berat') }}</div>
                            <div class="font-bold text-secondary">{{ $pickup->total_weight }} kg</div>
                        </div>
                    </div>

                    <!-- Potential Coins -->
                    <div class="bg-amber-50 p-4 rounded-2xl border border-amber-200 shadow-sm flex items-center justify-between">
                        <div class="text-xs text-amber-600 uppercase font-bold tracking-wider">{{ __('Potensi Koin') }}</div>
                        <div class="font-bold text-amber-500 text-xl flex items-center gap-1.5">~{{ $pickup->est_coins }} <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg></div>
                    </div>
                    
                    <!-- Action -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <form action="{{ route('driver.orders.accept', $pickup->id) }}" method="POST">
                            @csrf
                            <button @if($isDisabled) disabled @endif type="submit" class="w-full flex justify-center items-center gap-2 text-center py-4 {{ !$isDisabled ? 'bg-gradient-to-r from-amber-400 to-amber-500 text-white shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 hover:-translate-y-0.5 active:scale-[0.98]' : 'bg-gray-100 text-gray-400 border border-gray-300 opacity-60 cursor-not-allowed' }} font-bold rounded-2xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> 
                                {{ $isOffline ? __('Status Offline! Aktifkan Akun Dahulu') : __('Ambil Pesanan Ini') }}
                            </button>
                        </form>
                    </div>
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
                
                @if($pickup->waste_photo)
                    <img src="{{ Storage::url($pickup->waste_photo) }}" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10">
                @endif
            </div>
        </div>
    </template>
</div>
