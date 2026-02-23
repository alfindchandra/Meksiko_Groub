<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
        <p class="text-blue-100 text-sm font-medium mb-2">Total Transaksi</p>
        <p class="text-3xl font-black">{{ number_format($summary['total_sales'] ?? 0) }}</p>
    </div>
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
        <p class="text-green-100 text-sm font-medium mb-2">Total Pendapatan</p>
        <p class="text-3xl font-black">{{ number_format(($summary['total_revenue'] ?? 0) / 1000000, 1) }}Jt</p>
    </div>
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
        <p class="text-purple-100 text-sm font-medium mb-2">Item Terjual</p>
        <p class="text-3xl font-black">{{ number_format($summary['total_items'] ?? 0) }}</p>
    </div>
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
        <p class="text-yellow-100 text-sm font-medium mb-2">Avg Transaksi</p>
        <p class="text-3xl font-black">{{ number_format(($summary['avg_transaction'] ?? 0) / 1000, 0) }}K</p>
    </div>
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
        <p class="text-red-100 text-sm font-medium mb-2">Total Diskon</p>
        <p class="text-3xl font-black">{{ number_format(($summary['total_discount'] ?? 0) / 1000, 0) }}K</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">📈 Trend Harian</h3>
        <div wire:ignore class="relative h-[300px]">
            <canvas id="salesDailyTrendChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">💳 Metode Pembayaran</h3>
        <div wire:ignore class="relative h-[300px]">
            <canvas id="paymentMethodsChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">🏷️ Penjualan per Kategori</h3>
        <div wire:ignore class="relative h-[300px]">
            <canvas id="categoryBreakdownChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">⏰ Pola Waktu Transaksi</h3>
        <div wire:ignore class="relative h-[300px]">
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

    function initAllCharts() {
        // Destroy existing charts to prevent memory leaks and glitches
        Object.values(charts).forEach(chart => chart.destroy());

        // 1. Daily Trend
        charts.dailyTrend = new Chart(document.getElementById('salesDailyTrendChart'), {
            type: 'line',
            data: {
                labels: @json($dailyTrend->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
                datasets: [{
                    label: 'Revenue',
                    data: @json($dailyTrend->pluck('revenue')),
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });

        // 2. Payment Methods
        charts.payment = new Chart(document.getElementById('paymentMethodsChart'), {
            type: 'doughnut',
            data: {
                labels: @json($paymentMethods->pluck('payment_method')->map(fn($m) => strtoupper($m))),
                datasets: [{
                    data: @json($paymentMethods->pluck('total')),
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444'],
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });

        // 3. Category Breakdown
        charts.category = new Chart(document.getElementById('categoryBreakdownChart'), {
            type: 'bar',
            data: {
                labels: @json($categoryBreakdown->pluck('name')),
                datasets: [{
                    label: 'Revenue',
                    data: @json($categoryBreakdown->pluck('total_revenue')),
                    backgroundColor: '#3b82f6',
                    borderRadius: 8
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });

        // 4. Hourly Pattern
        charts.hourly = new Chart(document.getElementById('hourlyPatternChart'), {
            type: 'bar',
            data: {
                labels: @json($hourlyPattern->pluck('hour')->map(fn($h) => $h . ':00')),
                datasets: [{
                    label: 'Transaksi',
                    data: @json($hourlyPattern->pluck('transactions')),
                    backgroundColor: '#a855f7',
                    borderRadius: 8
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    }

    document.addEventListener('livewire:init', () => {
        initAllCharts();
        
        // Listen for filter updates from Livewire
        Livewire.on('charts-refreshed', () => {
            // Wait for next tick to ensure DOM is ready
            setTimeout(initAllCharts, 50);
        });
    });

    document.addEventListener('livewire:navigated', () => {
        initAllCharts();
    });
</script>
@endpush