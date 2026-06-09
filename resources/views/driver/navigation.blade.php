<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-secondary leading-tight">
            {{ __('Navigation to Pickup #') }}{{ $pickup->id }}
        </h2>
    </x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="py-12">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Map -->
                <div class="md:col-span-2 glass-panel rounded-3xl p-4">
                    <div id="map" class="h-96 w-full rounded-2xl z-0 border border-white/60"></div>
                </div>

                <!-- Action -->
                <div class="glass-panel rounded-3xl p-8 flex flex-col justify-between">
                    <div>
                        <h3 class="text-2xl font-serif font-bold mb-6 text-secondary">{{ __('Pickup Details') }}</h3>
                        <div class="space-y-4">
                            <div class="bg-white/40 p-4 rounded-2xl border border-white/60 shadow-sm">
                                <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">{{ __('Customer') }}</span>
                                <div class="font-bold text-gray-800 text-lg">{{ $pickup->user->name }}</div>
                            </div>
                            
                            @if($pickup->address_notes)
                            <div class="bg-white/40 p-4 rounded-2xl border border-white/60 shadow-sm border-l-4 border-l-primary">
                                <span class="text-xs text-primary uppercase font-bold tracking-wider">{{ __('House Details / Notes') }}</span>
                                <div class="font-medium text-gray-700 mt-1">{{ $pickup->address_notes }}</div>
                            </div>
                            @endif

                            <div class="bg-white/40 p-4 rounded-2xl border border-white/60 shadow-sm">
                                <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">{{ __('Status') }}</span>
                                <div class="mt-1"><span class="px-3 py-1.5 bg-blue-100 text-blue-800 text-xs font-bold rounded-full uppercase border border-blue-200">{{ $pickup->status }}</span></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        @if($pickup->cancel_requested_by === 'user')
                            <div class="mb-4 p-4 bg-red-50 rounded-2xl border border-red-200">
                                <p class="text-sm font-bold text-red-700 text-center mb-3">⚠️ User mengajukan pembatalan pesanan ini.</p>
                                <div class="flex gap-2">
                                    <form action="{{ route('driver.orders.approve-cancel', $pickup->id) }}" method="POST" class="w-1/2">
                                        @csrf
                                        <button type="submit" class="w-full py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition-all text-sm">
                                            {{ __('Setujui Batal') }}
                                        </button>
                                    </form>
                                    <form action="{{ route('driver.orders.reject-cancel', $pickup->id) }}" method="POST" class="w-1/2">
                                        @csrf
                                        <button type="submit" class="w-full py-2 bg-white text-gray-700 font-bold rounded-xl border border-gray-300 hover:bg-gray-50 transition-all text-sm">
                                            {{ __('Tolak') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('driver.verify', $pickup->id) }}" class="block w-full text-center py-5 bg-gradient-to-r from-primary to-emerald-500 text-white font-bold rounded-full btn-premium text-lg shadow-lg shadow-emerald-500/30">
                            📍 {{ __('I\'ve Arrived') }}
                        </a>

                        @if($pickup->cancel_requested_by === null)
                            <form action="{{ route('driver.orders.cancel', $pickup->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full mt-4 py-3 bg-red-50 text-red-600 font-bold rounded-xl border border-red-100 hover:bg-red-100 transition-all text-sm flex justify-center items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    {{ __('Ajukan Batal ke User') }}
                                </button>
                            </form>
                        @elseif($pickup->cancel_requested_by === 'driver')
                            <div class="mt-4 py-3 bg-amber-50 text-amber-700 font-bold rounded-xl border border-amber-100 text-sm text-center flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                {{ __('Menunggu persetujuan User untuk batal...') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var userLat = {{ $pickup->latitude ?? -7.942 }};
            var userLon = {{ $pickup->longitude ?? 112.62 }};
            var driverLat = userLat - 0.01; // Mock driver location near user
            var driverLon = userLon - 0.01;

            var map = L.map('map').setView([userLat, userLon], 14);

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

            // Add marker for user location
            L.marker([userLat, userLon], {icon: userIcon}).addTo(map)
                .bindPopup('<b>{{ __('Customer:') }} {{ $pickup->user->name }}</b><br>{{ $pickup->address }}')
                .openPopup();

            // Add marker for driver location
            L.marker([driverLat, driverLon], {icon: driverIcon}).addTo(map)
                .bindPopup('{{ __('Your current location') }}');
        });
    </script>
</x-app-layout>
