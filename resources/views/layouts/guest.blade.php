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
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-mesh">
        <x-auth-session-status :status="session('status')" />
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative">
            <!-- Language Switcher -->
            <div class="absolute top-6 right-6 z-50">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded-full text-white bg-primary hover:bg-emerald-600 focus:outline-none transition ease-in-out duration-150 shadow-sm shadow-emerald-500/30 gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>{{ app()->getLocale() == 'id' ? 'ID' : 'EN' }}</span>
                            <svg class="fill-current h-4 w-4 opacity-70" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('lang.switch', 'id')" class="font-sans font-medium text-gray-700 hover:text-primary hover:bg-emerald-50 rounded-xl transition-colors">
                            Bahasa Indonesia
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('lang.switch', 'en')" class="font-sans font-medium text-gray-700 hover:text-primary hover:bg-emerald-50 rounded-xl transition-colors">
                            English
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>
            <div class="mb-4">
                <a href="/" class="flex flex-col items-center gap-2 group">
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center border-2 border-emerald-100 group-hover:bg-emerald-100 transition-colors shadow-sm">
                        <svg class="w-10 h-10 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"></path>
                            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"></path>
                        </svg>
                    </div>
                    <span class="font-serif font-bold text-3xl tracking-tight text-secondary mt-2">Trash-Pay.</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white/90 backdrop-blur-md shadow-xl overflow-hidden sm:rounded-3xl border border-white">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
