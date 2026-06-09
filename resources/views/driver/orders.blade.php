<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-secondary leading-tight">
            {{ __('Order Pool') }}
        </h2>
    </x-slot>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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

            {{-- ACTIVE ORDERS --}}
            @if($activeOrders->isNotEmpty())
            <div class="glass-panel rounded-3xl p-8 mb-8 border border-primary/30 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl -z-10"></div>
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <h3 class="text-2xl font-serif font-bold text-secondary flex items-center gap-3">
                        <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse shadow-lg shadow-red-500/50"></span>
                        {{ __('Active Orders (On The Way)') }}
                    </h3>
                    <div class="bg-white/60 px-4 py-2 rounded-xl border border-white flex items-center gap-3 shadow-sm min-w-[200px]">
                        <span class="text-sm font-bold text-secondary">Kapasitas:</span>
                        <div class="flex-1 h-3 w-32 bg-gray-200 rounded-full overflow-hidden">
                            @php $pct = min(100, ($currentCapacity / 10) * 100); @endphp
                            <div class="h-full {{ $pct >= 100 ? 'bg-red-500' : 'bg-primary' }} transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-sm font-bold {{ $currentCapacity >= 10 ? 'text-red-600' : 'text-primary' }}">{{ $currentCapacity }}/10kg</span>
                    </div>
                </div>
                <div class="space-y-4">
                    @foreach($activeOrders as $order)
                        <div class="flex items-center justify-between p-5 bg-white/70 rounded-2xl border border-white shadow-sm hover:bg-white transition-colors">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-gradient-to-br from-primary/10 to-primary/5 text-primary rounded-2xl flex items-center justify-center font-serif font-bold text-2xl border border-primary/20 shadow-sm">
                                    {{ substr($order->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-secondary text-lg">{{ $order->user->name }}</div>
                                    <div class="text-sm font-medium text-secondary/60 mt-0.5">Pickup #{{ $order->id }} • {{ __($order->category->name) }} ({{ $order->total_weight }}kg)</div>
                                    @if($order->cancel_requested_by === 'user')
                                        <div class="mt-1 text-xs font-bold text-red-600 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            {{ __('User mengajukan batal!') }}
                                        </div>
                                    @elseif($order->cancel_requested_by === 'driver')
                                        <div class="mt-1 text-xs font-bold text-amber-600 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ __('Menunggu persetujuan batal...') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <div class="flex gap-3">
                                    <a href="{{ route('driver.navigation', $order->id) }}" class="px-6 py-3 bg-gradient-to-r from-primary to-emerald-500 text-white text-sm font-bold rounded-full btn-premium shadow-lg shadow-emerald-500/30 flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                        {{ __('Open Navigation') }}
                                    </a>
                                </div>
                                @if($order->cancel_requested_by === 'user')
                                    <div class="flex gap-2 mt-2 w-full justify-end">
                                        <form action="{{ route('driver.orders.approve-cancel', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-full shadow-md shadow-red-500/30 hover:bg-red-700 transition-colors">Setujui Batal</button>
                                        </form>
                                        <form action="{{ route('driver.orders.reject-cancel', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-white text-gray-700 text-xs font-bold rounded-full shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors">Tolak Batal</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($highRewardPickups->isEmpty() && $standardPickups->isEmpty())
                <div class="glass-panel rounded-3xl p-8 text-center py-16 flex flex-col items-center">
                    <div class="w-24 h-24 mb-6 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <h3 class="text-2xl font-serif font-bold text-secondary mb-2">{{ __('No Incoming Orders Yet') }}</h3>
                    <p class="text-gray-500">{{ __('Currently there are no citizens requesting waste pickup. Please be patient!') }}</p>
                </div>
            @endif

            {{-- AVAILABLE PICKUPS (COMBINED) --}}
            @if($highRewardPickups->isNotEmpty() || $standardPickups->isNotEmpty())
                <div x-data="{ filter: 'all' }" class="glass-panel rounded-3xl p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -z-10"></div>
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <h3 class="text-2xl font-serif font-bold text-secondary flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </span>
                            {{ __('Available Orders') }}
                        </h3>
                        
                        <!-- Custom Alpine Dropdown -->
                        <div x-data="{ open: false }" class="relative w-full md:w-auto min-w-[260px]" @click.away="open = false">
                            <button @click="open = !open" 
                                    class="w-full flex items-center justify-between gap-3 py-2.5 px-4 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/50 font-bold text-sm cursor-pointer transition-all border-2"
                                    :class="filter === 'high' ? 'border-amber-400 bg-amber-50/50 text-amber-700' : (filter === 'all' ? 'border-gray-200 bg-white text-gray-700 hover:border-gray-300' : 'border-primary/50 bg-primary/5 text-primary')">
                                <span class="whitespace-nowrap truncate" x-text="filter === 'all' ? '{{ __('Semua Kategori') }}' : (filter === 'high' ? '{{ __('Prioritas (Banyak TrashCoin)') }}' : (filter === 'nearest' ? '{{ __('Terdekat (< 5km)') }}' : '{{ __('Standar') }}'))"></span>
                                <svg class="w-4 h-4 transition-transform duration-200 flex-shrink-0" :class="{'rotate-180': open, 'text-amber-500': filter === 'high', 'text-gray-500': filter === 'all', 'text-primary': filter !== 'all' && filter !== 'high'}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200" 
                                 x-transition:enter-start="opacity-0 scale-95 translateY-[-10px]" 
                                 x-transition:enter-end="opacity-100 scale-100 translateY-0" 
                                 x-transition:leave="transition ease-in duration-100" 
                                 x-transition:leave-start="opacity-100 scale-100 translateY-0" 
                                 x-transition:leave-end="opacity-0 scale-95 translateY-[-10px]" 
                                 class="absolute right-0 md:left-0 mt-2 w-full min-w-max origin-top-right md:origin-top-left bg-white border border-gray-100 rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 z-20 py-2 overflow-hidden"
                                 style="display: none;">
                                
                                <button @click="filter = 'all'; open = false" 
                                        class="w-full text-left px-4 py-2.5 text-sm font-bold transition-colors flex items-center justify-between"
                                        :class="filter === 'all' ? 'text-primary bg-primary/10' : 'text-gray-600 hover:bg-emerald-50 hover:text-primary'">
                                    {{ __('Semua Kategori') }}
                                    <svg x-show="filter === 'all'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </button>

                                <button @click="filter = 'nearest'; open = false" 
                                        class="w-full text-left px-4 py-2.5 text-sm font-bold transition-colors flex items-center justify-between"
                                        :class="filter === 'nearest' ? 'text-primary bg-primary/10' : 'text-gray-600 hover:bg-emerald-50 hover:text-primary'">
                                    {{ __('Terdekat (< 5km)') }}
                                    <svg x-show="filter === 'nearest'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                                
                                <button @click="filter = 'high'; open = false" 
                                        class="w-full text-left px-4 py-2.5 text-sm font-bold transition-colors flex items-center justify-between"
                                        :class="filter === 'high' ? 'text-amber-600 bg-amber-50' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-600'">
                                    {{ __('Prioritas (Banyak TrashCoin)') }}
                                    <svg x-show="filter === 'high'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                                
                                <button @click="filter = 'standard'; open = false" 
                                        class="w-full text-left px-4 py-2.5 text-sm font-bold transition-colors flex items-center justify-between"
                                        :class="filter === 'standard' ? 'text-primary bg-primary/10' : 'text-gray-600 hover:bg-emerald-50 hover:text-primary'">
                                    {{ __('Standar') }}
                                    <svg x-show="filter === 'standard'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($highRewardPickups as $pickup)
                            <div x-show="filter === 'all' || filter === 'high' || (filter === 'nearest' && {{ $pickup->distance }} <= 5)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="h-full" style="display: none;" x-init="$el.style.display = 'block'">
                                @include('driver.partials.order-card', ['pickup' => $pickup, 'currentCapacity' => $currentCapacity ?? 0, 'type' => 'high'])
                            </div>
                        @endforeach

                        @foreach($standardPickups as $pickup)
                            <div x-show="filter === 'all' || filter === 'standard' || (filter === 'nearest' && {{ $pickup->distance }} <= 5)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="h-full" style="display: none;" x-init="$el.style.display = 'block'">
                                @include('driver.partials.order-card', ['pickup' => $pickup, 'currentCapacity' => $currentCapacity ?? 0, 'type' => 'standard'])
                            </div>
                        @endforeach
                    </div>
                    
                    <div x-show="filter === 'high' && {{ $highRewardPickups->count() }} === 0" style="display: none;" class="text-center py-8 text-gray-500 italic">
                        {{ __('Tidak ada pesanan prioritas saat ini.') }}
                    </div>
                    <div x-show="filter === 'standard' && {{ $standardPickups->count() }} === 0" style="display: none;" class="text-center py-8 text-gray-500 italic">
                        {{ __('Tidak ada pesanan standar saat ini.') }}
                    </div>
                    <div x-show="filter === 'nearest' && {{ $highRewardPickups->where('distance', '<=', 5)->count() + $standardPickups->where('distance', '<=', 5)->count() }} === 0" style="display: none;" class="text-center py-8 text-gray-500 italic">
                        {{ __('Tidak ada pesanan di sekitar Anda (< 5km).') }}
                    </div>
                </div>
            @endif

        </div>
    </div>

    @if(auth()->user()->driver_status === 'offline')
        <template x-teleport="body">
            <div x-data="{ showOfflineWarning: true }" x-show="showOfflineWarning" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4" @click.self="showOfflineWarning = false">
                <!-- Backdrop -->
                <div x-show="showOfflineWarning" x-transition.opacity class="fixed inset-0 bg-secondary/80 backdrop-blur-sm pointer-events-none"></div>
                
                <!-- Modal Content -->
                <div x-show="showOfflineWarning"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative z-10 text-center flex flex-col items-center">
                     
                    <div class="w-20 h-20 bg-red-100 text-red-500 rounded-full flex items-center justify-center mb-6 shadow-inner">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    
                    <h3 class="text-2xl font-serif font-bold text-secondary mb-3">{{ __('Akses Ditahan!') }}</h3>
                    <p class="text-gray-500 mb-8">{{ __('Status Anda saat ini sedang Offline. Tolong aktifkan akun driver Anda dengan menekan tombol sakelar di menu Beranda terlebih dahulu agar Anda dapat mengambil pesanan.') }}</p>
                    
                    <button @click="showOfflineWarning = false" class="w-full py-3.5 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-primary hover:text-white hover:border-primary active:bg-emerald-600 transition-colors border border-gray-200">
                        {{ __('Saya Mengerti') }}
                    </button>
                </div>
            </div>
        </template>
    @endif
</x-app-layout>
