<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-secondary leading-tight">
            {{ __('Payout Approvals') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-emerald-100 text-emerald-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="glass-panel rounded-3xl p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-400/10 rounded-full blur-3xl -z-10"></div>
                <h3 class="text-3xl font-serif font-bold text-secondary mb-6 tracking-tight flex items-center gap-3">
                    <span class="text-amber-500 bg-amber-100 p-2.5 rounded-2xl shadow-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg>
                    </span> {{ __('Pending Redemption Requests') }}
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/50 border-b border-white/60">
                                <th class="py-4 px-6 font-bold text-gray-600 rounded-tl-xl backdrop-blur-sm">ID</th>
                                <th class="py-4 px-6 font-bold text-gray-600 backdrop-blur-sm">User</th>
                                <th class="py-4 px-6 font-bold text-gray-600 backdrop-blur-sm">{{ __('Provider & Account No.') }}</th>
                                <th class="py-4 px-6 font-bold text-gray-600 text-right backdrop-blur-sm">{{ __('Amount (Coins)') }}</th>
                                <th class="py-4 px-6 font-semibold text-gray-600 text-center rounded-tr-xl backdrop-blur-sm">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($redemptions as $redemption)
                                <tr class="border-b border-white/40 hover:bg-white/60 transition-colors">
                                    <td class="py-4 px-6 text-sm font-bold text-gray-500">#{{ $redemption->id }}</td>
                                    <td class="py-4 px-6 font-bold text-secondary text-lg">{{ $redemption->user->name }}</td>
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-lg
                                            @if($redemption->provider === 'Dana') text-blue-600
                                            @elseif($redemption->provider === 'ShopeePay') text-orange-600
                                            @else text-green-600
                                            @endif">
                                            {{ $redemption->provider }}
                                        </div>
                                        <div class="text-sm font-medium text-gray-500 font-mono">{{ $redemption->account_number }}</div>
                                    </td>
                                    <td class="py-4 px-6 text-right font-serif font-bold text-2xl text-amber-500 flex justify-end items-center gap-1.5">{{ number_format($redemption->amount) }} <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v8m0-8V6m0 12v-2m0 0v-2"></path></svg></td>
                                    <td class="py-4 px-6 flex justify-center gap-2">
                                        <form action="{{ route('admin.payouts.approve', $redemption->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-400 to-teal-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 hover:-translate-y-0.5 transition-all" title="Approve">
                                                {{ __('Transfer Completed') }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.payouts.reject', $redemption->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-red-100/50 backdrop-blur-sm text-red-600 font-bold border border-red-200/50 rounded-xl hover:bg-red-100 transition-colors shadow-sm" title="Reject">
                                                {{ __('Reject') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($redemptions->isEmpty())
                        <div class="text-center py-12">
                            <div class="w-20 h-20 mx-auto mb-4 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <p class="text-gray-500">{{ __('All caught up! No pending payouts.') }}</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
