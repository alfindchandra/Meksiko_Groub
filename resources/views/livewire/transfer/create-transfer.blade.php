<div>
    @section('page-title', 'Buat Transfer Baru')

    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Buat Transfer Baru</h2>
                <p class="mt-2 text-sm text-gray-500">
                    @if(auth()->user()->isRider())
                        Ajukan permintaan barang dari gudang pusat ke outlet Anda
                    @else
                        Transfer barang antar outlet atau gudang
                    @endif
                </p>
            </div>
            
            <a href="{{ route('transfer.list') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                Kembali ke Daftar
            </a>
        </div>

        <form wire:submit.prevent="submit" class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @can('manage-users')    
            <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="p-2 bg-blue-50 rounded-lg mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Konfigurasi Rute</h3>
                    </div>

                   
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="relative">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2 ml-1">Dari Outlet</label>
                            <select wire:model.live="fromOutletId" 
                                    class="block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-all sm:text-sm py-3"
                                    @if(auth()->user()->isRider()) disabled @endif>
                                <option value="">Pilih Outlet Asal</option>
                                @foreach($fromOutlets as $outlet)
                                <option value="{{ $outlet->id }}">
                                    {{ $outlet->name }} @if($outlet->type === 'warehouse') (GUDANG) @endif
                                </option>
                                @endforeach
                            </select>
                            @error('fromOutletId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="relative">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2 ml-1">Ke Outlet</label>
                            <select wire:model="toOutletId" 
                                    class="block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-all sm:text-sm py-3"
                                    @if(auth()->user()->isRider()) disabled @endif>
                                <option value="">Pilih Outlet Tujuan</option>
                                @foreach($toOutlets as $outlet)
                                <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                                @endforeach
                            </select>
                            @error('toOutletId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    @if(auth()->user()->isRider())
                    <div class="mt-6 flex items-center p-4 bg-amber-50 rounded-xl border border-amber-100">
                        <svg class="w-5 h-5 text-amber-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10a8 8 0 11-16 0 8 8 0 0118 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" fill-rule="evenodd" clip-rule="evenodd"/></svg>
                        <p class="text-sm text-amber-800 font-medium">Anda hanya dapat meminta stok dari Gudang Pusat.</p>
                    </div>
                    @endif
                </div>
                @endcan
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="max-w-2xl mx-auto">
                    <div class="relative" x-data="{ open: @entangle('showProductSearch') }">
                        <label class="block text-center text-sm font-semibold text-gray-700 mb-4">Cari & Tambah Produk</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-blue-500 text-gray-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </div>
                            <input type="text"
                                   wire:model.live.debounce.300ms="searchProduct"
                                   @focus="open = true"
                                   placeholder="Cari SKU atau Nama Produk..."
                                   class="block w-full pl-11 pr-4 py-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-blue-50/50 focus:border-blue-500 transition-all shadow-inner text-lg">
                        </div>

                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute z-20 w-full mt-3 bg-white border border-gray-100 rounded-2xl shadow-2xl max-h-80 overflow-y-auto p-2 border-t-4 border-t-blue-500">
                            @if(count($availableProducts) > 0)
                                @foreach($availableProducts as $product)
                                <button type="button"
                                        wire:click="addProduct({{ $product['id'] }})"
                                        class="flex items-center w-full p-4 hover:bg-blue-50 rounded-xl transition-colors group text-left">
                                    <div class="flex-1">
                                        <p class="font-bold text-gray-900 group-hover:text-blue-700 transition-colors">{{ $product['name'] }}</p>
                                        <p class="text-xs font-mono text-gray-500 uppercase tracking-tighter">{{ $product['sku'] }}</p>
                                    </div>
                                    <div class="ml-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="bg-blue-600 text-white p-1 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                        </span>
                                    </div>
                                </button>
                                @endforeach
                            @elseif(strlen($searchProduct) >= 2)
                                <div class="py-12 text-center">
                                    <div class="mb-3 flex justify-center text-gray-300">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Produk tidak ditemukan</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if(count($selectedProducts) > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                    <h3 class="font-bold text-gray-900 uppercase tracking-wider text-sm">Item Transfer</h3>
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">{{ count($selectedProducts) }} Item</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4 text-center">Ketersediaan</th>
                                <th class="px-6 py-4 text-center">Jumlah Transfer</th>
                                <th class="px-6 py-4 text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($selectedProducts as $index => $item)
                            <tr class="group hover:bg-gray-50/80 transition-colors {{ $item['quantity'] > $item['available_stock'] ? 'bg-red-50/50' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-900">{{ $item['product_name'] }}</span>
                                        <span class="text-xs font-mono text-gray-400 uppercase tracking-tighter">{{ $item['product_sku'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-sm font-bold {{ $item['available_stock'] > 0 ? 'text-gray-900' : 'text-red-500' }}">
                                            {{ number_format($item['available_stock']) }}
                                        </span>
                                        <span class="text-[10px] uppercase font-semibold text-gray-400 leading-none">{{ $item['unit'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-center">
                                        <input type="number"
                                               wire:model.live="selectedProducts.{{ $index }}.quantity"
                                               min="1"
                                               class="w-24 px-3 py-2 text-center rounded-xl border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all font-bold sm:text-sm {{ $item['quantity'] > $item['available_stock'] ? 'border-red-500 focus:ring-red-100' : '' }}">
                                        @error("selectedProducts.{$index}.quantity")
                                            <span class="text-[10px] text-red-600 font-medium mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button type="button"
                                            wire:click="removeProduct({{ $index }})"
                                            class="p-2 text-gray-300 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3 ml-1">Catatan Tambahan</label>
                <textarea wire:model="notes"
                          rows="3"
                          class="block w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 transition-all sm:text-sm"
                          placeholder="Contoh: Barang butuh cepat untuk promo akhir minggu..."></textarea>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('transfer.list') }}" 
                   class="px-6 py-3 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors uppercase tracking-widest">
                    Batal
                </a>
                <button type="submit"
                        class="relative inline-flex items-center px-8 py-3 overflow-hidden text-white bg-blue-600 rounded-xl group active:bg-blue-700 focus:outline-none focus:ring disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-lg shadow-blue-200"
                        wire:loading.attr="disabled"
                        @if(empty($selectedProducts)) disabled @endif>
                    <span class="absolute right-0 transition-transform translate-x-full group-hover:-translate-x-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    </span>
                    
                    <span class="text-sm font-bold uppercase tracking-widest transition-all group-hover:mr-4">
                        <span wire:loading.remove wire:target="submit">Kirim Permintaan</span>
                        <span wire:loading wire:target="submit">Memproses...</span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>