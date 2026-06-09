<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-serif font-semibold text-2xl text-secondary leading-tight">
                {{ __('Papan Peringkat') }}
            </h2>
            <div class="flex gap-1 bg-white/50 backdrop-blur-md p-1.5 rounded-full border border-gray-200 shadow-sm overflow-x-auto max-w-full">
                <a href="{{ route('leaderboard', ['period' => 'all']) }}" class="whitespace-nowrap px-4 py-1.5 rounded-full text-sm font-bold transition-all {{ $period === 'all' ? 'bg-primary text-white shadow-md' : 'text-gray-500 hover:text-gray-800 hover:bg-white/60' }}">{{ __('Semua Waktu') }}</a>
                <a href="{{ route('leaderboard', ['period' => 'monthly']) }}" class="whitespace-nowrap px-4 py-1.5 rounded-full text-sm font-bold transition-all {{ $period === 'monthly' ? 'bg-primary text-white shadow-md' : 'text-gray-500 hover:text-gray-800 hover:bg-white/60' }}">{{ __('Bulan Ini') }}</a>
                <a href="{{ route('leaderboard', ['period' => 'weekly']) }}" class="whitespace-nowrap px-4 py-1.5 rounded-full text-sm font-bold transition-all {{ $period === 'weekly' ? 'bg-primary text-white shadow-md' : 'text-gray-500 hover:text-gray-800 hover:bg-white/60' }}">{{ __('Minggu Ini') }}</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">
            
            @if($currentUserRank)
                <div class="mb-8 p-4 sm:p-6 bg-gradient-to-r from-primary/10 to-transparent border border-primary/20 rounded-3xl flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center font-bold text-2xl text-primary shadow-sm border-2 border-primary/20">
                            #{{ $currentUserRank }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <div class="font-bold text-secondary text-lg sm:text-xl">{{ __('Peringkat Anda Saat Ini') }}</div>
                                @if(auth()->user()->role === 'user')
                                    <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">Warga</span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 text-xs font-bold rounded-full border border-blue-200">Driver</span>
                                @endif
                            </div>
                            <div class="text-sm font-medium text-gray-600 mt-0.5">
                                @if(auth()->user()->role === 'user')
                                    {{ number_format($currentUserData->impact_score ?? 0, 1) }} kg CO2 Reduced
                                @else
                                    {{ number_format($currentUserData->collected_weight ?? 0, 1) }} kg Collected
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($currentUserRank <= 10)
                        <div class="text-emerald-700 font-bold bg-emerald-100 border border-emerald-200 px-5 py-2.5 rounded-xl text-sm flex items-center gap-2 shadow-sm">
                            Masuk Top 10!
                        </div>
                    @else
                        <div class="text-gray-600 font-medium text-sm bg-white/60 px-5 py-2.5 rounded-xl border border-gray-200 text-center">
                            Terus kumpulkan sampah untuk masuk Top 10!
                        </div>
                    @endif
                </div>
            @endif

            <div class="grid md:grid-cols-2 gap-8">
                
                {{-- User Leaderboard --}}
                <div class="glass-panel rounded-3xl p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-400/10 rounded-full blur-3xl -z-10"></div>
                    <h3 class="text-3xl font-serif font-bold mb-6 text-emerald-600 tracking-tight flex items-center gap-3">
                        Top Eco-Heroes
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
                        Top Eco-Drivers
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
