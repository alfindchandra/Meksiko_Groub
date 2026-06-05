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
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </span>
                            Laporan Inventori
                        </h1>
                        <p class="mt-2 text-sm text-gray-500 font-medium">Analisis interaktif status aset barang Anda
                        </p>
                    </div>
                </div>
            </div>

            <!-- Nav Tabs -->
            @include('livewire.admin.reports.nav-tabs')

            <!-- Filters -->
            <div
                class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] ring-1 ring-gray-100 p-6 mb-8 lg:p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2 ml-1">Dari
                            Tanggal</label>
                        <input type="date" wire:model.live="dateFrom"
                            class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-gray-700 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2 ml-1">Sampai
                            Tanggal</label>
                        <input type="date" wire:model.live="dateTo"
                            class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-gray-700 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2 ml-1">Cabang /
                            Outlet</label>
                        <select wire:model.live="selectedOutlet"
                            class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-gray-700">
                            <option value="">Semua Outlet</option>
                            @foreach($outlets as $outlet)
                            <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <div
                    class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl shadow-[0_8px_30px_rgba(59,130,246,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-400 relative overflow-hidden group border border-blue-400/30">
                    <div
                        class="absolute -right-4 -top-4 w-28 h-28 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out">
                    </div>
                    <div
                        class="absolute -bottom-4 -left-4 w-20 h-20 bg-white opacity-5 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in">
                    </div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <p
                            class="text-blue-100 text-[10px] font-bold tracking-wider uppercase mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Total Transaksi
                        </p>
                        <p class="text-3xl md:text-4xl font-black truncate">
                            {{ number_format($summary['total_transactions']) }}</p>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-3xl shadow-[0_8px_30px_rgba(16,185,129,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-400 relative overflow-hidden group border border-emerald-400/30">
                    <div
                        class="absolute -right-4 -top-4 w-28 h-28 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out">
                    </div>
                    <div
                        class="absolute -bottom-4 -left-4 w-20 h-20 bg-white opacity-5 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in">
                    </div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <p
                            class="text-emerald-100 text-[10px] font-bold tracking-wider uppercase mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Total Pendapatan
                        </p>
                        <p class="text-3xl md:text-4xl font-black whitespace-nowrap">
                            {{ number_format($summary['total_revenue'] / 1000000, 1) }}<span
                                class="text-xl font-semibold opacity-80 ml-1">Jt</span>
                        </p>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-3xl shadow-[0_8px_30px_rgba(99,102,241,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-400 relative overflow-hidden group border border-indigo-400/30">
                    <div
                        class="absolute -right-4 -top-4 w-28 h-28 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out">
                    </div>
                    <div
                        class="absolute -bottom-4 -left-4 w-20 h-20 bg-white opacity-5 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in">
                    </div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <p
                            class="text-indigo-100 text-[10px] font-bold tracking-wider uppercase mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Lunas
                        </p>
                        <p class="text-3xl md:text-4xl font-black truncate">
                            {{ number_format($summary['paid_transactions']) }}</p>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-3xl shadow-[0_8px_30px_rgba(6,182,212,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-400 relative overflow-hidden group border border-cyan-400/30">
                    <div
                        class="absolute -right-4 -top-4 w-28 h-28 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out">
                    </div>
                    <div
                        class="absolute -bottom-4 -left-4 w-20 h-20 bg-white opacity-5 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in">
                    </div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <p
                            class="text-cyan-100 text-[10px] font-bold tracking-wider uppercase mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Selesai
                        </p>
                        <p class="text-3xl md:text-4xl font-black truncate">
                            {{ number_format($summary['completed_transactions']) }}</p>
                    </div>
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
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Min. Stok
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Reserved
                                </th>
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
                                    <span
                                        class="text-sm font-medium text-yellow-600">{{ number_format($stock->reserved) }}</span>
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
                    const catLabels = newData ? Object.keys(newData.stockByCategory) : @json($stockByCategory - >
                        keys());
                    const catData = newData ? Object.values(newData.stockByCategory).map(c => c.quantity) : @json(
                        $stockByCategory - > pluck('quantity') - > values());

                    stockCharts.category = new Chart(catCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: catLabels,
                            datasets: [{
                                data: catData,
                                backgroundColor: ['#6366f1', '#14b8a6', '#f59e0b', '#ec4899', '#8b5cf6',
                                    '#10b981'
                                ],
                                borderWidth: 0,
                                hoverOffset: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '65%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        font: {
                                            family: "'Inter', sans-serif"
                                        }
                                    }
                                }
                            }
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
                            labels: dates.map(d => new Date(d).toLocaleDateString('id-ID', {
                                day: 'numeric',
                                month: 'short'
                            })),
                            datasets: types.map((type) => {
                                const typeLower = type.toLowerCase();
                                let color = colorMap[typeLower] || '#9ca3af';

                                return {
                                    label: type.replace(/_/g, ' ').toUpperCase(),
                                    data: dates.map(date => {
                                        const item = movementData.find(m => m.date === date && m
                                            .type === type);
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
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        font: {
                                            family: "'Inter', sans-serif"
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#f3f4f6',
                                        borderDash: [4, 4]
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
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
                    if (data) {
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