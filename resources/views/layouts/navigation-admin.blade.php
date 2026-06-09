<nav x-data="{ open: false }" class="sticky top-0 z-50 glass-panel shadow-sm transition-all">
    <!-- Primary Navigation Menu -->
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 group">
                        <svg class="w-8 h-8 text-primary group-hover:-rotate-12 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"></path>
                            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"></path>
                        </svg>
                        <span class="font-serif font-bold text-2xl tracking-tight text-secondary">Trash-Pay.</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                @php
                    $activeRoute = '';
                    if(request()->routeIs('admin.dashboard')) $activeRoute = 'admin.dashboard';
                    if(request()->routeIs('admin.analytics')) $activeRoute = 'admin.analytics';
                    if(request()->routeIs('admin.pricing')) $activeRoute = 'admin.pricing';
                    if(request()->routeIs('admin.drivers')) $activeRoute = 'admin.drivers';
                    if(request()->routeIs('admin.payouts')) $activeRoute = 'admin.payouts';
                @endphp
                <div class="hidden sm:flex sm:ms-10 items-center">
                    <div x-data="{
                            activeTab: '{{ $activeRoute }}',
                            currentLine: '{{ $activeRoute }}',
                            lineStyle: { opacity: 0 },
                            updateLine(tab) {
                                this.currentLine = tab;
                                if (!tab) {
                                    this.lineStyle = { opacity: 0 };
                                    return;
                                }
                                let el = this.$refs['nav_' + tab.replace('.', '_')];
                                if (el) {
                                    this.lineStyle = {
                                        left: el.offsetLeft + 'px',
                                        width: el.offsetWidth + 'px',
                                        opacity: 1
                                    };
                                } else {
                                    this.lineStyle = { opacity: 0 };
                                }
                            },
                            navigate(tab, url) {
                                this.activeTab = tab;
                                this.updateLine(tab);
                                setTimeout(() => {
                                    window.location.href = url;
                                }, 250);
                            },
                            init() {
                                setTimeout(() => this.updateLine(this.activeTab), 50);
                                window.addEventListener('resize', () => this.updateLine(this.activeTab));
                            }
                        }" 
                        @mouseleave="updateLine(activeTab)"
                        class="flex space-x-8 h-16 relative">
                        
                        <!-- Sliding Bottom Line -->
                        <div class="absolute bottom-0 h-1 bg-primary rounded-t-md transition-all duration-300 ease-out"
                             :style="lineStyle"></div>
                        
                        <a href="{{ route('admin.dashboard') }}" 
                           x-ref="nav_admin_dashboard"
                           @mouseenter="updateLine('admin.dashboard')"
                           @click.prevent="navigate('admin.dashboard', '{{ route('admin.dashboard') }}')"
                           class="inline-flex items-center px-1 pt-1 text-sm font-bold transition-colors duration-200"
                           :class="currentLine === 'admin.dashboard' ? 'text-primary' : 'text-gray-500 hover:text-gray-700'">
                            {{ __('Dashboard') }}
                        </a>
                        
                        <a href="{{ route('admin.analytics') }}" 
                           x-ref="nav_admin_analytics"
                           @mouseenter="updateLine('admin.analytics')"
                           @click.prevent="navigate('admin.analytics', '{{ route('admin.analytics') }}')"
                           class="inline-flex items-center px-1 pt-1 text-sm font-bold transition-colors duration-200"
                           :class="currentLine === 'admin.analytics' ? 'text-primary' : 'text-gray-500 hover:text-gray-700'">
                            {{ __('Analytics') }}
                        </a>
                        
                        <a href="{{ route('admin.pricing') }}" 
                           x-ref="nav_admin_pricing"
                           @mouseenter="updateLine('admin.pricing')"
                           @click.prevent="navigate('admin.pricing', '{{ route('admin.pricing') }}')"
                           class="inline-flex items-center px-1 pt-1 text-sm font-bold transition-colors duration-200"
                           :class="currentLine === 'admin.pricing' ? 'text-primary' : 'text-gray-500 hover:text-gray-700'">
                            {{ __('Pricing Control') }}
                        </a>
                        
                        <a href="{{ route('admin.drivers') }}" 
                           x-ref="nav_admin_drivers"
                           @mouseenter="updateLine('admin.drivers')"
                           @click.prevent="navigate('admin.drivers', '{{ route('admin.drivers') }}')"
                           class="inline-flex items-center px-1 pt-1 text-sm font-bold transition-colors duration-200"
                           :class="currentLine === 'admin.drivers' ? 'text-primary' : 'text-gray-500 hover:text-gray-700'">
                            {{ __('Eco-Drivers') }}
                        </a>
                        
                        <a href="{{ route('admin.payouts') }}" 
                           x-ref="nav_admin_payouts"
                           @mouseenter="updateLine('admin.payouts')"
                           @click.prevent="navigate('admin.payouts', '{{ route('admin.payouts') }}')"
                           class="inline-flex items-center px-1 pt-1 text-sm font-bold transition-colors duration-200"
                           :class="currentLine === 'admin.payouts' ? 'text-primary' : 'text-gray-500 hover:text-gray-700'">
                            {{ __('Payouts') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-4">
                <!-- Language Switcher -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 shadow-sm text-sm leading-4 font-bold rounded-full text-white bg-primary hover:bg-emerald-600 border border-emerald-500/50 focus:outline-none transition ease-in-out duration-150">
                            <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div class="font-sans">{{ app()->getLocale() == 'id' ? 'ID' : 'EN' }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('lang.switch', 'id')" class="font-sans font-medium text-gray-700 rounded-xl hover:bg-emerald-50 hover:text-primary transition-colors">
                            Bahasa Indonesia
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('lang.switch', 'en')" class="font-sans font-medium text-gray-700 rounded-xl hover:bg-emerald-50 hover:text-primary transition-colors">
                            English
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 shadow-sm text-sm leading-4 font-bold rounded-full text-white bg-primary hover:bg-emerald-600 border border-emerald-500/50 focus:outline-none transition ease-in-out duration-150">
                            <div class="font-sans">{{ Auth::user()->name }} (Admin)</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="font-sans font-medium text-gray-700">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="font-sans font-medium text-gray-700">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/90 backdrop-blur-md">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.analytics')" :active="request()->routeIs('admin.analytics')">
                {{ __('Analytics') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.pricing')" :active="request()->routeIs('admin.pricing')">
                {{ __('Pricing Control') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.drivers')" :active="request()->routeIs('admin.drivers')">
                {{ __('Eco-Drivers') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.payouts')" :active="request()->routeIs('admin.payouts')">
                {{ __('Payouts') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
