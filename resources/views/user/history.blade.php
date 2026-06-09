<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-semibold text-2xl text-secondary leading-tight">
            {{ __('Riwayat Pesanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ mainTab: 'pesanan' }">
                <!-- Top Level Tabs -->
                <div class="flex gap-4 mb-6 overflow-x-auto pb-2 scrollbar-hide">
                    <button @click="mainTab = 'pesanan'" 
                            :class="{ 'bg-primary text-white shadow-md': mainTab === 'pesanan', 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200': mainTab !== 'pesanan' }"
                            class="px-6 py-3 rounded-full font-bold text-sm transition-all flex items-center gap-2 whitespace-nowrap">
                        📋 {{ __('Riwayat Pesanan') }}
                    </button>
                    <button @click="mainTab = 'penukaran'" 
                            :class="{ 'bg-amber-500 text-white shadow-md': mainTab === 'penukaran', 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200': mainTab !== 'penukaran' }"
                            class="px-6 py-3 rounded-full font-bold text-sm transition-all flex items-center gap-2 whitespace-nowrap">
                        🪙 {{ __('Riwayat Penukaran') }}
                    </button>
                </div>

                <!-- Pesanan Section -->
                <div x-show="mainTab === 'pesanan'" x-transition.opacity.duration.300ms>
                    <div x-data="{ tab: 'active' }">
                <div class="glass-panel rounded-3xl p-8 relative overflow-hidden shadow-sm">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-400/10 rounded-full blur-3xl -z-10"></div>
                    <h3 class="text-3xl font-serif font-bold mb-8 text-secondary flex items-center justify-between">
                        <span class="flex items-center gap-3">
                            <span class="p-3 bg-emerald-100 text-emerald-600 rounded-2xl">📋</span> 
                            {{ __('Pesanan Kamu') }}
                        </span>
                    </h3>

                    <!-- Tabs -->
                    <div class="flex border-b border-gray-200 mb-8 bg-gray-50/50 rounded-t-2xl px-4 pt-4">
                        <button @click="tab = 'active'" 
                                :class="{ 'border-primary text-primary bg-white shadow-sm rounded-t-xl': tab === 'active', 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 rounded-t-xl': tab !== 'active' }"
                                class="w-1/2 pb-4 pt-3 border-b-2 font-bold text-sm transition-all focus:outline-none text-center">
                            {{ __('Aktif') }}
                        </button>
                        <button @click="tab = 'completed'" 
                                :class="{ 'border-primary text-primary bg-white shadow-sm rounded-t-xl': tab === 'completed', 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-100/50 rounded-t-xl': tab !== 'completed' }"
                                class="w-1/2 pb-4 pt-3 border-b-2 font-bold text-sm transition-all focus:outline-none text-center">
                            {{ __('Riwayat') }}
                        </button>
                    </div>

                    <!-- Active Tab -->
                    <div x-show="tab === 'active'" x-transition.opacity.duration.300ms>
                        @php $activePickupsList = $pickups->whereIn('status', ['pending', 'on-the-way']); @endphp
                        @if($activePickupsList->isEmpty())
                            <div class="text-center py-16">
                                <div class="text-6xl mb-4 opacity-50">🍃</div>
                                <p class="text-gray-500 font-medium text-lg">{{ __('Belum ada penjemputan aktif.') }}</p>
                                <p class="text-sm text-gray-400 mt-2">{{ __('Yuk, mulai kumpulkan sampah dan pesan penjemputan!') }}</p>
                                <a href="{{ route('user.pickup') }}" class="mt-6 inline-block px-6 py-2.5 bg-primary text-white font-bold rounded-full hover:bg-emerald-600 transition-colors shadow-md">{{ __('Pesan Penjemputan') }}</a>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($activePickupsList as $pickup)
                                    @include('user.partials.pickup-card', ['pickup' => $pickup])
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Completed Tab -->
                    <div x-show="tab === 'completed'" style="display: none;" x-transition.opacity.duration.300ms>
                        @php $completedPickupsList = $pickups->whereIn('status', ['completed', 'rejected', 'cancelled']); @endphp
                        @if($completedPickupsList->isEmpty())
                            <div class="text-center py-16">
                                <div class="text-6xl mb-4 opacity-50">📂</div>
                                <p class="text-gray-500 font-medium text-lg">{{ __('Belum ada riwayat penjemputan.') }}</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($completedPickupsList as $pickup)
                                    @include('user.partials.pickup-card', ['pickup' => $pickup])
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                </div>
            </div>

            <!-- Penukaran Section -->
                <div x-show="mainTab === 'penukaran'" style="display: none;" x-transition.opacity.duration.300ms>
                    <div class="glass-panel rounded-3xl p-8 relative overflow-hidden shadow-sm">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-400/10 rounded-full blur-3xl -z-10"></div>
                        <h3 class="text-3xl font-serif font-bold mb-8 text-secondary flex items-center justify-between">
                            <span class="flex items-center gap-3">
                                <span class="p-3 bg-amber-100 text-amber-600 rounded-2xl">💸</span> 
                                {{ __('Riwayat Penukaran Koin') }}
                            </span>
                        </h3>

                        @if($redemptions->isEmpty())
                            <div class="text-center py-16">
                                <div class="text-6xl mb-4 opacity-50">📭</div>
                                <p class="text-gray-500 font-medium text-lg">{{ __('Belum ada riwayat penukaran koin.') }}</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($redemptions as $redemption)
                                    @include('partials.redemption-card', ['redemption' => $redemption])
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
