<div x-data="{ showModal: @entangle('showConfirmModal') }" class="min-h-screen py-8 bg-gray-50/50">
    @section('page-title', 'Kirim Transfer - ' . $transfer->transfer_number)

    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-xs font-medium text-gray-400 uppercase tracking-wider">
                        <li>Transfer</li>
                        <li><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg></li>
                        <li class="text-indigo-600">Pengiriman</li>
                    </ol>
                </nav>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kirim Transfer Barang</h2>
                <p class="text-sm text-gray-500 font-mono mt-1">{{ $transfer->transfer_number }}</p>
            </div>
            <a href="{{ route('transfer.detail', $transfer->id) }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8 relative">
                <div class="flex-1 text-center md:text-left">
                    <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1 block">Dari Outlet</span>
                    <h4 class="text-xl font-bold text-gray-900">{{ $transfer->fromOutlet->name }}</h4>
                    <p class="text-sm text-gray-500 font-mono">{{ $transfer->fromOutlet->code }}</p>
                </div>

                <div class="flex flex-col items-center">
                    <div class="bg-indigo-50 p-3 rounded-full mb-2">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full border border-emerald-200 uppercase">Approved</span>
                </div>

                <div class="flex-1 text-center md:text-right">
                    <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest mb-1 block">Ke Outlet</span>
                    <h4 class="text-xl font-bold text-gray-900">{{ $transfer->toOutlet->name }}</h4>
                    <p class="text-sm text-gray-500 font-mono">{{ $transfer->toOutlet->code }}</p>
                </div>
            </div>
        </div>

        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 ml-1">Detail Item & Stok</h3>
        <div class="grid grid-cols-1 gap-4 mb-8">
            @foreach($itemQuantities as $itemId => $item)
            <div class="bg-white rounded-2xl border transition-all duration-200 
                {{ $item['quantity_sent'] > $item['available_stock'] ? 'border-rose-300 ring-4 ring-rose-50' : 'border-gray-200 hover:shadow-md' }}">
                <div class="p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div class="flex items-start space-x-4">
                            <div class="p-3 bg-gray-50 rounded-xl text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4V4"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">{{ $item['product_name'] }}</h4>
                                <p class="text-xs text-gray-500 font-mono">SKU: {{ $item['product_sku'] }}</p>
                            </div>
                        </div>
                        
                        <div>
                            @if($item['quantity_sent'] > $item['available_stock'])
                                <span class="px-3 py-1.5 bg-rose-50 text-rose-600 text-xs font-bold rounded-lg border border-rose-100 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                    Stok Tidak Cukup
                                </span>
                            @elseif($item['quantity_sent'] < $item['quantity_requested'])
                                <span class="px-3 py-1.5 bg-amber-50 text-amber-600 text-xs font-bold rounded-lg border border-amber-100">Kirim Sebagian</span>
                            @else
                                <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 text-xs font-bold rounded-lg border border-emerald-100">Lengkap</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">Diminta</p>
                            <div class="flex items-baseline space-x-1">
                                <span class="text-2xl font-black text-gray-800">{{ number_format($item['quantity_requested']) }}</span>
                                <span class="text-xs text-gray-500">{{ $item['unit'] }}</span>
                            </div>
                        </div>

                        <div class="bg-emerald-50/50 rounded-xl p-4 border border-emerald-100">
                            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-tighter mb-1">Stok Tersedia</p>
                            <div class="flex items-baseline space-x-1">
                                <span class="text-2xl font-black text-emerald-700">{{ number_format($item['available_stock']) }}</span>
                                <span class="text-xs text-emerald-600">{{ $item['unit'] }}</span>
                            </div>
                        </div>

                        <div class="bg-indigo-600 rounded-xl p-4 shadow-lg shadow-indigo-100 relative overflow-hidden group">
                            <p class="text-[10px] font-bold text-indigo-100 uppercase tracking-tighter mb-1 relative z-10">Jumlah Kirim</p>
                            <div class="flex items-center space-x-1 relative z-10">
                                <input type="number"
                                       wire:model.live="itemQuantities.{{ $itemId }}.quantity_sent"
                                       class="w-full bg-transparent border-none p-0 text-2xl font-black text-white focus:ring-0">
                                <span class="text-xs text-indigo-200">{{ $item['unit'] }}</span>
                            </div>
                            <div class="absolute -right-2 -bottom-2 opacity-10 text-white group-hover:scale-110 transition-transform">
                                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>
                    </div>

                    @error("itemQuantities.{$itemId}.quantity_sent")
                        <p class="mt-3 text-xs text-rose-600 font-bold flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </p> 
                    @enderror
                </div>
            </div>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="p-6">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest mb-6">Informasi Logistik</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 ml-1">Nama Kurir / Pengirim</label>
                        <input type="text" wire:model="courierName" 
                               class="w-full rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 py-3 transition-all"
                               placeholder="Masukkan nama kurir...">
                        @error('courierName') <p class="text-[10px] text-rose-500 font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-500 ml-1">No. Kendaraan</label>
                        <input type="text" wire:model="vehicleNumber" 
                               class="w-full rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 py-3 transition-all"
                               placeholder="Contoh: B 1234 ABC">
                        @error('vehicleNumber') <p class="text-[10px] text-rose-500 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-6 space-y-2">
                    <label class="text-xs font-bold text-gray-500 ml-1">Catatan Pengiriman</label>
                    <textarea wire:model="notes" rows="3" 
                              class="w-full rounded-xl border-gray-200 focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Tambahkan instruksi khusus jika ada..."></textarea>
                </div>
            </div>
        </div>

        <div class="bg-indigo-900 rounded-2xl p-6 shadow-2xl shadow-indigo-200 flex flex-col md:flex-row items-center justify-between gap-6 text-white overflow-hidden relative">
            <div class="flex items-center space-x-8 relative z-10">
                <div class="text-center">
                    <p class="text-[10px] font-bold text-indigo-300 uppercase">Produk</p>
                    <p class="text-2xl font-black">{{ count($itemQuantities) }}</p>
                </div>
                <div class="w-px h-10 bg-indigo-700"></div>
                <div class="text-center">
                    <p class="text-[10px] font-bold text-indigo-300 uppercase">Total Unit</p>
                    <p class="text-2xl font-black">{{ number_format(collect($itemQuantities)->sum('quantity_sent')) }}</p>
                </div>
            </div>

            <div class="flex items-center space-x-4 w-full md:w-auto relative z-10">
                <button @click="$wire.openConfirmModal()"
                        type="button"
                        class="w-full md:w-auto px-8 py-4 bg-white text-indigo-900 font-black rounded-xl hover:bg-indigo-50 transition-all flex items-center justify-center shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                    </svg>
                    PROSES PENGIRIMAN
                </button>
            </div>
            
            <svg class="absolute -right-10 -bottom-10 w-48 h-48 text-indigo-800 opacity-50" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"></path></svg>
        </div>
    </div>

    <div x-show="showModal" x-cloak
         class="fixed inset-0 z-[100] overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showModal = false"></div>

            <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden border border-gray-100">
                <div class="p-8 text-center">
                    <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    
                    <h3 class="text-2xl font-black text-gray-900 mb-2">Finalisasi Pengiriman?</h3>
                    <p class="text-sm text-gray-500 mb-6 px-4">Setelah diproses, stok di outlet asal akan langsung berkurang secara otomatis.</p>
                    
                    <div class="bg-gray-50 rounded-2xl p-4 text-left space-y-3 border border-gray-100 mb-8 text-sm">
                        <div class="flex justify-between font-medium">
                            <span class="text-gray-400">Ke Outlet:</span>
                            <span class="text-gray-900">{{ $transfer->toOutlet->name }}</span>
                        </div>
                        <div class="flex justify-between font-medium">
                            <span class="text-gray-400">Total Unit:</span>
                            <span class="text-indigo-600 font-bold">{{ number_format(collect($itemQuantities)->sum('quantity_sent')) }} unit</span>
                        </div>
                        @if($courierName)
                        <div class="flex justify-between font-medium">
                            <span class="text-gray-400">Kurir:</span>
                            <span class="text-gray-900">{{ $courierName }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showModal = false" 
                                class="px-6 py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl hover:bg-gray-200 transition-all">
                            Periksa Lagi
                        </button>
                        <button wire:click="confirmSend" 
                                class="px-6 py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all flex items-center justify-center shadow-lg shadow-indigo-200"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>Ya, Kirim</span>
                            <svg wire:loading class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>