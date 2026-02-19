<div>
    @section('page-title', 'Dashboard Admin')

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Outlets -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Outlet</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $totalOutlets }}</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.data.outlets') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                    Lihat detail →
                </a>
            </div>
        </div>

        <!-- Total Products -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Produk</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $totalProducts }}</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('stock.list') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                    Lihat detail →
                </a>
            </div>
        </div>

        <!-- Total Stock Value -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Nilai Total Stok</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">
                        Rp {{ number_format($totalStockValue, 0, ',', '.') }}
                    </p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Transfers -->
        <div class="card hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Transfer Pending</p>
                    <p class="mt-1 text-3xl font-bold text-gray-900">{{ $pendingTransfers }}</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('transfer.pending') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                    Lihat detail →
                </a>
            </div>
        </div>
    </div>

    <!-- Low Stock Alert -->
    @if($lowStockItems > 0)
    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div class="flex-1">
                <p class="text-sm font-medium text-yellow-800">
                    Peringatan Stok Menipis!
                </p>
                <p class="text-sm text-yellow-700 mt-1">
                    Terdapat {{ $lowStockItems }} item dengan stok di bawah minimum. 
                    <a href="{{ route('stock.low-stock') }}" class="font-semibold underline">Lihat sekarang</a>
                </p>
            </div>
            <button wire:click="refreshData" class="ml-4 text-yellow-700 hover:text-yellow-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Stock by Outlet -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Stok Per Outlet</h3>
                <button wire:click="refreshData" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
            <div class="space-y-4">
                @forelse($stockByOutlet as $outlet)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-semibold text-primary-600">
                                {{ substr($outlet->code, -2) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $outlet->name }}</p>
                            <p class="text-sm text-gray-500">{{ $outlet->code }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-gray-900">
                            {{ number_format($outlet->total_items ?? 0) }}
                        </p>
                        <p class="text-xs text-gray-500">items</p>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">Belum ada data outlet</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Transfers -->
        <div class="card">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Transfer Terbaru</h3>
                <a href="{{ route('transfer.list') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                    Lihat semua →
                </a>
            </div>
            <div class="space-y-3">
                @forelse($recentTransfers as $transfer)
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-1">
                            <p class="font-medium text-gray-900 text-sm">{{ $transfer->transfer_number }}</p>
                            <span class="badge 
                                @if($transfer->status === 'pending') badge-warning
                                @elseif($transfer->status === 'approved') badge-info
                                @elseif($transfer->status === 'in_transit') badge-info
                                @elseif($transfer->status === 'received') badge-success
                                @else badge-danger
                                @endif">
                                {{ ucfirst($transfer->status) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500">
                            {{ $transfer->fromOutlet->name }} → {{ $transfer->toOutlet->name }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $transfer->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <a href="{{ route('transfer.detail', $transfer->id) }}" 
                       class="ml-4 text-primary-600 hover:text-primary-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                @empty
                <p class="text-center text-gray-500 py-8">Belum ada transfer</p>
                @endforelse
            </div>
        </div>
    </div>
</div>