<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-secondary leading-tight">
            {{ __('Eco-Driver Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-primary/20 border border-primary/30 text-secondary px-4 py-3 rounded-xl relative">
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl relative">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Add New Driver Form -->
                <div class="lg:col-span-1">
                    <div class="glass-panel rounded-3xl p-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-400/20 rounded-full blur-2xl -z-10"></div>
                        <h3 class="text-xl font-serif font-bold text-secondary mb-6">{{ __('Add New Driver') }}</h3>
                        
                        <form method="POST" action="{{ route('admin.drivers.store') }}">
                            @csrf
                            
                            <div class="mb-4">
                                <x-input-label for="name" :value="__('Full Name')" />
                                <x-text-input id="name" class="block mt-1 w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary bg-white/70" type="text" name="name" :value="old('name')" required />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="email" :value="__('Driver Email')" />
                                <x-text-input id="email" class="block mt-1 w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary bg-white/70" type="email" name="email" :value="old('email')" required />
                            </div>

                            <div class="mb-4">
                                <x-input-label for="password" :value="__('Password')" />
                                <x-text-input id="password" class="block mt-1 w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary bg-white/70" type="password" name="password" required />
                            </div>

                            <div class="mb-6">
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary bg-white/70" type="password" name="password_confirmation" required />
                            </div>

                                {{ __('Create Driver Account') }}
                        </form>
                    </div>
                </div>

                <!-- Driver List and Pending Applications -->
                <div class="lg:col-span-2 space-y-8">
                    
                    @if($applications->count() > 0)
                        <!-- Pending Applications -->
                        <div class="glass-panel rounded-3xl p-6 border-2 border-amber-200 shadow-lg shadow-amber-200/50 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-400/20 rounded-full blur-2xl -z-10"></div>
                            <h3 class="text-xl font-serif font-bold text-amber-600 mb-6 flex items-center gap-2">
                                <span>⚠️</span> {{ __('Pending Registration Approvals') }}
                            </h3>
                            
                            <div class="space-y-4">
                                @foreach($applications as $app)
                                    <div class="p-4 bg-white/60 rounded-2xl border border-amber-200/50 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                                        <div class="flex items-start gap-4">
                                            <a href="{{ Storage::url($app->ktp_photo) }}" target="_blank" class="shrink-0 w-16 h-12 bg-gray-200 rounded-lg overflow-hidden border border-gray-300 hover:opacity-80 transition-opacity" title="{{ __('View KTP') }}">
                                                <img src="{{ Storage::url($app->ktp_photo) }}" class="w-full h-full object-cover" alt="KTP">
                                            </a>
                                            <div>
                                                <h4 class="font-bold text-secondary">{{ $app->user->name }}</h4>
                                                <div class="text-xs text-gray-500 flex flex-col gap-0.5 mt-1">
                                                    <span>NIK: <strong>{{ $app->nik }}</strong></span>
                                                    <span>{{ __('Plate:') }} <strong>{{ $app->vehicle_plate }}</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex gap-2 shrink-0">
                                            <form action="{{ route('admin.drivers.approve', $app->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-500/30 hover:-translate-y-0.5 transition-transform">{{ __('Approve') }}</button>
                                            </form>
                                            <form action="{{ route('admin.drivers.reject', $app->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-red-500 text-white text-xs font-bold rounded-xl shadow-lg shadow-red-500/30 hover:-translate-y-0.5 transition-transform">{{ __('Reject') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="glass-panel rounded-3xl p-6">
                        <h3 class="text-xl font-serif font-bold text-secondary mb-6">{{ __('Eco-Driver List') }}</h3>
                        
                        <div class="space-y-4">
                            @forelse($drivers as $driver)
                                <div class="p-4 bg-white/40 rounded-2xl border border-white/60 flex items-center justify-between hover:bg-white/60 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center text-2xl">
                                            🚚
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-secondary">{{ $driver->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $driver->email }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if($driver->driver_status === 'online')
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">{{ __('Online') }}</span>
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-500 border border-gray-200 text-xs font-bold rounded-full">{{ __('Offline') }}</span>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-1">{{ __('Join') }} {{ $driver->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <div class="text-4xl mb-4 opacity-50">🚚</div>
                                    <p class="text-gray-500 font-medium">{{ __('No Eco-Driver account yet.') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
