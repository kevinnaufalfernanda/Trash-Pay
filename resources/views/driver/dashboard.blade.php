<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-semibold text-2xl text-secondary leading-tight">
            {{ __('Driver Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-primary/20 text-secondary px-4 py-3 rounded-xl border border-primary/30 font-medium">{{ session('success') }}</div>
            @endif

            {{-- Toggle Status --}}
            <div class="mb-10 glass-panel rounded-3xl p-8 flex items-center justify-between" 
                 x-data="{ 
                    online: {{ auth()->user()->driver_status === 'online' ? 'true' : 'false' }},
                    toggleStatus() {
                        this.online = !this.online;
                        fetch('{{ route('driver.status.update') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ status: this.online ? 'online' : 'offline' })
                        });
                    }
                 }">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-3xl transition-all duration-300 ease-out shadow-lg"
                         :class="online ? 'bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-emerald-500/30' : 'bg-gray-100 text-gray-400 border border-white/50'">
                        <span x-show="online">🟢</span>
                        <span x-show="!online" style="display: none;">💤</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-serif text-secondary mb-1">{{ __('Driver Status') }}</h3>
                        <p class="text-sm transition-colors duration-500 font-light"
                           :class="online ? 'text-primary' : 'text-secondary/50'">
                            <span x-text="online ? '{{ __('Online & Available') }}' : '{{ __('Offline / Resting') }}'"></span>
                        </p>
                    </div>
                </div>
                
                <!-- Toggle Switch -->
                <button type="button" 
                        class="relative inline-flex h-10 w-20 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
                        :class="online ? 'bg-primary' : 'bg-gray-300'"
                        @click="toggleStatus()">
                    <span class="sr-only">Toggle status</span>
                    <span class="pointer-events-none relative inline-block h-9 w-9 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                          :class="online ? 'translate-x-10' : 'translate-x-0'">
                        <span class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity duration-200 ease-in"
                              :class="online ? 'opacity-0 duration-100 ease-out' : 'opacity-100 duration-200 ease-in'">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 12 12">
                                <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity duration-300 ease-in"
                              :class="online ? 'opacity-100 duration-300 ease-in' : 'opacity-0 duration-100 ease-out'">
                            <svg class="h-4 w-4 text-primary" fill="currentColor" viewBox="0 0 12 12">
                                <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                            </svg>
                        </span>
                    </span>
                </button>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mb-10">
                
                {{-- Coin Balance (GIANT BLOCK like User Dashboard) --}}
                <div class="md:col-span-1 glass-panel rounded-3xl p-8 text-center relative overflow-hidden group flex flex-col justify-center">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-amber-400/20 rounded-full blur-3xl -z-10 group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="text-amber-600/80 text-xs font-bold tracking-widest uppercase mb-4">{{ __('Coin Balance') }}</div>
                    <div class="text-6xl font-serif font-bold text-accent mb-4 tracking-tighter">{{ number_format($driver->coin_balance) }} <span class="text-3xl opacity-90 drop-shadow-sm">🪙</span></div>
                    <a href="{{ route('driver.redeem') }}" class="inline-block px-8 py-3 bg-gradient-to-r from-accent to-amber-400 text-white rounded-full text-sm font-bold shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 hover:-translate-y-0.5 transition-all">{{ __('Withdraw Balance') }}</a>
                </div>

                {{-- Driver Stats (4-grid) --}}
                <div class="md:col-span-2 grid grid-cols-2 gap-4">
                    <div class="glass-panel rounded-3xl p-6 text-center border-t border-white/60">
                        <div class="text-4xl mb-2">📦</div>
                        <div class="text-xs font-bold tracking-widest text-gray-500 uppercase mb-2">{{ __('Pending Orders') }}</div>
                        <div class="text-4xl font-serif font-bold text-accent">{{ $pendingCount }}</div>
                    </div>
                    <div class="glass-panel rounded-3xl p-6 text-center border-t border-white/60 relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-b from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="text-4xl mb-2 relative z-10 group-hover:-translate-y-1 transition-transform">🚚</div>
                        <div class="text-xs font-bold tracking-widest text-gray-500 uppercase mb-2 relative z-10">{{ __('Active Now') }}</div>
                        <div class="text-4xl font-serif font-bold text-primary relative z-10">{{ $activeOrders->count() }}</div>
                    </div>
                    <div class="glass-panel rounded-3xl p-6 text-center border-t border-white/60">
                        <div class="text-4xl mb-2">✅</div>
                        <div class="text-xs font-bold tracking-widest text-gray-500 uppercase mb-2">{{ __('Completed') }}</div>
                        <div class="text-4xl font-serif font-bold text-secondary">{{ $completedCount }}</div>
                    </div>
                    <div class="glass-panel rounded-3xl p-6 text-center border-t border-white/60">
                        <div class="text-4xl mb-2">⚖️</div>
                        <div class="text-xs font-bold tracking-widest text-gray-500 uppercase mb-2">{{ __('Weight Collected') }}</div>
                        <div class="text-4xl font-serif font-bold text-secondary">{{ number_format($totalWeightCollected, 1) }} <span class="text-lg opacity-60">kg</span></div>
                    </div>
                </div>

            </div>

            {{-- Active Orders --}}
            @if($activeOrders->isNotEmpty())
            <div class="glass-panel rounded-3xl p-8 mb-10">
                <h3 class="text-2xl font-serif font-bold mb-6 text-secondary flex items-center gap-3">
                    <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse shadow-lg shadow-red-500/50"></span>
                    {{ __('My Active Orders') }}
                </h3>
                <div class="space-y-4">
                    @foreach($activeOrders as $order)
                        <div class="flex items-center justify-between p-5 bg-white/50 rounded-2xl border border-white/60 shadow-sm hover:bg-white/70 transition-colors">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-gradient-to-br from-gray-100 to-white text-primary rounded-2xl flex items-center justify-center font-serif font-bold text-2xl border border-white shadow-sm">
                                    {{ substr($order->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-secondary">{{ $order->user->name }}</div>
                                    <div class="text-sm font-light text-secondary/60 mt-0.5">Pickup #{{ $order->id }}</div>
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
                                <div class="flex gap-2">
                                    <a href="{{ route('driver.navigation', $order->id) }}" class="px-5 py-2.5 bg-white/80 border border-gray-200 text-secondary text-sm font-bold rounded-full hover:bg-white transition-colors">{{ __('Navigate') }}</a>
                                    <a href="{{ route('driver.verify', $order->id) }}" class="px-5 py-2.5 bg-gradient-to-r from-primary to-emerald-500 text-white text-sm font-bold rounded-full btn-premium shadow-lg shadow-emerald-500/30">{{ __('Verify Weight') }}</a>
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

            {{-- CTA --}}
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-[2.5rem] shadow-xl shadow-emerald-500/20 p-12 text-center text-white relative overflow-hidden group">
                <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80')] bg-cover bg-center opacity-10 mix-blend-overlay group-hover:scale-105 transition-transform duration-700"></div>
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/20 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <div class="text-6xl mb-6 drop-shadow-md group-hover:-translate-y-2 transition-transform duration-500">🚀</div>
                    <h3 class="text-4xl font-serif font-bold mb-3 tracking-tight">{{ __('Ready to collect waste?') }}</h3>
                    <p class="text-emerald-50 mb-8 font-medium text-lg">{{ __('Check the Order Pool for new pickup requests near you.') }}</p>
                    <a href="{{ route('driver.orders') }}" class="inline-block px-10 py-4 bg-gradient-to-r from-accent to-amber-400 text-white rounded-full font-bold text-lg shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 hover:-translate-y-0.5 transition-all">
                        {{ __('View Order Pool') }} ({{ $pendingCount }} {{ __('pending') }})
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
