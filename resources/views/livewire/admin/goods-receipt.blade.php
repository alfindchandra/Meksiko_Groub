<div>
    @section('page-title', 'Penerimaan Barang')

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Penerimaan Barang</h2>
                <p class="mt-2 text-sm text-gray-500 flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Catat barang masuk dari supplier dengan detail ke dalam gudang.
                </p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary-100 text-primary-800">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    {{ \Carbon\Carbon::parse($receiptDate)->format('d M Y') }}
                </span>
            </div>
        </div>

        <form wire:submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Info & Search -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Informasi Penerimaan Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                Informasi Form
                            </h3>
                        </div>
                        <div class="p-6 space-y-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gudang Tujuan</label>
                                <select wire:model="warehouseId" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200">
                                    <option value="">-- Pilih Gudang --</option>
                                    @foreach($warehouses as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }} ({{ $w->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Penerimaan</label>
                                <input type="date" wire:model="receiptDate" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Supplier</label>
                                <input type="text" wire:model="supplierName" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200" placeholder="PT. Sumber Jaya">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Invoice / PO</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <input type="text" wire:model="invoiceNumber" class="w-full pl-10 rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200" placeholder="INV-20240101">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Product Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative z-20">
                        <label class="block text-sm font-semibold text-gray-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Cari & Tambah Produk
                        </label>
                        <div class="relative" x-data="{ open: @entangle('showProductSearch') }">
                            <div class="relative">
                                <input type="text"
                                       wire:model.live.debounce.300ms="searchProduct"
                                       @focus="open = true"
                                       placeholder="Ketik nama atau SKU produk..."
                                       class="w-full pl-4 pr-10 py-3 rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-all duration-300 bg-gray-50 focus:bg-white text-gray-900">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none" wire:loading wire:target="searchProduct">
                                    <svg class="animate-spin h-5 w-5 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </div>

                            <div x-show="open"
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-2"
                                 class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl max-h-72 overflow-y-auto"
                                 style="display: none;">
                                @if(count($availableProducts) > 0)
                                    <ul class="divide-y divide-gray-100">
                                        @foreach($availableProducts as $product)
                                        <li>
                                            <button type="button"
                                                    wire:click="addProduct({{ $product['id'] }})"
                                                    class="w-full flex items-center p-3 hover:bg-primary-50 transition-colors focus:outline-none focus:bg-primary-50 text-left">
                                                <div class="flex-shrink-0 h-10 w-10 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center font-bold">
                                                    {{ substr($product['name'], 0, 1) }}
                                                </div>
                                                <div class="ml-4 flex-1">
                                                    <p class="text-sm font-semibold text-gray-900">{{ $product['name'] }}</p>
                                                    <p class="text-xs text-gray-500">SKU: {{ $product['sku'] }}</p>
                                                </div>
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            </button>
                                        </li>
                                        @endforeach
                                    </ul>
                                @elseif(strlen($searchProduct) >= 2)
                                    <div class="p-6 text-center text-gray-500">
                                        <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p class="text-sm">Produk tidak ditemukan</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Products Table & Summary -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                Daftar Item Diterima
                            </h3>
                            <span class="bg-primary-100 text-primary-800 text-xs font-bold px-2.5 py-0.5 rounded-full">{{ count($receivedProducts) }} Items</span>
                        </div>
                        
                        <div class="p-0 flex-1 overflow-x-auto">
                            @if(count($receivedProducts) > 0)
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-white">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Qty</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Harga Satuan</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-40">Subtotal</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($receivedProducts as $index => $item)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-gray-100 text-gray-500 rounded-lg flex items-center justify-center font-bold text-lg">
                                                    {{ substr($item['product_name'], 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $item['product_name'] }}</div>
                                                    <div class="text-xs text-gray-500">{{ $item['product_sku'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex flex-col items-center">
                                                <input type="number"
                                                       wire:model.live.debounce.2000ms="receivedProducts.{{ $index }}.quantity"
                                                       min="1"
                                                       class="w-full text-center rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                                <span class="text-[10px] uppercase font-semibold text-gray-400 mt-1">{{ $item['unit'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="number"
                                                   wire:model.live="receivedProducts.{{ $index }}.unit_cost"
                                                   min="0"
                                                   class="w-full text-right rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4 text-right whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">
                                                Rp {{ number_format((float)($item['total_cost'] === '' ? 0 : $item['total_cost']), 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center whitespace-nowrap">
                                            <button type="button"
                                                    wire:click="removeProduct({{ $index }})"
                                                    class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                                <div class="w-24 h-24 mb-4 text-gray-200">
                                    <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.5 14H8c-1.66 0-3-1.34-3-3s1.34-3 3-3l.14.01C8.54 8.28 10.13 7 12 7c2.21 0 4 1.79 4 4h.5c1.38 0 2.5 1.12 2.5 2.5S17.88 16 16.5 16z"/></svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada produk</h3>
                                <p class="text-gray-500 text-sm max-w-sm">Silakan cari dan tambahkan produk yang akan dimasukkan ke gudang melalui form pencarian di sebelah kiri.</p>
                            </div>
                            @endif
                        </div>
                        
                        @if(count($receivedProducts) > 0)
                        <div class="bg-gray-50/80 px-6 py-5 border-t border-gray-100">
                            <div class="flex flex-col md:flex-row justify-end items-end md:items-center space-y-4 md:space-y-0 md:space-x-8">
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 mb-1">Total Kuantitas</p>
                                    <p class="text-xl font-bold text-gray-900">
                                        {{ number_format(collect($receivedProducts)->sum(fn($item) => (float)($item['quantity'] === '' ? 0 : $item['quantity']))) }} <span class="text-sm font-normal text-gray-500">Unit</span>
                                    </p>
                                </div>
                                <div class="hidden md:block w-px h-10 bg-gray-300"></div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500 mb-1">Total Nilai Barang</p>
                                    <p class="text-2xl font-black text-primary-600">
                                        Rp {{ number_format(collect($receivedProducts)->sum(fn($item) => (float)($item['total_cost'] === '' ? 0 : $item['total_cost'])), 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Notes & Actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-800 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Catatan Tambahan
                            </label>
                            <textarea wire:model="notes" rows="3" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 transition-colors duration-200" placeholder="Tulis catatan jika ada..."></textarea>
                        </div>
                        
                        <div class="flex flex-col-reverse sm:flex-row sm:items-center justify-end gap-3 sm:gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                                Batal
                            </a>
                            <button type="submit"
                                    class="inline-flex justify-center items-center px-6 py-3 border border-transparent shadow-lg text-sm font-bold rounded-xl text-white bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed transform hover:-translate-y-0.5"
                                    @if(empty($receivedProducts)) disabled @endif>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan & Terima Barang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>