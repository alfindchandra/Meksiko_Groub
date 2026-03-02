<div x-data="{ showModal: @entangle('showConfirmModal') }" class="antialiased text-gray-800">
    @section('page-title', 'Terima Transfer - ' . $transfer->transfer_number)

    <div class="max-w-4xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                        <li>Transfer Stok</li>
                        <li><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="font-medium text-primary-600">Penerimaan</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    {{ $transfer->transfer_number }}
                </h1>
                <p class="mt-1 text-gray-500">Pastikan jumlah fisik barang sesuai dengan data di bawah ini.</p>
            </div>
            <div class="flex items-center">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-blue-100 text-blue-700 uppercase tracking-wider shadow-sm">
                    <span class="w-2 h-2 mr-2 bg-blue-500 rounded-full animate-pulse"></span>
                    In Transit
                </span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-8 relative">
                <div class="flex-1 text-center sm:text-left">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Asal Pengiriman</p>
                    <h3 class="text-xl font-bold text-gray-900">{{ $transfer->fromOutlet->name }}</h3>
                    <p class="text-sm font-mono text-gray-500 bg-gray-100 inline-block px-2 py-0.5 rounded mt-1">{{ $transfer->fromOutlet->code }}</p>
                </div>

                <div class="flex items-center justify-center bg-primary-50 rounded-full p-3 shadow-inner border border-primary-100">
                    <svg class="w-6 h-6 text-primary-600 rotate-90 sm:rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>

                <div class="flex-1 text-center sm:text-right">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Tujuan Penerimaan</p>
                    <h3 class="text-xl font-bold text-gray-900">{{ $transfer->toOutlet->name }}</h3>
                    <p class="text-sm font-mono text-gray-500 bg-gray-100 inline-block px-2 py-0.5 rounded mt-1">{{ $transfer->toOutlet->code }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-4 mb-8">
            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Daftar Barang ({{ count($transfer->items) }})
            </h3>
            
            @foreach($transfer->items as $item)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden group hover:border-primary-300 transition-all">
                <div class="p-5">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center border border-gray-100 group-hover:bg-primary-50 transition-colors">
                                <svg class="w-6 h-6 text-gray-400 group-hover:text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg leading-tight">{{ $item->product->name }}</h4>
                                <p class="text-sm font-mono text-gray-500 mt-1">SKU: {{ $item->product->sku }}</p>
                            </div>
                        </div>
                        <div class="bg-primary-50 px-4 py-2 rounded-xl text-center md:text-right border border-primary-100">
                            <p class="text-xs font-semibold text-primary-600 uppercase tracking-tighter">Qty Kirim</p>
                            <p class="text-2xl font-black text-primary-700">
                                {{ number_format($item->quantity_sent ?? $item->quantity_requested) }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="relative">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Diterima <span class="text-red-500">*</span></label>
                            <input type="number" 
                                   wire:model.lazy="receivedItems.{{ $item->id }}.quantity_received"
                                   class="block w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-all @if($receivedItems[$item->id]['quantity_received'] != ($item->quantity_sent ?? $item->quantity_requested)) border-yellow-400 bg-yellow-50 @endif"
                                   placeholder="0">
                            
                            @if($receivedItems[$item->id]['quantity_received'] != ($item->quantity_sent ?? $item->quantity_requested))
                                <p class="absolute -bottom-6 left-0 flex items-center text-xs font-medium text-yellow-700">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    Selisih terdeteksi
                                </p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Perbedaan</label>
                            <textarea wire:model="receivedItems.{{ $item->id }}.notes"
                                      rows="2"
                                      class="block w-full px-4 py-2 rounded-xl border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                      placeholder="Misal: Kurang 1 krn pecah..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="bg-gray-50 rounded-2xl p-6 border-2 border-dashed border-gray-200 mb-8">
            <label class="block text-sm font-bold text-gray-700 mb-3">Catatan Penerimaan (General)</label>
            <textarea wire:model="notes" 
                      rows="3" 
                      class="block w-full px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 bg-white"
                      placeholder="Tambahkan informasi tambahan pengiriman di sini..."></textarea>
        </div>

        <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pb-10">
            <a href="{{ route('transfer.detail', $transfer->id) }}" 
               class="w-full sm:w-auto text-center px-8 py-3 rounded-xl text-gray-600 font-bold hover:bg-gray-100 transition-colors">
                Batal
            </a>
            <button @click="showModal = true" 
                    class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-200 transform transition active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Konfirmasi Penerimaan
            </button>
        </div>
    </div>

    <template x-if="showModal">
        <div class="fixed inset-0 z-[60] overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100" 
                     class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" 
                     @click="showModal = false"></div>

                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     class="relative bg-white rounded-3xl shadow-2xl overflow-hidden max-w-lg w-full p-8 text-center">
                    
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-50 mb-6">
                        <svg class="h-10 w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    
                    <h3 class="text-2xl font-black text-gray-900 mb-2">Selesaikan Penerimaan?</h3>
                    <p class="text-gray-500 mb-8">
                        Anda akan memproses penerimaan untuk transfer <span class="font-bold text-gray-900">{{ $transfer->transfer_number }}</span>. Stok akan otomatis disesuaikan dan data tidak dapat diubah kembali.
                    </p>

                    <div class="grid grid-cols-2 gap-4">
                        <button @click="showModal = false" 
                                class="px-6 py-3 rounded-xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition-colors">
                            Periksa Lagi
                        </button>
                        <button wire:click="confirmReceive" 
                                @click="showModal = false"
                                class="px-6 py-3 rounded-xl bg-green-600 text-white font-bold hover:bg-green-700 shadow-lg shadow-green-200 transition-all active:scale-95">
                            Ya, Selesaikan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>