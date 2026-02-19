<div class="min-h-screen py-8 bg-gray-50/50">
    @section('page-title', 'Buat Transfer Baru')

    <div class="mx-auto">
        

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <form wire:submit.prevent="submit" class="p-6 sm:p-8">
                
                <div class="grid grid-cols-1 gap-y-6 gap-x-8 md:grid-cols-2 pb-8 border-b border-gray-100">
                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-gray-700">
                            <span class="bg-blue-50 text-blue-600 p-1.5 rounded-lg mr-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </span>
                            Dari Outlet <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select wire:model.live="fromOutletId" 
                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200 @if(auth()->user()->isKepalaRuko()) bg-gray-50 opacity-75 @endif"
                                @if(auth()->user()->isKepalaRuko()) disabled @endif>
                            <option value="">Pilih Outlet Pengirim</option>
                            @foreach($outlets as $outlet)
                            <option value="{{ $outlet->id }}">{{ $outlet->name }} ({{ $outlet->code }})</option>
                            @endforeach
                        </select>
                        @error('fromOutletId') <p class="text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="flex items-center text-sm font-semibold text-gray-700">
                            <span class="bg-green-50 text-green-600 p-1.5 rounded-lg mr-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </span>
                            Ke Outlet <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select wire:model="toOutletId" 
                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200">
                            <option value="">Pilih Outlet Penerima</option>
                            @foreach($outlets as $outlet)
                                @if($outlet->id != $fromOutletId)
                                <option value="{{ $outlet->id }}">{{ $outlet->name }} ({{ $outlet->code }})</option>
                                @endif
                            @endforeach
                        </select>
                        @error('toOutletId') <p class="text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="py-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-4">Pencarian Produk</label>
                    
                    @if($fromOutletId)
                    <div class="relative" x-data="{ open: @entangle('showProductSearch') }">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="searchProduct"
                                   @focus="open = true"
                                   placeholder="Ketik nama produk atau SKU untuk menambahkan..."
                                   class="block w-full pl-11 pr-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all">
                        </div>

                        <div x-show="open" 
                             x-cloak
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute z-20 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-xl max-h-72 overflow-y-auto divide-y divide-gray-50">
                            @forelse($availableProducts as $product)
                                <button type="button"
                                        wire:click="addProduct({{ $product->id }})"
                                        class="flex items-center justify-between w-full px-5 py-4 hover:bg-indigo-50/50 transition-colors group">
                                    <div class="text-left">
                                        <p class="font-bold text-gray-900 group-hover:text-indigo-700">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">SKU: <span class="font-mono">{{ $product->sku }}</span></p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $product->unit }}
                                    </span>
                                </button>
                            @empty
                                @if(strlen($searchProduct) >= 2)
                                <div class="px-5 py-10 text-center">
                                    <div class="bg-gray-50 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-sm text-gray-500 italic">Produk tidak ditemukan.</p>
                                </div>
                                @endif
                            @endforelse
                        </div>
                    </div>
                    @else
                    <div class="flex items-center p-4 bg-amber-50 rounded-xl border border-amber-100">
                        <svg class="w-5 h-5 text-amber-500 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        <p class="text-sm text-amber-800 font-medium">Silakan pilih outlet pengirim untuk mulai mencari produk.</p>
                    </div>
                    @endif
                </div>

                @if(count($items) > 0)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Daftar Item</h3>
                        <span class="text-xs font-medium px-2 py-1 bg-indigo-100 text-indigo-600 rounded-md">{{ count($items) }} Item terpilih</span>
                    </div>
                    
                    <div class="overflow-hidden border border-gray-200 rounded-2xl">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Informasi Produk</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Stok Outlet</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest w-32">Jumlah</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($items as $index => $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900">{{ $item['product_name'] }}</span>
                                            <span class="text-xs text-gray-500 font-mono">{{ $item['product_sku'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold {{ $item['available_stock'] > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                            {{ number_format($item['available_stock']) }}
                                            <span class="ml-1 text-xs opacity-70 font-normal">{{ $item['unit'] }}</span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" 
                                               wire:model.blur="items.{{ $index }}.quantity"
                                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-center font-bold"
                                               min="1" max="{{ $item['available_stock'] }}">
                                        @error("items.{$index}.quantity")
                                            <p class="mt-1 text-[10px] text-red-500 leading-tight">{{ $message }}</p>
                                        @enderror
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button type="button" 
                                                wire:click="removeItem({{ $index }})"
                                                class="inline-flex items-center p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <div class="space-y-2 mb-8">
                    <label class="block text-sm font-semibold text-gray-700">Catatan Tambahan</label>
                    <textarea wire:model="notes" 
                              rows="3" 
                              class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all"
                              placeholder="Contoh: Barang mendesak untuk stok akhir bulan..."></textarea>
                    @error('notes') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-end space-y-3 space-y-reverse sm:space-y-0 sm:space-x-4 pt-8 border-t border-gray-100">
                    <a href="{{ route('transfer.list') }}" 
                       class="w-full sm:w-auto px-6 py-2.5 text-center text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-all">
                        Batal
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto px-8 py-2.5 text-center text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-200 transition-all disabled:opacity-50"
                            wire:loading.attr="disabled"
                            wire:target="submit">
                        <span wire:loading.remove wire:target="submit" class="flex items-center justify-center">
                            Konfirmasi Transfer
                        </span>
                        <span wire:loading wire:target="submit" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>