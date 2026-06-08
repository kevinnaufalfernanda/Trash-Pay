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
                    <span class="text-3xl drop-shadow-md">💸</span> Pending Redemption Requests
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white/50 border-b border-white/60">
                                <th class="py-4 px-6 font-bold text-gray-600 rounded-tl-xl backdrop-blur-sm">ID</th>
                                <th class="py-4 px-6 font-bold text-gray-600 backdrop-blur-sm">User</th>
                                <th class="py-4 px-6 font-bold text-gray-600 backdrop-blur-sm">Provider & No. Akun</th>
                                <th class="py-4 px-6 font-bold text-gray-600 text-right backdrop-blur-sm">Amount (Coins)</th>
                                <th class="py-4 px-6 font-semibold text-gray-600 text-center rounded-tr-xl backdrop-blur-sm">Actions</th>
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
                                    <td class="py-4 px-6 text-right font-serif font-bold text-2xl text-amber-500">{{ number_format($redemption->amount) }} 🪙</td>
                                    <td class="py-4 px-6 flex justify-center gap-2">
                                        <form action="{{ route('admin.payouts.approve', $redemption->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-emerald-400 to-teal-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 hover:-translate-y-0.5 transition-all" title="Approve">
                                                Transfer Selesai
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.payouts.reject', $redemption->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-red-100/50 backdrop-blur-sm text-red-600 font-bold border border-red-200/50 rounded-xl hover:bg-red-100 transition-colors shadow-sm" title="Reject">
                                                Tolak
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($redemptions->isEmpty())
                        <div class="text-center py-12">
                            <div class="text-5xl mb-4">🙌</div>
                            <p class="text-gray-500">All caught up! No pending payouts.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
