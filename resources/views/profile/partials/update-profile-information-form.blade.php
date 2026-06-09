<section x-data="{ zoomProfile: false }">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex items-center gap-6">
            <div class="shrink-0">
                @if($user->profile_photo_path)
                    <img @click="zoomProfile = true" class="cursor-pointer h-20 w-20 object-cover rounded-full border-2 border-gray-200 hover:scale-105 transition-transform hover:shadow-md" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" />
                @else
                    <div @click="zoomProfile = true" class="cursor-pointer h-20 w-20 rounded-full border-2 border-gray-200 bg-gray-100 flex items-center justify-center text-3xl text-gray-400 font-bold uppercase hover:scale-105 transition-transform hover:shadow-md">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <label class="block">
                <span class="sr-only">{{ __('Choose profile photo') }}</span>
                <input type="file" name="profile_photo" accept="image/*" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-primary/10 file:text-primary
                    hover:file:bg-primary/20
                    transition-all
                "/>
            </label>
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <!-- Fullscreen Image Zoom -->
    <template x-teleport="body">
        <div x-show="zoomProfile" style="display: none;" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <!-- Darker Backdrop for Zoom -->
            <div x-show="zoomProfile" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-md" @click="zoomProfile = false"></div>
            
            <div x-show="zoomProfile"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="relative z-10 max-w-2xl w-full flex flex-col items-center justify-center">
                 
                <button @click="zoomProfile = false" class="absolute -top-12 right-0 sm:-right-8 p-2 text-white/50 hover:text-white transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                @if($user->profile_photo_path)
                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" class="w-64 h-64 sm:w-96 sm:h-96 rounded-full shadow-2xl object-cover border-4 border-white/20">
                @else
                    <div class="w-64 h-64 sm:w-96 sm:h-96 rounded-full bg-white flex items-center justify-center text-8xl text-gray-400 font-bold uppercase shadow-2xl border-4 border-white/20">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
            </div>
        </div>
    </template>
</section>
