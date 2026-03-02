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
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-3xl shadow-[0_8px_30px_rgba(59,130,246,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-400 relative overflow-hidden group border border-blue-400/30">
        <div class="absolute -right-4 -top-4 w-28 h-28 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-white opacity-5 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in"></div>
        <div class="relative z-10 flex flex-col justify-between h-full">
            <p class="text-blue-100 text-xs font-bold tracking-wider uppercase mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Total Transaksi
            </p>
            <p class="text-4xl font-black">{{ number_format($summary['total_transactions']) }}</p>
        </div>
    </div>
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-3xl shadow-[0_8px_30px_rgba(16,185,129,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-400 relative overflow-hidden group border border-emerald-400/30">
        <div class="absolute -right-4 -top-4 w-28 h-28 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-white opacity-5 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in"></div>
        <div class="relative z-10 flex flex-col justify-between h-full">
            <p class="text-emerald-100 text-xs font-bold tracking-wider uppercase mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Total Pinjaman
            </p>
            <p class="text-4xl font-black">{{ number_format($summary['total_loan_amount'] / 1000000, 1) }}<span class="text-2xl font-semibold opacity-80">Jt</span></p>
        </div>
    </div>
    <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-3xl shadow-[0_8px_30px_rgba(245,158,11,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-400 relative overflow-hidden group border border-amber-400/30">
        <div class="absolute -right-4 -top-4 w-28 h-28 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-white opacity-5 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in"></div>
        <div class="relative z-10 flex flex-col justify-between h-full">
            <p class="text-amber-100 text-xs font-bold tracking-wider uppercase mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg>
                Admin Fee
            </p>
            <p class="text-4xl font-black">{{ number_format($summary['total_admin_fee'] / 1000, 0) }}<span class="text-2xl font-semibold opacity-80">K</span></p>
        </div>
    </div>
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-3xl shadow-[0_8px_30px_rgba(99,102,241,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-400 relative overflow-hidden group border border-indigo-400/30">
        <div class="absolute -right-4 -top-4 w-28 h-28 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-white opacity-5 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in"></div>
        <div class="relative z-10 flex flex-col justify-between h-full">
            <p class="text-indigo-100 text-xs font-bold tracking-wider uppercase mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Aktif
            </p>
            <p class="text-4xl font-black">{{ number_format($summary['active_pawns']) }}</p>
        </div>
    </div>
    <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-3xl shadow-[0_8px_30px_rgba(6,182,212,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-400 relative overflow-hidden group border border-cyan-400/30">
        <div class="absolute -right-4 -top-4 w-28 h-28 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-white opacity-5 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in"></div>
        <div class="relative z-10 flex flex-col justify-between h-full">
            <p class="text-cyan-100 text-xs font-bold tracking-wider uppercase mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Ditebus
            </p>
            <p class="text-4xl font-black">{{ number_format($summary['redeemed_pawns']) }}</p>
        </div>
    </div>
    <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-3xl shadow-[0_8px_30px_rgba(225,29,72,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-400 relative overflow-hidden group border border-rose-400/30">
        <div class="absolute -right-4 -top-4 w-28 h-28 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-white opacity-5 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in"></div>
        <div class="relative z-10 flex flex-col justify-between h-full">
            <p class="text-rose-100 text-xs font-bold tracking-wider uppercase mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Overdue
            </p>
            <p class="text-4xl font-black">{{ number_format($summary['overdue_pawns']) }}</p>
        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let pawnDailyChart = null;
    let pawnStatusChart = null;

    function destroyCharts() {
        if (pawnDailyChart) {
            pawnDailyChart.destroy();
            pawnDailyChart = null;
        }
        if (pawnStatusChart) {
            pawnStatusChart.destroy();
            pawnStatusChart = null;
        }
    }

    function initPawnDailyChart(data) {
        const canvas = document.getElementById('pawnDailyTrendChart');
        if (!canvas || !data) return;

        const ctx = canvas.getContext('2d');

        if (pawnDailyChart) {
            pawnDailyChart.destroy();
            pawnDailyChart = null;
        }

        const gradientDaily = ctx.createLinearGradient(0, 0, 0, 400);
        gradientDaily.addColorStop(0, 'rgba(59, 130, 246, 0.5)');
        gradientDaily.addColorStop(1, 'rgba(59, 130, 246, 0.05)');

        const gradientLoan = ctx.createLinearGradient(0, 0, 0, 400);
        gradientLoan.addColorStop(0, 'rgba(34, 197, 94, 0.5)');
        gradientLoan.addColorStop(1, 'rgba(34, 197, 94, 0.05)');

        pawnDailyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d => 
                    new Date(d.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })
                ),
                datasets: [
                    {
                        label: 'Transaksi',
                        data: data.map(d => d.transactions),
                        borderColor: '#3b82f6',
                        backgroundColor: gradientDaily,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#3b82f6',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Total Pinjaman',
                        data: data.map(d => d.total_loan),
                        borderColor: '#22c55e',
                        backgroundColor: gradientLoan,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#22c55e',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label + ': ';
                                if (context.dataset.yAxisID === 'y1') {
                                    return label + 'Rp ' + Number(context.raw).toLocaleString('id-ID');
                                }
                                return label + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left',
                        title: { display: true, text: 'Transaksi' }
                    },
                    y1: {
                        type: 'linear',
                        position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000) + ' Jt';
                                if (value >= 1000) return (value / 1000) + ' K';
                                return value;
                            }
                        },
                        title: { display: true, text: 'Pinjaman (Rp)' }
                    }
                }
            }
        });
    }

    function initPawnStatusChart(data) {
        const canvas = document.getElementById('pawnStatusChart');
        if (!canvas || !data) return;

        const ctx = canvas.getContext('2d');

        if (pawnStatusChart) {
            pawnStatusChart.destroy();
            pawnStatusChart = null;
        }

        pawnStatusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.map(d => ({
                    active: 'Aktif',
                    extended: 'Diperpanjang',
                    redeemed: 'Ditebus',
                    defaulted: 'Lelang'
                }[d.status] || d.status)),
                datasets: [{
                    data: data.map(d => d.count),
                    backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#ef4444'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%'
            }
        });
    }

    document.addEventListener('livewire:initialized', () => {

        // INIT FIRST LOAD
        initPawnDailyChart(@json($dailyTrend));
        initPawnStatusChart(@json($statusDistribution));

        // UPDATE FROM LIVEWIRE
        Livewire.on('update-pawn-charts', (event) => {

            const payload = Array.isArray(event) ? event[0] : event;
            if (!payload?.data) return;

            destroyCharts();

            setTimeout(() => {
                initPawnDailyChart(payload.data.dailyTrend);
                initPawnStatusChart(payload.data.statusDistribution);
            }, 100);
        });

    });

    // FIX jika pakai wire:navigate
    document.addEventListener('livewire:navigated', () => {
        destroyCharts();
        setTimeout(() => {
            initPawnDailyChart(@json($dailyTrend));
            initPawnStatusChart(@json($statusDistribution));
        }, 100);
    });

</script>
@endpush
        </div>
    </div>
</div>