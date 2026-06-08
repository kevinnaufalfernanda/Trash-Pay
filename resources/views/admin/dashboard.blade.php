<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-secondary leading-tight">
            {{ __('Admin Control Panel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">

            {{-- Stats Row --}}
            <div class="grid md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <div class="glass-panel rounded-3xl p-5 text-center">
                    <div class="text-2xl mb-1">⚖️</div>
                    <div class="text-xs font-semibold text-gray-500 uppercase">Total Sampah</div>
                    <div class="text-2xl font-bold text-secondary">{{ number_format($totalWeight, 1) }} <span class="text-sm">kg</span></div>
                </div>
                <div class="glass-panel rounded-3xl p-5 text-center">
                    <div class="text-2xl mb-1">🌿</div>
                    <div class="text-xs font-semibold text-gray-500 uppercase">CO2 Reduced</div>
                    <div class="text-2xl font-bold text-primary">{{ number_format($co2Reduced, 1) }} <span class="text-sm">kg</span></div>
                </div>
                <div class="glass-panel rounded-3xl p-5 text-center">
                    <div class="text-2xl mb-1">✅</div>
                    <div class="text-xs font-semibold text-gray-500 uppercase">Completed</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $completedPickups }}</div>
                </div>
                <div class="glass-panel rounded-3xl p-5 text-center">
                    <div class="text-2xl mb-1">💳</div>
                    <div class="text-xs font-semibold text-gray-500 uppercase">Pending Payouts</div>
                    <div class="text-2xl font-bold text-amber-500">{{ $pendingPayouts }}</div>
                </div>
                <div class="glass-panel rounded-3xl p-5 text-center">
                    <div class="text-2xl mb-1">👤</div>
                    <div class="text-xs font-semibold text-gray-500 uppercase">Users</div>
                    <div class="text-2xl font-bold text-secondary">{{ $totalUsers }}</div>
                </div>
                <div class="glass-panel rounded-3xl p-5 text-center">
                    <div class="text-2xl mb-1">🚚</div>
                    <div class="text-xs font-semibold text-gray-500 uppercase">Drivers</div>
                    <div class="text-2xl font-bold text-secondary">{{ $totalDrivers }}</div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="grid md:grid-cols-3 gap-6">

                <a href="{{ route('admin.analytics') }}" class="glass-panel rounded-3xl p-8 text-center hover:border-primary hover:bg-white/60 transition-all group">
                    <div class="text-5xl mb-4 group-hover:scale-110 transition-transform">📈</div>
                    <h3 class="text-xl font-bold text-secondary mb-1">Analytics Dashboard</h3>
                    <p class="text-sm text-gray-500">Monitoring statistik volume & emisi</p>
                </a>

                <a href="{{ route('admin.pricing') }}" class="glass-panel rounded-3xl p-8 text-center hover:border-primary hover:bg-white/60 transition-all group">
                    <div class="text-5xl mb-4 group-hover:scale-110 transition-transform">🏷️</div>
                    <h3 class="text-xl font-bold text-secondary mb-1">Pricing Control</h3>
                    <p class="text-sm text-gray-500">Kelola harga per kategori</p>
                </a>

                <a href="{{ route('admin.payouts') }}" class="glass-panel rounded-3xl p-8 text-center hover:border-amber-400 hover:bg-white/60 transition-all group relative">
                    @if($pendingPayouts > 0)
                        <span class="absolute top-4 right-4 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg shadow-red-500/50">{{ $pendingPayouts }}</span>
                    @endif
                    <div class="text-5xl mb-4 group-hover:scale-110 transition-transform">💳</div>
                    <h3 class="text-xl font-bold text-secondary mb-1">Payout Approvals</h3>
                    <p class="text-sm text-gray-500">Validasi penarikan saldo user</p>
                </a>

            </div>

        </div>
    </div>
</x-app-layout>
