<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400..800&family=Plus+Jakarta+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

        <style>
            [x-cloak] { display: none !important; }
        </style>
        <!-- Scripts & Styles via CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#10B981', // Emerald 500
                            secondary: '#0F172A', // Slate 900
                            accent: '#F59E0B', // Amber 500
                            light: '#F8FAFC', // Slate 50
                        },
                        fontFamily: {
                            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                            serif: ['"Outfit"', 'sans-serif'], // Digunakan untuk headings
                        }
                    }
                }
            }
        </script>
        <style>
            [x-cloak] { display: none !important; }
            
            /* Premium Modern Mesh Background */
            .bg-mesh {
                background-color: #f8fafc;
                background-image: 
                    radial-gradient(at 40% 20%, hsla(160, 100%, 74%, 0.15) 0px, transparent 50%),
                    radial-gradient(at 80% 0%, hsla(189, 100%, 56%, 0.15) 0px, transparent 50%),
                    radial-gradient(at 0% 50%, hsla(355, 100%, 93%, 0.2) 0px, transparent 50%),
                    radial-gradient(at 80% 50%, hsla(340, 100%, 76%, 0.15) 0px, transparent 50%),
                    radial-gradient(at 0% 100%, hsla(22, 100%, 77%, 0.15) 0px, transparent 50%),
                    radial-gradient(at 80% 100%, hsla(242, 100%, 70%, 0.15) 0px, transparent 50%),
                    radial-gradient(at 0% 0%, hsla(343, 100%, 76%, 0.1) 0px, transparent 50%);
                background-attachment: fixed;
            }

            /* Sleek Glass Panel */
            .glass-panel {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.5);
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .glass-panel:hover {
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
                transform: translateY(-2px);
            }

            /* Premium Button */
            .btn-premium {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.39);
            }
            .btn-premium:hover {
                box-shadow: 0 6px 20px rgba(16, 185, 129, 0.23);
                transform: translateY(-1px);
            }
            .btn-premium:active {
                transform: translateY(1px);
            }
        </style>
    </head>
    <body class="font-sans antialiased text-secondary selection:bg-primary/20 bg-mesh min-h-screen">
        
        <!-- Global Error Toast -->
        @php
            $hasAnyErrors = false;
            foreach ($errors->getBags() as $bag) {
                if ($bag->any()) {
                    $hasAnyErrors = true;
                    break;
                }
            }
        @endphp
        @if ($hasAnyErrors)
            <div x-data="{ show: false }" 
                 x-show="show"
                 x-init="setTimeout(() => show = true, 50); setTimeout(() => show = false, 5000)"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 -translate-y-20 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 -translate-y-20 scale-95"
                 class="fixed top-8 left-1/2 -translate-x-1/2 z-[9999] w-[90%] max-w-md bg-red-600 rounded-2xl shadow-2xl border border-red-400 p-5 flex items-start gap-4">
                <div class="flex-shrink-0 text-white mt-0.5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="flex-1 text-white">
                    <h3 class="font-bold text-lg mb-1">{{ __('An error occurred') }}</h3>
                    <ul class="text-sm font-medium text-red-100 mt-1">
                        @foreach ($errors->getBags() as $bag)
                            @foreach ($bag->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
                <button @click="show = false" class="text-red-200 hover:text-white transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        <div class="pb-20 sm:pb-0">
            @auth
                @include('layouts.navigation-' . auth()->user()->role)
            @else
                @include('layouts.navigation')
            @endauth

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/40 backdrop-blur-md border-b border-white/60 relative z-40">
                    <div class="max-w-[90rem] mx-auto py-5 px-4 sm:px-6 lg:px-8 font-serif">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="relative z-10" x-data="{ pageLoaded: false }" x-init="setTimeout(() => pageLoaded = true, 50)">
                <div x-cloak x-show="pageLoaded" 
                     x-transition:enter="transition ease-out duration-500" 
                     x-transition:enter-start="opacity-0 translate-y-4" 
                     x-transition:enter-end="opacity-100 translate-y-0">
                    {{ $slot }}
                </div>
            </main>

            <footer class="py-8 text-center text-secondary/40">
                <p class="font-sans text-sm font-medium">&copy; {{ date('Y') }} Trash-Pay. {{ __('Turn waste into fun!') }}</p>
            </footer>

            <!-- Bottom Navigation Bar (Mobile Only - Gamified Floating Nav) -->
            @auth
            <div class="fixed bottom-4 left-4 right-4 z-50 bg-white/90 backdrop-blur-md border-2 border-gray-100 rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] sm:hidden pb-safe">
                <div class="grid h-16 grid-cols-3 font-medium">
                    @if(auth()->user()->role === 'user')
                        <a href="{{ route('user.dashboard') }}" class="inline-flex flex-col items-center justify-center px-2 group {{ request()->routeIs('user.dashboard') ? 'text-primary' : 'text-gray-400' }}">
                            <div class="p-1.5 rounded-2xl {{ request()->routeIs('user.dashboard') ? 'bg-primary/10' : 'group-hover:bg-gray-50' }} transition-colors">
                                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                            </div>
                            <span class="text-[10px] font-bold">Home</span>
                        </a>
                        <a href="{{ route('user.pickup') }}" class="inline-flex flex-col items-center justify-center px-2 group">
                            <div class="relative -top-5 p-3 rounded-full bg-gradient-to-br from-primary to-emerald-500 text-white btn-premium shadow-lg shadow-emerald-500/30 border-4 border-white/50 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <span class="text-[10px] font-bold -mt-3 text-gray-500">Pickup</span>
                        </a>
                        <a href="{{ route('user.redeem') }}" class="inline-flex flex-col items-center justify-center px-2 group {{ request()->routeIs('user.redeem') ? 'text-accent' : 'text-gray-400' }}">
                            <div class="p-1.5 rounded-2xl {{ request()->routeIs('user.redeem') ? 'bg-accent/10' : 'group-hover:bg-gray-50' }} transition-colors">
                                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                            </div>
                            <span class="text-[10px] font-bold">Rewards</span>
                        </a>
                    @elseif(auth()->user()->role === 'driver')
                        <a href="{{ route('driver.dashboard') }}" class="inline-flex flex-col items-center justify-center px-2 group {{ request()->routeIs('driver.dashboard') ? 'text-primary' : 'text-gray-400' }}">
                            <div class="p-1.5 rounded-2xl {{ request()->routeIs('driver.dashboard') ? 'bg-primary/10' : 'group-hover:bg-gray-50' }} transition-colors">
                                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                            </div>
                            <span class="text-[10px] font-bold">Dashboard</span>
                        </a>
                        <a href="{{ route('driver.orders') }}" class="inline-flex flex-col items-center justify-center px-2 group">
                            <div class="relative -top-5 p-3 rounded-full bg-gradient-to-br from-primary to-emerald-500 text-white btn-premium shadow-lg shadow-emerald-500/30 border-4 border-white/50 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            </div>
                            <span class="text-[10px] font-bold -mt-3 text-gray-500">Order Pool</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="inline-flex flex-col items-center justify-center px-2 group {{ request()->routeIs('profile.edit') ? 'text-primary' : 'text-gray-400' }}">
                            <div class="p-1.5 rounded-2xl {{ request()->routeIs('profile.edit') ? 'bg-primary/10' : 'group-hover:bg-gray-50' }} transition-colors">
                                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                            </div>
                            <span class="text-[10px] font-bold">Profil</span>
                        </a>
                    @endif
                </div>
            </div>
            @endauth
        </div>
    </body>
</html>
