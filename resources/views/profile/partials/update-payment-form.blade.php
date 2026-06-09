<section x-data="{
    paymentNumber: '{{ old('payment_number', $user->payment_number) }}',
    confirmed: false
}">
    <header>
        <h2 class="text-lg font-bold text-gray-900">
            {{ __('Update Payment Details') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Update your payment number for easy coin redemption to DANA or GoPay.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update-payment') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="payment_number" :value="__('Payment Number / E-Wallet Account')" />
            <x-text-input id="payment_number" name="payment_number" type="tel" class="mt-1 block w-full" x-model="paymentNumber" 
                @input="paymentNumber = $event.target.value.replace(/[^\d+]/g, '').replace(/(?!^)\+/g, '')" 
                required autocomplete="tel" placeholder="{{ __('e.g., 081234567890') }}" />
            <x-input-error class="mt-2" :messages="$errors->updatePayment->get('payment_number')" />
        </div>

        <div class="flex items-start">
            <div class="flex items-center h-5">
                <input id="confirm_payment" type="checkbox" x-model="confirmed" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary text-primary cursor-pointer" required>
            </div>
            <label for="confirm_payment" class="ml-2 text-sm font-medium text-gray-900 cursor-pointer">{{ __('I confirm that this payment number is correct and I am responsible for any transfer errors.') }}</label>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button x-bind:disabled="!confirmed">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'payment-updated')
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
</section>
