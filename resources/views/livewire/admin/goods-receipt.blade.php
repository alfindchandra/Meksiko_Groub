<div>
    @section('page-title', 'Penerimaan Barang')

    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Penerimaan Barang ke Gudang</h2>
            <p class="mt-1 text-sm text-gray-600">Input barang yang diterima dari supplier</p>
        </div>

        <form wire:submit.prevent="submit">
            <!-- Header Info -->
            <div class="card mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penerimaan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gudang Tujuan</label>
                        <select wire:model="warehouseId" class="form-input">
                            <option value="">Pilih Gudang</option>
                            @foreach($warehouses as $w)
                            <option value="{{ $w->id }}">{{ $w->name }} ({{ $w->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Penerimaan</label>
                        <input type="date" wire:model="receiptDate" class="form-input">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Supplier</label>
                        <input type="text" wire:model="supplierName" class="form-input" placeholder="PT. Sumber Jaya">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Invoice / PO</label>
                        <input type="text" wire:model="invoiceNumber" class="form-input" placeholder="INV-20240101">
                    </div>
                </div>
            </div>

            <!-- Product Search -->
            <div class="card mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Produk</label>
                <div class="relative" x-data="{ open: @entangle('showProductSearch') }">
                    <input type="text"
                           wire:model.live.debounce.300ms="searchProduct"
                           @focus="open = true"
                           placeholder="Cari produk..."
                           class="form-input">

                    <div x-show="open"
                         @click.away="open = false"
                         x-transition
                         class="absolute z-10 w-full mt-2 bg-white border rounded-lg shadow-lg max-h-64 overflow-y-auto"
                         style="display: none;">
                        @if(count($availableProducts) > 0)
                            @foreach($availableProducts as $product)
                            <button type="button"
                                    wire:click="addProduct({{ $product['id'] }})"
                                    class="flex items-center justify-between w-full px-4 py-3 hover:bg-gray-50 border-b">
                                <div class="text-left">
                                    <p class="font-medium text-gray-900">{{ $product['name'] }}</p>
                                    <p class="text-sm text-gray-500">SKU: {{ $product['sku'] }}</p>
                                </div>
                            </button>
                            @endforeach
                        @elseif(strlen($searchProduct) >= 2)
                            <div class="px-4 py-8 text-center text-gray-500">
                                <p class="text-sm">Produk tidak ditemukan</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            @if(count($receivedProducts) > 0)
            <div class="card mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Barang Diterima ({{ count($receivedProducts) }} items)
                </h3>

                <div class="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga Satuan</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($receivedProducts as $index => $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-900">{{ $item['product_name'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $item['product_sku'] }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number"
                                           wire:model.live="receivedProducts.{{ $index }}.quantity"
                                           min="1"
                                           class="w-24 px-3 py-2 text-center border rounded-lg">
                                    <span class="text-xs text-gray-500 block text-center mt-1">{{ $item['unit'] }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number"
                                           wire:model.live="receivedProducts.{{ $index }}.unit_cost"
                                           min="0"
                                           class="w-32 px-3 py-2 text-right border rounded-lg">
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <p class="text-lg font-bold text-primary-600">
                                        Rp {{ number_format($item['total_cost'], 0, ',', '.') }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button"
                                            wire:click="removeProduct({{ $index }})"
                                            class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-right font-semibold">Total</td>
                                <td class="px-6 py-4 text-right font-semibold">
                                    {{ number_format(collect($receivedProducts)->sum('quantity')) }} unit
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-primary-600 text-lg">
                                    Rp {{ number_format(collect($receivedProducts)->sum('total_cost'), 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @endif

            <!-- Notes -->
            <div class="card mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea wire:model="notes" rows="3" class="form-input" placeholder="Catatan tambahan..."></textarea>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.dashboard') }}" class="btn-secondary">Batal</a>
                <button type="submit"
                        class="btn-primary"
                        @if(empty($receivedProducts)) disabled @endif>
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Terima Barang
                </button>
            </div>
        </form>
    </div>
</div>