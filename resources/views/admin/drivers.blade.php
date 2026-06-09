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

            <div class="max-w-5xl mx-auto space-y-8">
                <!-- Driver List and Pending Applications -->
                <div>
                    
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
                                    <div x-data="{ zoomKTP: false, zoomSIM: false, zoomSTNK: false, zoomSKCK: false }" class="p-4 bg-white/60 rounded-2xl border border-amber-200/50 flex flex-col justify-between gap-4">
                                        <div class="flex items-start justify-between w-full">
                                            <div>
                                                <h4 class="font-bold text-secondary">{{ $app->user->name }}</h4>
                                                <div class="text-xs text-gray-500 flex flex-col gap-0.5 mt-1">
                                                    <span>NIK: <strong>{{ $app->nik }}</strong></span>
                                                    <span>{{ __('Plate:') }} <strong>{{ $app->vehicle_plate }}</strong></span>
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

                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-2">
                                            <!-- KTP -->
                                            <div>
                                                <p class="text-xs font-bold text-gray-500 mb-1">KTP</p>
                                                <div @click="zoomKTP = true" class="cursor-pointer w-full h-24 bg-gray-200 rounded-lg overflow-hidden border border-gray-300 hover:opacity-80 transition-opacity">
                                                    <img src="{{ Storage::url($app->ktp_photo) }}" class="w-full h-full object-cover" alt="KTP">
                                                </div>
                                                <!-- Zoom KTP -->
                                                <template x-teleport="body">
                                                    <div x-show="zoomKTP" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
                                                        <div x-show="zoomKTP" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-md" @click="zoomKTP = false"></div>
                                                        <div x-show="zoomKTP" x-transition class="relative z-10 max-w-5xl w-full flex flex-col items-center justify-center">
                                                            <button @click="zoomKTP = false" class="absolute -top-12 right-0 sm:-right-8 p-2 text-white/50 hover:text-white transition-colors">
                                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            </button>
                                                            <img src="{{ Storage::url($app->ktp_photo) }}" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10">
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- SIM -->
                                            <div>
                                                <p class="text-xs font-bold text-gray-500 mb-1">SIM C</p>
                                                <div @click="zoomSIM = true" class="cursor-pointer w-full h-24 bg-gray-200 rounded-lg overflow-hidden border border-gray-300 hover:opacity-80 transition-opacity">
                                                    <img src="{{ Storage::url($app->sim_photo) }}" class="w-full h-full object-cover" alt="SIM">
                                                </div>
                                                <!-- Zoom SIM -->
                                                <template x-teleport="body">
                                                    <div x-show="zoomSIM" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
                                                        <div x-show="zoomSIM" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-md" @click="zoomSIM = false"></div>
                                                        <div x-show="zoomSIM" x-transition class="relative z-10 max-w-5xl w-full flex flex-col items-center justify-center">
                                                            <button @click="zoomSIM = false" class="absolute -top-12 right-0 sm:-right-8 p-2 text-white/50 hover:text-white transition-colors">
                                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            </button>
                                                            <img src="{{ Storage::url($app->sim_photo) }}" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10">
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- STNK -->
                                            <div>
                                                <p class="text-xs font-bold text-gray-500 mb-1">STNK</p>
                                                <div @click="zoomSTNK = true" class="cursor-pointer w-full h-24 bg-gray-200 rounded-lg overflow-hidden border border-gray-300 hover:opacity-80 transition-opacity">
                                                    <img src="{{ Storage::url($app->stnk_photo) }}" class="w-full h-full object-cover" alt="STNK">
                                                </div>
                                                <!-- Zoom STNK -->
                                                <template x-teleport="body">
                                                    <div x-show="zoomSTNK" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
                                                        <div x-show="zoomSTNK" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-md" @click="zoomSTNK = false"></div>
                                                        <div x-show="zoomSTNK" x-transition class="relative z-10 max-w-5xl w-full flex flex-col items-center justify-center">
                                                            <button @click="zoomSTNK = false" class="absolute -top-12 right-0 sm:-right-8 p-2 text-white/50 hover:text-white transition-colors">
                                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            </button>
                                                            <img src="{{ Storage::url($app->stnk_photo) }}" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10">
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- SKCK -->
                                            <div>
                                                <p class="text-xs font-bold text-gray-500 mb-1">SKCK</p>
                                                <div @click="zoomSKCK = true" class="cursor-pointer w-full h-24 bg-gray-200 rounded-lg overflow-hidden border border-gray-300 hover:opacity-80 transition-opacity">
                                                    <img src="{{ Storage::url($app->skck_photo) }}" class="w-full h-full object-cover" alt="SKCK">
                                                </div>
                                                <!-- Zoom SKCK -->
                                                <template x-teleport="body">
                                                    <div x-show="zoomSKCK" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
                                                        <div x-show="zoomSKCK" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-md" @click="zoomSKCK = false"></div>
                                                        <div x-show="zoomSKCK" x-transition class="relative z-10 max-w-5xl w-full flex flex-col items-center justify-center">
                                                            <button @click="zoomSKCK = false" class="absolute -top-12 right-0 sm:-right-8 p-2 text-white/50 hover:text-white transition-colors">
                                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            </button>
                                                            <img src="{{ Storage::url($app->skck_photo) }}" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10">
                                                        </div>
                                                    </div>
                                                </template>
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
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                            <h3 class="text-xl font-serif font-bold text-secondary">{{ __('Eco-Driver List') }}</h3>
                            
                            <!-- Filters -->
                            <div class="flex flex-wrap items-center gap-3">
                                <!-- Status Filter -->
                                <div x-data="{ openStatus: false }" class="relative z-20">
                                    <button @click="openStatus = !openStatus" @click.away="openStatus = false" 
                                            class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-colors focus:ring-2 focus:ring-emerald-500/20 {{ ($status ?? 'all') !== 'all' ? 'bg-emerald-100 border border-emerald-200 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 border border-gray-200 text-gray-600 hover:bg-gray-200' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @php
                                            $statusLabels = [
                                                'all' => __('Semua Status'),
                                                'online' => __('Aktif (Online)'),
                                                'offline' => __('Tidak Aktif (Offline)')
                                            ];
                                            $currentStatus = $statusLabels[$status ?? 'all'] ?? __('Semua Status');
                                        @endphp
                                        {{ $currentStatus }}
                                        <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-180': openStatus}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    
                                    <div x-show="openStatus" x-transition.opacity style="display: none;" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-30">
                                        <div class="px-3 py-2 mb-1 border-b border-gray-50">
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Filter Status') }}</p>
                                        </div>
                                        <div class="space-y-1 px-2">
                                            <a href="{{ route('admin.drivers', ['sort' => $sort ?? 'name_asc', 'status' => 'all']) }}" class="block px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ ($status ?? 'all') === 'all' ? 'text-emerald-700 bg-emerald-50' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }}">{{ __('Semua Status') }}</a>
                                            <a href="{{ route('admin.drivers', ['sort' => $sort ?? 'name_asc', 'status' => 'online']) }}" class="block px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ ($status ?? 'all') === 'online' ? 'text-emerald-700 bg-emerald-50' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }}">{{ __('Aktif (Online)') }}</a>
                                            <a href="{{ route('admin.drivers', ['sort' => $sort ?? 'name_asc', 'status' => 'offline']) }}" class="block px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ ($status ?? 'all') === 'offline' ? 'text-emerald-700 bg-emerald-50' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }}">{{ __('Tidak Aktif (Offline)') }}</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sort Dropdown -->
                                <div x-data="{ openSort: false }" class="relative z-20">
                                <button @click="openSort = !openSort" @click.away="openSort = false" 
                                        class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-colors focus:ring-2 focus:ring-emerald-500/20 {{ ($sort ?? 'default') !== 'default' ? 'bg-emerald-100 border border-emerald-200 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-100 border border-gray-200 text-gray-600 hover:bg-gray-200' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                    @php
                                        $sortLabels = [
                                            'default' => __('No Filter'),
                                            'name_asc' => __('Nama (A-Z)'),
                                            'name_desc' => __('Nama (Z-A)'),
                                            'date_desc' => __('Terbaru Bergabung'),
                                            'date_asc' => __('Terlama Bergabung')
                                        ];
                                        $currentSort = $sortLabels[$sort ?? 'default'] ?? __('No Filter');
                                    @endphp
                                    {{ $currentSort }}
                                    <svg class="w-4 h-4 transition-transform duration-300" :class="{'rotate-180': openSort}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                
                                <div x-show="openSort" x-transition.opacity style="display: none;" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-30">
                                    <div class="px-3 py-2 mb-1 border-b border-gray-50">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Urutkan Berdasarkan') }}</p>
                                    </div>
                                        <div class="space-y-1 px-2">
                                            <a href="{{ route('admin.drivers', ['sort' => 'default', 'status' => $status ?? 'all']) }}" class="block px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ ($sort ?? 'default') === 'default' ? 'text-emerald-700 bg-emerald-50' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }}">{{ __('No Filter') }}</a>
                                            <a href="{{ route('admin.drivers', ['sort' => 'name_asc', 'status' => $status ?? 'all']) }}" class="block px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ ($sort ?? 'default') === 'name_asc' ? 'text-emerald-700 bg-emerald-50' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }}">{{ __('Nama (A-Z)') }}</a>
                                            <a href="{{ route('admin.drivers', ['sort' => 'name_desc', 'status' => $status ?? 'all']) }}" class="block px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ ($sort ?? 'default') === 'name_desc' ? 'text-emerald-700 bg-emerald-50' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }}">{{ __('Nama (Z-A)') }}</a>
                                            <a href="{{ route('admin.drivers', ['sort' => 'date_desc', 'status' => $status ?? 'all']) }}" class="block px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ ($sort ?? 'default') === 'date_desc' ? 'text-emerald-700 bg-emerald-50' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }}">{{ __('Terbaru Bergabung') }}</a>
                                            <a href="{{ route('admin.drivers', ['sort' => 'date_asc', 'status' => $status ?? 'all']) }}" class="block px-3 py-2 text-sm font-medium transition-colors rounded-lg {{ ($sort ?? 'default') === 'date_asc' ? 'text-emerald-700 bg-emerald-50' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }}">{{ __('Terlama Bergabung') }}</a>
                                        </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        
                        <div class="space-y-4">
                            @forelse($drivers as $driver)
                                <div x-data="{ showDetailModal: false, zoomKTP: false, zoomSIM: false, zoomSTNK: false, zoomSKCK: false }" 
                                     @click="if(!$event.target.closest('form')) showDetailModal = true"
                                     class="p-4 bg-white/40 rounded-2xl border border-white/60 flex flex-col sm:flex-row gap-4 sm:items-center justify-between hover:bg-white/60 transition-all shadow-sm cursor-pointer hover:shadow-md hover:-translate-y-0.5 group">
                                    <div class="flex items-center gap-4">
                                        @if($driver->avatar)
                                            <div class="w-12 h-12 shrink-0 rounded-xl overflow-hidden group-hover:scale-110 transition-transform shadow-sm border border-emerald-100">
                                                <img src="{{ asset('storage/' . $driver->avatar) }}" alt="{{ $driver->name }}" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-12 h-12 shrink-0 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm border border-emerald-100">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-bold text-secondary text-lg group-hover:text-primary transition-colors">{{ $driver->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $driver->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap sm:flex-nowrap gap-4 items-center sm:justify-end border-t sm:border-t-0 border-gray-100 pt-3 sm:pt-0">
                                        <div class="flex flex-col sm:text-center border-r border-gray-200 pr-4 sm:px-4">
                                            <span class="text-[10px] text-gray-400 font-bold mb-0.5 uppercase tracking-wider">{{ __('Pickups') }}</span>
                                            <span class="text-xl font-bold text-emerald-600">{{ $driver->completed_jobs ?? 0 }}</span>
                                        </div>
                                        <div class="flex flex-col sm:text-center sm:border-r border-gray-200 pr-4 sm:px-4">
                                            <span class="text-[10px] text-gray-400 font-bold mb-0.5 uppercase tracking-wider">{{ __('Weight') }}</span>
                                            <span class="text-xl font-bold text-emerald-600">{{ number_format($driver->collected_weight ?? 0, 1) }}<span class="text-sm font-medium text-gray-500 ml-1">kg</span></span>
                                        </div>
                                        <div class="sm:text-right w-full sm:w-auto mt-2 sm:mt-0 flex flex-row sm:flex-col items-center sm:items-end justify-between">
                                            @if($driver->driver_status === 'online')
                                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> {{ __('Online') }}</span>
                                                <p class="text-[10px] font-medium text-emerald-600 mt-1.5">{{ __('Sedang Aktif') }}</p>
                                            @else
                                                <span class="px-3 py-1 bg-gray-100 text-gray-500 border border-gray-200 text-xs font-bold rounded-full">{{ __('Offline') }}</span>
                                                <p class="text-[10px] font-medium text-gray-400 mt-1.5" title="{{ $driver->updated_at->format('d M Y, H:i') }}">{{ __('Terakhir online: ') }} {{ $driver->updated_at->diffForHumans() }}</p>
                                            @endif
                                            
                                            <div class="flex flex-col items-end gap-2 mt-1">
                                                <p class="text-[10px] font-medium text-gray-400">{{ __('Joined') }} {{ $driver->created_at->format('d M Y') }}</p>


                                                    <!-- Detail & Revoke Modal -->
                                                    <template x-teleport="body">
                                                        <div x-show="showDetailModal" style="display: none;" class="fixed inset-0 z-[120] flex items-center justify-center p-4">
                                                            <div x-show="showDetailModal" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-md" @click="showDetailModal = false"></div>
                                                            <div x-show="showDetailModal" 
                                                                 x-transition:enter="transition ease-out duration-300"
                                                                 x-transition:enter-start="opacity-0 translate-y-8 scale-90"
                                                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                                 x-transition:leave="transition ease-in duration-200"
                                                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                                                 x-transition:leave-end="opacity-0 translate-y-8 scale-90"
                                                                 class="relative z-10 bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto overflow-x-hidden flex flex-col">
                                                                
                                                                <div class="sticky top-0 bg-white/80 backdrop-blur-md border-b border-gray-100 p-6 flex items-center justify-between z-20">
                                                                    <div class="flex items-center gap-4">
                                                                        <div class="w-12 h-12 bg-primary/10 text-primary rounded-xl flex items-center justify-center">
                                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                                        </div>
                                                                        <div>
                                                                            <h3 class="text-xl font-bold text-gray-900">{{ $driver->name }}</h3>
                                                                            <p class="text-sm text-gray-500">{{ $driver->email }}</p>
                                                                        </div>
                                                                    </div>
                                                                    <button @click="showDetailModal = false" class="p-2 text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-full transition-colors">
                                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                    </button>
                                                                </div>
                                                                
                                                                <div class="p-6">
                                                                    @if($driver->driverApplication)
                                                                        <div class="bg-gray-50 rounded-2xl p-6 mb-8 border border-gray-100">
                                                                            <h4 class="font-bold text-secondary mb-4">{{ __('Data Kendaraan & Identitas') }}</h4>
                                                                            <div class="grid grid-cols-2 gap-4 mb-6">
                                                                                <div>
                                                                                    <p class="text-xs text-gray-500 font-bold uppercase mb-1">{{ __('Nomor Induk Kependudukan (NIK)') }}</p>
                                                                                    <p class="font-mono text-gray-900 bg-white px-3 py-1.5 rounded-lg border border-gray-200 inline-block shadow-sm">{{ $driver->driverApplication->nik }}</p>
                                                                                </div>
                                                                                <div>
                                                                                    <p class="text-xs text-gray-500 font-bold uppercase mb-1">{{ __('Nomor Polisi Kendaraan') }}</p>
                                                                                    <p class="font-mono text-gray-900 bg-white px-3 py-1.5 rounded-lg border border-gray-200 inline-block shadow-sm">{{ $driver->driverApplication->vehicle_plate }}</p>
                                                                                </div>
                                                                            </div>

                                                                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                                                                                <!-- KTP -->
                                                                                <div>
                                                                                    <p class="text-xs font-bold text-gray-500 mb-2 uppercase">{{ __('KTP') }}</p>
                                                                                    <div @click="zoomKTP = true" class="cursor-pointer w-full h-32 bg-gray-200 rounded-xl overflow-hidden border border-gray-300 hover:ring-4 ring-primary/30 transition-all shadow-sm">
                                                                                        <img src="{{ Storage::url($driver->driverApplication->ktp_photo) }}" class="w-full h-full object-cover" alt="KTP">
                                                                                    </div>
                                                                                </div>
                                                                                <!-- SIM -->
                                                                                <div>
                                                                                    <p class="text-xs font-bold text-gray-500 mb-2 uppercase">{{ __('SIM C') }}</p>
                                                                                    <div @click="zoomSIM = true" class="cursor-pointer w-full h-32 bg-gray-200 rounded-xl overflow-hidden border border-gray-300 hover:ring-4 ring-primary/30 transition-all shadow-sm">
                                                                                        <img src="{{ Storage::url($driver->driverApplication->sim_photo) }}" class="w-full h-full object-cover" alt="SIM">
                                                                                    </div>
                                                                                </div>
                                                                                <!-- STNK -->
                                                                                <div>
                                                                                    <p class="text-xs font-bold text-gray-500 mb-2 uppercase">{{ __('STNK') }}</p>
                                                                                    <div @click="zoomSTNK = true" class="cursor-pointer w-full h-32 bg-gray-200 rounded-xl overflow-hidden border border-gray-300 hover:ring-4 ring-primary/30 transition-all shadow-sm">
                                                                                        <img src="{{ Storage::url($driver->driverApplication->stnk_photo) }}" class="w-full h-full object-cover" alt="STNK">
                                                                                    </div>
                                                                                </div>
                                                                                <!-- SKCK -->
                                                                                <div>
                                                                                    <p class="text-xs font-bold text-gray-500 mb-2 uppercase">{{ __('SKCK') }}</p>
                                                                                    <div @click="zoomSKCK = true" class="cursor-pointer w-full h-32 bg-gray-200 rounded-xl overflow-hidden border border-gray-300 hover:ring-4 ring-primary/30 transition-all shadow-sm">
                                                                                        <img src="{{ Storage::url($driver->driverApplication->skck_photo) }}" class="w-full h-full object-cover" alt="SKCK">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div class="bg-gray-50 rounded-2xl p-8 mb-8 border border-gray-100 text-center">
                                                                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                                            <p class="text-gray-500">{{ __('Data pendaftaran tidak ditemukan atau sudah dihapus.') }}</p>
                                                                        </div>
                                                                    @endif

                                                                    <div class="bg-red-50 border border-red-100 rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-6">
                                                                        <div class="text-center sm:text-left">
                                                                            <h4 class="font-bold text-red-800 mb-1">{{ __('Berhentikan Mitra Driver') }}</h4>
                                                                            <p class="text-sm text-red-600">{{ __('Tindakan ini akan mengembalikan status akun menjadi User biasa dan menghapus permanen seluruh data pendaftarannya di atas.') }}</p>
                                                                        </div>
                                                                        <form action="{{ route('admin.drivers.revoke', $driver->id) }}" method="POST" class="shrink-0 w-full sm:w-auto" onsubmit="return confirm('{{ __('Anda yakin ingin memberhentikan driver ini secara permanen?') }}');">
                                                                            @csrf
                                                                            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl transition-colors shadow-lg shadow-red-500/30 flex items-center justify-center gap-2">
                                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path></svg>
                                                                                {{ __('Revoke Access') }}
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Zoom Overlays for Document Images -->
                                                            @if($driver->driverApplication)
                                                                <!-- Zoom KTP -->
                                                                <div x-show="zoomKTP" style="display: none;" class="fixed inset-0 z-[130] flex items-center justify-center p-4">
                                                                    <div x-show="zoomKTP" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-xl" @click="zoomKTP = false"></div>
                                                                    <div x-show="zoomKTP" x-transition class="relative z-10 max-w-5xl w-full flex flex-col items-center justify-center">
                                                                        <button @click="zoomKTP = false" class="absolute -top-12 right-0 sm:-right-8 p-2 text-white/50 hover:text-white transition-colors">
                                                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                        </button>
                                                                        <img src="{{ Storage::url($driver->driverApplication->ktp_photo) }}" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10">
                                                                    </div>
                                                                </div>
                                                                <!-- Zoom SIM -->
                                                                <div x-show="zoomSIM" style="display: none;" class="fixed inset-0 z-[130] flex items-center justify-center p-4">
                                                                    <div x-show="zoomSIM" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-xl" @click="zoomSIM = false"></div>
                                                                    <div x-show="zoomSIM" x-transition class="relative z-10 max-w-5xl w-full flex flex-col items-center justify-center">
                                                                        <button @click="zoomSIM = false" class="absolute -top-12 right-0 sm:-right-8 p-2 text-white/50 hover:text-white transition-colors">
                                                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                        </button>
                                                                        <img src="{{ Storage::url($driver->driverApplication->sim_photo) }}" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10">
                                                                    </div>
                                                                </div>
                                                                <!-- Zoom STNK -->
                                                                <div x-show="zoomSTNK" style="display: none;" class="fixed inset-0 z-[130] flex items-center justify-center p-4">
                                                                    <div x-show="zoomSTNK" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-xl" @click="zoomSTNK = false"></div>
                                                                    <div x-show="zoomSTNK" x-transition class="relative z-10 max-w-5xl w-full flex flex-col items-center justify-center">
                                                                        <button @click="zoomSTNK = false" class="absolute -top-12 right-0 sm:-right-8 p-2 text-white/50 hover:text-white transition-colors">
                                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                        </button>
                                                                        <img src="{{ Storage::url($driver->driverApplication->stnk_photo) }}" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10">
                                                                    </div>
                                                                </div>
                                                                <!-- Zoom SKCK -->
                                                                <div x-show="zoomSKCK" style="display: none;" class="fixed inset-0 z-[130] flex items-center justify-center p-4">
                                                                    <div x-show="zoomSKCK" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-xl" @click="zoomSKCK = false"></div>
                                                                    <div x-show="zoomSKCK" x-transition class="relative z-10 max-w-5xl w-full flex flex-col items-center justify-center">
                                                                        <button @click="zoomSKCK = false" class="absolute -top-12 right-0 sm:-right-8 p-2 text-white/50 hover:text-white transition-colors">
                                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                                        </button>
                                                                        <img src="{{ Storage::url($driver->driverApplication->skck_photo) }}" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain border border-white/10">
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
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
