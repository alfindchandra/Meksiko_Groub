<div>
    @section('page-title', 'Laporan Penjualan')

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center">
                            <span class="bg-indigo-100 text-indigo-600 p-2 rounded-xl mr-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </span>
                            Laporan Penjualan
                        </h1>
                        <p class="mt-2 text-sm text-gray-500 font-medium">Analisis interaktif pendapatan dan transaksi penjualan</p>
                    </div>
                </div>
            </div>

            <!-- Nav Tabs -->
            @include('livewire.admin.reports.nav-tabs')

            <!-- Filters -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] ring-1 ring-gray-100 p-6 mb-8 lg:p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2 ml-1">Dari Tanggal</label>
                        <input type="date" wire:model.live="dateFrom" class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-gray-700 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2 ml-1">Sampai Tanggal</label>
                        <input type="date" wire:model.live="dateTo" class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-gray-700 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2 ml-1">Cabang / Outlet</label>
                        <select wire:model.live="selectedOutlet" class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-gray-700">
                            <option value="">Semua Outlet</option>
                            @foreach($outlets as $outlet)
                            <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
    <div class="bg-blue-500 rounded-3xl shadow-[0_8px_30px_rgba(59,130,246,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-300 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <p class="text-blue-100 text-xs font-bold tracking-wider uppercase mb-1">Transaksi</p>
        <p class="text-3xl font-black relative z-10">{{ number_format($summary['total_sales'] ?? 0) }}</p>
    </div>
    <div class="bg-emerald-500 rounded-3xl shadow-[0_8px_30px_rgba(16,185,129,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-300 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <p class="text-emerald-100 text-xs font-bold tracking-wider uppercase mb-1">Pendapatan</p>
        <p class="text-3xl font-black relative z-10">{{ number_format(($summary['total_revenue'] ?? 0) / 1000000, 1) }}Jt</p>
    </div>
    <div class="bg-indigo-500 rounded-3xl shadow-[0_8px_30px_rgba(99,102,241,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-300 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <p class="text-indigo-100 text-xs font-bold tracking-wider uppercase mb-1">Item Terjual</p>
        <p class="text-3xl font-black relative z-10">{{ number_format($summary['total_items'] ?? 0) }}</p>
    </div>
    <div class="bg-amber-500 rounded-3xl shadow-[0_8px_30px_rgba(245,158,11,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-300 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <p class="text-amber-100 text-xs font-bold tracking-wider uppercase mb-1">Avg Order</p>
        <p class="text-3xl font-black relative z-10">{{ number_format(($summary['avg_transaction'] ?? 0) / 1000, 0) }}K</p>
    </div>
    <div class="bg-rose-500 rounded-3xl shadow-[0_8px_30px_rgba(225,29,72,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-300 relative overflow-hidden group col-span-2 md:col-span-1">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <p class="text-rose-100 text-xs font-bold tracking-wider uppercase mb-1">Total Diskon</p>
        <p class="text-3xl font-black relative z-10">{{ number_format(($summary['total_discount'] ?? 0) / 1000, 0) }}K</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Trend Chart spans full width on top -->
    <div class="lg:col-span-3 bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <span class="p-2 bg-emerald-50 rounded-xl mr-3 text-emerald-500 border border-emerald-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </span>
                Trend Pendapatan Harian
            </h3>
        </div>
        <div wire:ignore class="relative h-[320px]">
            <canvas id="salesDailyTrendChart"></canvas>
        </div>
    </div>

    <!-- The 3 sub-charts -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
            <span class="mr-2 text-xl">💳</span> Metode Bayar
        </h3>
        <div wire:ignore class="relative h-[250px]">
            <canvas id="paymentMethodsChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
            <span class="mr-2 text-xl">🏷️</span> Omset Kategori
        </h3>
        <div wire:ignore class="relative h-[250px]">
            <canvas id="categoryBreakdownChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
            <span class="mr-2 text-xl">⏰</span> Transaksi per Jam
        </h3>
        <div wire:ignore class="relative h-[250px]">
            <canvas id="hourlyPatternChart"></canvas>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">🏆 Top 10 Produk Terlaris</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Price</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($topProducts as $product)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center justify-center w-8 h-8 rounded-lg font-bold text-white text-sm
                            {{ $loop->iteration <= 3 ? 'bg-gradient-to-br from-yellow-400 to-orange-500 shadow-sm' : 'bg-gray-300' }}">
                            {{ $loop->iteration }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-gray-900">{{ $product->name }}</div>
                        <div class="text-xs text-gray-500">{{ $product->sku }}</div>
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-semibold text-gray-700">
                        {{ number_format($product->total_qty) }}
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        <span class="text-sm font-bold text-green-600">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm text-gray-600">
                        Rp {{ number_format($product->total_revenue / max($product->total_qty, 1), 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    let charts = {};

    function initAllCharts(newData = null) {
        // Destroy existing charts to prevent memory leaks and glitches
        Object.values(charts).forEach(chart => chart.destroy());

        // Format modern gradients untuk garfik trend
        const ctxDaily = document.getElementById('salesDailyTrendChart').getContext('2d');
        const gradientDaily = ctxDaily.createLinearGradient(0, 0, 0, 300);
        gradientDaily.addColorStop(0, 'rgba(16, 185, 129, 0.4)'); // emerald-500
        gradientDaily.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        // Extract Data
        const rawDailyTrend = newData ? newData.dailyTrend : @json($dailyTrend);
        const dailyLabels = rawDailyTrend.map(d => new Date(d.date).toLocaleDateString('id-ID', {day: 'numeric', month: 'short'}));
        const dailyData = rawDailyTrend.map(d => d.revenue);

        // 1. Daily Trend
        charts.dailyTrend = new Chart(ctxDaily, {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: dailyData,
                    borderColor: '#10b981', // emerald-500
                    backgroundColor: gradientDaily,
                    borderWidth: 3,
                    tension: 0.4, // smooth curve
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        padding: 12,
                        titleFont: { size: 13, family: "'Inter', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + Number(context.raw).toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: "'Inter', sans-serif" } } },
                    y: { 
                        grid: { borderDash: [4, 4], color: '#f3f4f6' },
                        ticks: { font: { family: "'Inter', sans-serif" } },
                        beginAtZero: true
                    }
                }
            }
        });

        // 2. Payment Methods
        const rawPayment = newData ? newData.paymentMethods : @json($paymentMethods);
        const paymentLabels = rawPayment.map(p => String(p.payment_method).toUpperCase());
        const paymentData = rawPayment.map(p => p.total);

        charts.payment = new Chart(document.getElementById('paymentMethodsChart'), {
            type: 'doughnut',
            data: {
                labels: paymentLabels,
                datasets: [{
                    data: paymentData,
                    backgroundColor: ['#6366f1', '#14b8a6', '#f59e0b', '#ec4899', '#8b5cf6', '#10b981'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                cutout: '70%',
                plugins: { 
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { family: "'Inter', sans-serif" } } } 
                } 
            }
        });

        // 3. Category Breakdown
        const rawCategory = newData ? newData.categoryBreakdown : @json($categoryBreakdown);
        const categoryLabels = rawCategory.map(c => c.name);
        const categoryData = rawCategory.map(c => c.total_revenue);

        charts.category = new Chart(document.getElementById('categoryBreakdownChart'), {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Revenue',
                    data: categoryData,
                    backgroundColor: '#8b5cf6',
                    borderRadius: 6,
                    barPercentage: 0.6
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { border: { display: false }, grid: { color: '#f3f4f6', drawTicks: false } }
                }
            }
        });

        // 4. Hourly Pattern
        const rawHourly = newData ? newData.hourlyPattern : @json($hourlyPattern);
        const hourlyLabels = rawHourly.map(h => String(h.hour).padStart(2, '0') + ':00');
        const hourlyData = rawHourly.map(h => h.transactions);

        charts.hourly = new Chart(document.getElementById('hourlyPatternChart'), {
            type: 'line',
            data: {
                labels: hourlyLabels,
                datasets: [{
                    label: 'Transaksi',
                    data: hourlyData,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 6
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { border: { display: false }, grid: { borderDash: [4, 4], color: '#f3f4f6' } }
                }
            }
        });
    }

    document.addEventListener('livewire:initialized', () => {
        initAllCharts();
        
        Livewire.on('update-sales-charts', (event) => {
            const data = (Array.isArray(event) ? event[0] : event).data;
            if(data) {
                setTimeout(() => initAllCharts(data), 50);
            }
        });
    });

    document.addEventListener('livewire:navigated', () => {
        initAllCharts();
    });
</script>
@endpush
        </div>
    </div>
</div>