<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-secondary leading-tight">
            {{ __('Order Pool') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-emerald-100 text-emerald-700 px-4 py-3 rounded-xl shadow-sm border border-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl shadow-sm border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            @if($highRewardPickups->isEmpty() && $standardPickups->isEmpty())
                <div class="glass-panel rounded-3xl p-8 text-center py-16">
                    <div class="text-6xl mb-4">📭</div>
                    <h3 class="text-2xl font-serif font-bold text-secondary mb-2">{{ __('No Incoming Orders Yet') }}</h3>
                    <p class="text-gray-500">{{ __('Currently there are no citizens requesting waste pickup. Please be patient!') }}</p>
                </div>
            @endif

            {{-- HIGH REWARD PICKUPS --}}
            @if($highRewardPickups->isNotEmpty())
                <div class="glass-panel rounded-3xl p-8 border-2 border-amber-300 shadow-lg shadow-amber-300/30 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-amber-400/20 rounded-full blur-3xl -z-10"></div>
                    <h3 class="text-2xl font-serif font-bold mb-6 text-amber-600 flex items-center gap-3">
                        <span class="text-3xl animate-bounce">🔥</span>
                        {{ __('Priority Orders (High Coins)') }}
                    </h3>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($highRewardPickups as $pickup)
                            <div class="bg-gradient-to-br from-white/90 to-amber-50/90 rounded-3xl border-2 border-amber-200 p-6 shadow-sm hover:-translate-y-1 transition-transform group flex flex-col h-full">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center font-serif font-bold text-2xl shadow-sm border border-amber-200 group-hover:scale-110 transition-transform">
                                            {{ substr($pickup->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-secondary">{{ $pickup->user->name }}</div>
                                            <div class="text-xs font-bold text-amber-600 uppercase">{{ $pickup->category->name }}</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-white bg-amber-500 px-3 py-1 rounded-full shadow-sm">{{ __('Est:') }} {{ $pickup->est_coins }} 🪙</span>
                                </div>
                                
                                @if($pickup->waste_photo)
                                    <img src="{{ Storage::url($pickup->waste_photo) }}" class="w-full h-32 object-cover rounded-xl mb-4 border border-amber-200/50">
                                @endif

                                <div class="flex-grow space-y-2 mb-6">
                                    <div class="flex items-start gap-2 text-sm text-gray-600">
                                        <span class="mt-0.5">⚖️</span>
                                        <p>{{ __('Estimated Weight:') }} <strong>{{ $pickup->total_weight }} kg</strong></p>
                                    </div>
                                    <div class="flex items-start gap-2 text-sm text-gray-600">
                                        <span class="mt-0.5">📍</span>
                                        <p>{{ __('Address:') }} <strong>{{ $pickup->address ?? __('No specific address') }}</strong></p>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <div class="flex gap-2">
                                        <a href="{{ route('driver.preview', $pickup->id) }}" class="flex-1 py-4 bg-amber-50 text-amber-600 border border-amber-200 font-bold rounded-2xl hover:bg-amber-100 transition-colors flex justify-center items-center gap-2 text-sm">
                                            🗺️ {{ __('Check Map') }}
                                        </a>
                                        <form action="{{ route('driver.orders.accept', $pickup->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button class="w-full h-full py-4 bg-gradient-to-r from-amber-400 to-orange-500 text-white font-bold rounded-2xl shadow-lg shadow-orange-500/30 hover:shadow-orange-500/50 transition-shadow flex justify-center items-center gap-2 text-sm">
                                                🚀 {{ __('Take') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- STANDARD PICKUPS --}}
            @if($standardPickups->isNotEmpty())
                <div class="glass-panel rounded-3xl p-8">
                    <h3 class="text-2xl font-serif font-bold mb-6 text-secondary flex items-center gap-3">
                        <span class="text-2xl">📦</span>
                        {{ __('Standard Orders') }}
                    </h3>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($standardPickups as $pickup)
                            <div class="bg-white/60 rounded-3xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow group flex flex-col h-full">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-gray-100 text-gray-600 rounded-2xl flex items-center justify-center font-serif font-bold text-2xl shadow-sm border border-gray-200 group-hover:scale-110 transition-transform">
                                            {{ substr($pickup->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-secondary">{{ $pickup->user->name }}</div>
                                            <div class="text-xs font-bold text-gray-500 uppercase">{{ $pickup->category->name }}</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-primary bg-primary/10 px-3 py-1 rounded-full border border-primary/20">{{ __('Est:') }} {{ $pickup->est_coins }} 🪙</span>
                                </div>
                                
                                @if($pickup->waste_photo)
                                    <img src="{{ Storage::url($pickup->waste_photo) }}" class="w-full h-32 object-cover rounded-xl mb-4 border border-gray-200">
                                @endif

                                <div class="flex-grow space-y-2 mb-6">
                                    <div class="flex items-start gap-2 text-sm text-gray-600">
                                        <span class="mt-0.5">⚖️</span>
                                        <p>{{ __('Estimated Weight:') }} <strong>{{ $pickup->total_weight }} kg</strong></p>
                                    </div>
                                    <div class="flex items-start gap-2 text-sm text-gray-600">
                                        <span class="mt-0.5">📍</span>
                                        <p>{{ __('Address:') }} <strong>{{ $pickup->address ?? __('No specific address') }}</strong></p>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    <div class="flex gap-2">
                                        <a href="{{ route('driver.preview', $pickup->id) }}" class="flex-1 py-3 bg-gray-50 text-gray-600 border border-gray-200 font-bold rounded-2xl hover:bg-gray-100 transition-colors flex justify-center items-center gap-2 text-sm">
                                            🗺️ {{ __('Check Map') }}
                                        </a>
                                        <form action="{{ route('driver.orders.accept', $pickup->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button class="w-full h-full py-3 bg-white border-2 border-primary text-primary hover:bg-primary hover:text-white font-bold rounded-2xl transition-colors flex justify-center items-center gap-2 text-sm">
                                                🚀 {{ __('Take') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
