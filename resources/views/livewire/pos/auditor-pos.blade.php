<div class="min-h-screen bg-[#f8fafc] pb-12">
    @section('page-title', 'POS Auditor')

    {{-- Top Bar --}}
    <div class="sticky top-0 z-50 bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-2.5 h-2.5 bg-blue-600 rounded-full"></div>
            <span class="text-slate-800 text-xs font-black tracking-widest uppercase">Auditor POS</span>
        </div>
        <span class="text-slate-400 font-mono text-xs">{{ now()->format('D, d M Y') }}</span>
    </div>

    <div class="max-w-[1600px] mx-auto p-4 lg:p-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            {{-- LEFT PANEL --}}
            <div class="lg:col-span-7 xl:col-span-8 space-y-6">
                
                {{-- Outlet Selector --}}
                <div class="bg-slate-50 rounded-2xl shadow-sm border border-slate-200 p-5">
    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">
        ▸ Cari & Pilih Ruko / Outlet
    </label>
    
    <div class="relative" x-data="{ open: true }" @click.away="open = false">
        {{-- Input Pencarian --}}
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            
            <input type="text"
                   wire:model.live.debounce.300ms="searchOutlet"
                   @focus="open = true"
                   placeholder="Ketik nama ruko atau kota cabang..."
                   class="block w-full pl-11 pr-10 py-3 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-blue-500 text-slate-900 text-sm font-medium transition-all">
            
            {{-- Tombol Reset jika outlet sudah terpilih --}}
            @if($selectedOutletId)
            <button type="button" 
                    wire:click="resetSelectedOutlet" 
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-red-500">
                ✕
            </button>
            @endif
        </div>

        {{-- Dropdown Hasil Pencarian Cabang --}}
        @if(!empty($searchOutlet) && $selectedOutletId == null)
        <div x-show="open" 
             class="absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-xl max-h-60 overflow-y-auto overflow-hidden p-1.5 space-y-0.5 animate-in fade-in slide-in-from-top-2 duration-150">
            
            @forelse($outlets as $outlet)
            <button type="button"
                    wire:click="selectOutlet({{ $outlet->id }})"
                    @click="open = false"
                    class="w-full text-left px-4 py-2.5 rounded-lg text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 font-medium transition-colors flex items-center justify-between group">
                <div>
                    <span class="font-bold text-slate-800 group-hover:text-blue-700">{{ $outlet->name }}</span>
                    <span class="text-xs text-slate-400 block mt-0.5">Cabang: {{ $outlet->city }}</span>
                </div>
                <svg class="w-4 h-4 text-slate-300 group-hover:text-blue-500 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            @empty
            <div class="px-4 py-3 text-slate-400 text-xs text-center italic">
                Cabang / Outlet tidak ditemukan
            </div>
            @endforelse
            
        </div>
        @endif
    </div>
