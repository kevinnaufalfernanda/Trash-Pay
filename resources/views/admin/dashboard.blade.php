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
                <div class="glass-panel rounded-3xl p-5 text-center flex flex-col items-center transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="w-10 h-10 mb-2 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                    <div class="text-xs font-semibold text-gray-500 uppercase">{{ __('Total Waste') }}</div>
                    <div class="text-2xl font-bold text-secondary">{{ number_format($totalWeight, 1) }} <span class="text-sm">kg</span></div>
                </div>
                <div class="glass-panel rounded-3xl p-5 text-center flex flex-col items-center transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="w-10 h-10 mb-2 rounded-xl bg-emerald-50 flex items-center justify-center text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"></path></svg>
                    </div>
                    <div class="text-xs font-semibold text-gray-500 uppercase">{{ __('CO2 Reduced') }}</div>
                    <div class="text-2xl font-bold text-primary">{{ number_format($co2Reduced, 1) }} <span class="text-sm">kg</span></div>
                </div>
                <div class="glass-panel rounded-3xl p-5 text-center flex flex-col items-center transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="w-10 h-10 mb-2 rounded-xl bg-emerald-50 flex items-center justify-center text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="text-xs font-semibold text-gray-500 uppercase">{{ __('Completed') }}</div>
                    <div class="text-2xl font-bold text-emerald-600">{{ $completedPickups }}</div>
                </div>
                <div class="glass-panel rounded-3xl p-5 text-center flex flex-col items-center transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="w-10 h-10 mb-2 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    </div>
                    <div class="text-xs font-semibold text-gray-500 uppercase">{{ __('Pending Payouts') }}</div>
                    <div class="text-2xl font-bold text-amber-500">{{ $pendingPayouts }}</div>
                </div>
                <div class="glass-panel rounded-3xl p-5 text-center flex flex-col items-center transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="w-10 h-10 mb-2 rounded-xl bg-emerald-50 flex items-center justify-center text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </div>
                    <div class="text-xs font-semibold text-gray-500 uppercase">{{ __('Users') }}</div>
                    <div class="text-2xl font-bold text-secondary">{{ $totalUsers }}</div>
                </div>
                <div class="glass-panel rounded-3xl p-5 text-center flex flex-col items-center transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                    <div class="w-10 h-10 mb-2 rounded-xl bg-emerald-50 flex items-center justify-center text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                    </div>
                    <div class="text-xs font-semibold text-gray-500 uppercase">{{ __('Drivers') }}</div>
                    <div class="text-2xl font-bold text-secondary">{{ $totalDrivers }}</div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="grid md:grid-cols-3 gap-6">

                <a href="{{ route('admin.analytics') }}" class="glass-panel rounded-3xl p-8 text-center hover:border-primary hover:bg-white/60 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl active:scale-[0.98] group flex flex-col items-center">
                    <div class="w-20 h-20 mb-6 rounded-3xl bg-emerald-50 flex items-center justify-center text-primary group-hover:scale-110 transition-transform shadow-sm">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9l-5 5-4-4-5 5" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-secondary mb-1">{{ __('Analytics Dashboard') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('Monitoring volume & emission statistics') }}</p>
                </a>

                <a href="{{ route('admin.pricing') }}" class="glass-panel rounded-3xl p-8 text-center hover:border-amber-400 hover:bg-white/60 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl active:scale-[0.98] group flex flex-col items-center">
                    <div class="w-20 h-20 mb-6 rounded-3xl bg-amber-50 flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform shadow-sm">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-secondary mb-1">{{ __('Pricing Control') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('Manage price per category') }}</p>
                </a>

                <a href="{{ route('admin.payouts') }}" class="glass-panel rounded-3xl p-8 text-center hover:border-amber-400 hover:bg-white/60 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl active:scale-[0.98] group flex flex-col items-center relative">
                    @if($pendingPayouts > 0)
                        <span class="absolute top-4 right-4 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg shadow-red-500/50">{{ $pendingPayouts }}</span>
                    @endif
                    <div class="w-20 h-20 mb-6 rounded-3xl bg-amber-50 flex items-center justify-center text-amber-500 group-hover:scale-110 transition-transform shadow-sm">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-secondary mb-1">{{ __('Payout Approvals') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('Validate user balance withdrawal') }}</p>
                </a>

            </div>

        </div>
    </div>
</x-app-layout>
