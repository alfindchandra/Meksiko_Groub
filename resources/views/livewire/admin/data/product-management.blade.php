<div>
    <div class="min-h-screen py-8 bg-gray-50/50">
        @section('page-title', 'Kelola Produk')

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kelola Produk</h2>
                    <p class="mt-1 text-sm text-gray-500">Manajemen inventaris, harga, varian satuan, dan kategorisasi produk.</p>
                </div>
                <button wire:click="openModal"
                    class="inline-flex items-center px-6 py-3 bg-indigo-600 rounded-xl font-bold text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Produk Baruuuu
                </button>
            </div>

            {{-- Filter --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                    <div class="md:col-span-8">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Cari Produk</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </span>
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nama produk atau SKU..."
                                class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 sm:text-sm transition-all">
                        </div>
                    </div>
                    <div class="md:col-span-4">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Kategori</label>
                        <select wire:model.live="categoryFilter"
                            class="block w-full py-3 px-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 sm:text-sm transition-all">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">SKU & Info</th>
                                <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Kategori</th>
                                <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Harga Dasar</th>
                                <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Varian Satuan</th>
                                <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($products as $product)
                            <tr class="hover:bg-indigo-50/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <span class="text-[10px] font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded block w-fit mb-1">{{ $product->sku }}</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $product->name }}</span>
                                    @if($product->description)
                                    <span class="text-xs text-gray-400 line-clamp-1 block mt-0.5">{{ Str::limit($product->description, 40) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs font-bold bg-gray-100 text-gray-600 rounded-lg">{{ $product->category->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <span class="text-sm font-black text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <span class="block text-[10px] text-gray-400 font-bold uppercase mt-0.5">per {{ $product->unit }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($product->activeVariants->count() > 0)
                                        <div class="flex flex-wrap gap-1 justify-center">
                                            @foreach($product->activeVariants as $v)
                                            <span class="text-[9px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-bold whitespace-nowrap">
                                                {{ $v->unit_name }} · Rp {{ number_format($v->price, 0, ',', '.') }}
                                            </span>
                                            @endforeach
                                        </div>
                                        <button wire:click="openVariantModal({{ $product->id }})" class="mt-1.5 text-[10px] font-bold text-blue-600 hover:underline">Edit Varian</button>
                                    @else
                                        <button wire:click="openVariantModal({{ $product->id }})"
                                            class="text-[10px] font-bold text-gray-400 hover:text-blue-600 border border-dashed border-gray-300 hover:border-blue-400 px-3 py-1 rounded-lg transition-all">
                                            + Tambah Varian
                                        </button>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button wire:click="toggleActive({{ $product->id }})">
                                        @if($product->is_active)
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-700 border border-emerald-200">Active</span>
                                        @else
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-100 text-rose-700 border border-rose-200">Inactive</span>
                                        @endif
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-1">
                                        <button wire:click="openVariantModal({{ $product->id }})" title="Kelola Varian Satuan"
                                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                        </button>
                                        <!-- <button wire:click="openDiscountModal({{ $product->id }})" title="Kelola Diskon Grosir"
                                            class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </button> -->
                                        <button wire:click="edit({{ $product->id }})"
                                            class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button wire:click="delete({{ $product->id }})" wire:confirm="Yakin hapus produk ini?"
                                            class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <p class="text-sm font-bold text-gray-400">Tidak ada produk ditemukan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($products->hasPages())
                <div class="px-4 py-4 bg-gray-50 border-t border-gray-100 overflow-x-auto">
                    {{ $products->links() }}
                </div>
                @endif
            </div>
        </div>

        {{-- ══════════ MODAL PRODUK ══════════ --}}
        @if($showModal)
        <div class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="$set('showModal',false)"></div>
                <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-2xl w-full border border-gray-100">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <div>
                            <h3 class="text-2xl font-black text-gray-900">{{ $editMode ? 'Edit Produk' : 'Produk Baru' }}</h3>
                            <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest font-bold">Lengkapi rincian produk</p>
                        </div>
                        <button wire:click="$set('showModal',false)" class="p-2 text-gray-400 hover:text-gray-900 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <form wire:submit.prevent="save" class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">SKU</label>
                                <input type="text" wire:model="sku" placeholder="Cth: FNB-001"
                                    class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all font-mono">
                                @error('sku')<p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kategori</label>
                                <select wire:model="category_id" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2 space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Produk</label>
                                <input type="text" wire:model="name" placeholder="Cth: Singa Beer 330ml"
                                    class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                                @error('name')<p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="md:col-span-2 space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Deskripsi</label>
                                <textarea wire:model="description" rows="2" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="Opsional..."></textarea>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">
                                    Harga Dasar (Rp)
                                    <span class="text-blue-500 normal-case font-normal ml-1 text-[9px]">— satuan terkecil</span>
                                </label>
                               <div
                                x-data="{
                                    value: @entangle('price')
                                }"
                            >
                                <input
                                    type="text"
                                    x-model="value"
                                    x-on:input="
                                        let n = $event.target.value.replace(/\D/g,'');
                                        value = new Intl.NumberFormat('id-ID').format(n);
                                    "
                                    class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl"
                                >
                            </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">
                                    Satuan Dasar
                                    <span class="text-blue-500 normal-case font-normal ml-1 text-[9px]">— untuk stok</span>
                                </label>
                                <input type="text" wire:model="unit" placeholder="btl / pcs / kg"
                                    class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                                @error('unit')<p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Min. Safety Stock</label>
                                <input type="number" wire:model="min_stock" min="0"
                                    class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                            </div>
                            <div class="flex items-center space-x-3 pt-5">
                                <input type="checkbox" wire:model="is_active" id="is_active_prod"
                                    class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="is_active_prod" class="text-sm font-bold text-gray-700">Produk Aktif</label>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
                            <p class="text-xs font-bold text-blue-800">
                                💡 Setelah simpan, gunakan tombol <strong>ikon varian (📦)</strong> di tabel untuk menambah tingkatan harga seperti:<br>
                                1 btl = Rp 40.000 &nbsp;·&nbsp; 1 ctn = Rp 390.000 &nbsp;·&nbsp; 1/2 crt = Rp 390.000
                            </p>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-50">
                            <button type="button" wire:click="$set('showModal',false)" class="px-6 py-3 text-sm font-bold text-gray-400 hover:text-gray-900">Batal</button>
                            <button type="submit" class="px-10 py-3 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">
                                <span wire:loading.remove>{{ $editMode ? 'Update Data' : 'Simpan Produk' }}</span>
                                <span wire:loading>Memproses...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- ══════════ MODAL VARIAN SATUAN ══════════ --}}
        @if($showVariantModal && $selectedProductForVariant)
        <div class="fixed inset-0 z-[110] overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm" wire:click="closeVariantModal"></div>
                <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-xl w-full border border-gray-100">

                    <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-[2.5rem]">
                        <div>
                            <h3 class="text-xl font-black text-gray-900 flex items-center gap-2">
                                <span class="text-2xl">📦</span> Varian Satuan
                            </h3>
                            <p class="text-sm font-bold text-blue-700 mt-0.5">{{ $selectedProductForVariant->name }}</p>
                            <p class="text-[11px] text-gray-500">Harga dasar: <span class="font-bold">Rp {{ number_format($selectedProductForVariant->price, 0, ',', '.') }} / {{ $selectedProductForVariant->unit }}</span></p>
                        </div>
                        <button wire:click="closeVariantModal" class="p-2 text-gray-400 hover:text-gray-900 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-5 max-h-[65vh] overflow-y-auto">

                        {{-- Contoh --}}
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3">
                            <p class="text-xs text-amber-800 font-bold">
                                💡 Contoh tingkatan satuan:<br>
                                <span class="font-black">1 btl</span> = Rp 40.000 &nbsp;·&nbsp;
                                <span class="font-black">1 ctn</span> = Rp 390.000 &nbsp;·&nbsp;
                                <span class="font-black">1/2 crt</span> = Rp 390.000
                            </p>
                        </div>

                        {{-- List varian --}}
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Varian Tersimpan ({{ count($variants) }})</p>
                            @if(count($variants) > 0)
                            <div class="space-y-2">
                                @foreach($variants as $variant)
                                <div class="flex items-center justify-between p-3.5 bg-white rounded-xl border-2
                                    {{ $editingVariantId == $variant['id'] ? 'border-blue-400 bg-blue-50/50' : 'border-gray-100' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white font-black text-xs shadow-sm">
                                            {{ $loop->iteration }}
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-900 text-sm">{{ $variant['unit_name'] }}</p>
                                            <p class="text-xs font-bold text-blue-600">Rp {{ number_format($variant['price'], 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button wire:click="toggleVariantActive({{ $variant['id'] }})"
                                            class="text-[10px] font-black px-2 py-0.5 rounded-full {{ $variant['is_active'] ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $variant['is_active'] ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                        <button wire:click="editVariant({{ $variant['id'] }})"
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button wire:click="deleteVariant({{ $variant['id'] }})" wire:confirm="Hapus varian {{ $variant['unit_name'] }}?"
                                            class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-6 border-2 border-dashed border-gray-200 rounded-xl">
                                <p class="text-sm text-gray-400 font-medium">Belum ada varian — tambahkan di bawah</p>
                            </div>
                            @endif
                        </div>

                        {{-- Form --}}
                        <div class="border-t-2 border-dashed border-gray-100 pt-5">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                                {{ $editingVariantId ? '✏ Edit Varian' : '＋ Tambah Varian Baru' }}
                            </p>
                            <div class="grid grid-cols-3 gap-3">
                                <div class="col-span-1 space-y-1">
                                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider ml-1">Nama Satuan</label>
                                    <input type="text" wire:model="variantUnitName" placeholder="btl / ctn / 1/2 crt"
                                        class="w-full px-3 py-2.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 text-sm font-bold transition-all">
                                    @error('variantUnitName')<p class="text-[10px] text-rose-500 font-bold ml-1 mt-0.5">{{ $message }}</p>@enderror
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider ml-1">Harga (Rp)</label>
                                    <input type="number" wire:model="variantPrice" min="0" placeholder="390000"
                                        class="w-full px-3 py-2.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 text-sm font-black transition-all">
                                    @error('variantPrice')<p class="text-[10px] text-rose-500 font-bold ml-1 mt-0.5">{{ $message }}</p>@enderror
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-gray-500 uppercase tracking-wider ml-1">Urutan</label>
                                    <input type="number" wire:model="variantSortOrder" min="0" placeholder="0"
                                        class="w-full px-3 py-2.5 bg-gray-50 border-transparent rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500 text-sm transition-all">
                                </div>
                            </div>
                            <div class="flex gap-3 mt-3">
                                <button wire:click="saveVariant"
                                    class="flex-1 py-2.5 bg-blue-600 text-white font-black text-sm rounded-xl hover:bg-blue-700 transition-all shadow shadow-blue-200 active:scale-95">
                                    {{ $editingVariantId ? '✓ Update Varian' : '＋ Simpan Varian' }}
                                </button>
                                @if($editingVariantId)
                                <button wire:click="cancelEditVariant"
                                    class="px-5 py-2.5 border border-gray-200 text-gray-600 font-bold text-sm rounded-xl hover:bg-gray-50 transition-all">
                                    Batal
                                </button>
                                @endif
                            </div>
                        </div>

                    </div>

                    <div class="p-5 border-t border-gray-100 bg-gray-50 flex justify-end rounded-b-[2.5rem]">
                        <button wire:click="closeVariantModal"
                            class="px-8 py-2.5 bg-gray-900 text-white font-bold text-sm rounded-xl hover:bg-black transition-all">
                            Selesai
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ══════════ MODAL DISKON ══════════ --}}
        @if($showDiscountModal && $selectedProductForDiscount)
        <div class="fixed inset-0 z-[110] overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500/60 backdrop-blur-sm" wire:click="closeDiscountModal"></div>
                <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Kelola Diskon Kuantitas</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $selectedProductForDiscount->name }}</p>
                        </div>
                        <button wire:click="closeDiscountModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="px-6 py-4 max-h-80 overflow-y-auto space-y-3">
                        @foreach($discountTiers as $index => $tier)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1 grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Min. Qty</label>
                                    <input type="number" wire:model="discountTiers.{{ $index }}.min_quantity" class="w-full border-gray-200 rounded-lg text-sm py-1.5" min="1">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Diskon (%)</label>
                                    <input type="number" wire:model="discountTiers.{{ $index }}.discount_percentage" class="w-full border-gray-200 rounded-lg text-sm py-1.5" min="0" max="100" step="0.01">
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" wire:model="discountTiers.{{ $index }}.is_active" class="rounded border-gray-300 text-indigo-600">
                                <label class="text-xs text-gray-600">Aktif</label>
                            </div>
                            <button wire:click="removeDiscountTier({{ $index }})" class="text-red-500 hover:text-red-700 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        @endforeach
                        @if(empty($discountTiers))
                        <p class="text-center text-sm text-gray-400 py-4 italic">Belum ada diskon kuantitas.</p>
                        @endif
                        <button wire:click="addDiscountTier" class="w-full py-2 border-2 border-dashed border-gray-300 rounded-lg text-sm font-bold text-gray-500 hover:border-indigo-400 hover:text-indigo-600 transition-all">
                            + Tambah Tingkat Diskon
                        </button>
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-xs text-blue-800"><strong>Contoh:</strong> Min Qty 6 = 10%, Min Qty 12 = 20%. Beli 12 item otomatis dapat diskon 20%.</p>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 rounded-b-2xl">
                        <button wire:click="closeDiscountModal" class="px-4 py-2 text-sm font-bold text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100">Batal</button>
                        <button wire:click="saveDiscounts" class="px-6 py-2 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Simpan Diskon</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>