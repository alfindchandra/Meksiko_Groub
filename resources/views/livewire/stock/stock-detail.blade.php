<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Stok</h1>
                    <p class="mt-2 text-lg text-gray-600">{{ $stock->product->name }}</p>
                    <p class="text-sm text-gray-500">SKU: {{ $stock->product->sku }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Terakhir Diperbarui</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $stock->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Filter Rentang Tanggal Mutasi</h2>
                    <p class="text-sm text-gray-500">Contoh: 1 Maret 2026 sampai 30 Maret 2026.</p>
                </div>

                <div>
                    <label for="fromDate" class="text-sm font-medium text-gray-700">Dari</label>
                    <input type="date" id="fromDate" wire:model="fromDate" wire:change="loadStockHistories"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500" />
                </div>

                <div>
                    <label for="toDate" class="text-sm font-medium text-gray-700">Sampai</label>
                    <input type="date" id="toDate" wire:model="toDate" wire:change="loadStockHistories"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500" />
                </div>
            </div>
        </div>

        <!-- Back Button -->


        <!-- Stock History -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Riwayat Mutasi Stok (10 Terakhir)</h2>

            @if($stockHistories->count() > 0)
            <div class="space-y-4">
                @foreach($stockHistories as $history)
                <div
                    class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-100 hover:bg-gray-100 transition-colors">
                    <!-- Icon -->
                    <div class="flex-shrink-0 mr-4">
                        @if(in_array($history->type, ['in', 'transfer_in']))
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        @elseif(in_array($history->type, ['out', 'transfer_out']))
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                            </svg>
                        </div>
                        @else
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">{{ $history->type_display ?? $history->type }}
                            </p>
                            <span
                                class="text-sm font-bold {{ in_array($history->type, ['in', 'transfer_in']) ? 'text-green-600' : 'text-red-600' }}">
                                {{ in_array($history->type, ['in', 'transfer_in']) ? '+' : '-' }}{{ number_format($history->quantity_change) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-xs text-gray-500 truncate">{{ $history->notes ?? '-' }}</p>
                            <p class="text-xs text-gray-400 ml-2 whitespace-nowrap">
                                {{ $history->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <p class="text-xs text-gray-400">Oleh: {{ $history->user->name }}</p>
                            <p class="text-xs text-gray-400">
                                {{ number_format($history->quantity_before) }}
                                <svg class="w-3 h-3 inline text-gray-400 mx-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                                {{ number_format($history->quantity_after) }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="mt-4 text-lg font-medium text-gray-900">Belum ada riwayat mutasi</p>
                <p class="mt-2 text-gray-500">Riwayat perubahan stok akan muncul di sini.</p>
            </div>
            @endif
        </div>
    </div>
</div>