@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-2xl border border-gray-200 shadow-sm focus:border-primary focus:ring-primary focus:ring-4 focus:ring-primary/20 text-base py-3 px-4 bg-white/70 transition-all font-medium placeholder-gray-400 text-gray-800']) }}>
