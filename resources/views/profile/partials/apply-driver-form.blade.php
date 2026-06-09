<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-secondary font-serif">
            {{ __('Daftar Menjadi Eco-Driver') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ __('Bergabunglah menjadi pahlawan lingkungan! Dengan menjadi Eco-Driver, kamu bisa mendapatkan penghasilan tambahan dengan menjemput sampah dari warga.') }}
        </p>
    </header>

    @php
        $application = auth()->user()->driverApplication;
    @endphp

    @if($application)
        <div class="p-4 rounded-xl border border-gray-200 {{ $application->status === 'pending' ? 'bg-amber-50' : ($application->status === 'approved' ? 'bg-emerald-50' : 'bg-red-50') }}">
            <div class="flex items-center gap-3">
                <div class="text-2xl">
                    {{ $application->status === 'pending' ? '⏳' : ($application->status === 'approved' ? '✅' : '❌') }}
                </div>
                <div>
                    <h3 class="font-bold text-secondary text-sm">Status Pendaftaran: <span class="uppercase">{{ $application->status }}</span></h3>
                    <p class="text-xs text-gray-600 mt-1">
                        @if($application->status === 'pending')
                            Aplikasi kamu sedang ditinjau oleh Admin. Mohon bersabar menunggu persetujuan.
                        @elseif($application->status === 'approved')
                            Selamat! Kamu sudah menjadi Eco-Driver. Relog akun kamu jika dashboard driver belum muncul.
                        @else
                            Mohon maaf, aplikasi kamu ditolak. Pastikan data KTP dan plat kendaraan sudah benar.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @else
        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'apply-driver-modal')"
            class="px-6 py-2 bg-gradient-to-r from-primary to-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 hover:-translate-y-0.5 transition-transform"
        >{{ __('Daftar Menjadi Eco-Driver') }}</button>

        @if (session('status') === 'application-submitted')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 4000)"
                class="mt-4 text-sm text-emerald-600 font-medium"
            >{{ __('Berhasil dikirim.') }}</p>
        @endif

        <x-modal name="apply-driver-modal" :show="$errors->applyDriver->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.apply-driver') }}" enctype="multipart/form-data" class="p-6 space-y-6 max-h-[85vh] overflow-y-auto">
                @csrf
                
                <h2 class="text-xl font-bold text-secondary font-serif mb-4">
                    {{ __('Formulir Pendaftaran Eco-Driver') }}
                </h2>

                <div>
                    <x-input-label for="nik" :value="__('Nomor Induk Kependudukan (NIK)')" />
                    <x-text-input id="nik" name="nik" type="text" class="mt-1 block w-full bg-white/70" required placeholder="16 Digit Angka NIK" pattern="\d{16}" title="Harus 16 digit angka" />
                    <x-input-error :messages="$errors->applyDriver->get('nik')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="vehicle_plate" :value="__('Plat Nomor Kendaraan')" />
                    <x-text-input id="vehicle_plate" name="vehicle_plate" type="text" class="mt-1 block w-full bg-white/70 uppercase" required placeholder="Contoh: N 1234 AB" />
                    <x-input-error :messages="$errors->applyDriver->get('vehicle_plate')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="ktp_photo" :value="__('Upload Foto KTP')" />
                    <input id="ktp_photo" name="ktp_photo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-emerald-50 file:text-emerald-700
                        hover:file:bg-emerald-100 cursor-pointer border border-gray-200 rounded-2xl bg-white/70" required />
                    <p class="text-xs text-gray-400 mt-1">Format: JPG/PNG, Maksimal: 2MB.</p>
                    <x-input-error :messages="$errors->applyDriver->get('ktp_photo')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="sim_photo" :value="__('Upload Foto SIM C')" />
                    <input id="sim_photo" name="sim_photo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-emerald-50 file:text-emerald-700
                        hover:file:bg-emerald-100 cursor-pointer border border-gray-200 rounded-2xl bg-white/70" required />
                    <p class="text-xs text-gray-400 mt-1">Format: JPG/PNG, Maksimal: 2MB.</p>
                    <x-input-error :messages="$errors->applyDriver->get('sim_photo')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="stnk_photo" :value="__('Upload Foto STNK')" />
                    <input id="stnk_photo" name="stnk_photo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-emerald-50 file:text-emerald-700
                        hover:file:bg-emerald-100 cursor-pointer border border-gray-200 rounded-2xl bg-white/70" required />
                    <p class="text-xs text-gray-400 mt-1">Format: JPG/PNG, Maksimal: 2MB.</p>
                    <x-input-error :messages="$errors->applyDriver->get('stnk_photo')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="skck_photo" :value="__('Upload Foto SKCK')" />
                    <input id="skck_photo" name="skck_photo" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-emerald-50 file:text-emerald-700
                        hover:file:bg-emerald-100 cursor-pointer border border-gray-200 rounded-2xl bg-white/70" required />
                    <p class="text-xs text-gray-400 mt-1">Format: JPG/PNG, Maksimal: 2MB.</p>
                    <x-input-error :messages="$errors->applyDriver->get('skck_photo')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Batal') }}
                    </x-secondary-button>

                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-primary to-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 hover:-translate-y-0.5 transition-transform">
                        Kirim Pendaftaran
                    </button>
                </div>
            </form>
        </x-modal>
    @endif
</section>
