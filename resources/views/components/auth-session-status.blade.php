@props(['status'])

@if ($status)
    <div x-data="{ show: false }" 
         x-show="show"
         x-init="setTimeout(() => show = true, 50); setTimeout(() => show = false, 5000)"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 -translate-y-20 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-20 scale-95"
         class="fixed top-8 left-1/2 -translate-x-1/2 z-[9999] w-[90%] max-w-md bg-emerald-500 rounded-2xl shadow-2xl border border-emerald-400 p-4 flex items-center gap-3">
        <div class="flex-shrink-0 text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="flex-1 text-white font-medium text-center">
            {{ $status }}
        </div>
        <button @click="show = false" class="text-emerald-200 hover:text-white transition-colors p-1 flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
@endif
