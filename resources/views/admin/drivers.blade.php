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
                                <span class="text-amber-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </span> {{ __('Pending Registration Approvals') }}
                            </h3>
                            
                            <div class="space-y-4">
                                @foreach($applications as $app)
                                    <div x-data="{ zoomKTP: false }" class="p-4 bg-white/60 rounded-2xl border border-amber-200/50 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                                        <div class="flex items-start gap-4">
                                            <div @click="zoomKTP = true" class="cursor-pointer shrink-0 w-16 h-12 bg-gray-200 rounded-lg overflow-hidden border border-gray-300 hover:opacity-80 transition-opacity" title="{{ __('View KTP') }}">
                                                <img src="{{ Storage::url($app->ktp_photo) }}" class="w-full h-full object-cover" alt="KTP">
                                            </div>
                                            
                                            <!-- Fullscreen KTP Zoom -->
                                            <template x-teleport="body">
                                                <div x-show="zoomKTP" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
                                                    <!-- Darker Backdrop for Zoom -->
                                                    <div x-show="zoomKTP" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-md" @click="zoomKTP = false"></div>
                                                    
                                                    <div x-show="zoomKTP"
                                                         x-transition:enter="transition ease-out duration-300"
                                                         x-transition:enter-start="opacity-0 scale-90"
                                                         x-transition:enter-end="opacity-100 scale-100"
                                                         x-transition:leave="transition ease-in duration-200"
                                                         x-transition:leave-start="opacity-100 scale-100"
                                                         x-transition:leave-end="opacity-0 scale-90"
                                                         class="relative z-10 max-w-5xl w-full flex flex-col items-center justify-center">
                                                         
                                                        <button @click="zoomKTP = false" class="absolute -top-12 right-0 sm:-right-8 p-2 text-white/50 hover:text-white transition-colors">
                                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                        
                                                        <img src="{{ Storage::url($app->ktp_photo) }}" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10">
                                                    </div>
                                                </div>
                                            </template>
                                            
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
                                        <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
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
                                    <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
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
