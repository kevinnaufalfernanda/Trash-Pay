<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-semibold text-2xl text-secondary leading-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-primary/20 border border-primary/30 text-secondary px-4 py-3 rounded-xl relative" role="alert">
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <!-- AI Scan Call to Action -->
            <div class="mb-10">
                <a href="{{ route('user.pickup') }}" class="relative block overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-primary to-emerald-600 p-10 btn-premium group shadow-lg shadow-primary/30">
                    <div class="absolute right-0 top-0 -mt-8 -mr-8 h-40 w-40 rounded-full bg-white opacity-10 group-hover:scale-[2.5] transition-transform duration-700 ease-out"></div>
                    <div class="absolute left-10 bottom-10 h-20 w-20 rounded-full bg-white opacity-20 blur-2xl"></div>
                    <div class="relative flex flex-col items-center justify-center text-center">
                        <div class="mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-white/20 backdrop-blur-md text-white text-4xl shadow-sm border border-white/30 group-hover:-translate-y-2 transition-all duration-300 ease-out">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h3 class="mb-3 text-3xl font-serif font-bold text-white tracking-tight">{{ __('Scan AI & Get Coins!') }}</h3>
                        <p class="text-emerald-50 max-w-md font-medium text-lg leading-relaxed">{{ __('Point your camera at your waste and let the AI magic work.') }}</p>
                    </div>
                </a>
            </div>

            <div class="grid md:grid-cols-2 gap-8 mb-10">
                <!-- Impact Score -->
                <div class="glass-panel rounded-3xl p-8 text-center relative overflow-hidden group">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-emerald-400/20 rounded-full blur-3xl -z-10 group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="text-emerald-600/80 text-xs font-bold tracking-widest uppercase mb-4">{{ __('Impact Score (CO2)') }}</div>
                    <div class="text-6xl font-serif font-bold text-secondary mb-2 tracking-tighter">{{ number_format($impactScore ?? 0, 2) }} <span class="text-2xl text-gray-400 font-sans">kg</span></div>
                    <p class="text-sm text-gray-500 font-medium flex items-center justify-center gap-1.5">
                        {{ __('The Earth thanks you!') }}
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </p>
                </div>

                <!-- Coin Balance -->
                <div class="glass-panel rounded-3xl p-8 text-center relative overflow-hidden group">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-amber-400/20 rounded-full blur-3xl -z-10 group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="text-amber-600/80 text-xs font-bold tracking-widest uppercase mb-4">{{ __('Coin Balance') }}</div>
                    <div class="text-6xl font-serif font-bold text-accent mb-4 tracking-tighter flex items-center justify-center gap-2">
                        {{ number_format($user->coin_balance) }} 
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
                    </div>
                    <a href="{{ route('user.redeem') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-accent to-amber-400 text-white rounded-full text-sm font-bold shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 hover:-translate-y-0.5 transition-all">{{ __('Redeem Coins Now') }}</a>
                </div>
            </div>

            <!-- Panduan Koin -->
            <div class="glass-panel rounded-3xl p-8 mb-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl -z-10"></div>
                
                <h3 class="text-2xl font-serif font-bold mb-2 text-secondary flex items-center gap-2">
                    {{ __('Trash-Pay Coin Guide') }}
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </h3>
                <p class="text-gray-500 mb-6 font-medium text-sm">{{ __('Exchange your waste for coins! Collect and withdraw to your e-Wallet.') }}</p>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Nilai Tukar -->
                    <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-5 border border-white/80 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">{{ __('Exchange Rate') }}</div>
                            <div class="font-bold text-lg text-secondary">{{ __('10 Coins = Rp 1.000') }}</div>
                        </div>
                    </div>

                    <!-- Kategori Reward -->
                    <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-5 border border-white/80 shadow-sm">
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Reward per Kilogram</div>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($categories as $category)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <x-category-icon :category="$category" class="w-6 h-6 text-emerald-600" />
                                        <span class="text-sm font-semibold text-secondary">{{ __($category->name) }}</span>
                                    </div>
                                    <span class="text-sm font-bold text-primary">{{ $category->price_per_kg }} <span class="text-xs">{{ __('Coins') }}</span></span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Pickups -->
            <div class="glass-panel rounded-3xl p-8">
                <h3 class="text-2xl font-serif font-bold mb-6 text-secondary">{{ __('Pickup History') }}</h3>
                @if($recentPickups->isEmpty())
                    <div class="text-center py-8">
                        <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">{{ __('No history yet. Let\'s start scanning your waste!') }}</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($recentPickups->take(3) as $pickup)
                            @include('user.partials.dashboard-pickup-card', ['pickup' => $pickup])
                        @endforeach
                    </div>
                    <div class="mt-6 flex justify-center">
                        <a href="{{ route('user.history') }}" class="inline-block px-6 py-2.5 bg-gray-50 text-gray-600 font-bold rounded-full border border-gray-200 hover:bg-gray-100 transition-colors text-sm">
                            {{ __('Riwayat Lebih Detail') }} &rarr;
                        </a>
                    </div>
                @endif
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
