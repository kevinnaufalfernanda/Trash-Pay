<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-secondary leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white rounded-3xl card-bouncy border-2 border-gray-100">
                <div class="w-full">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white rounded-3xl card-bouncy border-2 border-gray-100">
                <div class="w-full">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white rounded-3xl card-bouncy border-2 border-blue-100 shadow-blue-100/50">
                <div class="w-full">
                    @include('profile.partials.update-payment-form')
                </div>
            </div>

            @if(auth()->user()->role !== 'admin')
                <div class="p-4 sm:p-8 bg-white rounded-3xl card-bouncy border-2 border-red-100">
                    <div class="w-full">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            @endif

            @if(auth()->user()->role === 'user')
                <div class="p-4 sm:p-8 bg-white rounded-3xl card-bouncy border-2 border-emerald-100 shadow-emerald-100/50">
                    <div class="w-full">
                        @include('profile.partials.apply-driver-form')
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