</div>

                {{-- Search Product --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">
                        ▸ Cari Barang
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text"
                               wire:model.live.debounce.300ms="searchProduct"
                               placeholder="Ketik nama atau SKU (min. 2 karakter)..."
                               class="block w-full pl-11 pr-4 py-3.5 bg-slate-50 border-slate-500 rounded-xl focus:ring-2 focus:ring-blue-500 text-slate-900 text-lg transition-all"
                               autocomplete="off">
                    </div>
                </div>

                {{-- Search Results --}}
                @if(strlen($searchProduct) >= 2)
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 animate-in fade-in slide-in-from-bottom-4 duration-300">
                    @forelse($products as $product)
                    <button type="button" wire:click="addToCart({{ $product->id }})"
                            class="group bg-white p-4 rounded-2xl border border-slate-200 hover:border-blue-500 hover:shadow-md transition-all text-left w-full">
                        <div class="space-y-2">
                            <div>
                                <h4 class="font-bold text-slate-800 line-clamp-1 group-hover:text-blue-600">{{ $product->name }}</h4>
                                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mt-0.5">{{ $product->sku }} {{ $product->unit ? '• ' . $product->unit : '' }}</p>
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-slate-50">
                                <span class="text-xs text-slate-400">Harga Jual</span>
                                <div class="text-right">
                                    <span class="text-blue-600 font-extrabold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    @if($product->activeVariants->count() > 0)
                                    <div class="text-[10px] font-bold text-blue-600 mt-1">📦 {{ $product->activeVariants->count() }} Varian</div>
                                    @endif
                                </div>
                            </div>
                            @if($product->activeVariants->count() > 0)
                            <div class="bg-blue-50 rounded-lg p-2 border border-blue-100">
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
                            <div class="bg-green-50 rounded-lg p-2 border border-green-100">
                                <p class="text-[10px] font-bold text-green-700 mb-1">Harga Grosir:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($product->activeDiscountTiers as $tier)
                                    <span class="text-[9px] bg-green-100 text-green-700 px-2 py-0.5 rounded font-bold">
                                        {{ $tier->min_quantity }}+ = -{{ number_format($tier->discount_percentage, 0) }}%
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </button>
                    @empty
                    <div class="bg-white rounded-2xl border border-dashed border-slate-300 py-8 text-center col-span-full">
                        <p class="text-slate-400 font-medium text-sm">Produk tidak ditemukan.</p>
                    </div>
                    @endforelse
                </div>
                @endif

                {{-- Cart Items --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 flex items-center text-sm">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Daftar Barang Laku
                        </h3>
                        <span class="px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-600 shadow-sm">
                            {{ count($cart) }} Item
                        </span>
                    </div>

                    <div class="p-6">
                        @if(count($cart) > 0)
                        <div class="space-y-4">
                            @foreach($cart as $index => $item)
                            <div class="relative p-4 bg-white rounded-2xl border border-slate-100 transition-all group shadow-sm">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <p class="font-bold text-slate-900 leading-tight">{{ $item['product_name'] }}</p>
                                        <p class="text-xs font-mono text-slate-400 uppercase mt-1">
                                            {{ $item['product_sku'] }} · <span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[10px] font-sans font-bold">{{ $item['unit'] }}</span>
                                        </p>
                                    </div>

                                    <div class="flex items-center justify-between md:justify-end gap-6">
                                        {{-- Quantity Adjuster --}}
                                        <div class="flex items-center bg-white border border-slate-200 rounded-xl p-1 shadow-sm ring-1 ring-black/5">
                                            <button wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] - 1 }})"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 text-slate-500 font-bold transition-colors">-</button>
                                            <span class="w-10 text-center font-black text-slate-800 text-sm">{{ $item['quantity'] }}</span>
                                            <button wire:click="updateQuantity({{ $index }}, {{ $item['quantity'] + 1 }})"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 text-slate-500 font-bold transition-colors">+</button>
                                        </div>

                                        {{-- Price Info --}}
                                        <div class="text-right min-w-[140px]">
                                            <p class="text-[10px] text-slate-400">@ Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</p>
                                            <p class="font-black text-base text-slate-900 font-mono mt-0.5">
                                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                            </p>
                                        </div>

                                        {{-- Remove Action --}}
                                        <button wire:click="removeFromCart({{ $index }})"
                                                class="p-2 text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        {{-- Total Summary Row --}}
                        <div class="flex justify-between items-center p-4 mt-6 bg-slate-50 rounded-2xl border border-slate-100">
                            <span class="color-[#1e293b] text-xs font-black uppercase tracking-wider pl-2">Total Ringkasan</span>
                            <span class="text-blue-600 font-mono text-2xl font-black pr-2">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @else
                        <div class="text-center py-16">
                            <div class="flex flex-col items-center opacity-30">
                                <svg class="w-14 h-14 mb-3 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                </svg>
                                <p class="font-bold uppercase tracking-widest text-xs text-slate-500">Belum ada barang</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT PANEL --}}
            <div class="lg:col-span-5 xl:col-span-4 space-y-6">
                
                {{-- Setoran Card --}}
                <div class="bg-white rounded-2xl shadow-sm border-2 border-blue-500 p-6 sticky top-6 space-y-6">
                    <h3 class="text-base font-black text-slate-900 flex items-center justify-between border-b border-slate-100 pb-4">
                        <span class="flex items-center gap-2 text-blue-600 uppercase tracking-wider text-xs">
                            <span class="inline-block w-2 h-2 bg-blue-600 rounded-full"></span>
                            Konfirmasi Setoran
                        </span>
                    </h3>

                    {{-- Selected Outlet Data --}}
                    <div class="bg-slate-50 border border-slate-200/60 rounded-xl p-4">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Outlet Terpilih</div>
                        <div class="color-[#0f172a] text-sm font-bold">
                            {{ $selectedOutletName ?: '— Belum dipilih —' }}
                        </div>
                    </div>

                    {{-- Total Obligation Data --}}
                    <div class="bg-slate-50 border border-slate-200/60 rounded-xl p-4">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Barang Laku</div>
                        <div class="text-blue-600 text-2xl font-black font-mono">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</div>
                    </div>

                    {{-- Physical Deposit Field --}}
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nominal Titipan Fisik</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-base font-bold text-slate-400 italic">Rp</span>
                            <input type="text"
                                placeholder="0"
                                x-data="{
                                    // Format angka saat pertama kali dimuat
                                    formatNumber(val) {
                                        if (!val) return '';
                                        return val.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                    }
                                }"
                                {{-- Ambil value murni dari Livewire, lalu format tampilannya dengan titik --}}
                                x-bind:value="formatNumber($wire.nominalTitipan)"
                                {{-- Saat user mengetik, bersihkan titiknya lalu kirim angka murninya ke Livewire --}}
                                @input="
                                    let rawValue = $event.target.value.replace(/\D/g, '');
                                    $wire.set('nominalTitipan', rawValue === '' ? 0 : parseInt(rawValue));
                                "
                                class="block w-full pl-12 pr-4 py-3.5 bg-slate-900 text-white text-xl font-mono rounded-xl focus:ring-2 focus:ring-blue-500 border-none font-bold shadow-inner text-right">
                        </div>

                        {{-- Set to Match Button --}}
                        <button wire:click="$set('nominalTitipan', {{ (int)$this->subtotal }})"
                                class="w-full mt-3 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all uppercase tracking-wider">
                            ⬡ Atur Nominal Pas
                        </button>
                    </div>

                    {{-- Balance Check Alert Box --}}
                    @if((float)($nominalTitipan ?: 0) > 0)
                    <div class="p-4 rounded-xl border animate-in zoom-in-95 duration-200
                        {{ $this->kekurangan > 0 ? 'bg-red-50 border-red-100 text-red-900' : ($this->lebih > 0 ? 'bg-green-50 border-green-100 text-green-900' : 'bg-amber-50 border-amber-100 text-amber-900') }}">
                        
                        @if($this->kekurangan > 0)
                            <div class="text-[10px] font-bold uppercase tracking-wider text-red-600 mb-1">⚠ Selisih Kurang</div>
                            <div class="text-xl font-black font-mono text-red-600">- Rp {{ number_format($this->kekurangan, 0, ',', '.') }}</div>
                            <p class="text-[11px] text-red-500 mt-1">Uang fisik belum cukup menutup total barang laku.</p>
                        @elseif($this->lebih > 0)
                            <div class="text-[10px] font-bold uppercase tracking-wider text-green-600 mb-1">✓ Selisih Lebih</div>
                            <div class="text-xl font-black font-mono text-green-600">+ Rp {{ number_format($this->lebih, 0, ',', '.') }}</div>
                            <p class="text-[11px] text-green-500 mt-1">Ada sisa kelebihan dari titipan uang fisik.</p>
                        @else
                            <div class="text-[10px] font-bold uppercase tracking-wider text-amber-600 mb-1">✓ Cocok</div>
                            <div class="text-lg font-black text-amber-700">LUNAS / PAS ✅</div>
                            <p class="text-[11px] text-amber-600 mt-1">Jumlah uang fisik sudah sesuai dengan sistem.</p>
                        @endif
                    </div>
                    @endif
                     {{-- Copyable Report Output Card --}}
                @if($summaryText)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">▸ Teks Laporan</span>
                        <button onclick="copyAuditSummary()"
                                id="copy-btn"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-[11px] px-4 py-2 rounded-xl transition-all shadow-sm shadow-blue-100 tracking-wide uppercase">
                            ⎘ Copy Teks
                        </button>
                    </div>

                    <pre id="summary-text"
                         class="bg-slate-50 border border-slate-100 rounded-xl p-4 text-slate-600 font-mono text-xs line-clamp-[12] overflow-y-auto white-space-pre-wrap word-break-break-word leading-relaxed shadow-inner">{{ $summaryText }}</pre>
                </div>
                @endif
                </div>

               
            </div>

        </div>
    </div>

    {{-- Copy Clipboard Logic Script --}}
    <script>
    function copyAuditSummary() {
        const text = document.getElementById('summary-text').innerText;
        const btn = document.getElementById('copy-btn');

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(() => {
                btn.innerText = '✓ Tersalin!';
                btn.style.background = '#22c55e';
                setTimeout(() => {
                    btn.innerText = '⎘ Copy Teks';
                    btn.style.background = '#2563eb';
                }, 2000);
            });
        } else {
            const ta = document.createElement('textarea');
            ta.value = text;
            ta.style.position = 'fixed';
            ta.style.opacity = '0';
            document.body.appendChild(ta);
            ta.focus(); ta.select();
            try {
                document.execCommand('copy');
                btn.innerText = '✓ Tersalin!';
                btn.style.background = '#22c55e';
                setTimeout(() => {
                    btn.innerText = '⎘ Copy Teks';
                    btn.style.background = '#2563eb';
                }, 2000);
            } catch(e) {}
            document.body.removeChild(ta);
        }
    }
    </script>

    <!-- Variant Selection Modal -->
    @if($selectedVariantProductId)
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="$wire.set('selectedVariantProductId', null)">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full animate-in zoom-in-95">
            @php
                $product = \App\Models\Product::find($selectedVariantProductId);
            @endphp
            
            <div class="px-6 py-4 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h3 class="text-lg font-bold text-slate-900">Pilih Satuan</h3>
                <p class="text-sm text-slate-600 mt-1">{{ $product?->name }}</p>
            </div>

            <div class="p-6 space-y-2 max-h-96 overflow-y-auto">
                @if($product && $product->activeVariants->count() > 0)
                    @foreach($product->activeVariants as $variant)
                    <button wire:click="selectVariant({{ $variant->id }})"
                            class="w-full p-4 border-2 border-slate-200 rounded-xl text-left hover:border-blue-500 hover:bg-blue-50 transition-all flex items-center justify-between">
                        <div>
                            <p class="font-bold text-slate-900">{{ $variant->unit_name }}</p>
                            <p class="text-sm text-slate-600 mt-0.5">{{ $product->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-extrabold text-blue-600 text-lg">Rp {{ number_format($variant->price, 0, ',', '.') }}</p>
                        </div>
                    </button>
                    @endforeach
                @endif
            </div>

            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex gap-2">
                <button wire:click="$set('selectedVariantProductId', null)"
                        class="flex-1 px-4 py-2 border border-slate-300 rounded-lg font-bold text-slate-700 hover:bg-slate-100 transition-all">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif
</div>