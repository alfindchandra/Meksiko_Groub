<div class="max-w-5xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Buat Transaksi Baru</h1>
            <p class="text-sm text-gray-500">Input penerimaan order layanan cuci & repair.</p>
        </div>
        <a href="{{ route('meksikoclean.transactions.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <ul class="text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">1. Informasi Pelanggan</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama Pelanggan</label>
                    <input type="text" wire:model="customer_name" class="w-full rounded-xl border-gray-200 focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="Contoh: Budi Santoso">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">No. WhatsApp</label>
                    <input type="text" wire:model="customer_phone" class="w-full rounded-xl border-gray-200 focus:ring-primary-500 focus:border-primary-500 text-sm" placeholder="0812xxxx">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tipe Pesanan</label>
                    <select wire:model.live="order_type" class="w-full rounded-xl border-gray-200 focus:ring-primary-500 focus:border-primary-500 text-sm">
                        <option value="online">Online / COD</option>
                        <option value="offline">Offline / Walk-in</option>
                        <option value="mitra">Drop-off Mitra</option>
                    </select>
                </div>
                @if ($order_type === 'mitra')
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Pilih Mitra</label>
                    <select wire:model="partner_id" class="w-full rounded-xl border-gray-200 focus:ring-primary-500 focus:border-primary-500 text-sm">
                        <option value="">-- Lokasi Mitra --</option>
                        @foreach ($partners as $partner)
                            <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status Pembayaran</label>
                    <select wire:model.live="payment_status" class="w-full rounded-xl border-gray-200 focus:ring-primary-500 focus:border-primary-500 text-sm">
                        <option value="unpaid">Belum Lunas</option>
                        <option value="paid">Lunas (Bayar Sekarang)</option>
                    </select>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">2. Rincian Layanan</h3>
                <button type="button" wire:click="addItem" class="inline-flex items-center text-xs font-bold text-primary-600 hover:text-primary-700 bg-primary-50 px-3 py-1.5 rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    TAMBAH ITEM
                </button>
            </div>
            
            <div class="p-0 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Layanan</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase">Qty</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-400 uppercase">Subtotal</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($items as $index => $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <select wire:model.live="items.{{ $index }}.category" class="w-full border-none focus:ring-0 text-sm bg-transparent">
                                    <option value="">-- Pilih --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat }}">{{ ucfirst($cat) }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-6 py-4">
                                <select wire:model.live="items.{{ $index }}.service_id" class="w-full border-none focus:ring-0 text-sm bg-transparent {{ empty($item['category']) ? 'opacity-30' : '' }}" {{ empty($item['category']) ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Layanan --</option>
                                    @if(!empty($item['category']))
                                        @foreach ($services->where('category', $item['category']) as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }} (Rp{{ number_format($service->price, 0, ',', '.') }})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td class="px-6 py-4">
                                <input type="number" wire:model.live="items.{{ $index }}.qty" min="1" class="w-16 mx-auto block border-gray-200 rounded-lg text-sm text-center focus:ring-primary-500">
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-700 text-sm">
                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(count($items) > 1)
                                <button type="button" wire:click="removeItem({{ $index }})" class="text-gray-300 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-6 bg-gray-50/50 border-t border-gray-100">
                <div class="flex flex-col md:flex-row justify-end items-end md:items-start gap-6">
                    <div class="w-full md:w-80 space-y-3">
                        <div class="flex justify-between items-center text-gray-600">
                            <span class="text-sm">Total Tagihan</span>
                            <span class="text-lg font-bold text-gray-900">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                        </div>

                        @if($payment_status === 'paid')
                        <div class="space-y-3 pt-3 border-t border-gray-200">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Uang Diterima (Cash)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm font-bold">Rp</span>
                                    <input type="number" wire:model.live="amount_paid" class="w-full pl-10 pr-4 py-2 bg-white border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500 font-bold text-gray-800" placeholder="0">
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 {{ $changeAmount >= 0 ? 'bg-green-50' : 'bg-red-50' }} rounded-xl transition-colors">
                                <span class="text-xs font-bold {{ $changeAmount >= 0 ? 'text-green-700' : 'text-red-700' }} uppercase">Kembalian</span>
                                <span class="text-lg font-black {{ $changeAmount >= 0 ? 'text-green-700' : 'text-red-700' }}">
                                    Rp {{ number_format($changeAmount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between gap-4">
             <p class="text-xs text-gray-400 italic">* Pastikan data layanan sudah sesuai sebelum menyimpan.</p>
             <button type="submit" class="inline-flex items-center px-8 py-3 bg-primary-600 border border-transparent rounded-xl font-bold text-white shadow-lg shadow-primary-200 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                Simpan Transaksi
            </button>
        </div>
    </form>
</div>