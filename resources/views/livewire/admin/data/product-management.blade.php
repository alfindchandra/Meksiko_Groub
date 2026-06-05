<div>
    <div x-data="{ showModal: @entangle('showModal') }" class="min-h-screen py-8 bg-gray-50/50">
        @section('page-title', 'Kelola Produk')

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kelola Produk</h2>
                    <p class="mt-1 text-sm text-gray-500">Manajemen inventaris, harga, dan kategorisasi produk Anda.</p>
                </div>
                <button @click="showModal = true" wire:click="openModal"
                    class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Produk Baru
                </button>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                    <div class="md:col-span-8">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Cari Produk</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Ketik nama produk atau SKU..."
                                class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all sm:text-sm">
                        </div>
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 ml-1">Kategori</label>
                        <select wire:model.live="categoryFilter"
                            class="block w-full py-3 px-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all sm:text-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">SKU & Info</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Kategori</th>
                                <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Harga Satuan</th>
                                <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Safety Stock</th>
                                <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                                <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($products as $product)
                            <tr class="hover:bg-indigo-50/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-mono font-bold text-indigo-600 bg-indigo-50 self-start px-2 py-0.5 rounded mb-1">{{ $product->sku }}</span>
                                        <span class="text-sm font-bold text-gray-900">{{ $product->name }}</span>
                                        @if($product->description)
                                        <span class="text-xs text-gray-400 line-clamp-1 mt-0.5">{{ Str::limit($product->description, 40) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-bold bg-gray-100 text-gray-600 rounded-lg">{{ $product->category->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <span class="text-sm font-black text-gray-900 tracking-tight">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span class="block text-[10px] text-gray-400 font-bold uppercase mt-0.5">per {{ $product->unit }}</span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap font-bold text-gray-700">
                                    {{ $product->min_stock }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <button wire:click="toggleActive({{ $product->id }})"
                                        class="relative inline-flex items-center group-hover:scale-110 transition-transform cursor-pointer">
                                        @if($product->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-700 border border-emerald-200">Active</span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-100 text-rose-700 border border-rose-200">Inactive</span>
                                        @endif
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button wire:click="openDiscountModal({{ $product->id }})" 
                                            class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-xl transition-all" 
                                            title="Kelola Diskon">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>

                                        <button wire:click="edit({{ $product->id }})" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="delete({{ $product->id }})" wire:confirm="Yakin ingin menghapus produk ini?" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="max-w-xs mx-auto text-center">
                                        <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4V4" />
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-gray-900">Produk Kosong</h3>
                                        <p class="text-xs text-gray-500 mt-1">Sistem tidak menemukan data produk yang sesuai.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($products->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                    {{ $products->links() }}
                </div>
                @endif
            </div>
        </div>

        <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showModal = false"></div>

                <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-2xl w-full overflow-hidden border border-gray-100">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <div>
                            <h3 class="text-2xl font-black text-gray-900">{{ $editMode ? 'Edit Produk' : 'Produk Baru' }}</h3>
                            <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest font-bold">Lengkapi rincian produk di bawah ini</p>
                        </div>
                        <button @click="showModal = false" class="p-2 text-gray-400 hover:text-gray-900 hover:bg-white rounded-full transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="save" class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">SKU (Kode Produk)</label>
                                <input type="text" wire:model="sku" placeholder="Contoh: FNB-001" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                @error('sku') <p class="text-[10px] text-rose-500 font-bold uppercase tracking-tight ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kategori</label>
                                <select wire:model="category_id" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <p class="text-[10px] text-rose-500 font-bold uppercase tracking-tight ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Produk</label>
                                <input type="text" wire:model="name" placeholder="Contoh: Coca Cola 330ml" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                @error('name') <p class="text-[10px] text-rose-500 font-bold uppercase tracking-tight ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Deskripsi Singkat</label>
                                <textarea wire:model="description" rows="2" placeholder="Detail spesifikasi produk..." class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"></textarea>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Harga Jual (Rp)</label>
                                <input type="number" wire:model="price" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all font-black text-gray-900 tracking-tight">
                                @error('price') <p class="text-[10px] text-rose-500 font-bold uppercase tracking-tight ml-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Satuan</label>
                                <select wire:model="unit" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                    <option value="btl">Btl</option>
                                    <option value="ctn">Ctn</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Min. Safety Stock</label>
                                <input type="number" wire:model="min_stock" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            </div>

                            <div class="flex items-center space-x-3 pt-6">
                                <input type="checkbox" wire:model="is_active" id="is_active_product" class="h-6 w-11 rounded-full border-transparent bg-gray-200 text-indigo-600 focus:ring-transparent focus:ring-offset-0 transition-all cursor-pointer">
                                <label for="is_active_product" class="text-sm font-black text-gray-700">Produk Aktif</label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-8 border-t border-gray-50">
                            <button type="button" @click="showModal = false" class="px-6 py-3 text-sm font-bold text-gray-400 hover:text-gray-900 transition-colors">Batal</button>
                            <button type="submit" class="px-10 py-3 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all active:scale-95 disabled:opacity-50">
                                <span wire:loading.remove>{{ $editMode ? 'Update Data' : 'Simpan Produk' }}</span>
                                <span wire:loading class="flex items-center">
                                    <svg class="animate-spin h-5 w-5 mr-3 text-white" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if($selectedProductForDiscount)
<div x-data="{ show: @entangle('showDiscountModal') }"
     x-show="show"
     x-transition
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="show = false"></div>

        <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Kelola Diskon Kuantitas</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $selectedProductForDiscount->name }}</p>
                    </div>
                    <button wire:click="closeDiscountModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="px-6 py-4 max-h-96 overflow-y-auto">
                @if(count($discountTiers) > 0)
                <div class="space-y-3">
                    @foreach($discountTiers as $index => $tier)
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1 grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Min. Qty</label>
                                <input type="number" 
                                       wire:model="discountTiers.{{ $index }}.min_quantity"
                                       class="form-input text-sm"
                                       min="1"
                                       placeholder="6">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Diskon (%)</label>
                                <input type="number" 
                                       wire:model="discountTiers.{{ $index }}.discount_percentage"
                                       class="form-input text-sm"
                                       min="0"
                                       max="100"
                                       step="0.01"
                                       placeholder="10">
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" 
                                   wire:model="discountTiers.{{ $index }}.is_active"
                                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <label class="text-xs text-gray-600">Aktif</label>
                        </div>
                        <button wire:click="removeDiscountTier({{ $index }})"
                                class="text-red-600 hover:text-red-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">Belum ada diskon kuantitas</p>
                </div>
                @endif

                <button wire:click="addDiscountTier"
                        class="mt-4 w-full btn-secondary text-sm">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Tingkat Diskon
                </button>

                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs text-blue-800">
                        <strong>💡 Contoh:</strong> Min Qty 6 = Diskon 10%, Min Qty 12 = Diskon 20%<br>
                        Saat pelanggan beli 12 item, otomatis dapat diskon 20% (bukan 10%)
                    </p>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex items-center justify-end space-x-3">
                <button wire:click="closeDiscountModal" class="btn-secondary">
                    Batal
                </button>
                <button wire:click="saveDiscounts" class="btn-primary">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Diskon
                </button>
            </div>
        </div>
    </div>
</div>
@endif
    </div>

    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #D1D5DB; }
    </style>
</div>