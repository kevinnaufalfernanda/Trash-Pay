<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-semibold text-2xl text-secondary leading-tight">
            {{ __('Leaderboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8">
                
                {{-- User Leaderboard --}}
                <div class="glass-panel rounded-3xl p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-400/10 rounded-full blur-3xl -z-10"></div>
                    <h3 class="text-3xl font-serif font-bold mb-6 text-emerald-600 tracking-tight flex items-center gap-3">
                        🌱 Top Eco-Heroes
                    </h3>
                    <p class="text-gray-500 font-medium mb-8">{{ __('Users with the biggest CO2 reduction impact.') }}</p>

                    <div class="space-y-4">
                        @foreach($topUsers as $index => $user)
                            <div class="flex items-center justify-between p-4 bg-white/40 rounded-2xl border {{ $index < 3 ? 'border-amber-200/50 bg-gradient-to-r from-amber-50/50 to-transparent' : 'border-white/60' }} shadow-sm hover:bg-white/60 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 flex items-center justify-center font-bold text-xl rounded-full {{ $index == 0 ? 'bg-yellow-400 text-white shadow-lg' : ($index == 1 ? 'bg-gray-300 text-white' : ($index == 2 ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-500')) }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800 text-lg">{{ $user->name }}</div>
                                        <div class="text-sm font-medium text-emerald-600">{{ number_format($user->impact_score, 1) }} kg CO2 Reduced</div>
                                    </div>
                                </div>
                                @if($index == 0) <span class="text-3xl drop-shadow-md">👑</span> @endif
                            </div>
                        @endforeach
                        
                        @if($topUsers->isEmpty())
                            <div class="text-center py-8 text-gray-500 font-medium">{{ __('No eco-heroes data yet.') }}</div>
                        @endif
                    </div>
                </div>

                {{-- Driver Leaderboard --}}
                <div class="glass-panel rounded-3xl p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl -z-10"></div>
                    <h3 class="text-3xl font-serif font-bold mb-6 text-blue-600 tracking-tight flex items-center gap-3">
                        🚚 Top Eco-Drivers
                    </h3>
                    <p class="text-gray-500 font-medium mb-8">{{ __('Street heroes with the highest total collected waste.') }}</p>

                    <div class="space-y-4">
                        @foreach($topDrivers as $index => $driver)
                            <div class="flex items-center justify-between p-4 bg-white/40 rounded-2xl border {{ $index < 3 ? 'border-blue-200/50 bg-gradient-to-r from-blue-50/50 to-transparent' : 'border-white/60' }} shadow-sm hover:bg-white/60 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 flex items-center justify-center font-bold text-xl rounded-full {{ $index == 0 ? 'bg-yellow-400 text-white shadow-lg' : ($index == 1 ? 'bg-gray-300 text-white' : ($index == 2 ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-500')) }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800 text-lg">{{ $driver->name }}</div>
                                        <div class="text-sm font-medium text-blue-600">{{ number_format($driver->collected_weight, 1) }} kg Collected</div>
                                    </div>
                                </div>
                                @if($index == 0) <span class="text-3xl drop-shadow-md">🚀</span> @endif
                            </div>
                        @endforeach

                        @if($topDrivers->isEmpty())
                            <div class="text-center py-8 text-gray-500 font-medium">{{ __('No street heroes data yet.') }}</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
