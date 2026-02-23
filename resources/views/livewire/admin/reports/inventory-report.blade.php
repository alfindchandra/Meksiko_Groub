<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
        <p class="text-blue-100 text-sm font-medium mb-2">Total Produk</p>
        <p class="text-3xl font-black">{{ number_format($summary['total_products'] ?? 0) }}</p>
    </div>
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
        <p class="text-green-100 text-sm font-medium mb-2">Total Quantity</p>
        <p class="text-3xl font-black">{{ number_format($summary['total_quantity'] ?? 0) }}</p>
    </div>
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
        <p class="text-yellow-100 text-sm font-medium mb-2">Reserved</p>
        <p class="text-3xl font-black">{{ number_format($summary['total_reserved'] ?? 0) }}</p>
    </div>
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
        <p class="text-red-100 text-sm font-medium mb-2">Low Stock</p>
        <p class="text-3xl font-black">{{ number_format($summary['low_stock_items'] ?? 0) }}</p>
    </div>
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
        <p class="text-purple-100 text-sm font-medium mb-2">Total Nilai</p>
        <p class="text-3xl font-black">{{ number_format(($summary['total_value'] ?? 0) / 1000000, 1) }}Jt</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
            <span class="mr-2">📦</span> Stok per Kategori
        </h3>
        <div wire:ignore class="relative h-[300px]">
            <canvas id="stockByCategoryChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
            <span class="mr-2">📊</span> Pergerakan Stok
        </h3>
        <div wire:ignore class="relative h-[300px]">
            <canvas id="stockMovementChart"></canvas>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
        <h3 class="text-lg font-bold text-red-900 flex items-center">
            <span class="mr-2">⚠️</span> Produk dengan Stok Menipis
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Outlet</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Stok</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Min. Stok</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Reserved</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($lowStockProducts as $stock)
                <tr class="hover:bg-red-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">{{ $stock->product->name }}</div>
                        <div class="text-xs text-gray-500">{{ $stock->product->sku }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ $stock->outlet->name }}
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-md font-bold text-sm">
                            {{ number_format($stock->quantity) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-600">
                        {{ number_format($stock->product->min_stock) }}
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        <span class="text-sm font-medium text-yellow-600">{{ number_format($stock->reserved) }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">
                        Semua stok dalam kondisi aman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    let stockCharts = {};

    function initStockCharts() {
        // Hancurkan instance lama jika ada
        if (stockCharts.category) stockCharts.category.destroy();
        if (stockCharts.movement) stockCharts.movement.destroy();

        // 1. Stock by Category
        const catCanvas = document.getElementById('stockByCategoryChart');
        if (catCanvas) {
            stockCharts.category = new Chart(catCanvas, {
                type: 'doughnut',
                data: {
                    labels: @json($stockByCategory->keys()),
                    datasets: [{
                        data: @json($stockByCategory->pluck('quantity')->values()),
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        // 2. Stock Movement
        const movCanvas = document.getElementById('stockMovementChart');
        if (movCanvas) {
            const movementData = @json($stockMovement);
            const dates = [...new Set(movementData.map(m => m.date))];
            const types = [...new Set(movementData.map(m => m.type))];
            
            stockCharts.movement = new Chart(movCanvas, {
                type: 'line',
                data: {
                    labels: dates.map(d => new Date(d).toLocaleDateString('id-ID', {day: 'numeric', month: 'short'})),
                    datasets: types.map((type, idx) => ({
                        label: type.replace('_', ' ').toUpperCase(),
                        data: dates.map(date => {
                            const item = movementData.find(m => m.date === date && m.type === type);
                            return item ? item.total_qty : 0;
                        }),
                        borderColor: ['#10b981', '#ef4444', '#f59e0b', '#3b82f6', '#8b5cf6'][idx],
                        backgroundColor: 'transparent',
                        tension: 0.4,
                        pointRadius: 3
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    }

    // Integrasi Livewire 3
    document.addEventListener('livewire:init', () => {
        initStockCharts();
        
        Livewire.on('charts-refreshed', () => {
            setTimeout(initStockCharts, 50);
        });
    });

    document.addEventListener('livewire:navigated', () => {
        initStockCharts();
    });
</script>
@endpush