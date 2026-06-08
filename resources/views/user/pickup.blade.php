<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-semibold text-2xl text-secondary leading-tight">
            {{ __('Request Pickup') }}
        </h2>
    </x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-panel rounded-3xl p-10 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-400/10 rounded-full blur-3xl -z-10"></div>
                
                <h3 class="text-3xl font-serif font-bold mb-2 text-secondary tracking-tight">{{ __('Request Pickup') }}</h3>
                <p class="text-secondary/60 text-sm mb-10 font-medium leading-relaxed">{{ __('Upload a photo of your waste. Our AI Scanner will automatically categorize it and turn it into coins!') }}</p>

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
                          },
                          addressText: '',
                          gettingLocation: false,
                          lat: '',
                          lon: '',
                          map: null,
                          marker: null,
                          initMap() {
                              // Default to Malang Center
                              this.map = L.map('pickup-map').setView([-7.95, 112.61], 13);
                              L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                  attribution: '&copy; OpenStreetMap contributors'
                              }).addTo(this.map);

                              var userIcon = L.icon({
                                  iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
                                  iconSize: [38, 38],
                                  iconAnchor: [19, 38]
                              });

                              this.marker = L.marker([-7.95, 112.61], {icon: userIcon, draggable: true}).addTo(this.map);

                              // Update coords when marker is dragged
                              this.marker.on('dragend', async (e) => {
                                  const pos = e.target.getLatLng();
                                  this.lat = pos.lat;
                                  this.lon = pos.lng;
                                  await this.reverseGeocode(pos.lat, pos.lng);
                              });

                              // Update coords when map is clicked
                              this.map.on('click', async (e) => {
                                  this.marker.setLatLng(e.latlng);
                                  this.lat = e.latlng.lat;
                                  this.lon = e.latlng.lng;
                                  await this.reverseGeocode(e.latlng.lat, e.latlng.lng);
                              });
                          },
                          async reverseGeocode(lat, lon) {
                              try {
                                  const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`);
                                  const data = await response.json();
                                  if(data && data.display_name) {
                                      this.addressText = data.display_name;
                                  } else {
                                      this.addressText = `${lat}, ${lon}`;
                                  }
                              } catch(e) {
                                  console.log('Geocode failed');
                              }
                          },
                          getLocation() {
                              if (!navigator.geolocation) {
                                  alert('{{ __('Your browser does not support the location feature.') }}');
                                  return;
                              }
                              this.gettingLocation = true;
                              navigator.geolocation.getCurrentPosition(
                                  async (position) => {
                                      this.lat = position.coords.latitude;
                                      this.lon = position.coords.longitude;
                                      
                                      // Update Map
                                      this.map.setView([this.lat, this.lon], 16);
                                      this.marker.setLatLng([this.lat, this.lon]);
                                      
                                      await this.reverseGeocode(this.lat, this.lon);
                                      this.gettingLocation = false;
                                  },
                                  (error) => {
                                      alert('{{ __('Failed to get location. Make sure GPS permission is active.') }}');
                                      this.gettingLocation = false;
                                  }
                              );
                          }
                      }" x-init="initMap()">
                    @csrf

                    {{-- Step 1: Upload Photo with AI Scanner --}}
                    <div class="mb-10">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-white text-sm font-bold shadow-[0_2px_0_0_#047857]">1</span>
                            <label class="text-lg font-serif font-bold text-secondary">{{ __('Upload Waste Photo') }}</label>
                        </div>

                        <div class="relative border-2 border-dashed border-gray-300 rounded-3xl p-8 text-center hover:border-primary hover:bg-white/50 transition-all bg-white/30 backdrop-blur-sm cursor-pointer group shadow-sm">
                            <template x-if="!previewUrl">
                                <div>
                                    <div class="text-4xl mb-3 opacity-80">📸</div>
                                    <p class="text-secondary/50 text-sm font-light">{{ __('Click to select a photo from your device') }}</p>
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
                                    <span class="font-serif font-medium text-secondary">{{ __('AI Vision Processing...') }}</span>
                                    <p class="text-sm text-secondary/60 mt-1 font-light">{{ __('Gently analyzing the materials in your photo.') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Scan Result --}}
                        <div x-show="scanned" x-transition class="mt-6 p-5 bg-primary/10 border border-primary/20 rounded-2xl" style="display:none;">
                            <div class="flex items-center gap-4">
                                <span class="text-3xl" x-text="resultIcon"></span>
                                <div>
                                    <div class="font-serif font-medium text-secondary">{{ __('Scan Complete!') }}</div>
                                    <div class="text-sm text-secondary/70 font-light mt-1">{{ __('Category:') }} <strong class="font-medium text-primary" x-text="resultCategory"></strong></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2: Confirm Category (auto-selected by AI, can override) --}}
                    <div class="mb-10">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-white text-sm font-bold shadow-[0_2px_0_0_#047857]">2</span>
                            <label class="text-lg font-serif font-bold text-secondary">{{ __('Confirm Category') }}</label>
                        </div>
                        <p class="text-sm text-gray-500 font-medium mb-5 ml-11">{{ __('Auto-selected by AI. You can manually change it if necessary.') }}</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($categories as $category)
                                <div>
                                    <input type="radio" name="category_id" id="cat_{{ $category->id }}"
                                           value="{{ $category->id }}" class="peer hidden" required>
                                    <label for="cat_{{ $category->id }}"
                                           class="block text-center cursor-pointer bg-white/50 border border-white/60 shadow-sm rounded-2xl p-5 hover:border-emerald-300 peer-checked:border-primary peer-checked:border-2 peer-checked:bg-emerald-50/80 peer-checked:shadow-md transition-all">
                                        <div class="text-4xl mb-3 cat-icon opacity-90 group-hover:scale-110 transition-transform">{{ $category->icon ?? '🗑️' }}</div>
                                        <div class="font-bold text-sm cat-name text-secondary">{{ $category->name }}</div>
                                        <div class="text-xs text-primary mt-1 font-bold">{{ number_format($category->price_per_kg, 0, ',', '.') }} {{ __('Coins/kg') }}</div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Step 3: Estimated Weight --}}
                    <div class="mb-10">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-white text-sm font-bold shadow-[0_2px_0_0_#047857]">3</span>
                            <label for="estimated_weight" class="text-lg font-serif font-bold text-secondary">{{ __('Estimated Weight') }}</label>
                        </div>
                        <input type="number" step="0.1" min="0.1" name="estimated_weight" id="estimated_weight"
                               class="w-full mt-2 rounded-2xl border border-gray-200 shadow-sm focus:border-primary focus:ring-primary focus:ring-4 focus:ring-primary/20 text-lg font-bold py-4 px-5 bg-white/70 transition-all"
                               placeholder="{{ __('e.g., 2.5 kg') }}" required>
                        <p class="text-sm text-secondary/60 font-medium mt-3 ml-1">{{ __('Final weight will be confirmed by the Eco-Driver upon pickup.') }}</p>
                    </div>

                    {{-- Step 4: Address --}}
                    <div class="mb-10">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-white text-sm font-bold shadow-[0_2px_0_0_#047857]">4</span>
                                <label for="address" class="text-lg font-serif font-bold text-secondary">{{ __('Pickup Address') }}</label>
                            </div>
                            <button type="button" @click="getLocation()" 
                                    class="text-sm bg-primary/10 text-primary px-4 py-1.5 rounded-full font-bold flex items-center gap-2 hover:bg-primary hover:text-white transition-all disabled:opacity-50"
                                    :disabled="gettingLocation">
                                <span x-show="!gettingLocation">📍 {{ __('Detect My Location') }}</span>
                                <span x-show="gettingLocation" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    {{ __('Searching...') }}
                                </span>
                            </button>
                        </div>
                        <div class="mb-4">
                            <div id="pickup-map" class="w-full h-64 rounded-2xl border border-gray-200 z-0"></div>
                            <p class="text-xs text-gray-500 mt-2 font-medium">{{ __('💡 You can drag the pin or click on the map to adjust the accurate location.') }}</p>
                        </div>
                        
                        <input type="hidden" name="latitude" x-model="lat">
                        <input type="hidden" name="longitude" x-model="lon">

                        <textarea name="address" id="address" rows="2" x-model="addressText"
                               class="w-full mt-2 rounded-2xl border border-gray-200 shadow-sm focus:border-primary focus:ring-primary focus:ring-4 focus:ring-primary/20 text-lg font-medium py-4 px-5 bg-white/70 transition-all"
                               placeholder="{{ __('e.g., Jl. Sudirman No. 12, Malang') }}" required></textarea>
                               
                        <label for="address_notes" class="text-sm font-bold text-gray-500 uppercase tracking-widest mt-4 block mb-2">{{ __('House Details (Optional)') }}</label>
                        <input type="text" name="address_notes" id="address_notes"
                               class="w-full rounded-xl border border-gray-200 shadow-sm focus:border-primary focus:ring-primary focus:ring-4 focus:ring-primary/20 font-medium py-3 px-4 bg-white/70 transition-all"
                               placeholder="{{ __('e.g., Dark green house, black fence') }}">
                    </div>

                    <button type="submit"
                            class="w-full px-8 py-5 bg-gradient-to-r from-primary to-emerald-500 text-white font-bold rounded-full btn-premium text-lg">
                        {{ __('🚀 Call Driver Now') }}
                    </button>
                </form>
            </div>

            {{-- Active Pickups Queue --}}
            @if($activePickups->isNotEmpty())
            <div class="mt-8 glass-panel rounded-3xl p-8 relative overflow-hidden border-2 border-emerald-100/50 shadow-lg shadow-emerald-500/5">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-400/10 rounded-full blur-2xl -z-10"></div>
                <h3 class="text-2xl font-serif font-bold mb-6 text-secondary flex items-center gap-2">
                    {{ __('Your Pickup Queue') }} <span class="animate-pulse">🚚</span>
                </h3>
                <div class="space-y-4">
                    @foreach($activePickups as $pickup)
                    <div class="bg-white/60 border border-white/80 rounded-2xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm hover:shadow-md transition-all">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-3xl">
                                {{ $pickup->category->icon ?? '📦' }}
                            </div>
                            <div>
                                <div class="font-bold text-secondary text-lg mb-0.5">Pickup #{{ $pickup->id }}</div>
                                <div class="text-sm text-gray-500 font-medium">{{ __('Category:') }} {{ $pickup->category->name }} • Est. {{ $pickup->total_weight }} kg</div>
                            </div>
                        </div>
                        <div class="text-left md:text-right">
                            @if($pickup->status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-50 text-amber-700 rounded-full text-sm font-bold tracking-wide border border-amber-200 shadow-sm">
                                    <svg class="animate-spin h-4 w-4 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    {{ __('Looking for a Driver...') }}
                                </span>
                            @elseif($pickup->status === 'on-the-way')
                                <span class="inline-block px-4 py-2 bg-emerald-50 text-emerald-700 rounded-full text-sm font-bold tracking-wide border border-emerald-200 shadow-sm">{{ __('🚚 Driver on the Way') }}</span>
                                <div class="text-xs text-secondary mt-2 font-bold bg-white/50 inline-block px-3 py-1 rounded-lg border border-white/60">{{ __('Driver:') }} {{ $pickup->driver->name ?? 'Eco-Driver' }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</x-app-layout>
