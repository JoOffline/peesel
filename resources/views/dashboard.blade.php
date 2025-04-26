<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Card Total Kategori -->
                        <div class="bg-blue-100 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-blue-800">Total Kategori</h3>
                                    <p class="text-3xl font-bold text-blue-900 mt-2">{{ $categoryCount }}</p>
                                </div>
                                <div class="bg-blue-200 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10v10H7z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Total Item -->
                        <div class="bg-green-100 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-green-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-green-800">Total Item</h3>
                                    <p class="text-3xl font-bold text-green-900 mt-2">{{ $itemCount }}</p>
                                </div>
                                <div class="bg-green-200 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Total Transaksi -->
                        <div class="bg-yellow-100 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-yellow-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-yellow-800">Total Transaksi</h3>
                                    <p class="text-3xl font-bold text-yellow-900 mt-2">{{ $transactionCount }}</p>
                                    <p class="text-sm text-yellow-700 mt-1">Total item terjual: {{ $totalItemsSold }}</p>
                                </div>
                                <div class="bg-yellow-200 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Total Penjualan -->
                        <div class="bg-purple-100 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-purple-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-purple-800">Total Penjualan</h3>
                                    <p class="text-3xl font-bold text-purple-900 mt-2">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
                                    <p class="text-sm text-purple-700 mt-1">Semua transaksi</p>
                                </div>
                                <div class="bg-purple-200 p-3 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Grafik Penjualan Mingguan -->
                    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Penjualan Mingguan</h3>
                        <div class="w-full h-64">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Script Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data dari controller untuk grafik
            const labels = {!! json_encode($weeklyLabels) !!};
            const data = {!! json_encode($weeklySales) !!};
            
            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Penjualan (Rp)',
                        data: data,
                        fill: true,
                        backgroundColor: 'rgba(124, 58, 237, 0.1)',
                        borderColor: 'rgb(124, 58, 237)',
                        tension: 0.3,
                        borderWidth: 2,
                        pointBackgroundColor: 'rgb(124, 58, 237)',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
