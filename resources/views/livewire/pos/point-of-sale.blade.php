<div class="min-h-screen bg-[#f8fafc] pb-12">
    @section('page-title', 'Point of Sale')

    <div class="max-w-[1600px] mx-auto p-4 lg:p-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- ══════════ LEFT PANEL ══════════ --}}
            <div class="lg:col-span-7 xl:col-span-8 space-y-6">

                {{-- Search --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text"
                               wire:model.live.debounce.300ms="searchProduct"
                               placeholder="Scan Barcode atau Cari Produk (Min. 2 karakter)..."
                               class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary-500 text-slate-900 text-lg transition-all"
                               autofocus>
                    </div>
                </div>

                {{-- Search Results --}}
                @if(strlen($searchProduct) >= 2)
                <div class="flex flex-col gap-2 animate-in fade-in slide-in-from-bottom-4 duration-300">
                    @forelse($products as $product)
                    <button type="button" wire:click="addToCart({{ $product->id }})"
                            class="group bg-white p-4 rounded-2xl border border-slate-200 hover:border-primary-500 hover:shadow-md transition-all text-left w-full">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-800 group-hover:text-primary-600">{{ $product->name }}</h4>
                                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mt-0.5">{{ $product->sku }}{{ $product->unit ? ' · ' . $product->unit : '' }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="text-primary-600 font-extrabold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                @if($product->activeVariants->count() > 0)
                                <div class="text-[10px] font-bold text-blue-600 mt-0.5">📦 {{ $product->activeVariants->count() }} Varian — klik untuk pilih</div>
                                @endif
                                @if($product->activeDiscountTiers->count() > 0)
                                <div class="text-[10px] font-bold text-green-600 mt-0.5">✓ Ada Harga Grosir</div>
                                @endif
                            </div>
                        </div>

                        @if($product->activeVariants->count() > 0)
                        <div class="mt-2 bg-blue-50 rounded-lg p-2 border border-blue-100">
                            <p class="text-[10px] font-bold text-blue-700 mb-1">Pilihan Satuan:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($product->activeVariants as $variant)
                                <span class="text-[9px] bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-bold">
                                    {{ $variant->unit_name }} = Rp {{ number_format($variant->price, 0, ',', '.') }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($product->activeDiscountTiers->count() > 0)
                        <div class="mt-2 bg-green-50 rounded-lg p-2 border border-green-100">
                            <p class="text-[10px] font-bold text-green-700 mb-1">Harga Grosir:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($product->activeDiscountTiers as $tier)
                                <span class="text-[9px] bg-green-100 text-green-700 px-2 py-0.5 rounded font-bold">
                                    {{ $tier->min_quantity }}+ pcs = -{{ number_format($tier->discount_percentage, 0) }}%
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </button>
                    @empty
                    <div class="bg-white rounded-2xl border border-dashed border-slate-300 py-12 text-center">
                        <p class="text-slate-400 font-medium">Produk tidak ditemukan.</p>
                    </div>
                    @endforelse
                </div>
                @endif

                {{-- Cart --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Item Belanja
                        </h3>
                        <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-600 shadow-sm">
                            {{ count($cart) }} Baris
                        </span>
                    </div>

                    <div class="p-6">
                        @if(count($cart) > 0)
                        <div class="space-y-3">
                            @foreach($cart as $index => $item)
                            <div class="relative p-4 rounded-2xl border-2 transition-all group shadow-sm
                                {{ ($item['item_discount_percentage'] ?? 0) > 0 ? 'border-green-100 bg-green-50/30' : 'border-slate-50 bg-white' }}">

                                @if(($item['item_discount_percentage'] ?? 0) > 0)
                                <div class="absolute -top-2 -right-2 bg-green-500 text-white text-[10px] font-black px-2 py-1 rounded-lg shadow-lg uppercase tracking-tighter">
                                    -{{ number_format($item['item_discount_percentage'], 0) }}%
                                </div>
                                @endif

                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <p class="font-bold text-slate-900 leading-tight">{{ $item['product_name'] }}</p>
                                        <p class="text-xs font-mono text-slate-400 uppercase mt-0.5">
                                            {{ $item['product_sku'] }}
                                            @if(!empty($item['product_unit']))
                                            · <span class="bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded text-[9px] font-sans font-black">{{ $item['product_unit'] }}</span>
                                            @endif
                                            @if(!empty($item['variant_name']))
                                            · <span class="bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded text-[9px] font-sans font-black">{{ $item['variant_name'] }}</span>
                                            @endif
                                        </p>
                                        @if(($item['has_tier_discount'] ?? false))
                                        <div class="mt-1.5 inline-flex items-center text-[10px] font-bold text-green-600 uppercase tracking-tight bg-green-100 px-2 py-0.5 rounded">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"/></svg>
                                            Grosir
                                        </div>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between md:justify-end gap-4">
                                        {{-- Qty --}}
                                        <div class="flex items-center bg-white border border-slate-200 rounded-xl p-1 shadow-sm">
                                            <button wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] - 1 }})"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 text-slate-500 transition-colors text-lg font-bold">−</button>
                                            <span class="w-10 text-center font-black text-slate-800 text-sm">{{ $item['quantity'] }}</span>
                                            <button wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})"
                                                class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 text-slate-500 transition-colors text-lg font-bold">+</button>
                                        </div>

                                        {{-- Price --}}
                                        <div class="text-right min-w-[120px]">
                                            @if(($item['item_discount_percentage'] ?? 0) > 0)
                                            <p class="text-[10px] text-slate-400 line-through">Rp {{ number_format($item['unit_price'] * $item['quantity'], 0, ',', '.') }}</p>
                                            @else
                                            <p class="text-[10px] text-slate-400">@ Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</p>
                                            @endif
                                            <p class="font-black text-lg {{ ($item['item_discount_percentage'] ?? 0) > 0 ? 'text-green-600' : 'text-slate-900' }} font-mono">
                                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                            </p>
                                        </div>

                                        <button wire:click="removeFromCart({{ $index }})"
                                            class="p-2 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-20">
                            <div class="flex flex-col items-center opacity-30">
                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                <p class="font-bold uppercase tracking-widest text-sm">Keranjang Kosong</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ══════════ RIGHT PANEL ══════════ --}}
            <div class="lg:col-span-5 xl:col-span-4">
                <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-6 sticky top-6">
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center justify-between">
                        Konfirmasi Pembayaran
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ date('d M Y') }}</span>
                    </h3>

                    <div class="mb-6">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 ml-1">Nama Pelanggan</label>
                        <input type="text" wire:model="customerName"
                            class="w-full text-sm rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-primary-500 py-3"
                            placeholder="Umum">
                    </div>

                    <div class="mb-6">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3 ml-1">Metode Bayar</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(['cash' => 'Tunai', 'transfer' => 'Transfer'] as $key => $label)
                            <button type="button" wire:click="$set('paymentMethod','{{ $key }}')"
                                class="p-3 border-2 rounded-xl text-xs font-bold transition-all
                                {{ $paymentMethod === $key ? 'border-primary-500 bg-primary-50 text-primary-700 shadow-sm' : 'border-slate-100 text-slate-500 hover:border-slate-200 bg-slate-50' }}">
                                {{ $label }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Summary --}}
                    <div class="space-y-3 py-4 border-t border-slate-100 mb-6">
                        <div class="flex justify-between items-center text-slate-500 text-sm">
                            <span class="font-medium">Subtotal ({{ collect($cart)->sum('quantity') }} item)</span>
                            <span class="font-bold font-mono text-slate-700">Rp {{ number_format($this->subtotal + $this->totalItemDiscount, 0, ',', '.') }}</span>
                        </div>

                        @if($this->totalItemDiscount > 0)
                        <div class="flex justify-between items-center text-green-600 text-sm bg-green-50 px-3 py-2 rounded-lg border border-green-100 border-dashed">
                            <span class="font-bold text-[10px] uppercase tracking-tight flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M17.707 9.293l-5-5a1 1 0 00-1.414 0l-7 7a1 1 0 000 1.414l5 5a1 1 0 001.414 0l7-7a1 1 0 000-1.414zM9 11a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                                Diskon Grosir
                            </span>
                            <span class="font-bold font-mono">- Rp {{ number_format($this->totalItemDiscount, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between items-center text-slate-500 text-sm">
                            <span class="font-medium">Diskon Tambahan</span>
                            <div class="relative">
                                <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 italic font-bold">Rp</span>
                                <input type="number" wire:model.live="globalDiscount"
                                    class="w-32 pl-8 pr-2 py-1.5 border-slate-200 bg-white rounded-lg text-right text-sm font-bold focus:ring-primary-500"
                                    min="0">
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-slate-500 text-sm">
                            <span class="font-medium">Pajak</span>
                            <div class="relative">
                                <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 italic font-bold">Rp</span>
                                <input type="number" wire:model.live="tax"
                                    class="w-32 pl-8 pr-2 py-1.5 border-slate-200 bg-white rounded-lg text-right text-sm font-bold focus:ring-primary-500"
                                    min="0">
                            </div>
                        </div>

                        <div class="flex justify-between items-end text-2xl font-black text-slate-900 pt-3 border-t-2 border-slate-100">
                            <span class="text-sm font-black uppercase tracking-widest text-slate-400 pb-1">Total</span>
                            <span class="text-primary-600 font-mono tracking-tighter">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
                        </div>

                        @if($this->totalItemDiscount + $this->globalDiscount > 0)
                        <div class="bg-blue-50 text-[10px] text-center text-blue-700 font-black uppercase tracking-widest py-1.5 rounded-lg border border-blue-100">
                            Total Hemat: Rp {{ number_format($this->totalItemDiscount + $this->globalDiscount, 0, ',', '.') }}
                        </div>
                        @endif
                    </div>

                    {{-- Cash --}}
                    @if($paymentMethod === 'cash')
                    <div class="animate-in slide-in-from-top-2 duration-200">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 ml-1">Bayar Tunai</label>
                        <div class="relative mb-4">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-lg font-bold text-slate-400 italic">Rp</span>
                            <input type="number" wire:model.live="paidAmount"
                                class="block w-full pl-12 pr-4 py-4 bg-slate-900 text-white text-2xl font-mono rounded-2xl focus:ring-primary-500 border-none shadow-2xl"
                                placeholder="0">
                        </div>
                        <div class="flex gap-2 mb-4">
                            @foreach([50000, 100000, 200000] as $suggest)
                            <button type="button" wire:click="$set('paidAmount', {{ $suggest }})"
                                class="flex-1 px-3 py-2 text-xs font-bold bg-white border border-slate-200 rounded-xl text-slate-600 hover:border-primary-500 hover:text-primary-600 active:scale-95 transition-all shadow-sm">
                                {{ number_format($suggest/1000) }}k
                            </button>
                            @endforeach
                        </div>
                        <div class="p-4 rounded-2xl border transition-all {{ (float)$this->change > 0 ? 'bg-green-50 border-green-100' : 'bg-slate-50 border-slate-100' }}">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-bold text-slate-500 uppercase">Kembalian</span>
                                <span class="text-xl font-black {{ (float)$this->change > 0 ? 'text-green-600' : 'text-slate-400' }} font-mono">
                                    Rp {{ number_format($this->change, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100 mb-4">
                        <p class="text-[11px] font-bold text-amber-600 uppercase mb-1">Status Transfer</p>
                        <p class="text-xs text-amber-900 leading-relaxed">Pastikan dana sebesar <strong>Rp {{ number_format($this->total, 0, ',', '.') }}</strong> sudah masuk ke rekening outlet.</p>
                    </div>
                    @endif

                    <button wire:click="checkout"
                        class="w-full mt-6 py-5 rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg transition-all active:scale-95 flex items-center justify-center
                        {{ empty($cart) || ($paymentMethod === 'cash' && (float)$paidAmount < (float)$this->total)
                            ? 'bg-slate-100 text-slate-400 cursor-not-allowed shadow-none'
                            : 'bg-primary-600 hover:bg-primary-700 text-white shadow-primary-200' }}"
                        @if(empty($cart) || ($paymentMethod === 'cash' && (float)$paidAmount < (float)$this->total)) disabled @endif>
                        <span>Selesaikan Transaksi</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════ MODAL PILIH VARIAN ══════════ --}}
    @if($selectedVariantProductId)
    @php $varProduct = \App\Models\Product::with('activeVariants')->find($selectedVariantProductId); @endphp
    @if($varProduct)
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full animate-in zoom-in-95 duration-200">
            <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
                <h3 class="text-lg font-black text-slate-900">Pilih Satuan</h3>
                <p class="text-sm text-slate-600 mt-0.5">{{ $varProduct->name }}</p>
            </div>
            <div class="p-5 space-y-2 max-h-96 overflow-y-auto">
                {{-- Opsi harga dasar --}}
                <button wire:click="addToCart({{ $varProduct->id }})"
                    class="w-full p-4 border-2 border-slate-200 rounded-xl text-left hover:border-indigo-400 hover:bg-indigo-50 transition-all flex items-center justify-between group">
                    <div>
                        <p class="font-bold text-slate-900 group-hover:text-indigo-700">{{ $varProduct->unit }} <span class="text-[10px] ml-1 bg-slate-100 px-1.5 py-0.5 rounded text-slate-500 font-normal">Dasar</span></p>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $varProduct->name }}</p>
                    </div>
                    <p class="font-extrabold text-indigo-600 text-lg">Rp {{ number_format($varProduct->price, 0, ',', '.') }}</p>
                </button>
                {{-- Varian lainnya --}}
                @foreach($varProduct->activeVariants as $variant)
                <button wire:click="selectVariant({{ $variant->id }})"
                    class="w-full p-4 border-2 border-slate-200 rounded-xl text-left hover:border-blue-500 hover:bg-blue-50 transition-all flex items-center justify-between group">
                    <div>
                        <p class="font-bold text-slate-900 group-hover:text-blue-700">{{ $variant->unit_name }}</p>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $varProduct->name }}</p>
                    </div>
                    <p class="font-extrabold text-blue-600 text-lg">Rp {{ number_format($variant->price, 0, ',', '.') }}</p>
                </button>
                @endforeach
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
                <button wire:click="$set('selectedVariantProductId', null)"
                    class="w-full px-4 py-2.5 border border-slate-300 rounded-xl font-bold text-slate-700 hover:bg-slate-100 transition-all text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif
    @endif
</div>