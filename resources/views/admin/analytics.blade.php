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
                <div class="glass-panel rounded-3xl p-6 text-center">
                    <div class="text-sm font-semibold text-gray-500 uppercase mb-2">Total Waste Collected</div>
                    <div class="text-4xl font-serif font-bold text-secondary">{{ number_format($totalWeight, 2) }} <span class="text-xl">kg</span></div>
                </div>

                <!-- CO2 Reduced -->
                <div class="glass-panel rounded-3xl p-6 text-center">
                    <div class="text-sm font-semibold text-gray-500 uppercase mb-2">Total CO2 Reduced</div>
                    <div class="text-4xl font-serif font-bold text-primary">{{ number_format($co2Reduced, 2) }} <span class="text-xl">kg</span></div>
                    <p class="text-xs text-gray-400 mt-2">Formula: Total Weight × 1.2</p>
                </div>

                <!-- Completed Pickups -->
                <div class="glass-panel rounded-3xl p-6 text-center">
                    <div class="text-sm font-semibold text-gray-500 uppercase mb-2">Successful Pickups</div>
                    <div class="text-4xl font-serif font-bold text-blue-600">{{ number_format($completedPickups) }}</div>
                </div>
            </div>

            <div class="glass-panel rounded-3xl p-8">
                <h3 class="text-2xl font-serif font-bold mb-6 text-secondary">Waste by Category</h3>
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
                        label: 'Pickups',
                        data: data.data,
                        backgroundColor: [
                            '#10B981', // Emerald
                            '#F59E0B', // Amber
                            '#3B82F6'  // Blue
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
