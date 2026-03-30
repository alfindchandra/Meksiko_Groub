<div class="p-4 sm:p-6 lg:p-8 bg-gray-50 min-h-screen">
    @section('page-title', 'Dashboard - ' . $outlet->name)

    <div class="relative overflow-hidden mb-8 rounded-2xl bg-gradient-to-br from-indigo-600 to-primary-700 p-6 shadow-lg shadow-primary-200">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 h-40 w-40 rounded-full bg-black/10 blur-2xl"></div>

        <div class="relative flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-center md:text-left">
                <nav class="flex mb-2 justify-center md:justify-start" aria-label="Breadcrumb">
                    <span class="text-primary-100 text-xs font-medium uppercase tracking-wider">Operational Dashboard</span>
                </nav>
                <h2 class="text-3xl font-extrabold text-white tracking-tight">{{ $outlet->name }}</h2>
                <div class="mt-2 flex items-center justify-center md:justify-start gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 text-white border border-white/30">
                        {{ $outlet->code }}
                    </span>
                    <span class="text-primary-100 flex items-center text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $outlet->city }}
                    </span>
                </div>
            </div>
            <div class="hidden sm:flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl shadow-inner">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 mb-8 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:border-primary-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4V21M4 11v10l8 4"/></svg>
                </div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Inventory</span>
            </div>
            <p class="text-sm font-medium text-gray-500">Total Stok</p>
            <div class="flex items-baseline gap-2 mt-1">
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalStockItems) }}</p>
                <p class="text-xs text-gray-400 font-medium">SKUs</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:border-primary-300 transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500">Estimasi Nilai</p>
            <div class="flex items-baseline gap-1 mt-1">
                <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($totalStockValue / 1000000, 1) }}</p>
                <p class="text-sm font-semibold text-gray-500 underline decoration-emerald-300">Juta</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:border-red-200 transition-all {{ $lowStockCount > 0 ? 'bg-red-50/30' : '' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 {{ $lowStockCount > 0 ? 'bg-red-100 text-red-600' : 'bg-gray-50 text-gray-400' }} rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500">Stok Menipis</p>
            <p class="mt-1 text-3xl font-bold {{ $lowStockCount > 0 ? 'text-red-600' : 'text-gray-900' }}">
                {{ $lowStockCount }}
            </p>
            @if($lowStockCount > 0)
                <a href="{{ route('stock.low-stock') }}" class="inline-flex items-center mt-2 text-xs font-bold text-red-600 hover:underline">
                    LIHAT DETAIL <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:border-orange-200 transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                @if($pendingTransfers > 0)
                    <span class="flex h-2 w-2 rounded-full bg-orange-500"></span>
                @endif
            </div>
            <p class="text-sm font-medium text-gray-500">Transfer Masuk</p>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $pendingTransfers }}</p>
            @if($pendingTransfers > 0)
                <a href="{{ route('transfer.pending') }}" class="inline-flex items-center mt-2 text-xs font-bold text-orange-600 hover:underline">
                    PERLU APPROVAL <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            @endif
        </div>
    </div>

    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-[0.1em] mb-4">Aksi Cepat</h3>
    <div class="grid grid-cols-1 gap-4 mb-8 sm:grid-cols-3">
        <a href="{{ route('transfer.create') }}" class="group bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-primary-500 transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-50 text-primary-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <p class="font-bold text-gray-900 group-hover:text-primary-700">Buat Transfer</p>
                    <p class="text-xs text-gray-500">Request stok baru</p>
                </div>
            </div>
        </a>

        <a href="{{ route('sales.list') }}" class="group bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-emerald-500 transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <div>
                    <p class="font-bold text-gray-900 group-hover:text-emerald-700">Lihat Tansaksi</p>
                    <p class="text-xs text-gray-500">Lihat riwayat transaksi</p>
                </div>
            </div>
        </a>

        <a href="{{ route('stock.list') }}" class="group bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-amber-500 transition-all duration-200">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <p class="font-bold text-gray-900 group-hover:text-amber-700">Lihat Stok</p>
                    <p class="text-xs text-gray-500">Stock Barang</p>
                </div>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-2 h-5 bg-red-500 rounded-full"></span>
                    Stok Menipis
                </h3>
            </div>
            <div class="p-5">
                <div class="space-y-3">
                    @forelse($lowStockProducts as $stock)
                    <div class="group flex items-center justify-between p-4 bg-white border border-gray-100 rounded-xl hover:bg-red-50 hover:border-red-100 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 group-hover:bg-red-100 group-hover:text-red-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4V21M4 11v10l8 4" stroke-width="2"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $stock->product->name }}</p>
                                <p class="text-[10px] text-gray-400 font-mono tracking-tighter uppercase">SKU: {{ $stock->product->sku }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-lg font-black text-red-600">{{ $stock->quantity }}</span>
                                <span class="text-[10px] text-gray-400 font-medium">UNIT</span>
                            </div>
                            <p class="text-[10px] font-bold text-gray-400">Min. {{ $stock->product->min_stock }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10">
                        <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="text-gray-500 font-medium italic">Semua inventaris tercukupi.</p>
                    </div>
                    @endforelse
                </div>
                @if($lowStockCount > 5)
                <a href="{{ route('stock.low-stock') }}" class="block text-center mt-5 py-2 text-sm font-semibold text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                    Lihat {{ $lowStockCount - 5 }} Barang Lainnya
                </a>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-2 h-5 bg-indigo-500 rounded-full"></span>
                    Log Aktivitas
                </h3>
            </div>
            <div class="p-5">
                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @forelse($recentActivity as $activity)
                        <li>
                            <div class="relative pb-8">
                                @if (!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-100" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        @if($activity->type === 'transfer')
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                @if($activity->status === 'received') bg-emerald-100 text-emerald-600
                                                @elseif($activity->status === 'pending') bg-amber-100 text-amber-600
                                                @else bg-blue-100 text-blue-600 @endif">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                            </span>
                                        @elseif($activity->type === 'sale')
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white bg-emerald-100 text-emerald-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            </span>
                                        @elseif($activity->type === 'audit')
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white bg-purple-100 text-purple-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1 py-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">
                                                @if($activity->type === 'transfer')
                                                    {{ $activity->title }}
                                                    <span class="font-normal text-gray-500">{{ $activity->description }}</span>
                                                @elseif($activity->type === 'sale')
                                                    <span class="text-emerald-600">📊 Penjualan</span>
                                                    <span class="font-normal text-gray-500">{{ $activity->title }}</span>
                                                @elseif($activity->type === 'audit')
                                                    <span class="text-purple-600">✓ Audit</span>
                                                    <span class="font-normal text-gray-500">{{ $activity->title }}</span>
                                                @endif
                                            </p>
                                            <div class="mt-1 flex items-center gap-2 flex-wrap">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                                    @if($activity->type === 'transfer' && $activity->status === 'pending') bg-amber-50 text-amber-700
                                                    @elseif($activity->type === 'transfer' && $activity->status === 'received') bg-emerald-50 text-emerald-700
                                                    @elseif($activity->type === 'transfer') bg-blue-50 text-blue-700
                                                    @elseif($activity->type === 'sale') bg-emerald-50 text-emerald-700
                                                    @elseif($activity->type === 'audit') bg-purple-50 text-purple-700
                                                    @endif">
                                                    @if($activity->type === 'transfer')
                                                        {{ $activity->status }}
                                                    @elseif($activity->type === 'sale')
                                                        @if(isset($activity->total))
                                                            Rp {{ number_format($activity->total, 0, ',', '.') }}
                                                        @else
                                                            Selesai
                                                        @endif
                                                    @elseif($activity->type === 'audit')
                                                        {{ $activity->description }}
                                                    @endif
                                                </span>
                                                @if($activity->type === 'sale')
                                                    <span class="text-[10px] text-gray-500">
                                                        {{ $activity->description }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right text-xs whitespace-nowrap text-gray-400 italic">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="text-center py-10 text-gray-400 italic text-sm">Tidak ada aktivitas terbaru.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>