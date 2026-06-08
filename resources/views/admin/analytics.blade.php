<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif font-bold text-2xl text-secondary leading-tight">
            {{ __('Analytics Dashboard') }}
        </h2>
    </x-slot>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-12">
        <div class="max-w-[90rem] mx-auto sm:px-6 lg:px-8">
            
            <div class="grid md:grid-cols-3 gap-6 mb-8">
                <!-- Total Weight -->
                <div class="glass-panel border-t-4 border-t-amber-400 rounded-3xl p-6 text-center">
                    <div class="text-sm font-semibold text-amber-600 uppercase mb-2">{{ __('Total Waste Collected') }}</div>
                    <div class="text-4xl font-serif font-bold text-amber-500">{{ number_format($totalWeight, 2) }} <span class="text-xl">kg</span></div>
                </div>

                <!-- CO2 Reduced -->
                <div class="glass-panel border-t-4 border-t-emerald-400 rounded-3xl p-6 text-center">
                    <div class="text-sm font-semibold text-emerald-600 uppercase mb-2">{{ __('Total CO2 Reduced') }}</div>
                    <div class="text-4xl font-serif font-bold text-emerald-500">{{ number_format($co2Reduced, 2) }} <span class="text-xl">kg</span></div>
                    <p class="text-xs text-emerald-400 mt-2">{{ __('Formula: Total Weight × 1.2') }}</p>
                </div>

                <!-- Completed Pickups -->
                <div class="glass-panel border-t-4 border-t-blue-400 rounded-3xl p-6 text-center">
                    <div class="text-sm font-semibold text-blue-600 uppercase mb-2">{{ __('Successful Pickups') }}</div>
                    <div class="text-4xl font-serif font-bold text-blue-500">{{ number_format($completedPickups) }}</div>
                </div>
            </div>

            <div class="glass-panel rounded-3xl p-8">
                <h3 class="text-2xl font-serif font-bold mb-6 text-secondary">{{ __('Waste by Category') }}</h3>
                <div class="relative h-80 w-full">
                    <canvas id="wasteChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('wasteChart').getContext('2d');
            const data = {!! json_encode($chartData) !!};

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: '{{ __('Pickups') }}',
                        data: data.data,
                        backgroundColor: [
                            '#10B981', // Emerald for Plastik PET
                            '#F59E0B', // Amber for Kardus
                            '#3B82F6', // Blue for Kertas
                            '#8B5CF6', // Purple for Logam
                            '#EF4444', // Red for Kaca
                            '#EC4899', // Pink fallback
                            '#06B6D4'  // Cyan fallback
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
