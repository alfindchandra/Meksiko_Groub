<div class="p-2 sm:p-3 lg:p-4 bg-gray-50/50 min-h-screen">
    @section('page-title', 'Manajemen Stok')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50/30">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                <div class="md:col-span-6">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5 ml-1">Cari Produk</label>
                    <div class="relative group">
                        <input type="text" 
                               wire:model.live.debounce.300ms="search"
                               placeholder="Cari nama produk, SKU, atau kode..." 
                               class="w-full bg-white border-gray-200 rounded-xl pl-10 pr-10 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm py-2.5 shadow-sm">
                        <div class="absolute left-3 top-2.5 text-gray-400 group-focus-within:text-primary-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        @if($search)
                        <button wire:click="$set('search', '')" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        @endif
                    </div>
                </div>

                @can('access-all-outlets')
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5 ml-1">Outlet</label>
                    <select wire:model.live="selectedOutlet" class="w-full bg-white border-gray-200 rounded-xl text-sm py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                        <option value="">Semua Outlet</option>
                        @foreach($outlets as $outlet)
                        <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endcan

                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5 ml-1">Kategori</label>
                    <select wire:model.live="selectedCategory" class="w-full bg-white border-gray-200 rounded-xl text-sm py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            <button wire:click="sortBy('product_name')" class="flex items-center space-x-1 hover:text-primary-600 transition-colors">
                                <span>Produk</span>
                                @if($sortBy === 'product_name')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/></svg>
                                @endif
                            </button>
                        </th>
                        @can('access-all-outlets')
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Outlet</th>
                        @endcan
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Kategori</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-center">Jumlah</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($stocks as $stock)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 bg-gray-100 rounded-lg mr-3 flex items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">{{ $stock->product->name }}</p>
                                    <p class="text-xs text-gray-500 font-mono tracking-tighter">{{ $stock->product->sku }}</p>
                                </div>
                            </div>
                        </td>
                        @can('access-all-outlets')
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-800">{{ $stock->outlet->name }}</span>
                                <span class="text-xs text-gray-400">{{ $stock->outlet->code }}</span>
                            </div>
                        </td>
                        @endcan
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $stock->product->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-block">
                                <p class="text-base font-bold text-gray-900">{{ number_format($stock->quantity) }}</p>
                                @if($stock->reserved > 0)
                                <div class="flex items-center justify-center text-[10px] text-orange-500 font-medium">
                                    <span class="w-1.5 h-1.5 bg-orange-400 rounded-full mr-1 animate-pulse"></span>
                                    {{ $stock->reserved }} Dipesan
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($stock->is_low_stock)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-700 border border-red-100">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                Stok Menipis
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                Aman
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="$dispatch('openStockDetail', { stockId: {{ $stock->id }} })"
                                        class="p-2 text-green-400 hover:text-primary-600 hover:bg-gray-400 rounded-lg transition-all"
                                        title="Detail">
                                    <svg class="w-5 h-5 " fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                               
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="p-4 bg-gray-50 rounded-full mb-4">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">Tidak ada stok</h3>
                                <p class="text-gray-500 max-w-xs mx-auto">Kami tidak dapat menemukan data stok yang Anda cari dengan kriteria tersebut.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50/50 border-t items-center border-gray-100">
            {{ $stocks->links() }}
        </div>
    </div>
    <!-- ===== STOCK DETAIL MODAL ===== -->
    @if($selectedStock)
    <div x-data="{ show: @entangle('showDetailModal') }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75"
                 @click="$wire.closeDetailModal()"></div>

            <!-- Modal Panel -->
            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Detail Stok</h3>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $selectedStock->product->name }}</p>
                    </div>
                    <button wire:click="closeDetailModal"
                            class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="overflow-y-auto flex-1 px-6 py-4">
                    <!-- Product & Outlet Info -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <!-- Product Info -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 uppercase font-medium mb-2">Informasi Produk</p>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-xs text-gray-500">SKU</p>
                                    <p class="font-mono font-semibold text-gray-900">{{ $selectedStock->product->sku }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Nama</p>
                                    <p class="font-semibold text-gray-900">{{ $selectedStock->product->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Kategori</p>
                                    <span class="badge badge-info text-xs">{{ $selectedStock->product->category->name }}</span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Satuan</p>
                                    <p class="font-semibold text-gray-900">{{ $selectedStock->product->unit }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Harga Satuan</p>
                                    <p class="font-semibold text-gray-900">
                                        Rp {{ number_format($selectedStock->product->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Outlet Info -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 uppercase font-medium mb-2">Informasi Stok</p>
                            <div class="space-y-2">
                                <div>
                                    <p class="text-xs text-gray-500">Outlet</p>
                                    <p class="font-semibold text-gray-900">{{ $selectedStock->outlet->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $selectedStock->outlet->code }}</p>
                                </div>
                                <div class="pt-2">
                                    <p class="text-xs text-gray-500">Stok Tersedia</p>
                                    <p class="text-3xl font-bold
                                        {{ $selectedStock->is_low_stock ? 'text-red-600' : 'text-green-600' }}">
                                        {{ number_format($selectedStock->quantity) }}
                                    </p>
                                </div>
                                @if($selectedStock->reserved > 0)
                                <div>
                                    <p class="text-xs text-gray-500">Reserved (Transfer)</p>
                                    <p class="font-semibold text-orange-600">{{ number_format($selectedStock->reserved) }}</p>
                                </div>
                                @endif
                                <div>
                                    <p class="text-xs text-gray-500">Minimum Stok</p>
                                    <p class="font-semibold text-gray-900">{{ number_format($selectedStock->product->min_stock) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Status</p>
                                    @if($selectedStock->is_low_stock)
                                    <span class="badge badge-danger">Stok Menipis</span>
                                    @else
                                    <span class="badge badge-success">Normal</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Value -->
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-900">Estimasi Nilai Stok</p>
                                <p class="text-2xl font-bold text-blue-700 mt-1">
                                    Rp {{ number_format($selectedStock->quantity * $selectedStock->product->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Stock History -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">
                            Riwayat Mutasi Stok (10 Terakhir)
                        </h4>

                        @if(count($stockHistories) > 0)
                        <div class="space-y-2">
                            @foreach($stockHistories as $history)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <!-- Icon -->
                                <div class="flex-shrink-0 mr-3">
                                    @if(in_array($history->type, ['in', 'transfer_in']))
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </div>
                                    @elseif(in_array($history->type, ['out', 'transfer_out']))
                                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                        </svg>
                                    </div>
                                    @else
                                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </div>
                                    @endif
                                </div>

                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $history->type_display }}
                                        </p>
                                        <span class="text-sm font-bold
                                            {{ in_array($history->type, ['in', 'transfer_in']) ? 'text-green-600' : 'text-red-600' }}">
                                            {{ in_array($history->type, ['in', 'transfer_in']) ? '+' : '-' }}{{ number_format($history->quantity_change) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <p class="text-xs text-gray-500 truncate">
                                            {{ $history->notes ?? '-' }}
                                        </p>
                                        <p class="text-xs text-gray-400 ml-2 whitespace-nowrap">
                                            {{ $history->created_at->format('d M Y H:i') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-between mt-1">
                                        <p class="text-xs text-gray-400">Oleh: {{ $history->user->name }}</p>
                                        <p class="text-xs text-gray-400">
                                            {{ number_format($history->quantity_before) }}
                                            <svg class="w-3 h-3 inline text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                            </svg>
                                            {{ number_format($history->quantity_after) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <svg class="w-10 h-10 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Belum ada riwayat mutasi</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end px-6 py-4 border-t border-gray-200 bg-gray-50">
                   
                    <button wire:click="closeDetailModal" class="btn-primary text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>