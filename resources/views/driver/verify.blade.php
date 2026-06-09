<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-secondary leading-tight">
            {{ __('Input Real Weight — Pickup #') }}{{ $pickup->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Customer Info --}}
            <div class="glass-panel rounded-3xl p-6 mb-6">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-teal-50 text-emerald-600 rounded-2xl flex items-center justify-center font-bold text-2xl border border-emerald-200/50 shadow-sm">
                        {{ substr($pickup->user->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-bold text-secondary text-lg">{{ $pickup->user->name }}</div>
                        <div class="text-sm text-gray-500">Pickup #{{ $pickup->id }} • {{ __('Est. weight:') }} {{ $pickup->total_weight ?? 'N/A' }} kg</div>
                    </div>
                    <div class="ml-auto">
                        <span class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-xs font-bold uppercase border-2 border-blue-200">{{ $pickup->status }}</span>
                    </div>
                </div>
            </div>

            {{-- Weight Verification Form --}}
            <div class="glass-panel rounded-3xl p-10 relative overflow-hidden"
                 x-data="{
                     weight: 0,
                     categoryId: '',
                     categories: {{ Js::from($categories->map(fn($c) => ['id' => $c->id, 'price' => $c->price_per_kg, 'name' => __($c->name)])) }},
                     get selectedCategory() {
                         return this.categories.find(c => c.id == this.categoryId);
                     },
                     get estimatedCoins() {
                         if (this.selectedCategory && this.weight > 0) {
                             return Math.round(this.weight * this.selectedCategory.price);
                         }
                         return 0;
                     }
                 }">

                <h3 class="text-3xl font-serif font-bold mb-2 text-secondary tracking-tight">{{ __('Input Real Weight') }}</h3>
                <p class="text-gray-500 text-sm mb-8 font-medium">{{ __('Weigh the waste, select category, and coins will be automatically transferred to the user account.') }}</p>

                <form action="{{ route('driver.verify.store', $pickup->id) }}" method="POST">
                    @csrf

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-4">{{ __('Verified Category') }}</label>
                        <div class="flex flex-wrap justify-center gap-4">
                            @foreach($categories as $category)
                                <div class="relative group w-[calc(50%-0.5rem)] md:w-[calc((100%-2rem)/3)]">
                                    <input type="radio" name="category_id" id="cat_{{ $category->id }}" value="{{ $category->id }}" x-model="categoryId" class="peer hidden" required>
                                    <label for="cat_{{ $category->id }}" class="block text-center cursor-pointer bg-white/60 border border-gray-200 shadow-sm rounded-2xl p-4 hover:border-emerald-300 peer-checked:border-primary peer-checked:border-2 peer-checked:bg-emerald-50/80 peer-checked:shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg active:scale-95 h-full flex flex-col items-center justify-center">
                                        <div class="mb-2 opacity-90 group-hover:scale-110 transition-transform flex justify-center text-emerald-600">
                                            <x-category-icon :category="$category" class="w-10 h-10" />
                                        </div>
                                        <div class="font-bold text-sm text-secondary">{{ __($category->name) }}</div>
                                        <div class="text-xs text-primary mt-1 font-bold">{{ number_format($category->price_per_kg, 0, ',', '.') }} {{ __('Coins/kg') }}</div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-10">
                        <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('Final Weight (kg)') }}</label>
                        <input type="number" step="0.1" name="weight" min="0.1" x-model="weight"
                               class="w-full rounded-2xl border border-gray-200 shadow-sm focus:border-primary focus:ring-primary focus:ring-4 focus:ring-primary/20 text-4xl font-bold py-6 text-center bg-white/70 transition-all"
                               placeholder="0.0" required>
                        <p class="text-xs text-gray-400 mt-2 text-center">{{ __('Use an accurate scale.') }}</p>
                    </div>

                    {{-- Live Coin Calculation --}}
                    <div class="bg-amber-50/50 backdrop-blur-sm rounded-3xl p-8 mb-10 border border-amber-200 text-center relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-amber-100/50 rounded-bl-full -z-10"></div>
                        <div class="text-sm font-bold tracking-widest text-amber-800 uppercase mb-4">{{ __('Coins to be transferred') }}</div>
                        <div class="text-6xl font-serif font-bold text-amber-600 mb-2 tracking-tighter" x-text="estimatedCoins.toLocaleString()"></div>
                        <div class="text-3xl drop-shadow-sm opacity-90 mb-2">🪙</div>
                        <div class="text-sm font-medium text-amber-700 mt-4 bg-white/50 inline-block px-4 py-2 rounded-full border border-amber-200/50">
                            {{ __('Formula:') }} <strong x-text="weight"></strong> kg ×
                            <strong x-text="selectedCategory ? selectedCategory.price.toLocaleString() : '0'"></strong> {{ __('Coins/kg') }}
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full py-5 bg-gradient-to-r from-primary to-emerald-500 text-white font-bold rounded-full btn-premium text-xl shadow-lg shadow-emerald-500/30 flex items-center justify-center gap-3 transition-transform hover:scale-[1.02] active:scale-95 group">
                        <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ __('Complete & Transfer Coins') }}
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
