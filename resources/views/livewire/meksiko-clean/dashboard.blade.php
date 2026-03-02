<div class="px-4 py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Meksiko Clean</h1>
            <p class="mt-1 text-sm text-gray-500">Overview operasional dan kinerja bengkel servis & cuci.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('meksikoclean.transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:border-primary-900 focus:ring ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Order Baru
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Stat Card 1 -->
        <div class="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow-sm rounded-2xl overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
            <dt>
                <div class="absolute bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-3 shadow-sm">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <p class="ml-16 text-sm font-medium text-gray-500 truncate">Total Transaksi</p>
            </dt>
            <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['total_transactions']) }}</p>
            </dd>
        </div>

        <!-- Stat Card 2 -->
        <div class="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow-sm rounded-2xl overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
            <dt>
                <div class="absolute bg-gradient-to-br from-green-400 to-green-500 rounded-xl p-3 shadow-sm">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="ml-16 text-sm font-medium text-gray-500 truncate">Pendapatan Bulan Ini</p>
            </dt>
            <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
                <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($metrics['revenue_this_month'], 0, ',', '.') }}</p>
            </dd>
        </div>

        <!-- Stat Card 3 -->
        <div class="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow-sm rounded-2xl overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
            <dt>
                <div class="absolute bg-gradient-to-br from-yellow-400 to-orange-400 rounded-xl p-3 shadow-sm">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="ml-16 text-sm font-medium text-gray-500 truncate">Pesanan Pending</p>
            </dt>
            <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['pending_orders']) }}</p>
                 <span class="ml-2 text-sm text-gray-500">Order</span>
            </dd>
        </div>

        <!-- Stat Card 4 -->
        <div class="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow-sm rounded-2xl overflow-hidden border border-gray-100 hover:shadow-md transition-shadow">
            <dt>
                <div class="absolute bg-gradient-to-br from-purple-500 to-indigo-500 rounded-xl p-3 shadow-sm">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <p class="ml-16 text-sm font-medium text-gray-500 truncate">Selesai (Diambil)</p>
            </dt>
            <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
                <p class="text-2xl font-semibold text-gray-900">{{ number_format($metrics['completed_orders']) }}</p>
                 <span class="ml-2 text-sm text-gray-500">Item</span>
            </dd>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Aktivitas Terbaru (Table) -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg leading-6 font-semibold text-gray-900">Tracking Order Terbaru</h3>
                <a href="#" class="text-sm font-medium text-primary-600 hover:text-primary-500">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($latest_transactions as $trx)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $trx->transaction_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $trx->customer_name }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $trx->order_type === 'mitra' && $trx->partner ? 'Mitra ' . $trx->partner->name : $trx->customer_phone }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">{{ $trx->order_type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($trx->status === 'pending')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                @elseif($trx->status === 'proses')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Diproses</span>
                                @elseif($trx->status === 'selesai')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Diambil</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('meksikoclean.transactions.index') }}" class="text-primary-600 hover:text-primary-900">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">Belum ada transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions & Info -->
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="h-24 w-24 text-primary-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zM11 7h2v6h-2zm0 8h2v2h-2z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4 z-10 relative">Informasi Kemitraan</h3>
                <div class="space-y-4 z-10 relative">
                    <div class="flex items-center justify-between p-3 bg-gray-50/50 rounded-lg">
                        <span class="text-sm font-medium text-gray-600">Total Mitra Terdaftar</span>
                        <span class="text-lg font-bold text-primary-700">{{ number_format($metrics['total_partners']) }} <span class="text-xs font-medium text-gray-500">Lokasi</span></span>
                    </div>
                </div>
                <div class="mt-8 z-10 relative">
                     <a href="{{ route('meksikoclean.partners.index') }}" class="w-full justify-center inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-xl font-semibold text-xs uppercase tracking-widest hover:bg-gray-800 transition-colors shadow-sm">
                        Kelola Kemitraan
                    </a>
                </div>
            </div>

            <!-- Categories -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Layanan Terpopuler</h3>
                @if($popular_services->isEmpty())
                    <p class="text-sm text-gray-500">Belum ada data transaksi.</p>
                @else
                    <ul class="space-y-4">
                        @php
                            $colors = ['bg-blue-500', 'bg-purple-500', 'bg-yellow-500', 'bg-green-500'];
                        @endphp
                        @foreach($popular_services as $index => $stat)
                            @php
                                $percentage = $total_sold_all > 0 ? round(($stat->total_sold / $total_sold_all) * 100) : 0;
                            @endphp
                            <li class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="w-2 h-2 rounded-full {{ $colors[$index % count($colors)] }} mr-2"></span>
                                    <span class="text-sm text-gray-700 truncate w-32" title="{{ $stat->service->name }}">{{ $stat->service->name }}</span>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $percentage }}% ({{ $stat->total_sold }})</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
