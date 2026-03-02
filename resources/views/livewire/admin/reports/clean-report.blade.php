<div>
    @section('page-title', 'Laporan Meksiko Clean')

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center">
                            <span class="bg-indigo-100 text-indigo-600 p-2 rounded-xl mr-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </span>
                            Laporan Meksiko Clean
                        </h1>
                        <p class="mt-2 text-sm text-gray-500 font-medium">Analisis interaktif layanan cuci dan setrika</p>
                    </div>
                </div>
            </div>

            <!-- Nav Tabs -->
            @include('livewire.admin.reports.nav-tabs')

            <!-- Filters -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] ring-1 ring-gray-100 p-6 mb-8 lg:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2 ml-1">Dari Tanggal</label>
                        <input type="date" wire:model.live="dateFrom" class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-gray-700 cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2 ml-1">Sampai Tanggal</label>
                        <input type="date" wire:model.live="dateTo" class="w-full bg-gray-50 border-none rounded-2xl py-3 px-4 focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-gray-700 cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
                            Total Pendapatan
                        </p>
                        <p class="text-4xl font-black">{{ number_format($summary['total_revenue'] / 1000000, 1) }}<span class="text-2xl font-semibold opacity-80">Jt</span></p>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-3xl shadow-[0_8px_30px_rgba(99,102,241,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-400 relative overflow-hidden group border border-indigo-400/30">
                    <div class="absolute -right-4 -top-4 w-28 h-28 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
                    <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-white opacity-5 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in"></div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <p class="text-indigo-100 text-xs font-bold tracking-wider uppercase mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Lunas
                        </p>
                        <p class="text-4xl font-black">{{ number_format($summary['paid_transactions']) }}</p>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-3xl shadow-[0_8px_30px_rgba(6,182,212,0.3)] p-6 text-white transform hover:-translate-y-1 transition duration-400 relative overflow-hidden group border border-cyan-400/30">
                    <div class="absolute -right-4 -top-4 w-28 h-28 bg-white opacity-10 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
                    <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-white opacity-5 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in"></div>
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <p class="text-cyan-100 text-xs font-bold tracking-wider uppercase mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Selesai
                        </p>
                        <p class="text-4xl font-black">{{ number_format($summary['completed_transactions']) }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Trend Chart -->
                <div class="lg:col-span-3 bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <span class="p-2 bg-emerald-50 rounded-xl mr-3 text-emerald-500 border border-emerald-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </span>
                        Trend Pendapatan Harian
                    </h3>
                    <div wire:ignore class="relative h-[320px] w-full">
                        <canvas id="cleanDailyTrendChart"></canvas>
                    </div>
                </div>

                <!-- Services Chart -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <span class="mr-2 text-xl">🧺</span> Top 5 Layanan
                    </h3>
                    <div wire:ignore class="relative h-[250px] w-full">
                        <canvas id="cleanServicesChart"></canvas>
                    </div>
                </div>

                <!-- Order Types Chart -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                        <span class="mr-2 text-xl">🚚</span> Tipe Order
                    </h3>
                    <div wire:ignore class="relative h-[250px] w-full">
                        <canvas id="cleanOrderTypesChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    let cleanCharts = {};

    function initCleanCharts(newData = null) {
        Object.values(cleanCharts).forEach(chart => chart.destroy());

        // 1. Daily Trend
        const ctxDaily = document.getElementById('cleanDailyTrendChart').getContext('2d');
        const gradientDaily = ctxDaily.createLinearGradient(0, 0, 0, 400);
        gradientDaily.addColorStop(0, 'rgba(16, 185, 129, 0.5)');
        gradientDaily.addColorStop(1, 'rgba(16, 185, 129, 0.05)');

        const rawDailyTrend = newData ? newData.dailyTrend : @json($dailyTrend);
        
        cleanCharts.dailyTrend = new Chart(ctxDaily, {
            type: 'line',
            data: {
                labels: rawDailyTrend.map(d => new Date(d.date).toLocaleDateString('id-ID', {day: 'numeric', month: 'short'})),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: rawDailyTrend.map(d => d.revenue),
                    borderColor: '#10b981',
                    backgroundColor: gradientDaily,
                    borderWidth: 3,
                    tension: 0.4,
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
                interaction: { mode: 'index', intersect: false },
                animation: { y: { duration: 2000, easing: 'easeOutElastic' } },
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        titleFont: { size: 13, family: "'Inter', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) { return 'Rp ' + Number(context.raw).toLocaleString('id-ID'); }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: "'Inter', sans-serif" }, color: '#6b7280' } },
                    y: { 
                        grid: { borderDash: [4, 4], color: '#f3f4f6', drawBorder: false },
                        ticks: { 
                            font: { family: "'Inter', sans-serif" }, color: '#6b7280',
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000) + ' Jt';
                                if (value >= 1000) return (value / 1000) + ' K';
                                return value;
                            }
                        }
                    }
                }
            }
        });

        // 2. Services Breakdown
        const rawServices = newData ? newData.serviceBreakdown : @json($serviceBreakdown);
        const ctxServices = document.getElementById('cleanServicesChart').getContext('2d');
        const gradientServices = ctxServices.createLinearGradient(0, 0, 0, 400);
        gradientServices.addColorStop(0, '#8b5cf6');
        gradientServices.addColorStop(1, '#c4b5fd');

        cleanCharts.services = new Chart(ctxServices, {
            type: 'bar',
            data: {
                labels: rawServices.map(s => s.name),
                datasets: [{
                    label: 'Revenue',
                    data: rawServices.map(s => s.total_revenue),
                    backgroundColor: gradientServices,
                    borderRadius: 8,
                    barPercentage: 0.6
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                animation: { delay: (context) => context.dataIndex * 100 },
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(139, 92, 246, 0.9)',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) { return 'Rp ' + Number(context.raw).toLocaleString('id-ID'); }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: "'Inter', sans-serif" }, color: '#6b7280' } },
                    y: { display: false }
                }
            }
        });

        // 3. Order Types
        const rawOrderTypes = newData ? newData.orderTypes : @json($orderTypes);

        cleanCharts.orderTypes = new Chart(document.getElementById('cleanOrderTypesChart'), {
            type: 'doughnut',
            data: {
                labels: rawOrderTypes.map(o => (o.order_type === 'pickup' ? 'Penjemputan' : 'Bawa Sendiri')),
                datasets: [{
                    data: rawOrderTypes.map(o => o.count),
                    backgroundColor: ['#3b82f6', '#14b8a6'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                cutout: '75%',
                animation: { animateScale: true, animateRotate: true, duration: 1500, easing: 'easeOutQuart' },
                plugins: { 
                    legend: { 
                        position: 'bottom', 
                        labels: { usePointStyle: true, padding: 20, font: { family: "'Inter', sans-serif", size: 12 }, color: '#4b5563' } 
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#111827',
                        bodyColor: '#374151',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        boxPadding: 6,
                        callbacks: {
                            label: function(context) { return ' ' + context.label + ': ' + Number(context.raw).toLocaleString('id-ID'); }
                        }
                    }
                } 
            }
        });
    }

    document.addEventListener('livewire:initialized', () => {
        initCleanCharts();
        
        Livewire.on('update-clean-charts', (event) => {
            const data = (Array.isArray(event) ? event[0] : event).data;
            if(data) {
                setTimeout(() => initCleanCharts(data), 50);
            }
        });
    });

    document.addEventListener('livewire:navigated', () => {
        initCleanCharts();
    });
</script>
@endpush
