<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-secondary leading-tight">
            {{ __('Pricing Control') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-emerald-100 text-emerald-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="glass-panel rounded-3xl p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-amber-400/10 rounded-full blur-3xl -z-10"></div>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-secondary">{{ __('Manage Waste Prices') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('Update prices dynamically based on market rate.') }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/50 border-b border-white/60">
                                <th class="py-4 px-6 font-semibold text-gray-600">{{ __('Waste Category') }}</th>
                                <th class="py-4 px-6 font-semibold text-gray-600 text-right">{{ __('Reward (Coins/kg)') }}</th>
                                <th class="py-4 px-6 font-semibold text-gray-600 text-center">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr class="border-b border-white/40 hover:bg-white/60 transition-colors">
                                    <td class="py-4 px-6 flex items-center gap-3">
                                        <x-category-icon :category="$category" class="w-8 h-8 text-emerald-600" />
                                        <span class="font-bold text-secondary">{{ __($category->name) }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold text-amber-500 text-lg">
                                        {{ number_format($category->price_per_kg, 0, ',', '.') }} <svg class="w-5 h-5 inline-block text-amber-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
                                    </td>
                                    <td class="py-4 px-6">
                                        <form action="{{ route('admin.pricing.update', $category->id) }}" method="POST" class="flex gap-2 justify-center">
                                            @csrf
                                            <input type="number" name="price_per_kg" value="{{ $category->price_per_kg }}" class="w-32 rounded-xl border-gray-300 shadow-sm focus:border-primary focus:ring-primary text-sm" required>
                                            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-primary to-emerald-500 text-white font-semibold rounded-xl hover:shadow-lg shadow-emerald-500/30 hover:-translate-y-0.5 transition-all text-sm">
                                                {{ __('Update') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($categories->isEmpty())
                        <div class="text-center py-8 text-gray-500 text-sm">{{ __('No categories found. Please run seeder.') }}</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
