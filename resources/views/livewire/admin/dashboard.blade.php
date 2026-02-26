<div>
    @section('page-title', 'Dashboard Admin')

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Dashboard Admin</h1>
                        <p class="mt-2 text-sm text-gray-600">Selamat datang, {{ auth()->user()->name }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <select wire:model.live="dateRange" class="form-select rounded-xl border-gray-200">
                            <option value="today">Hari Ini</option>
                            <option value="week">Minggu Ini</option>
                            <option value="month">Bulan Ini</option>
                            <option value="year">Tahun Ini</option>
                            <option value="custom">Custom</option>
                        </select>
                        <a href="{{ route('admin.reports.sales') }}" class="btn-primary whitespace-nowrap bg-blue-600 text-white px-4 py-2 rounded-xl flex items-center shadow-sm hover:bg-blue-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Laporan
                        </a>
                    </div>
                </div>

                @if($dateRange === 'custom')
                <div class="mt-4 flex items-center space-x-4 animate-fade-in">
                    <input type="date" wire:model.live="customDateFrom" class="form-input rounded-xl border-gray-200">
                    <span class="text-gray-500">s/d</span>
                    <input type="date" wire:model.live="customDateTo" class="form-input rounded-xl border-gray-200">
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-2">Total Produk</p>
                            <p class="text-4xl font-black">{{ number_format($stats['total_products'] ?? 0) }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-4 rounded-xl text-white">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium mb-2">Outlet Aktif</p>
                            <p class="text-4xl font-black">{{ number_format($stats['total_outlets'] ?? 0) }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-4 rounded-xl"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm font-medium mb-2">Stok Menipis</p>
                            <p class="text-4xl font-black">{{ number_format($stats['low_stock_items'] ?? 0) }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-4 rounded-xl"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium mb-2">Transfer Pending</p>
                            <p class="text-4xl font-black">{{ number_format($stats['pending_transfers'] ?? 0) }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 p-4 rounded-xl"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Chart 1: Trend Penjualan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 transform hover:-translate-y-1 transition duration-300">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <span class="p-2 bg-green-50 rounded-lg mr-3"><svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg></span>
                        Trend Penjualan & Transaksi
                    </h3>
                    <div wire:ignore class="relative h-[300px] w-full">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Chart 2: Pendapatan per Outlet -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 transform hover:-translate-y-1 transition duration-300">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <span class="p-2 bg-blue-50 rounded-lg mr-3"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></span>
                        Pendapatan per Outlet
                    </h3>
                    <div wire:ignore class="relative h-[300px] w-full">
                        <canvas id="revenueByOutletChart"></canvas>
                    </div>
                </div>

                <!-- Chart 3: Penjualan per Kategori -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 transform hover:-translate-y-1 transition duration-300">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <span class="p-2 bg-purple-50 rounded-lg mr-3"><svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg></span>
                        Kategori Terlaris
                    </h3>
                    <div wire:ignore class="relative h-[300px] w-full">
                        <canvas id="salesByCategoryChart"></canvas>
                    </div>
                </div>

                <!-- Chart 4: Pergerakan Stok -> Stok per Cabang -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 transform hover:-translate-y-1 transition duration-300">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <span class="p-2 bg-orange-50 rounded-lg mr-3"><svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg></span>
                        Stok di Setiap Cabang
                    </h3>
                    <div wire:ignore class="relative h-[300px] w-full">
                        <canvas id="stockByOutletChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">Top 5 Produk Terlaris</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($topProducts ?? [] as $product)
                        <div class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                            <div class="flex items-center space-x-4">
                                <div class="bg-yellow-400 text-white w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm">#{{ $loop->iteration }}</div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $product->sku }}</p>
                                </div>
                            </div>
                            <div class="text-right text-sm">
                                <p class="font-bold text-gray-900">{{ number_format($product->total_qty) }} Unit</p>
                                <p class="text-green-600 font-medium">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="p-8 text-center text-gray-500">Data tidak ditemukan.</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl border-2 border-yellow-200 p-6 shadow-sm">
                    <h3 class="font-bold text-yellow-900 mb-4 flex items-center text-lg">
                        <span class="mr-2">📊</span> Pegadaian Aktif
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-yellow-800 text-sm">Gadai Berjalan</span>
                            <span class="font-black text-yellow-900">{{ number_format($stats['active_pawns'] ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-yellow-800 text-sm">Total Pinjaman</span>
                            <span class="font-black text-yellow-900">Rp {{ number_format(($stats['active_pawns_amount'] ?? 0) / 1000000, 1) }}Jt</span>
                        </div>
                        <div class="pt-3 border-t border-yellow-200 flex justify-between items-center">
                            <span class="text-red-600 text-sm font-bold">⚠️ Jatuh Tempo</span>
                            <span class="font-black text-red-700">{{ number_format($stats['overdue_pawns'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let salesChart, stockChart, revenueOutletChart, categoryChart;

        function initDashboardCharts() {
            const salesCanvas = document.getElementById('salesChart');
            const stockCanvas = document.getElementById('stockByOutletChart');
            const revenueOutletCanvas = document.getElementById('revenueByOutletChart');
            const categoryCanvas = document.getElementById('salesByCategoryChart');

            // Data dari Blade dikonversi ke JS sekali saja
            const salesData = @json($salesChart ?? ['labels' => [], 'revenue' => [], 'transactions' => []]);
            const stockData = @json($stockByOutlet ?? ['labels' => [], 'data' => []]);
            const revenueOutletData = @json($revenueByOutletChart ?? ['labels' => [], 'data' => []]);
            const categoryData = @json($salesByCategoryChart ?? ['labels' => [], 'data' => []]);

            if (salesCanvas) {
                if (salesChart) salesChart.destroy();
                salesChart = new Chart(salesCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: salesData.labels,
                        datasets: [
                            {
                                label: 'Pendapatan (Rp)',
                                data: salesData.revenue,
                                borderColor: '#22c55e',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                fill: true,
                                tension: 0.4,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Transaksi',
                                data: salesData.transactions,
                                borderColor: '#3b82f6',
                                borderDash: [5, 5],
                                tension: 0.4,
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        scales: {
                            y: { type: 'linear', position: 'left' },
                            y1: { type: 'linear', position: 'right', grid: { drawOnChartArea: false } }
                        }
                    }
                });
            }

            if (revenueOutletCanvas) {
                if (revenueOutletChart) revenueOutletChart.destroy();
                revenueOutletChart = new Chart(revenueOutletCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: revenueOutletData.labels,
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: revenueOutletData.data,
                            backgroundColor: '#3b82f6',
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }

            if (categoryCanvas) {
                if (categoryChart) categoryChart.destroy();
                categoryChart = new Chart(categoryCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: categoryData.labels,
                        datasets: [{
                            data: categoryData.data,
                            backgroundColor: ['#8b5cf6', '#ec4899', '#f43f5e', '#facc15', '#14b8a6', '#6366f1'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'right' } }
                    }
                });
            }

            if (stockCanvas) {
                if (stockChart) stockChart.destroy();
                stockChart = new Chart(stockCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: stockData.labels,
                        datasets: [{
                            data: stockData.data,
                            backgroundColor: ['#10b981', '#ef4444', '#f59e0b', '#3b82f6', '#8b5cf6'],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'right' } }
                    }
                });
            }
        }

        // Livewire 3 Event Listeners
        document.addEventListener('livewire:init', () => {
            // Inisialisasi awal saat halaman dimuat
            initDashboardCharts();

            // Menangkap event 'charts-refreshed' dari Livewire
            Livewire.on('charts-refreshed', (eventData) => {
                const data = eventData[0];
                updateChartsWithData(data);
            });
        });

        function updateChartsWithData(data) {
            if (salesChart) {
                salesChart.data.labels = data.sales.labels;
                salesChart.data.datasets[0].data = data.sales.revenue;
                salesChart.data.datasets[1].data = data.sales.transactions;
                salesChart.update();
            }

            if (revenueOutletChart) {
                revenueOutletChart.data.labels = data.revenueByOutlet.labels;
                revenueOutletChart.data.datasets[0].data = data.revenueByOutlet.data;
                revenueOutletChart.update();
            }

            if (categoryChart) {
                categoryChart.data.labels = data.salesByCategory.labels;
                categoryChart.data.datasets[0].data = data.salesByCategory.data;
                categoryChart.update();
            }

            if (stockChart) {
                stockChart.data.labels = data.stock.labels;
                stockChart.data.datasets[0].data = data.stock.data;
                stockChart.update();
            }
        }
    </script>
    @endpush
</div>