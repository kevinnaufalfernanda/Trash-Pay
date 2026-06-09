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
                                    <div class="text-sm font-medium text-secondary/60 mt-0.5">Pickup #{{ $order->id }} • {{ $order->category->name }} ({{ $order->total_weight }}kg)</div>
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

            {{-- HIGH REWARD PICKUPS --}}
            @if($highRewardPickups->isNotEmpty())
                <div class="glass-panel rounded-3xl p-8 border-2 border-amber-300 shadow-lg shadow-amber-300/30 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-amber-400/20 rounded-full blur-3xl -z-10"></div>
                    <h3 class="text-2xl font-serif font-bold mb-6 text-amber-600 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center animate-bounce shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path></svg>
                        </span>
                        {{ __('Priority Orders (High Coins)') }}
                    </h3>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($highRewardPickups as $pickup)
                            @include('driver.partials.order-card', ['pickup' => $pickup, 'currentCapacity' => $currentCapacity ?? 0, 'type' => 'high'])
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- STANDARD PICKUPS --}}
            @if($standardPickups->isNotEmpty())
                <div class="glass-panel rounded-3xl p-8">
                    <h3 class="text-2xl font-serif font-bold mb-6 text-secondary flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </span>
                        {{ __('Standard Orders') }}
                    </h3>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($standardPickups as $pickup)
                            @include('driver.partials.order-card', ['pickup' => $pickup, 'currentCapacity' => $currentCapacity ?? 0, 'type' => 'standard'])
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
