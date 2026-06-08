<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Trash-Pay Malang</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400..800&family=Plus+Jakarta+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
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

            .glass-panel {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.5);
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .glass-panel:hover {
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
                transform: translateY(-2px);
            }

            .btn-premium {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.39);
            }
            .btn-premium:hover {
                box-shadow: 0 6px 20px rgba(16, 185, 129, 0.23);
                transform: translateY(-1px);
            }

            .animate-fade-in-up {
                animation: fadeInUp 1s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body class="antialiased bg-mesh text-secondary font-sans overflow-x-hidden selection:bg-primary/20 selection:text-secondary">
        <!-- Navbar -->
        <nav class="fixed w-full z-50 glass-panel border-b-0 top-0 transition-all rounded-b-3xl">
            <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex items-center gap-2">
                        <span class="font-serif font-bold text-2xl tracking-tight text-secondary">Trash-Pay.</span>
                    </div>
                    <div class="flex items-center gap-6">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-secondary hover:text-primary transition-colors">{{ __('Dashboard') }}</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-secondary hover:text-primary transition-colors">{{ __('Log in') }}</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-full btn-premium">{{ __('Get Started') }}</a>
                                @endif
                            @endauth
                        @endif

                        <!-- Language Switcher -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-4 py-2 shadow-sm text-sm leading-4 font-bold rounded-full text-white bg-primary hover:bg-emerald-600 border border-emerald-500/50 focus:outline-none transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <div class="font-sans">{{ app()->getLocale() == 'id' ? 'Indonesia' : 'English' }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('lang.switch', 'id')" class="font-sans font-medium text-gray-700">Indonesia</x-dropdown-link>
                                <x-dropdown-link :href="route('lang.switch', 'en')" class="font-sans font-medium text-gray-700">English</x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative pt-36 pb-24 lg:pt-48 lg:pb-32 overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1497436072909-60f360e1d4b1?auto=format&fit=crop&q=80&w=2070')] bg-cover bg-center opacity-[0.02]"></div>
            
            <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-fade-in-up">
                <span class="inline-block py-1.5 px-5 rounded-full bg-white/60 backdrop-blur-md text-secondary text-xs font-semibold tracking-widest uppercase mb-8 border border-white shadow-sm">
                    {{ __('Wellness for the Earth') }}
                </span>
                <h1 class="text-5xl md:text-7xl font-serif font-bold tracking-tight mb-6 leading-tight text-secondary">
                    {{ __('Turn your waste into') }} <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-teal-400">{{ __('digital balance.') }}</span>
                </h1>
                <p class="mt-6 max-w-xl text-lg md:text-xl text-secondary/60 mx-auto mb-12 font-medium leading-relaxed">
                    {{ __('A seamless approach to Malang\'s circular economy. Classify waste gracefully, request pickups, and nurture your e-wallet.') }}
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-primary text-white rounded-full text-lg font-bold btn-premium">
                        {{ __('Join the Movement') }}
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-white/50 backdrop-blur-md text-secondary border border-white/50 shadow-sm rounded-full text-lg font-bold hover:bg-white/80 transition-colors">
                        {{ __('Partner with Us') }}
                    </a>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="py-24">
            <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="p-8 text-center glass-panel rounded-3xl">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-teal-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-emerald-500/30">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-serif font-bold mb-4 text-secondary">{{ __('Mindful Scanning') }}</h3>
                        <p class="text-secondary/60 font-medium leading-relaxed">{{ __('Let our AI gently identify your waste category and value with a single, calming snapshot.') }}</p>
                    </div>
                    <div class="p-8 text-center glass-panel rounded-3xl">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-indigo-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-blue-500/30">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </div>
                        <h3 class="text-2xl font-serif font-bold mb-4 text-secondary">{{ __('Seamless Pickup') }}</h3>
                        <p class="text-secondary/60 font-medium leading-relaxed">{{ __('Our dedicated eco-drivers will collect your sorted waste right from your sanctuary.') }}</p>
                    </div>
                    <div class="p-8 text-center glass-panel rounded-3xl">
                        <div class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 text-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-amber-500/30">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-serif font-bold mb-4 text-secondary">{{ __('Pure Returns') }}</h3>
                        <p class="text-secondary/60 font-medium leading-relaxed">{{ __('Exchange your digital balance into everyday value effortlessly and cleanly.') }}</p>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Footer -->
        <footer class="py-16 text-center">
            <p class="font-sans text-secondary/40 font-medium">&copy; {{ date('Y') }} Trash-Pay. {{ __('Healing the Earth, together.') }}</p>
        </footer>
    </body>
</html>
