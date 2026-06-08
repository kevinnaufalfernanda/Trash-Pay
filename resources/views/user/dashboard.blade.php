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
                            📸
                        </div>
                        <h3 class="mb-3 text-3xl font-serif font-bold text-white tracking-tight">Scan AI & Dapat Koin!</h3>
                        <p class="text-emerald-50 max-w-md font-medium text-lg leading-relaxed">Arahkan kamera ke sampahmu dan biarkan keajaiban AI bekerja.</p>
                    </div>
                </a>
            </div>

            <div class="grid md:grid-cols-2 gap-8 mb-10">
                <!-- Impact Score -->
                <div class="glass-panel rounded-3xl p-8 text-center relative overflow-hidden group">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-emerald-400/20 rounded-full blur-3xl -z-10 group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="text-emerald-600/80 text-xs font-bold tracking-widest uppercase mb-4">Impact Score (CO2)</div>
                    <div class="text-6xl font-serif font-bold text-secondary mb-2 tracking-tighter">{{ number_format($impactScore ?? 0, 2) }} <span class="text-2xl text-gray-400 font-sans">kg</span></div>
                    <p class="text-sm text-gray-500 font-medium">Bumi berterima kasih padamu! 🌍</p>
                </div>

                <!-- Coin Balance -->
                <div class="glass-panel rounded-3xl p-8 text-center relative overflow-hidden group">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-amber-400/20 rounded-full blur-3xl -z-10 group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="text-amber-600/80 text-xs font-bold tracking-widest uppercase mb-4">Saldo Koin</div>
                    <div class="text-6xl font-serif font-bold text-accent mb-4 tracking-tighter">{{ number_format($user->coin_balance) }} <span class="text-3xl opacity-90 drop-shadow-sm">🪙</span></div>
                    <a href="{{ route('user.redeem') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-accent to-amber-400 text-white rounded-full text-sm font-bold shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 hover:-translate-y-0.5 transition-all">Tukar Koin Sekarang</a>
                </div>
            </div>

            <!-- Panduan Koin -->
            <div class="glass-panel rounded-3xl p-8 mb-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl -z-10"></div>
                
                <h3 class="text-2xl font-serif font-bold mb-2 text-secondary">Panduan Koin Trash-Pay 🪙</h3>
                <p class="text-gray-500 mb-6 font-medium text-sm">Tukarkan sampahmu dengan koin! Kumpulkan dan cairkan ke e-Wallet.</p>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Nilai Tukar -->
                    <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-5 border border-white/80 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-2xl">
                            💰
                        </div>
                        <div>
                            <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Nilai Tukar</div>
                            <div class="font-bold text-lg text-secondary">10 Koin = Rp 1.000</div>
                        </div>
                    </div>

                    <!-- Kategori Reward -->
                    <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-5 border border-white/80 shadow-sm">
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Reward per Kilogram</div>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($categories as $category)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg">{{ $category->icon }}</span>
                                        <span class="text-sm font-semibold text-secondary">{{ $category->name }}</span>
                                    </div>
                                    <span class="text-sm font-bold text-primary">{{ $category->price_per_kg }} <span class="text-xs">Koin</span></span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Pickups -->
            <div class="glass-panel rounded-3xl p-8">
                <h3 class="text-2xl font-serif font-bold mb-6 text-secondary">Riwayat Penjemputan</h3>
                @if($recentPickups->isEmpty())
                    <div class="text-center py-8">
                        <div class="text-4xl mb-4 opacity-50">📭</div>
                        <p class="text-gray-500 text-sm font-medium">Belum ada riwayat. Yuk mulai scan sampahmu!</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($recentPickups as $pickup)
                            <div class="flex items-center justify-between p-4 bg-white/40 rounded-2xl border border-white/60 hover:bg-white/60 transition-colors group">
                                    <div class="flex items-center gap-5">
                                        <div class="w-14 h-14 bg-white rounded-xl flex items-center justify-center shadow-sm">
                                            @if($pickup->waste_photo)
                                                <img src="{{ Storage::url($pickup->waste_photo) }}" class="w-12 h-12 object-cover rounded-lg opacity-90" alt="Waste">
                                            @else
                                                <span class="text-2xl opacity-70">🗑️</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-medium text-secondary text-base">Pickup #{{ $pickup->id }}</div>
                                            <div class="text-sm text-secondary/50 font-light mt-0.5">{{ $pickup->created_at->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($pickup->status === 'completed')
                                            <span class="px-3 py-1.5 bg-primary/10 text-secondary rounded-full text-xs font-medium tracking-wide">Completed</span>
                                            <div class="text-sm font-medium text-accent mt-2">+{{ $pickup->total_coins }} 🪙</div>
                                        @elseif($pickup->status === 'on-the-way')
                                            <span class="px-3 py-1.5 bg-accent/10 text-accent rounded-full text-xs font-medium tracking-wide">On the way</span>
                                        @else
                                            <span class="px-3 py-1.5 bg-secondary/5 text-secondary/60 rounded-full text-xs font-medium tracking-wide">Pending</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
