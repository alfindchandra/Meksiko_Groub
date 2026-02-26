<div>
    @section('page-title', 'Laporan Pegadaian')

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
                            Laporan Pegadaian
                        </h1>
                        <p class="mt-2 text-sm text-gray-500 font-medium">Analisis interaktif performa pinjaman gadai</p>
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

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-6 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white">
        <p class="text-blue-100 text-sm font-medium mb-2">Total Transaksi</p>
        <p class="text-4xl font-black">{{ number_format($summary['total_transactions']) }}</p>
    </div>
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 text-white">
        <p class="text-green-100 text-sm font-medium mb-2">Total Pinjaman</p>
        <p class="text-4xl font-black">{{ number_format($summary['total_loan_amount'] / 1000000, 1) }}Jt</p>
    </div>
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-xl p-6 text-white">
        <p class="text-yellow-100 text-sm font-medium mb-2">Admin Fee</p>
        <p class="text-4xl font-black">{{ number_format($summary['total_admin_fee'] / 1000, 0) }}K</p>
    </div>
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl shadow-xl p-6 text-white">
        <p class="text-indigo-100 text-sm font-medium mb-2">Aktif</p>
        <p class="text-4xl font-black">{{ number_format($summary['active_pawns']) }}</p>
    </div>
    <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl shadow-xl p-6 text-white">
        <p class="text-cyan-100 text-sm font-medium mb-2">Ditebus</p>
        <p class="text-4xl font-black">{{ number_format($summary['redeemed_pawns']) }}</p>
    </div>
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-xl p-6 text-white">
        <p class="text-red-100 text-sm font-medium mb-2">Overdue</p>
        <p class="text-4xl font-black">{{ number_format($summary['overdue_pawns']) }}</p>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

    <!-- Daily Trend -->
    <div wire:ignore class="bg-white rounded-2xl shadow-sm border p-6">
        <h3 class="text-lg font-bold mb-6">📈 Trend Transaksi Harian</h3>
        <div class="relative h-[300px] w-full">
            <canvas id="pawnDailyTrendChart"></canvas>
        </div>
    </div>

    <!-- Status Distribution -->
    <div wire:ignore class="bg-white rounded-2xl shadow-sm border p-6">
        <h3 class="text-lg font-bold mb-6">📊 Distribusi Status</h3>
        <div class="relative h-[300px] w-full">
            <canvas id="pawnStatusChart"></canvas>
        </div>
    </div>

</div>

<!-- Category Breakdown -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
    <h3 class="text-lg font-bold text-gray-900 mb-6">💎 Breakdown Kategori Barang</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach($categoryBreakdown as $cat)
        <div class="p-4 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200">
            <p class="text-xs font-bold text-yellow-700 uppercase mb-2">
                @if($cat->item_category === 'emas') 💎
                @elseif($cat->item_category === 'perhiasan') 💍
                @elseif($cat->item_category === 'elektronik') 📱
                @elseif($cat->item_category === 'kendaraan') 🏍️
                @else 📦
                @endif
                {{ ucfirst($cat->item_category) }}
            </p>
            <p class="text-2xl font-black text-gray-900">{{ number_format($cat->count) }}</p>
            <p class="text-xs text-gray-600 mt-1">Rp {{ number_format($cat->total_loan / 1000000, 1) }}Jt</p>
        </div>
        @endforeach
    </div>
</div>



@push('scripts')
<script>
    let pawnDailyChart = null;
    let pawnStatusChart = null;

    function initPawnDailyChart(data) {
        const ctx = document.getElementById('pawnDailyTrendChart');
        if (!ctx) return;
        if (pawnDailyChart) { pawnDailyChart.destroy(); pawnDailyChart = null; }

        pawnDailyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d => new Date(d.date).toLocaleDateString('id-ID')),
                datasets: [
                    {
                        label: 'Transaksi',
                        data: data.map(d => d.transactions),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Total Pinjaman',
                        data: data.map(d => d.total_loan),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
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
                    y: { type: 'linear', position: 'left', title: { display: true, text: 'Transaksi' } },
                    y1: { type: 'linear', position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'Pinjaman (Rp)' } }
                }
            }
        });
    }

    function initPawnStatusChart(data) {
        const ctx = document.getElementById('pawnStatusChart');
        if (!ctx) return;
        if (pawnStatusChart) { pawnStatusChart.destroy(); pawnStatusChart = null; }

        pawnStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.map(d => ({
                    active: 'Aktif', extended: 'Diperpanjang',
                    redeemed: 'Ditebus', defaulted: 'Lelang'
                }[d.status] || d.status.charAt(0).toUpperCase() + d.status.slice(1))),
                datasets: [{
                    data: data.map(d => d.count),
                    backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // Init langsung dari data PHP saat partial pertama di-render
    initPawnDailyChart(@json($dailyTrend));
    initPawnStatusChart(@json($statusDistribution));

    document.addEventListener('livewire:initialized', () => {
        Livewire.on('update-pawn-charts', (event) => {
            const data = (Array.isArray(event) ? event[0] : event).data;
            if(data) {
                initPawnDailyChart(data.dailyTrend);
                initPawnStatusChart(data.statusDistribution);
            }
        });
    });
</script>
@endpush
        </div>
    </div>
</div>