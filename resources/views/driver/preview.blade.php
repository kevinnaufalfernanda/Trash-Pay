<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-secondary leading-tight">
            {{ __('Preview Order #') }}{{ $pickup->id }}
        </h2>
    </x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="py-12">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Map -->
                <div class="md:col-span-2 glass-panel rounded-3xl p-4">
                    <div id="map" class="h-[32rem] w-full rounded-2xl z-0 border border-white/60 shadow-sm"></div>
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
                                <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">{{ __('Estimation') }}</span>
                                <div class="font-bold text-gray-800 text-lg">{{ $pickup->total_weight }} kg • {{ $pickup->category->name }}</div>
                            </div>
                            
                            <div class="bg-white/40 p-4 rounded-2xl border border-amber-200 shadow-sm bg-amber-50">
                                <span class="text-xs text-amber-600 uppercase font-bold tracking-wider">{{ __('Potential Coins') }}</span>
                                <div class="font-bold text-amber-500 text-2xl">~{{ round($pickup->total_weight * $pickup->category->price_per_kg) }} 🪙</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 space-y-3">
                        <form action="{{ route('driver.orders.accept', $pickup->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-center py-4 bg-gradient-to-r from-amber-400 to-amber-500 text-white font-bold rounded-full text-lg shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 hover:-translate-y-0.5 transition-all">
                                🚀 {{ __('Take This Order') }}
                            </button>
                        </form>
                        <a href="{{ route('driver.orders') }}" class="block w-full text-center py-4 bg-white/50 text-secondary font-bold rounded-full text-lg shadow-sm border border-white/60 hover:bg-white/80 transition-all">
                            {{ __('Back to Pool') }}
                        </a>
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
