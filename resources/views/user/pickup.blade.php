<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-semibold text-2xl text-secondary leading-tight">
            {{ __('Request Pickup') }}
        </h2>
    </x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- TensorFlow.js & MobileNet -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/mobilenet@latest"></script>

    <div class="py-12">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8 grid lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2">
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
                          weight: '',
                          selectedCategoryPrice: 0,
                          resultIcon: '',
                          previewUrl: null,
                          i18n: {
                            'Status': '{{ __('Status') }}',
                            'Driver': '{{ __('Driver') }}',
                            'Completed': '{{ __('Completed') }}',
                            'Rejected': '{{ __('Rejected') }}',
                            'Help AI Learn!': '{{ __('Help AI Learn!') }}',
                            'I found multiple possibilities. Which one is correct?': '{{ __('I found multiple possibilities. Which one is correct?') }}',
                            'None of the above': '{{ __('None of the above') }}',
                            'Oops, my bad!': '{{ __('Oops, my bad!') }}',
                            'Please select the correct category below.': '{{ __('Please select the correct category below.') }}',
                            'Estimated Reward': '{{ __('Estimated Reward') }}',
                            'Coins': '{{ __('Coins') }}',
                            'Plastik PET': '{{ __('Plastik PET') }}',
                            'Kardus': '{{ __('Kardus') }}',
                            'Kertas': '{{ __('Kertas') }}',
                            'Logam': '{{ __('Logam') }}',
                            'Kaca': '{{ __('Kaca') }}',
                            'Learned from you!': '{{ __('Learned from you!') }}'
                          },
                          aiMemory: JSON.parse(localStorage.getItem('trashpay_ai_memory') || '{}'),
                          currentTopClass: null,
                          aiUsedMemory: false,
                          init() {
                              this.$watch('targetCategory', (val) => {
                                  if (val && this.currentTopClass) {
                                      // Save to memory!
                                      this.aiMemory[this.currentTopClass] = val;
                                      localStorage.setItem('trashpay_ai_memory', JSON.stringify(this.aiMemory));
                                  }
                              });
                          },
                          async scanPhoto(event) {
                              const file = event.target.files[0];
                              if (!file) return;
                              this.previewUrl = URL.createObjectURL(file);
                              this.scanning = true;
                              this.scanned = false;
                              this.resultCategory = '';
                              this.suggestedCategories = [];
                              this.suggestionsUI = [];
                              this.rejectedAI = false;
                              this.aiUsedMemory = false;
                              
                              try {
                                  // Wait for image element to render
                                  await this.$nextTick();
                                  const imgElement = document.getElementById('preview-image');
                                  
                                  // Wait for image to fully load
                                  await new Promise((resolve) => {
                                      if(imgElement.complete) resolve();
                                      else imgElement.onload = resolve;
                                  });

                                  // Load MobileNet model
                                  const model = await mobilenet.load();
                                  
                                  // Add an artificial delay to make the AI processing feel more authentic and satisfying
                                  await new Promise(resolve => setTimeout(resolve, 1500));
                                  
                                  // Request Top 10 predictions to make it much smarter
                                  const predictions = await model.classify(imgElement, 10);
                                  console.log('AI Predictions:', predictions);
                                  
                                  this.currentTopClass = predictions[0].className;
                                  
                                  const categoriesRegex = {
                                      'Kaca': /\b(glass|window|jar|mirror|goblet|beaker|vase|pitcher|bottle|sunglass|lens|goggle|loupe)\b/i,
                                      'Plastik PET': /\b(plastic|water bottle|pop bottle|pet|bag|cup|wrapper|container|tub|nipple|pill bottle|bucket|barrel|soap dispenser)\b/i,
                                      'Logam': /\b(metal|can|aluminum|tin|steel|iron|coin|pot|pan|buckle|padlock|chain|safe|wok|spatula|corkscrew|bucket|barrel|hook|nail|stone|rock|coral|mineral|crystal|paperweight|maze|hard disc)\b/i,
                                      'Kertas': /\b(paper|book|envelope|newspaper|tissue|card|document|receipt|binder|menu|packet|folder|web site|monitor|screen|laptop|origami|comic|notebook|crossword|mail|desk|printer)\b/i,
                                      'Kardus': /\b(cardboard|box|carton|crate|package|jigsaw)\b/i
                                  };

                                  // Score categories based on cumulative probability
                                  let scores = { 'Kaca': 0, 'Plastik PET': 0, 'Logam': 0, 'Kertas': 0, 'Kardus': 0 };

                                  // AI MEMORY CHECK: Has the user taught us about this specific top class before?
                                  if (this.aiMemory[this.currentTopClass]) {
                                      const learnedCat = this.aiMemory[this.currentTopClass];
                                      scores[learnedCat] += 10.0; // Massive score boost overrides normal predictions
                                      this.aiUsedMemory = true;
                                  }

                                  for (let p of predictions) {
                                      const className = p.className.toLowerCase();
                                      for (const [cat, regex] of Object.entries(categoriesRegex)) {
                                          if (regex.test(className)) {
                                              scores[cat] += p.probability;
                                          }
                                      }
                                  }

                                  let foundCats = [];
                                  const mappedClasses = [];
                                  
                                  // Sort categories by score descending
                                  const sortedScores = Object.entries(scores)
                                      .filter(([cat, score]) => score > 0)
                                      .sort((a, b) => b[1] - a[1]);

                                  if (sortedScores.length > 0) {
                                      for (let [cat, score] of sortedScores) {
                                          foundCats.push(cat);
                                          mappedClasses.push({ cat: cat, confidence: Math.min(99, Math.max(1, Math.round(score * 100))) });
                                      }
                                  } else {
                                      // Fallback if absolutely nothing matches
                                      foundCats.push('Kardus');
                                      mappedClasses.push({ cat: 'Kardus', confidence: Math.round(predictions[0].probability * 100) });
                                  }
                                  
                                  // PAD TO ALWAYS HAVE AT LEAST 2 CHOICES
                                  if (mappedClasses.length === 1) {
                                      const allCats = ['Plastik PET', 'Kardus', 'Kertas', 'Logam', 'Kaca'];
                                      const available = allCats.filter(c => c !== mappedClasses[0].cat);
                                      mappedClasses.push({ cat: available[0], confidence: Math.floor(Math.random() * 5) + 1 });
                                      foundCats.push(available[0]);
                                  }
                                  
                                  // Take up to top 2 distinct categories
                                  this.suggestedCategories = foundCats.slice(0, 2);
                                  this.suggestionsUI = mappedClasses.slice(0, 2);
                                  
                                  this.targetCategory = null; // Do NOT auto-select
                                  this.scanning = false;
                                  this.scanned = true;
                                  this.resultCategory = '';
                                  this.resultIcon = '';

                                  // Reset all radio inputs
                                  const radios = document.querySelectorAll('input[name=category_id]');
                                  for (let i = 0; i < radios.length; i++) {
                                      radios[i].checked = false;
                                  }
                                  
                              } catch(e) {
                                  console.error('AI Error:', e);
                                  this.scanning = false;
                                  this.scanned = true;
                              }
                          },
                          selectSuggested(catName) {
                              this.targetCategory = catName;
                              const radios = document.querySelectorAll('input[name=category_id]');
                              for (let i = 0; i < radios.length; i++) {
                                  const rawName = radios[i].getAttribute('data-raw-name');
                                  if (rawName.toLowerCase() === catName.toLowerCase() || catName.toLowerCase().includes(rawName.toLowerCase())) {
                                      radios[i].checked = true;
                                      radios[i].dispatchEvent(new Event('change'));
                                      this.resultCategory = radios[i].nextElementSibling.querySelector('.cat-name').innerText;
                                      const iconEl = radios[i].nextElementSibling.querySelector('.cat-icon svg');
                                      if (iconEl) {
                                          this.resultIcon = iconEl.outerHTML;
                                      }
                                      break;
                                  }
                              }
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

                              // Fix tile loading issues when map container resizes or renders
                              setTimeout(() => {
                                  this.map.invalidateSize();
                              }, 500);

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
                                    <div class="w-12 h-12 mb-3 mx-auto text-secondary/50">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </div>
                                    <p class="text-secondary/50 text-sm font-light">{{ __('Tap to take a photo or choose from gallery') }}</p>
                                </div>
                            </template>
                            <template x-if="previewUrl">
                                <img :src="previewUrl" id="preview-image" class="mx-auto max-h-48 rounded-xl object-cover shadow-md">
                            </template>
                            <input type="file" name="waste_photo" accept="image/*" capture="environment" required
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
                        <div x-show="scanned" x-transition class="mt-6 p-5 min-h-[130px] flex items-center bg-primary/10 border border-primary/20 rounded-2xl transition-all duration-500" style="display:none;">
                            <div class="grid relative w-full">
                                <div x-show="rejectedAI && !targetCategory" x-transition.opacity.duration.300ms class="col-start-1 row-start-1 flex items-center gap-4">
                                    <span class="w-10 h-10 text-red-500 flex items-center justify-center shrink-0 mt-0.5">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </span>
                                    <div>
                                        <div class="font-bold text-red-600 text-lg">{{ __('Oops, my bad!') }}</div>
                                        <div class="text-sm text-red-500/80 font-medium mt-1">{{ __('Please select the correct category below.') }}</div>
                                    </div>
                                </div>
                            

                            
                                <div x-show="targetCategory" x-transition.opacity.duration.300ms class="col-start-1 row-start-1 flex items-center gap-4">
                                <span class="w-12 h-12 text-emerald-600 flex items-center justify-center" x-html="resultIcon"></span>
                                <div>
                                    <div class="font-bold text-gray-800 text-lg">
                                        <span x-show="!rejectedAI">{{ __('Scan Complete!') }}</span>
                                        <span x-show="rejectedAI">{{ __('Category Confirmed!') }}</span>
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1">{{ __('Category:') }} <span class="font-bold text-primary" x-text="i18n[targetCategory] || targetCategory"></span></div>
                                    <template x-if="aiUsedMemory || rejectedAI">
                                        <div class="text-xs text-emerald-600 mt-1 font-bold flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            <span x-text="i18n['Learned from you!'] || 'Learned from you!'"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            
                                <div x-show="!targetCategory && suggestionsUI.length > 0" x-transition.opacity.duration.300ms class="col-start-1 row-start-1 flex flex-col gap-3">
                                <div class="flex items-start gap-4">
                                    <span class="w-10 h-10 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </span>
                                    <div>
                                        <div class="font-bold text-gray-800 text-lg">{{ __('Scan Complete!') }}</div>
                                        <div class="text-sm text-gray-600 mt-1">{{ __('I found multiple possibilities. Please select the correct one below:') }}</div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-2 ml-14">
                                    <template x-for="suggestion in suggestionsUI" :key="suggestion.cat">
                                        <button type="button" @click="selectSuggested(suggestion.cat)"
                                                class="px-4 py-2 bg-white border-2 border-emerald-100 hover:border-primary hover:bg-emerald-50 rounded-xl text-sm font-bold text-secondary transition-all shadow-sm flex items-center gap-2">
                                            <span x-text="i18n[suggestion.cat] || suggestion.cat"></span>
                                            <span class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded-md opacity-70" x-text="suggestion.confidence + '% match'"></span>
                                        </button>
                                    </template>
                                    <button type="button" @click="suggestionsUI = []; rejectedAI = true;"
                                            class="px-4 py-2 bg-gray-50 border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-100 rounded-xl text-sm font-bold text-gray-500 transition-all shadow-sm">
                                        {{ __('None of the above') }}
                                    </button>
                                </div>
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
                        <p class="text-sm text-gray-500 font-medium mb-5 ml-11">
                            {{ __('You can manually select a category from the options below.') }}
                            <span class="text-amber-600/90 font-bold flex items-center gap-1.5 mt-2 text-xs bg-amber-50 inline-flex px-3 py-1.5 rounded-lg border border-amber-200/50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                {{ __('AI is still learning and might make mistakes. Please double check.') }}
                            </span>
                        </p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($categories as $category)
                                <div>
                                    <input type="radio" name="category_id" id="cat_{{ $category->id }}"
                                           value="{{ $category->id }}" data-raw-name="{{ $category->name }}" data-price="{{ $category->price_per_kg }}"
                                           @change="selectedCategoryPrice = $event.target.dataset.price; targetCategory = '{{ $category->name }}'; resultCategory = '{{ __($category->name) }}'; const icon = $event.target.nextElementSibling.querySelector('.cat-icon svg'); if(icon) resultIcon = icon.outerHTML;"
                                           class="peer hidden" required>
                                    <label for="cat_{{ $category->id }}"
                                           class="block text-center cursor-pointer bg-white/50 border border-white/60 shadow-sm rounded-2xl p-5 hover:border-emerald-300 peer-checked:border-primary peer-checked:border-2 peer-checked:bg-emerald-50/80 peer-checked:shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg active:scale-95">
                                        <div class="mb-3 cat-icon opacity-90 group-hover:scale-110 transition-transform flex justify-center text-emerald-600"><x-category-icon :category="$category" class="w-12 h-12" /></div>
                                        <div class="font-bold text-sm cat-name text-secondary">{{ __($category->name) }}</div>
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            <div>
                                <input type="number" step="0.1" min="0.1" max="200" name="estimated_weight" id="estimated_weight" x-model="weight"
                                       @input="if(weight > 200) weight = 200; if(weight < 0) weight = ''"
                                       class="w-full h-full rounded-2xl border border-gray-200 shadow-sm focus:border-primary focus:ring-primary focus:ring-4 focus:ring-primary/20 text-lg font-bold py-4 px-5 bg-white/70 transition-all"
                                       placeholder="{{ __('e.g., 2.5 kg (Max: 200 kg)') }}" required>
                            </div>
                                   
                            {{-- Estimated Reward Badge --}}
                            <div>
                                <div class="h-full p-4 rounded-xl bg-gradient-to-r from-emerald-50 to-emerald-100/50 border border-emerald-200 flex items-center justify-between transition-all">
                                    <span class="font-bold text-emerald-800 flex items-center gap-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
                                        {{ __('Estimated Reward') }}
                                    </span>
                                    <span class="font-bold text-2xl text-emerald-600 flex items-center gap-2">
                                        <span x-text="Math.round((weight || 0) * selectedCategoryPrice).toLocaleString('id-ID')"></span>
                                        <span class="text-sm bg-emerald-200/50 text-emerald-800 px-2 py-0.5 rounded-md">{{ __('Coins') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 ml-1">
                            <span class="text-amber-600/90 font-bold flex items-center gap-1.5 w-max text-xs bg-amber-50 inline-flex px-3 py-1.5 rounded-lg border border-amber-200/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                {{ __('Final weight will be confirmed by the Eco-Driver upon pickup.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Step 4: Address --}}
                    <div class="mb-10">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-white text-sm font-bold shadow-[0_2px_0_0_#047857]">4</span>
                                <label for="address" class="text-lg font-serif font-bold text-secondary">{{ __('Pickup Address') }}</label>
                            </div>
                            <button type="button" @click="getLocation()" 
                                    class="text-sm bg-primary/10 text-primary px-4 py-1.5 rounded-full font-bold flex items-center gap-2 hover:bg-primary hover:text-white transition-all duration-200 hover:scale-105 active:scale-95 disabled:opacity-50"
                                    :disabled="gettingLocation">
                                <span x-show="!gettingLocation" class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ __('Detect My Location') }}
                                </span>
                                <span x-show="gettingLocation" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    {{ __('Searching...') }}
                                </span>
                            </button>
                        </div>
                        <div class="mb-4">
                            <div id="pickup-map" class="w-full h-64 rounded-2xl border border-gray-200 z-0"></div>
                            <div class="mt-2">
                                <span class="text-amber-600/90 font-bold flex items-center gap-1.5 w-max text-xs bg-amber-50 inline-flex px-3 py-1.5 rounded-lg border border-amber-200/50">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('You can drag the pin or click on the map to adjust the accurate location.') }}
                                </span>
                            </div>
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
                            class="w-full px-8 py-5 mt-8 bg-gradient-to-r from-primary to-emerald-500 text-white font-bold rounded-full btn-premium text-lg active:scale-[0.98] transition-all duration-300 hover:shadow-xl hover:shadow-primary/40 hover:-translate-y-1">
                        {{ __('Call Driver Now') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Pickup History Sidebar --}}
        <div class="lg:col-span-1">
            <div x-data="{ tab: 'active' }">
                <div class="glass-panel rounded-3xl p-8 relative overflow-hidden">
                    <h3 class="text-xl font-serif font-bold mb-4 text-secondary flex items-center justify-between">
                        <span class="flex items-center gap-2">{{ __('Your Orders') }} 
                            <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                        </span>
                    </h3>

                    <!-- Tabs -->
                    <div class="flex border-b border-gray-200 mb-6">
                        <button @click="tab = 'active'" 
                                :class="{ 'border-primary text-primary': tab === 'active', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'active' }"
                                class="w-1/2 pb-3 border-b-2 font-bold text-sm transition-all focus:outline-none text-center">
                            {{ __('Active') }}
                        </button>
                        <button @click="tab = 'completed'" 
                                :class="{ 'border-primary text-primary': tab === 'completed', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'completed' }"
                                class="w-1/2 pb-3 border-b-2 font-bold text-sm transition-all focus:outline-none text-center">
                            {{ __('History') }}
                        </button>
                    </div>

                    <!-- Active Tab -->
                    <div x-show="tab === 'active'">
                        @php $activePickupsList = $pickups->whereIn('status', ['pending', 'on-the-way']); @endphp
                        @if($activePickupsList->isEmpty())
                            <p class="text-gray-500 text-sm text-center py-4">{{ __('No active pickups.') }}</p>
                        @else
                            <div class="space-y-4">
                                @foreach($activePickupsList->take(3) as $pickup)
                                    @include('user.partials.pickup-card', ['pickup' => $pickup])
                                @endforeach
                            </div>
                            <div class="mt-6 flex justify-center">
                                <a href="{{ route('user.history') }}" class="inline-block px-6 py-2.5 bg-gray-50 text-gray-600 font-bold rounded-full border border-gray-200 hover:bg-gray-100 transition-all duration-200 text-sm hover:scale-105 active:scale-95">
                                    {{ __('Detailed History') }} &rarr;
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Completed Tab -->
                    <div x-show="tab === 'completed'" style="display: none;">
                        @php $completedPickupsList = $pickups->whereIn('status', ['completed', 'rejected', 'cancelled']); @endphp
                        @if($completedPickupsList->isEmpty())
                            <p class="text-gray-500 text-sm text-center py-4">{{ __('No pickup history yet.') }}</p>
                        @else
                            <div class="space-y-4">
                                @foreach($completedPickupsList->take(3) as $pickup)
                                    @include('user.partials.pickup-card', ['pickup' => $pickup])
                                @endforeach
                            </div>
                            <div class="mt-6 flex justify-center">
                                <a href="{{ route('user.history') }}" class="inline-block px-6 py-2.5 bg-gray-50 text-gray-600 font-bold rounded-full border border-gray-200 hover:bg-gray-100 transition-all duration-200 text-sm hover:scale-105 active:scale-95">
                                    {{ __('Detailed History') }} &rarr;
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</x-app-layout>
