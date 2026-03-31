<div x-data="{ 
    showApproveModal: @entangle('showApproveModal'),
    showRejectModal: @entangle('showRejectModal')
}" class="min-h-screen py-8 bg-gray-50/50">
    @section('page-title', 'Persetujuan Transfer - ' . $transfer->transfer_number)

    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol
                        class="flex items-center space-x-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <li>Transfer</li>
                        <li><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z">
                                </path>
                            </svg></li>
                        <li class="text-indigo-600">Persetujuan</li>
                    </ol>
                </nav>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Persetujuan Transfer</h2>
                <p class="text-sm font-mono text-gray-500 mt-1">{{ $transfer->transfer_number }}</p>
            </div>
            <a href="{{ route('transfer.pending') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-bold text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <div class="flex items-center space-x-4">
                        <div
                            class="h-12 w-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900">Informasi Transfer</h3>
                            <p class="text-xs text-gray-500">Diajukan pada
                                {{ $transfer->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <span
                        class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black bg-amber-50 text-amber-700 border border-amber-100 uppercase tracking-widest">
                        <span class="w-2 h-2 bg-amber-400 rounded-full mr-2 animate-pulse"></span>
                        Menunggu Persetujuan
                    </span>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-8 p-6 bg-gray-50 rounded-2xl border border-gray-100 relative">
                    <div class="text-center md:text-left">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Outlet Pengirim
                            (Asal)</p>
                        <p class="text-lg font-bold text-gray-900">{{ $transfer->fromOutlet->name }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ $transfer->fromOutlet->code }} •
                            {{ $transfer->fromOutlet->city }}</p>
                    </div>

                    <div class="flex flex-col items-center justify-center">
                        <div class="bg-white p-2 rounded-full shadow-sm border border-gray-100">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>

                    <div class="text-center md:text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Outlet Penerima
                            (Tujuan)</p>
                        <p class="text-lg font-bold text-gray-900">{{ $transfer->toOutlet->name }}</p>
                        <p class="text-sm text-gray-500 font-medium">{{ $transfer->toOutlet->code }} •
                            {{ $transfer->toOutlet->city }}</p>
                    </div>
                </div>

                <div class="mt-6 flex items-center p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100/50">
                    <div
                        class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ substr($transfer->requestedBy->name, 0, 2) }}
                    </div>
                    <div class="ml-4">
                        <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-tight">Diminta Oleh</p>
                        <p class="text-sm font-bold text-indigo-900">{{ $transfer->requestedBy->name }} <span
                                class="font-normal text-indigo-500 ml-1">—
                                {{ $transfer->requestedBy->role->display_name }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-900">Daftar Barang yang Diminta</h3>
                <span
                    class="text-xs font-bold px-2 py-1 bg-gray-100 text-gray-500 rounded-lg">{{ $transfer->items->count() }}
                    Item</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Produk</th>
                            <th
                                class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Qty Diminta</th>
                            <th
                                class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Stok Asal</th>
                            <th
                                class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Kelayakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($transfer->items as $item)
                        @php
                        $stock = \App\Models\Stock::where('product_id', $item->product_id)->where('outlet_id',
                        $transfer->from_outlet_id)->first();
                        $available = $stock ? $stock->available : 0;
                        $canFulfill = $available >= $item->quantity_requested;
                        @endphp
                        <tr class="{{ $canFulfill ? 'hover:bg-gray-50/50' : 'bg-rose-50/30' }} transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900">{{ $item->product->name }}</p>
                                <div class="flex items-center mt-0.5 space-x-2">
                                    <span class="text-[10px] font-mono text-gray-400">SKU:
                                        {{ $item->product->sku }}</span>
                                    <span class="text-gray-300">|</span>
                                    <span
                                        class="text-[10px] text-indigo-500 font-bold uppercase">{{ $item->product->category->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-black text-gray-900">
                                {{ number_format($item->quantity_requested) }}
                                <span
                                    class="text-[10px] font-normal text-gray-400 ml-1">{{ $item->product->unit }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="text-sm font-black {{ $canFulfill ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ number_format($available) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($canFulfill)
                                <span class="inline-flex items-center text-emerald-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                @else
                                <span class="inline-flex items-center text-rose-600 animate-pulse">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @php
        $insufficientStock = $transfer->items->filter(function($item) use ($transfer) {
        $stock = \App\Models\Stock::where('product_id', $item->product_id)->where('outlet_id',
        $transfer->from_outlet_id)->first();
        return !$stock || $stock->available < $item->quantity_requested;
            })->count();
            @endphp

            @if($insufficientStock > 0)
            <div
                class="mb-6 p-4 bg-rose-600 rounded-2xl shadow-lg shadow-rose-100 flex items-center space-x-4 text-white">
                <div class="bg-rose-500 p-2 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-black uppercase tracking-tight">Perhatian: Stok Tidak Memadai</p>
                    <p class="text-xs opacity-90">Terdapat {{ $insufficientStock }} item yang permintaannya melebihi
                        stok tersedia di outlet asal.</p>
                </div>
            </div>
            @endif

            <div
                class="bg-indigo-900 rounded-3xl p-6 sm:p-8 shadow-2xl shadow-indigo-200 flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative">
                <div class="relative z-10">
                    <h3 class="text-xl font-black text-white mb-1">Berikan Keputusan</h3>
                    <p class="text-indigo-300 text-xs">Setiap tindakan Anda akan tercatat dalam audit log sistem.</p>
                </div>

                <div class="flex items-center space-x-3 w-full md:w-auto relative z-10">
                    <button @click="showRejectModal = true"
                        class="flex-1 md:flex-none px-6 py-3.5 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-2xl transition-all shadow-lg shadow-rose-900/20">
                        Tolak Transfer
                    </button>
                    <button @click="showApproveModal = true"
                        class="flex-1 md:flex-none px-8 py-3.5 bg-white text-indigo-900 font-black rounded-2xl hover:bg-indigo-50 transition-all shadow-lg">
                        Setujui Transfer
                    </button>
                </div>

                <div class="absolute -right-10 -bottom-10 opacity-10 text-white">
                    <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
    </div>

    <div x-show="showApproveModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showApproveModal = false"></div>
            <div
                class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-md w-full p-8 text-center border border-gray-100">
                <div
                    class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2">Setujui Transfer?</h3>
                <p class="text-sm text-gray-500 mb-8">Ini akan memberi sinyal kepada outlet pengirim untuk segera
                    memproses fisik barang.</p>
                <div class="grid grid-cols-2 gap-3">
                    <button @click="showApproveModal = false"
                        class="px-6 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200 transition-all">Batal</button>
                    <button wire:click="approve"
                        class="px-6 py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">Ya,
                        Setujui</button>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showRejectModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showRejectModal = false"></div>
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-md w-full p-8 border border-gray-100">
                <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-900 mb-2">Tolak Transfer?</h3>
                <p class="text-sm text-gray-500 mb-6">Mohon berikan alasan penolakan agar pemohon dapat memperbaiki
                    permintaan di masa mendatang.</p>

                <div class="mb-8">
                    <textarea wire:model="rejectionReason" rows="3"
                        class="w-full rounded-2xl border-gray-200 focus:ring-rose-500 focus:border-rose-500 p-4 text-sm"
                        placeholder="Contoh: Stok sedang dipesan oleh customer lain..."></textarea>
                    @error('rejectionReason') <p class="mt-2 text-[10px] text-rose-500 font-black uppercase">
                        {{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button @click="showRejectModal = false"
                        class="px-6 py-4 bg-gray-100 text-gray-500 font-bold rounded-2xl hover:bg-gray-200">Batal</button>
                    <button wire:click="reject"
                        class="px-6 py-4 bg-rose-600 text-white font-bold rounded-2xl hover:bg-rose-700 shadow-lg shadow-rose-200 transition-all">Tolak
                        Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</div>