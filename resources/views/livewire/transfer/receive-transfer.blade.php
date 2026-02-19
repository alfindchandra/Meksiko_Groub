<div x-data="{ showModal: @entangle('showConfirmModal') }">
    @section('page-title', 'Terima Transfer - ' . $transfer->transfer_number)

    <div class="max-w-4xl mx-auto">
        <!-- Transfer Info -->
        <div class="card mb-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $transfer->transfer_number }}</h2>
                    <p class="mt-1 text-sm text-gray-600">Konfirmasi penerimaan barang transfer</p>
                </div>
                <span class="badge badge-info text-lg">In Transit</span>
            </div>

            <div class="grid grid-cols-2 gap-6 p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-500">Dari Outlet</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $transfer->fromOutlet->name }}</p>
                    <p class="text-sm text-gray-600">{{ $transfer->fromOutlet->code }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Ke Outlet</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $transfer->toOutlet->name }}</p>
                    <p class="text-sm text-gray-600">{{ $transfer->toOutlet->code }}</p>
                </div>
            </div>
        </div>

        <!-- Items to Receive -->
        <div class="card mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Barang yang Diterima</h3>
            
            <div class="space-y-4">
                @foreach($transfer->items as $item)
                <div class="p-4 border border-gray-200 rounded-lg bg-white hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $item->product->name }}</h4>
                            <p class="text-sm text-gray-500">SKU: {{ $item->product->sku }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Dikirim</p>
                            <p class="text-2xl font-bold text-primary-600">
                                {{ number_format($item->quantity_sent ?? $item->quantity_requested) }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jumlah Diterima <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   wire:model="receivedItems.{{ $item->id }}.quantity_received"
                                   min="0"
                                   max="{{ $item->quantity_sent ?? $item->quantity_requested }}"
                                   class="form-input">
                            @if($receivedItems[$item->id]['quantity_received'] != ($item->quantity_sent ?? $item->quantity_requested))
                            <p class="mt-1 text-xs text-yellow-600">
                                ⚠️ Jumlah berbeda dari yang dikirim
                            </p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Catatan (jika ada perbedaan)
                            </label>
                            <textarea wire:model="receivedItems.{{ $item->id }}.notes"
                                      rows="2"
                                      class="form-input"
                                      placeholder="Misal: Barang rusak 2 pcs"></textarea>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Additional Notes -->
        <div class="card mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Penerimaan</label>
            <textarea wire:model="notes" 
                      rows="3" 
                      class="form-input"
                      placeholder="Tambahkan catatan tambahan jika diperlukan..."></textarea>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('transfer.detail', $transfer->id) }}" class="btn-secondary">
                Batal
            </a>
            <button @click="showModal = true" 
                    class="btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Konfirmasi Penerimaan
            </button>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div x-show="showModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" 
                 @click="showModal = false"></div>

            <!-- Modal Panel -->
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 @click.away="showModal = false">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-green-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                Konfirmasi Penerimaan Transfer
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Apakah Anda yakin ingin mengkonfirmasi penerimaan transfer <strong>{{ $transfer->transfer_number }}</strong>?
                                </p>
                                <p class="mt-2 text-sm text-gray-500">
                                    Tindakan ini akan memperbarui stok dan tidak dapat dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="confirmReceive"
                            @click="showModal = false"
                            type="button"
                            class="btn-primary w-full sm:w-auto sm:ml-3">
                        Ya, Konfirmasi
                    </button>
                    <button @click="showModal = false"
                            type="button"
                            class="btn-secondary w-full sm:w-auto mt-3 sm:mt-0">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>