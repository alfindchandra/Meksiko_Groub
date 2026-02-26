<div>
    @section('page-title', 'Laporan Inventori')

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
                            Laporan Inventori
                        </h1>
                        <p class="mt-2 text-sm text-gray-500 font-medium">Analisis interaktif status aset barang Anda</p>
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
        <p class="text-blue-100 text-xs font-bold tracking-wider uppercase mb-1">Total Produk</p>
        <p class="text-3xl font-black relative z-10">{{ number_format($summary['total_products'] ?? 0) }}</p>
    </div>
    <div class="bg-emerald-500 rounded-3xl shadow-[0_8px_30px_rgba(16,185,129,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-300 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <p class="text-emerald-100 text-xs font-bold tracking-wider uppercase mb-1">Total Quantity</p>
        <p class="text-3xl font-black relative z-10">{{ number_format($summary['total_quantity'] ?? 0) }}</p>
    </div>
    <div class="bg-amber-500 rounded-3xl shadow-[0_8px_30px_rgba(245,158,11,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-300 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <p class="text-amber-100 text-xs font-bold tracking-wider uppercase mb-1">Reserved</p>
        <p class="text-3xl font-black relative z-10">{{ number_format($summary['total_reserved'] ?? 0) }}</p>
    </div>
    <div class="bg-rose-500 rounded-3xl shadow-[0_8px_30px_rgba(225,29,72,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-300 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <p class="text-rose-100 text-xs font-bold tracking-wider uppercase mb-1">Low Stock</p>
        <p class="text-3xl font-black relative z-10">{{ number_format($summary['low_stock_items'] ?? 0) }}</p>
    </div>
    <div class="bg-indigo-500 rounded-3xl shadow-[0_8px_30px_rgba(99,102,241,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-300 relative overflow-hidden group col-span-2 md:col-span-1">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        <p class="text-indigo-100 text-xs font-bold tracking-wider uppercase mb-1">Total Nilai Asset</p>
        <p class="text-3xl font-black relative z-10">{{ number_format(($summary['total_value'] ?? 0) / 1000000, 1) }}Jt</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 lg:col-span-1">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
            <span class="mr-2 text-xl">📦</span> Stok per Kategori
        </h3>
        <div wire:ignore class="relative h-[300px]">
            <canvas id="stockByCategoryChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
            <span class="mr-2 text-xl">📉</span> Pergerakan Stok Harian
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

    function initStockCharts(newData = null) {
        // Hancurkan instance lama jika ada
        if (stockCharts.category) stockCharts.category.destroy();
        if (stockCharts.movement) stockCharts.movement.destroy();

        // 1. Stock by Category
        const catCanvas = document.getElementById('stockByCategoryChart');
        if (catCanvas) {
            const catLabels = newData ? Object.keys(newData.stockByCategory) : @json($stockByCategory->keys());
            const catData = newData ? Object.values(newData.stockByCategory).map(c => c.quantity) : @json($stockByCategory->pluck('quantity')->values());

            stockCharts.category = new Chart(catCanvas, {
                type: 'doughnut',
                data: {
                    labels: catLabels,
                    datasets: [{
                        data: catData,
                        backgroundColor: ['#6366f1', '#14b8a6', '#f59e0b', '#ec4899', '#8b5cf6', '#10b981'],
                        borderWidth: 0,
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, font: { family: "'Inter', sans-serif" } } } }
                }
            });
        }

        // 2. Stock Movement
        const movCanvas = document.getElementById('stockMovementChart');
        if (movCanvas) {
            const movementData = newData ? newData.stockMovement : @json($stockMovement);
            const dates = [...new Set(movementData.map(m => m.date))];
            const types = [...new Set(movementData.map(m => m.type))];
            
            const colorMap = {
                'in': '#10b981', // green
                'out': '#ef4444', // red
                'adjustment': '#f59e0b', // orange
                'transfer_in': '#3b82f6', // blue
                'transfer_out': '#ec4899', // pink
            };

            stockCharts.movement = new Chart(movCanvas, {
                type: 'line',
                data: {
                    labels: dates.map(d => new Date(d).toLocaleDateString('id-ID', {day: 'numeric', month: 'short'})),
                    datasets: types.map((type) => {
                        const typeLower = type.toLowerCase();
                        let color = colorMap[typeLower] || '#9ca3af';

                        return {
                            label: type.replace(/_/g, ' ').toUpperCase(),
                            data: dates.map(date => {
                                const item = movementData.find(m => m.date === date && m.type === type);
                                return item ? item.total_qty : 0;
                            }),
                            borderColor: color,
                            backgroundColor: color + '20', // Add 20% opacity for fill
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            fill: true
                        };
                    })
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top', labels: { font: { family: "'Inter', sans-serif" } } }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f3f4f6', borderDash: [4, 4] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    }

    // Integrasi Livewire 3
    document.addEventListener('livewire:initialized', () => {
        initStockCharts();
        
        Livewire.on('update-inventory-charts', (event) => {
            const data = (Array.isArray(event) ? event[0] : event).data;
            if(data) {
                setTimeout(() => initStockCharts(data), 50);
            }
        });
    });

    document.addEventListener('livewire:navigated', () => {
        initStockCharts();
    });
</script>
@endpush
        </div>
    </div>
</div>