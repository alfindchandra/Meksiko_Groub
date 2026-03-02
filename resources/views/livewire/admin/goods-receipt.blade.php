<div>
    @section('page-title', 'Penerimaan Barang')

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Penerimaan Barang</h2>
                <p class="mt-1 text-gray-500 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    Input stok masuk dari supplier ke gudang pusat.
                </p>
            </div>
            <div class="bg-primary-50 px-4 py-2 rounded-2xl border border-primary-100 flex items-center shadow-sm">
                <span class="text-primary-700 font-bold text-sm">
                    {{ \Carbon\Carbon::parse($receiptDate)->format('d F Y') }}
                </span>
            </div>
        </div>

        <form wire:submit.prevent="submit" class="space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Informasi Pengiriman
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-600 ml-1">Gudang Tujuan</label>
                        <select wire:model="warehouseId" class="w-full rounded-xl border-gray-200 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-all">
                            <option value="">-- Pilih Gudang --</option>
                            @foreach($warehouses as $w)
                                <option value="{{ $w->id }}">{{ $w->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-600 ml-1">Supplier</label>
                        <input type="text" wire:model="supplierName" placeholder="Nama Supplier" class="w-full rounded-xl border-gray-200 focus:ring-primary-500 shadow-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-600 ml-1">No. Invoice</label>
                        <input type="text" wire:model="invoiceNumber" placeholder="INV/2024/..." class="w-full rounded-xl border-gray-200 focus:ring-primary-500 shadow-sm uppercase font-mono text-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-600 ml-1">Tanggal Masuk</label>
                        <input type="date" wire:model="receiptDate" class="w-full rounded-xl border-gray-200 focus:ring-primary-500 shadow-sm">
                    </div>
                </div>
            </div>

            <div class="relative z-30" x-data="{ open: @entangle('showProductSearch') }">
                <div class="bg-white p-4 rounded-2xl border-2 border-primary-100 shadow-lg shadow-primary-50 flex flex-col md:flex-row items-center gap-4">
                    <div class="flex-shrink-0 bg-primary-500 p-3 rounded-xl shadow-lg shadow-primary-200 text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <div class="flex-1 w-full relative">
                        <input type="text" 
                               wire:model.live.debounce.300ms="searchProduct"
                               @focus="open = true"
                               class="w-full border-none focus:ring-0 text-lg font-medium placeholder-gray-400" 
                               placeholder="Cari Produk atau Scan Barcode di sini...">
                        
                        <div x-show="open" 
                             @click.away="open = false" 
                             x-transition 
                             class="absolute left-0 right-0 mt-4 bg-white border border-gray-100 rounded-2xl shadow-2xl max-h-80 overflow-y-auto" 
                             style="display: none;">
                            @if(count($availableProducts) > 0)
                                @foreach($availableProducts as $product)
                                <button type="button" wire:click="addProduct({{ $product['id'] }})" class="w-full flex items-center p-4 hover:bg-primary-50 border-b border-gray-50 last:border-0 transition-colors">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center font-bold text-primary-600">{{ substr($product['name'], 0, 1) }}</div>
                                    <div class="ml-4 text-left">
                                        <p class="font-bold text-gray-900">{{ $product['name'] }}</p>
                                        <p class="text-xs text-gray-500">SKU: {{ $product['sku'] }}</p>
                                    </div>
                                    <svg class="ml-auto w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                                @endforeach
                            @elseif(strlen($searchProduct) > 1)
                                <div class="p-8 text-center text-gray-400">Produk tidak ditemukan.</div>
                            @endif
                        </div>
                    </div>
                    <div wire:loading wire:target="searchProduct" class="pr-4">
                        <svg class="animate-spin h-5 w-5 text-primary-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Item Produk</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center w-32">Qty</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right w-44">Harga Satuan</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-right w-44">Total</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center w-20"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($receivedProducts as $index => $item)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900">{{ $item['product_name'] }}</p>
                                    <p class="text-xs text-gray-500 font-mono">SKU: {{ $item['product_sku'] }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-center">
                                        <input type="number" wire:model.live.debounce.1000ms="receivedProducts.{{ $index }}.quantity" class="w-20 text-center rounded-xl border-gray-200 focus:ring-primary-500 focus:border-primary-500 py-1 font-bold">
                                        <span class="text-[10px] text-gray-400 mt-1 uppercase font-bold">{{ $item['unit'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-bold">Rp</span>
                                        <input type="number" wire:model.live="receivedProducts.{{ $index }}.unit_cost" class="w-full pl-9 rounded-xl border-gray-200 focus:ring-primary-500 py-1 text-right font-bold">
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-black text-gray-900 tracking-tight">
                                        Rp {{ number_format((float)($item['total_cost'] ?: 0), 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button" wire:click="removeProduct({{ $index }})" class="p-2 text-gray-300 hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-50 rounded-full mb-4">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                    <p class="text-gray-400 font-medium text-sm">Belum ada item ditambahkan. Gunakan kolom cari di atas.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($receivedProducts) > 0)
                <div class="bg-gray-50/50 p-6 border-t border-gray-100 grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-widest block mb-2">Catatan Tambahan</label>
                        <textarea wire:model="notes" rows="2" class="w-full rounded-2xl border-gray-200 focus:ring-primary-500 text-sm shadow-inner" placeholder="Contoh: Barang diterima dalam kondisi baik..."></textarea>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-gray-500">
                            <span class="text-sm font-bold">Total Kuantitas:</span>
                            <span class="font-bold text-gray-900">{{ collect($receivedProducts)->sum('quantity') }} Unit</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-gray-200 pt-3">
                            <span class="text-sm font-bold text-gray-900">Total Nilai Barang:</span>
                            <span class="text-3xl font-black text-primary-600 tracking-tighter">
                                Rp {{ number_format(collect($receivedProducts)->sum('total_cost'), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-4">
                <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto text-center px-8 py-3 text-gray-500 font-bold hover:bg-gray-100 rounded-2xl transition-all">
                    Batal
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto px-10 py-4 bg-gradient-to-br from-primary-600 to-primary-700 text-white font-black rounded-2xl shadow-xl shadow-primary-200 hover:shadow-primary-300 transform transition active:scale-95 flex items-center justify-center gap-2 disabled:opacity-50"
                        @if(empty($receivedProducts)) disabled @endif>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    SIMPAN PENERIMAAN
                </button>
            </div>
        </form>
    </div>
</div>