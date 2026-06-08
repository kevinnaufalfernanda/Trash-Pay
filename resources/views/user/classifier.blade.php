<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Waste Classifier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100" x-data="classifier()">
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-emerald-100 text-primary rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Identify Your Waste</h3>
                    <p class="text-gray-500 mb-8">Upload a photo of your waste to instantly know its category using our Smart AI.</p>

                    <!-- Upload Area -->
                    <div x-show="!processing && !result" class="max-w-md mx-auto">
                        <label class="flex flex-col items-center px-4 py-6 bg-white rounded-xl shadow-md tracking-wide uppercase border border-blue cursor-pointer hover:bg-emerald-50 hover:text-primary text-emerald-500 ease-linear transition-all duration-150 border-2 border-dashed border-emerald-300">
                            <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                            </svg>
                            <span class="mt-2 text-base leading-normal">Select a photo</span>
                            <input type='file' class="hidden" accept="image/*" @change="handleUpload" />
                        </label>
                    </div>

                    <!-- Processing State -->
                    <div x-show="processing" class="py-12" style="display: none;">
                        <svg class="animate-spin h-12 w-12 text-primary mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <h4 class="text-xl font-semibold text-gray-700 animate-pulse">Processing with AI...</h4>
                        <p class="text-gray-500 text-sm mt-2">Analyzing shape, texture, and materials.</p>
                    </div>

                    <!-- Result State -->
                    <div x-show="result" class="py-8" style="display: none;">
                        <div class="inline-block p-4 bg-emerald-100 rounded-full mb-4">
                            <span class="text-4xl">✨</span>
                        </div>
                        <h4 class="text-2xl font-bold text-secondary mb-2">Category Found!</h4>
                        <div class="text-3xl font-bold text-primary mb-6" x-text="result"></div>
                        
                        <div class="flex justify-center gap-4">
                            <button @click="reset" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-full font-semibold hover:bg-gray-300 transition-colors">Scan Another</button>
                            <a href="{{ route('user.pickup') }}" class="px-6 py-2 bg-primary text-white rounded-full font-semibold hover:bg-emerald-600 transition-colors shadow-md">Request Pickup</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function classifier() {
            return {
                processing: false,
                result: null,
                handleUpload(e) {
                    if(e.target.files.length === 0) return;
                    
                    this.processing = true;
                    this.result = null;

                    // Simulate API delay
                    setTimeout(() => {
                        const categories = ['Plastic PET', 'Cardboard', 'Paper'];
                        this.result = categories[Math.floor(Math.random() * categories.length)];
                        this.processing = false;
                    }, 2500);
                },
                reset() {
                    this.result = null;
                    this.processing = false;
                }
            }
        }
    </script>
</x-app-layout>
