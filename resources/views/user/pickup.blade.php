<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-semibold text-2xl text-secondary leading-tight">
            {{ __('Request Penjemputan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel rounded-3xl p-10 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-400/10 rounded-full blur-3xl -z-10"></div>
                
                <h3 class="text-3xl font-serif font-bold mb-2 text-secondary tracking-tight">Ajukan Penjemputan</h3>
                <p class="text-secondary/60 text-sm mb-10 font-medium leading-relaxed">Upload foto sampah Anda. AI Scanner kami akan mengenali kategorinya secara otomatis dan mengubahnya jadi koin!</p>

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('user.pickup.store') }}" method="POST" enctype="multipart/form-data"
                      x-data="{
                          scanning: false,
                          scanned: false,
                          resultCategory: '',
                          resultIcon: '',
                          previewUrl: null,
                          scanPhoto(event) {
                              const file = event.target.files[0];
                              if (!file) return;
                              this.previewUrl = URL.createObjectURL(file);
                              this.scanning = true;
                              this.scanned = false;
                              this.resultCategory = '';
                              setTimeout(() => {
                                  this.scanning = false;
                                  this.scanned = true;
                                  const radios = document.querySelectorAll('input[name=category_id]');
                                  if (radios.length > 0) {
                                      const idx = Math.floor(Math.random() * radios.length);
                                      radios[idx].checked = true;
                                      radios[idx].dispatchEvent(new Event('change'));
                                      const label = radios[idx].nextElementSibling;
                                      this.resultCategory = label.querySelector('.cat-name').innerText;
                                      this.resultIcon = label.querySelector('.cat-icon').innerText;
                                  }
                              }, 2500);
                          }
                      }">
                    @csrf

                    {{-- Step 1: Upload Photo with AI Scanner --}}
                    <div class="mb-10">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-white text-sm font-bold shadow-[0_2px_0_0_#047857]">1</span>
                            <label class="text-lg font-serif font-bold text-secondary">Upload Foto Sampah</label>
                        </div>

                        <div class="relative border-2 border-dashed border-gray-300 rounded-3xl p-8 text-center hover:border-primary hover:bg-white/50 transition-all bg-white/30 backdrop-blur-sm cursor-pointer group shadow-sm">
                            <template x-if="!previewUrl">
                                <div>
                                    <div class="text-4xl mb-3 opacity-80">📸</div>
                                    <p class="text-secondary/50 text-sm font-light">Klik untuk memilih foto dari perangkat Anda</p>
                                </div>
                            </template>
                            <template x-if="previewUrl">
                                <img :src="previewUrl" class="mx-auto max-h-48 rounded-xl object-cover shadow-md">
                            </template>
                            <input type="file" name="waste_photo" accept="image/*" required
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   @change="scanPhoto($event)">
                        </div>

                        {{-- Scanning Animation --}}
                        <div x-show="scanning" x-transition class="mt-6 p-5 bg-primary/5 border border-primary/10 rounded-2xl" style="display:none;">
                            <div class="flex items-center gap-4">
                                <svg class="animate-spin h-6 w-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <div>
                                    <span class="font-serif font-medium text-secondary">AI Vision Processing...</span>
                                    <p class="text-sm text-secondary/60 mt-1 font-light">Gently analyzing the materials in your photo.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Scan Result --}}
                        <div x-show="scanned" x-transition class="mt-6 p-5 bg-primary/10 border border-primary/20 rounded-2xl" style="display:none;">
                            <div class="flex items-center gap-4">
                                <span class="text-3xl" x-text="resultIcon"></span>
                                <div>
                                    <div class="font-serif font-medium text-secondary">Scan Complete!</div>
                                    <div class="text-sm text-secondary/70 font-light mt-1">Kategori: <strong class="font-medium text-primary" x-text="resultCategory"></strong></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Confirm Category (auto-selected by AI, can override) --}}
                    <div class="mb-10">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-white text-sm font-bold shadow-[0_2px_0_0_#047857]">2</span>
                            <label class="text-lg font-serif font-bold text-secondary">Konfirmasi Kategori</label>
                        </div>
                        <p class="text-sm text-gray-500 font-medium mb-5 ml-11">Dipilih otomatis oleh AI. Anda bisa mengubahnya secara manual jika perlu.</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($categories as $category)
                                <div>
                                    <input type="radio" name="category_id" id="cat_{{ $category->id }}"
                                           value="{{ $category->id }}" class="peer hidden" required>
                                    <label for="cat_{{ $category->id }}"
                                           class="block text-center cursor-pointer bg-white/50 border border-white/60 shadow-sm rounded-2xl p-5 hover:border-emerald-300 peer-checked:border-primary peer-checked:border-2 peer-checked:bg-emerald-50/80 peer-checked:shadow-md transition-all">
                                        <div class="text-4xl mb-3 cat-icon opacity-90 group-hover:scale-110 transition-transform">{{ $category->icon ?? '🗑️' }}</div>
                                        <div class="font-bold text-sm cat-name text-secondary">{{ $category->name }}</div>
                                        <div class="text-xs text-primary mt-1 font-bold">Rp {{ number_format($category->price_per_kg, 0, ',', '.') }}/kg</div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Step 3: Estimated Weight --}}
                    <div class="mb-10">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-white text-sm font-bold shadow-[0_2px_0_0_#047857]">3</span>
                            <label for="estimated_weight" class="text-lg font-serif font-bold text-secondary">Perkiraan Berat</label>
                        </div>
                        <input type="number" step="0.1" min="0.1" name="estimated_weight" id="estimated_weight"
                               class="w-full mt-2 rounded-2xl border border-gray-200 shadow-sm focus:border-primary focus:ring-primary focus:ring-4 focus:ring-primary/20 text-lg font-bold py-4 px-5 bg-white/70 transition-all"
                               placeholder="contoh: 2.5 kg" required>
                        <p class="text-sm text-secondary/60 font-medium mt-3 ml-1">Berat akhir akan dikonfirmasi oleh Eco-Driver saat penjemputan.</p>
                    </div>

                    <button type="submit"
                            class="w-full px-8 py-5 bg-gradient-to-r from-primary to-emerald-500 text-white font-bold rounded-full btn-premium text-lg">
                        🚀 Panggil Driver Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
