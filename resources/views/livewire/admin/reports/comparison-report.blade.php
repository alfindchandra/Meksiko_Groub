<div>
    @section('page-title', 'Komparasi Outlet')

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
                            Komparasi Outlet
                        </h1>
                        <p class="mt-2 text-sm text-gray-500 font-medium">Bandingkan performa tiap cabang dan kategori produk</p>
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

<div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] ring-1 ring-gray-100 overflow-hidden mb-8">
    <div class="px-8 py-6 border-b border-gray-100 bg-white flex justify-between items-center">
        <h3 class="text-xl font-bold text-gray-900 flex items-center">
            <span class="p-2 bg-blue-50 rounded-xl mr-3 text-blue-500 border border-blue-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </span>
            Perbandingan Performa Outlet
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Outlet</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Avg/Transaksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($outletComparison as $outlet)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">{{ $outlet->outlet->name }}</div>
                        <div class="text-xs text-gray-500">{{ $outlet->outlet->code }}</div>
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-900 font-semibold">
                        {{ number_format($outlet->transactions) }}
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        <span class="text-sm font-bold text-green-600">Rp {{ number_format($outlet->revenue, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap text-sm text-gray-600 italic">
                        Rp {{ number_format($outlet->revenue / max($outlet->transactions, 1), 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada data transaksi outlet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] ring-1 ring-gray-100 p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-8 flex items-center">
            <span class="p-2 bg-indigo-50 rounded-xl mr-3 text-indigo-500 border border-indigo-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </span>
            Trend 6 Bulan Terakhir
        </h3>
        <div wire:ignore class="relative h-[350px] w-full">
            <canvas id="monthlyComparisonChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] ring-1 ring-gray-100 p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-8 flex items-center">
            <span class="p-2 bg-amber-50 rounded-xl mr-3 text-amber-500 border border-amber-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </span>
            Performa Kategori Produk
        </h3>
        <div class="space-y-6">
        @foreach($categoryPerformance as $cat)
        <div>
            <div class="flex items-center justify-between mb-2">
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ $cat->name }}</p>
                    <p class="text-xs text-gray-500">{{ number_format($cat->total_qty) }} unit • {{ number_format($cat->transaction_count) }} transaksi</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-blue-600">Rp {{ number_format($cat->total_revenue / 1000000, 1) }}Jt</p>
                </div>
            </div>
            <div class="relative pt-1">
                <div class="overflow-hidden h-3 text-xs flex rounded-full bg-gray-100 border border-gray-200">
                    @php
                        $maxRevenue = $categoryPerformance->max('total_revenue');
                        $percentage = $maxRevenue > 0 ? ($cat->total_revenue / $maxRevenue) * 100 : 0;
                    @endphp
                    <div style="width:{{ $percentage }}%" 
                         class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-1000">
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    let comparisonChart;

    function initComparisonChart(newData = null) {
        const canvas = document.getElementById('monthlyComparisonChart');
        if (!canvas) return;

        if (comparisonChart) comparisonChart.destroy();

        const data = newData ? newData.monthlyComparison : @json(collect($monthlyComparison));

        comparisonChart = new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: data.map(item => item.month),
                datasets: [
                    {
                        label: 'Penjualan',
                        data: data.map(item => item.sales),
                        backgroundColor: '#3b82f6', // blue-500
                        borderRadius: 6,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8
                    }, 
                    {
                        label: 'Pegadaian',
                        data: data.map(item => item.pawns),
                        backgroundColor: '#f59e0b', // amber-500
                        borderRadius: 6,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'top', 
                        labels: { 
                            usePointStyle: true, 
                            padding: 20, 
                            font: { family: "'Inter', sans-serif" } 
                        } 
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { 
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f3f4f6' }
                    }
                }
            }
        });
    }

    document.addEventListener('livewire:initialized', () => {
        initComparisonChart();
        
        Livewire.on('update-comparison-charts', (event) => {
            const data = (Array.isArray(event) ? event[0] : event).data;
            if(data) {
                setTimeout(() => initComparisonChart(data), 50);
            }
        });
    });

    document.addEventListener('livewire:navigated', () => {
        initComparisonChart();
    });
</script>
@endpush
        </div>
    </div>
</div>