<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-secondary leading-tight">
            {{ __('Order Pool') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-emerald-100 text-emerald-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="glass-panel rounded-3xl p-8">
                <h3 class="text-2xl font-serif font-bold mb-6 text-secondary flex items-center gap-3">
                    <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse shadow-lg shadow-red-500/50"></span>
                    Active Pickups in Malang
                </h3>

                @if($pickups->isEmpty())
                    <div class="text-center py-8">
                        <div class="text-4xl mb-4">📭</div>
                        <p class="text-gray-500">No pending orders at the moment. Relax!</p>
                    </div>
                @else
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($pickups as $pickup)
                            <div class="bg-white/40 rounded-3xl border border-white/60 p-6 shadow-sm hover:bg-white/60 transition-all hover:shadow-lg group">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-white to-gray-50 text-primary rounded-2xl flex items-center justify-center font-serif font-bold text-2xl shadow-sm border border-white/60 group-hover:scale-110 transition-transform">
                                            {{ substr($pickup->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-800">{{ $pickup->user->name }}</div>
                                            <div class="text-sm font-medium text-gray-500">Est: {{ $pickup->total_weight ?? 'N/A' }} kg</div>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-primary bg-primary/10 px-3 py-1 rounded-full border border-primary/20 backdrop-blur-sm">#{{ $pickup->id }}</span>
                                </div>
                                
                                @if($pickup->waste_photo)
                                    <img src="{{ Storage::url($pickup->waste_photo) }}" class="w-full h-32 object-cover rounded-xl mb-4">
                                @endif

                                <div class="flex gap-2 mt-4">
                                    <form action="{{ route('driver.orders.accept', $pickup->id) }}" method="POST" class="w-full">
                                        @csrf
                                        <button class="w-full py-4 bg-gradient-to-r from-primary to-emerald-500 text-white font-bold rounded-full btn-premium text-lg">
                                            Accept Order
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
