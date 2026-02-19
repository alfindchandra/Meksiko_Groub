<div>
    @section('page-title', 'Dashboard - ' . $outlet->name)

    <!-- Outlet Info Banner -->
    <div class="card mb-6 bg-gradient-to-r from-primary-600 to-primary-700 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">{{ $outlet->name }}</h2>
                <p class="mt-1 text-primary-100">{{ $outlet->code }} - {{ $outlet->city }}</p>
            </div>
            <div class="flex items-center justify-center w-16 h-16 bg-white/20 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="card">
            <p class="text-sm font-medium text-gray-600">Total Stok</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalStockItems) }}</p>
            <p class="mt-1 text-sm text-gray-500">items</p>
        </div>

        <div class="card">
            <p class="text-sm font-medium text-gray-600">Nilai Stok</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">
                Rp {{ number_format($totalStockValue / 1000000, 1) }}jt
            </p>
            <p class="mt-1 text-sm text-gray-500">approx.</p>
        </div>

        <div class="card">
            <p class="text-sm font-medium text-gray-600">Stok Menipis</p>
            <p class="mt-2 text-3xl font-bold {{ $lowStockCount > 0 ? 'text-red-600' : 'text-green-600' }}">
                {{ $lowStockCount }}
            </p>
            @if($lowStockCount > 0)
            <a href="{{ route('stock.low-stock') }}" class="mt-1 text-sm text-red-600 hover:text-red-700 font-medium">
                Lihat detail →
            </a>
            @else
            <p class="mt-1 text-sm text-gray-500">Semua aman</p>
            @endif
        </div>

        <div class="card">
            <p class="text-sm font-medium text-gray-600">Transfer Masuk</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $pendingTransfers }}</p>
            @if($pendingTransfers > 0)
            <a href="{{ route('transfer.pending') }}" class="mt-1 text-sm text-primary-600 hover:text-primary-700 font-medium">
                Butuh persetujuan →
            </a>
            @else
            <p class="mt-1 text-sm text-gray-500">Tidak ada</p>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-3">
        <a href="{{ route('transfer.create') }}" 
           class="card hover:shadow-lg transition-shadow cursor-pointer group">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center group-hover:bg-primary-200 transition-colors">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Buat Transfer</p>
                    <p class="text-sm text-gray-500">Request barang baru</p>
                </div>
            </div>
        </a>

        <a href="" 
           class="card hover:shadow-lg transition-shadow cursor-pointer group">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Sesuaikan Stok</p>
                    <p class="text-sm text-gray-500">Tambah/kurangi manual</p>
                </div>
            </div>
        </a>

        <a href="{{ route('audit.create') }}" 
           class="card hover:shadow-lg transition-shadow cursor-pointer group">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition-colors">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Audit Stok</p>
                    <p class="text-sm text-gray-500">Cek fisik barang</p>
                </div>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Low Stock Alert -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Stok Menipis</h3>
            <div class="space-y-3">
                @forelse($lowStockProducts as $stock)
                <div class="flex items-center justify-between p-3 bg-red-50 border border-red-100 rounded-lg">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 text-sm">{{ $stock->product->name }}</p>
                        <p class="text-xs text-gray-500">SKU: {{ $stock->product->sku }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-red-600">{{ $stock->quantity }}</p>
                        <p class="text-xs text-gray-500">Min: {{ $stock->product->min_stock }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Semua stok aman!</p>
                </div>
                @endforelse
            </div>
            @if($lowStockCount > 5)
            <div class="mt-4 text-center">
                <a href="{{ route('stock.low-stock') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                    Lihat {{ $lowStockCount - 5 }} lainnya →
                </a>
            </div>
            @endif
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h3>
            <div class="space-y-3">
                @forelse($recentActivity as $transfer)
                <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="flex-shrink-0 w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mt-0.5">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            Transfer {{ $transfer->transfer_number }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ $transfer->fromOutlet->name }} → {{ $transfer->toOutlet->name }}
                        </p>
                        <div class="flex items-center mt-1 space-x-2">
                            <span class="badge text-xs
                                @if($transfer->status === 'pending') badge-warning
                                @elseif($transfer->status === 'approved') badge-info
                                @elseif($transfer->status === 'in_transit') badge-info
                                @elseif($transfer->status === 'received') badge-success
                                @else badge-danger
                                @endif">
                                {{ ucfirst($transfer->status) }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $transfer->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">Belum ada aktivitas</p>
                @endforelse
            </div>
        </div>
    </div>
</div>