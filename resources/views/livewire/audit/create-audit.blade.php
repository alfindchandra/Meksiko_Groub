<div class="py-12 bg-gray-50 min-h-screen">
    @section('page-title', 'Audit Stok Barang')
    <form wire:submit.prevent="submit" class="space-y-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Audit Stok Barang</h2>
                <p class="mt-2 text-sm text-gray-600 italic">Lakukan pengecekan fisik stok secara berkala untuk akurasi data sistem.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Sesi: {{ date('d M Y') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Konfigurasi Audit</h3>
                    
                    <form wire:submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Outlet Target</label>
                            <select wire:model.live="outletId" 
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @if(auth()->user()->isKepalaRuko()) bg-gray-100 @endif"
                                    @if(auth()->user()->isKepalaRuko()) disabled @endif>
                                <option value="">Pilih Outlet</option>
                                @foreach($outlets as $outlet)
                                    <option value="{{ $outlet->id }}">{{ $outlet->name }} ({{ $outlet->code }})</option>
                                @endforeach
                            </select>
                            @error('outletId') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                       
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Catatan Internal</label>
                            <textarea wire:model="notes" rows="3" placeholder="Misal: Audit bulanan rutin..."
                                      class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        </div>
                    </form>
                </div>

                @if(count($selectedProducts) > 0)
                <div class="bg-indigo-900 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="text-md font-bold mb-4 opacity-80 uppercase tracking-wider text-xs">Ringkasan Audit</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm">Total Dicek</span>
                            <span class="font-bold text-xl">{{ count($selectedProducts) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-green-300">
                            <span class="text-sm">Surplus (+)</span>
                            <span class="font-bold">{{ collect($selectedProducts)->filter(fn($i) => $i['difference'] > 0)->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center text-red-300">
                            <span class="text-sm">Defisit (-)</span>
                            <span class="font-bold">{{ collect($selectedProducts)->filter(fn($i) => $i['difference'] < 0)->count() }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex flex-col md:flex-row md:items-center space-y-3 md:space-y-0 md:space-x-3">
                        <div class="flex-1 relative" x-data="{ open: @entangle('showProductSearch') }">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="searchProduct" @focus="open = true"
                                   placeholder="Cari SKU atau Nama Produk..."
                                   class="block w-full pl-10 rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 @if(!$outletId) bg-gray-50 cursor-not-allowed @endif"
                                   @if(!$outletId) disabled @endif>
                            
                            <div x-show="open" @click.away="open = false" x-transition
                                 class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-xl shadow-xl max-h-60 overflow-y-auto">
                                @forelse($availableProducts as $product)
                                    <button type="button" wire:click="addProduct({{ $product->id }})"
                                            class="flex items-center justify-between w-full px-4 py-3 hover:bg-indigo-50 text-left border-b border-gray-50 last:border-0 transition-colors">
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-500 font-mono">{{ $product->sku }}</p>
                                        </div>
                                        <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11H9v2H7v2h2v2h2v-2h2V9h-2V7z"/></svg>
                                    </button>
                                @empty
                                    <div class="px-4 py-6 text-center text-gray-500 text-sm italic">Produk tidak ditemukan...</div>
                                @endforelse
                            </div>
                        </div>
                        <button type="button" wire:click="loadAllProducts" 
                                class="inline-flex items-center px-4 py-2.5 border border-indigo-600 text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition-all disabled:opacity-50"
                                @if(!$outletId) disabled @endif>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            Audit Semua Produk
                        </button>
                    </div>
                    @if(!$outletId)
                        <p class="mt-2 text-xs text-amber-600 font-medium">* Pilih outlet terlebih dahulu untuk memuat produk</p>
                    @endif
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    @if(count($selectedProducts) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Item</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Sistem</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Fisik</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Selisih</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Justifikasi</th>
                                        <th class="px-6 py-4"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($selectedProducts as $index => $item)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $item['product_name'] }}</div>
                                                <div class="text-[10px] font-mono text-gray-500 uppercase tracking-tighter">{{ $item['product_sku'] }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-center font-bold text-gray-600">
                                                {{ number_format($item['system_quantity']) }} <span class="text-[10px] font-normal uppercase">{{ $item['unit'] }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="number" wire:model.live="selectedProducts.{{ $index }}.physical_quantity"
                                                       class="w-20 mx-auto block rounded-lg border-gray-300 text-center font-bold focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm shadow-sm">
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @php $diff = $item['difference']; @endphp
                                                <span class="px-2.5 py-1 rounded-full text-xs font-black {{ $diff == 0 ? 'bg-gray-100 text-gray-500' : ($diff > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                                    {{ $diff > 0 ? '+' : '' }}{{ $diff }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($diff != 0)
                                                    <input type="text" wire:model="selectedProducts.{{ $index }}.reason"
                                                           placeholder="Alasan selisih..."
                                                           class="w-full rounded-lg border-gray-300 text-xs focus:ring-indigo-500 shadow-sm">
                                                    @error("selectedProducts.{$index}.reason") <span class="text-[10px] text-red-500 block mt-1">{{ $message }}</span> @enderror
                                                @else
                                                    <span class="text-[10px] text-gray-400 font-medium italic">Data Sesuai</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <button type="button" wire:click="removeProduct({{ $index }})" class="text-gray-400 hover:text-red-500 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <div class="bg-indigo-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Belum Ada Produk</h3>
                            <p class="text-sm text-gray-500 max-w-xs mx-auto">Silakan cari produk atau muat semua produk untuk memulai proses audit fisik.</p>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-end space-y-3 space-y-reverse sm:space-y-0 sm:space-x-4 pt-8 border-t border-gray-100">
                    <a href="{{ route('audit.list') }}" 
                       class="w-full sm:w-auto px-6 py-2.5 text-center text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-all">
                        Batal
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto px-8 py-2.5 text-center text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-200 transition-all disabled:opacity-50"
                            wire:loading.attr="disabled"
                            wire:target="submit"> 
                        <span wire:loading.remove wire:target="submit" class="flex items-center justify-center">
                            Audit Sekarang
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
            </div>
        </div>
    </div>
</form>
</div>