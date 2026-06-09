<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-semibold text-2xl text-secondary leading-tight">
            {{ __('Order History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ mainTab: 'pesanan' }">
                <!-- Top Level Tabs -->
                <div x-data="{
                        currentLine: 'pesanan',
                        lineStyle: { opacity: 0 },
                        lineColor: 'bg-primary',
                        updateLine(tab) {
                            this.currentLine = tab;
                            this.lineColor = tab === 'penukaran' ? 'bg-amber-500' : 'bg-primary';
                            let el = this.$refs['tab_' + tab];
                            if (el) {
                                this.lineStyle = {
                                    left: el.offsetLeft + 'px',
                                    width: el.offsetWidth + 'px',
                                    opacity: 1
                                };
                            }
                        },
                        init() {
                            setTimeout(() => this.updateLine(this.mainTab), 50);
                            window.addEventListener('resize', () => this.updateLine(this.mainTab));
                            this.$watch('mainTab', value => this.updateLine(value));
                        }
                    }" 
                    @mouseleave="updateLine(mainTab)"
                    class="flex flex-wrap gap-x-8 gap-y-4 mb-8 border-b-2 border-gray-100 relative h-auto sm:h-12">
                    
                    <!-- Sliding Bottom Line -->
                    <div class="absolute bottom-[-2px] h-1 rounded-t-md transition-all duration-300 ease-out z-10"
                         :class="lineColor"
                         :style="lineStyle"></div>

                    <button @click="mainTab = 'pesanan'" 
                            x-ref="tab_pesanan"
                            @mouseenter="updateLine('pesanan')"
                            class="pb-3 text-sm font-bold flex items-center gap-2 transition-colors duration-200 focus:outline-none whitespace-nowrap"
                            :class="currentLine === 'pesanan' ? 'text-primary' : 'text-gray-500 hover:text-gray-700'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg> 
                        {{ __('Order History') }}
                    </button>
                    
                    <button @click="mainTab = 'penukaran'" 
                            x-ref="tab_penukaran"
                            @mouseenter="updateLine('penukaran')"
                            class="pb-3 text-sm font-bold flex items-center gap-2 transition-colors duration-200 focus:outline-none whitespace-nowrap"
                            :class="currentLine === 'penukaran' ? 'text-amber-500' : 'text-gray-500 hover:text-gray-700'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg> 
                        {{ __('Redemption History') }}
                    </button>
                </div>

                <!-- Pesanan Section -->
                <div x-show="mainTab === 'pesanan'" x-transition.opacity.duration.300ms>
                    <div x-data="{ tab: 'active' }">
                <div class="glass-panel rounded-3xl p-8 relative overflow-hidden shadow-sm">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-400/10 rounded-full blur-3xl -z-10"></div>
                    <h3 class="text-3xl font-serif font-bold mb-8 text-secondary flex items-center justify-between">
                        <span class="flex items-center gap-3">
                            <span class="p-3 bg-emerald-100 text-emerald-600 rounded-2xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </span> 
                            {{ __('Your Orders') }}
                        </span>
                    </h3>

                    <!-- Tabs -->
                    <div x-data="{
                            currentSub: 'active',
                            subLineStyle: { opacity: 0 },
                            updateSubLine(subTab) {
                                this.currentSub = subTab;
                                let el = this.$refs['subtab_' + subTab];
                                if (el) {
                                    this.subLineStyle = {
                                        left: el.offsetLeft + 'px',
                                        width: el.offsetWidth + 'px',
                                        opacity: 1
                                    };
                                }
                            },
                            init() {
                                setTimeout(() => this.updateSubLine(this.tab), 50);
                                window.addEventListener('resize', () => this.updateSubLine(this.tab));
                                this.$watch('tab', value => this.updateSubLine(value));
                            }
                        }"
                        @mouseleave="updateSubLine(tab)"
                        class="flex mb-8 border-b-2 border-gray-100 relative h-12">
                        
                        <!-- Sliding Bottom Line -->
                        <div class="absolute bottom-[-2px] h-1 bg-primary rounded-t-md transition-all duration-300 ease-out z-10"
                             :style="subLineStyle"></div>

                        <button @click="tab = 'active'" 
                                x-ref="subtab_active"
                                @mouseenter="updateSubLine('active')"
                                class="w-1/2 pb-3 font-bold text-sm transition-colors duration-200 focus:outline-none text-center"
                                :class="currentSub === 'active' ? 'text-primary' : 'text-gray-500 hover:text-gray-700'">
                            {{ __('Active') }}
                        </button>
                        
                        <button @click="tab = 'completed'" 
                                x-ref="subtab_completed"
                                @mouseenter="updateSubLine('completed')"
                                class="w-1/2 pb-3 font-bold text-sm transition-colors duration-200 focus:outline-none text-center"
                                :class="currentSub === 'completed' ? 'text-primary' : 'text-gray-500 hover:text-gray-700'">
                            {{ __('History') }}
                        </button>
                    </div>

                    <!-- Active Tab -->
                    <div x-show="tab === 'active'" x-transition.opacity.duration.300ms>
                        @php $activePickupsList = $pickups->whereIn('status', ['pending', 'on-the-way']); @endphp
                        @if($activePickupsList->isEmpty())
                            <div class="text-center py-16">
                                <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium text-lg">{{ __('No active pickups.') }}</p>
                                <p class="text-sm text-gray-400 mt-2">{{ __("Let's start collecting waste and request a pickup!") }}</p>
                                <a href="{{ route('user.pickup') }}" class="mt-6 inline-block px-6 py-2.5 bg-primary text-white font-bold rounded-full hover:bg-emerald-600 transition-all duration-200 active:scale-95 hover:scale-105 shadow-md hover:shadow-lg">{{ __('Request Pickup') }}</a>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($activePickupsList as $pickup)
                                    @include('user.partials.pickup-card', ['pickup' => $pickup])
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Completed Tab -->
                    <div x-show="tab === 'completed'" style="display: none;" x-transition.opacity.duration.300ms>
                        @php $completedPickupsList = $pickups->whereIn('status', ['completed', 'rejected', 'cancelled']); @endphp
                        @if($completedPickupsList->isEmpty())
                            <div class="text-center py-16">
                                <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium text-lg">{{ __('No pickup history yet.') }}</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($completedPickupsList as $pickup)
                                    @include('user.partials.pickup-card', ['pickup' => $pickup])
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                </div>
            </div>

            <!-- Penukaran Section -->
                <div x-show="mainTab === 'penukaran'" style="display: none;" x-transition.opacity.duration.300ms>
                    <div class="glass-panel rounded-3xl p-8 relative overflow-hidden shadow-sm">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-400/10 rounded-full blur-3xl -z-10"></div>
                        <h3 class="text-3xl font-serif font-bold mb-8 text-secondary flex items-center justify-between">
                            <span class="flex items-center gap-3">
                                <span class="p-3 bg-amber-100 text-amber-600 rounded-2xl">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
                                </span> 
                                {{ __('Coin Redemption History') }}
                            </span>
                        </h3>

                        @if($redemptions->isEmpty())
                            <div class="text-center py-16">
                                <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium text-lg">{{ __('No redemption history yet.') }}</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($redemptions as $redemption)
                                    @include('partials.redemption-card', ['redemption' => $redemption])
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
