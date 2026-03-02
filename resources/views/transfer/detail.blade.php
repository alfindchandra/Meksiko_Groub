<x-app-layout>
    @php
        $transfer = \App\Models\StockTransfer::with([
            'fromOutlet', 'toOutlet', 'requestedBy', 'approvedBy',
            'sentBy', 'receivedBy', 'items.product', 'shipment'
        ])->findOrFail($transferId);

        // Mapping Status Warna & Ikon
        $statusMap = [
            'pending'   => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'label' => 'Pending'],
            'approved'  => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'label' => 'Approved'],
            'in_transit'=> ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200', 'label' => 'In Transit'],
            'received'  => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'label' => 'Received'],
            'rejected'  => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'label' => 'Rejected'],
        ];
        $curr = $statusMap[$transfer->status] ?? $statusMap['pending'];
    @endphp

    <div class="min-h-screen bg-gray-50/50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Detail Transfer</h2>
                    <div class="flex items-center mt-1 space-x-2">
                        <span class="text-sm font-mono text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $transfer->transfer_number }}</span>
                        <span class="text-gray-300">•</span>
                        <span class="text-sm text-gray-500">{{ $transfer->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
                <a href="{{ route('transfer.list') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="{{ $curr['bg'] }} {{ $curr['border'] }} border-2 rounded-3xl p-6 flex flex-col items-center justify-center text-center">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] {{ $curr['text'] }} mb-2">Status Saat Ini</span>
                    <div class="text-2xl font-black {{ $curr['text'] }} flex items-center">
                        @if($transfer->status === 'in_transit')
                            <span class="relative flex h-3 w-3 mr-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-indigo-600"></span>
                            </span>
                        @endif
                        {{ $curr['label'] }}
                    </div>
                </div>

                <div class="md:col-span-2 bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Asal</p>
                            <h4 class="font-bold text-gray-900">{{ $transfer->fromOutlet->name }}</h4>
                            <p class="text-xs text-gray-500">{{ $transfer->fromOutlet->code }} • {{ $transfer->fromOutlet->city }}</p>
                        </div>
                        <div class="px-4">
                            <div class="bg-indigo-50 p-2 rounded-full">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </div>
                        </div>
                        <div class="flex-1 text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tujuan</p>
                            <h4 class="font-bold text-gray-900">{{ $transfer->toOutlet->name }}</h4>
                            <p class="text-xs text-gray-500">{{ $transfer->toOutlet->code }} • {{ $transfer->toOutlet->city }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm">
                        <div class="p-6 border-b border-gray-50">
                            <h3 class="font-bold text-gray-900">Rincian Barang</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Produk</th>
                                        <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Request</th>
                                        @if($transfer->sent_at) <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kirim</th> @endif
                                        @if($transfer->received_at) <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Terima</th> @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($transfer->items as $item)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="font-bold text-gray-900">{{ $item->product->name }}</p>
                                            <p class="text-[10px] font-mono text-gray-400">{{ $item->product->sku }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="font-bold text-gray-700">{{ number_format($item->quantity_requested) }}</span>
                                            <span class="text-[10px] text-gray-400 block">{{ $item->product->unit }}</span>
                                        </td>
                                        @if($transfer->sent_at)
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-lg font-bold text-sm">
                                                {{ number_format($item->quantity_sent ?? $item->quantity_requested) }}
                                            </span>
                                        </td>
                                        @endif
                                        @if($transfer->received_at)
                                        <td class="px-6 py-4 text-center">
                                            <span class="font-bold {{ $item->quantity_received != $item->quantity_sent ? 'text-rose-600' : 'text-emerald-600' }}">
                                                {{ number_format($item->quantity_received) }}
                                            </span>
                                            @if($item->quantity_received != $item->quantity_sent)
                                                <span class="block text-[9px] font-black text-rose-500 uppercase mt-1">Selisih</span>
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                    @if($item->notes)
                                    <tr>
                                        <td colspan="100%" class="px-6 py-2 bg-amber-50/50">
                                            <p class="text-xs text-amber-700 italic flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                                {{ $item->notes }}
                                            </p>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($transfer->notes)
                    <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Catatan Transfer</h3>
                        <p class="text-gray-700 text-sm leading-relaxed">{{ $transfer->notes }}</p>
                    </div>
                    @endif
                </div>

                <div class="space-y-6">
                    <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-md ring-4 ring-gray-50">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Tindakan Cepat</h3>
                        <div class="flex flex-col space-y-3">
                            @can('approve', $transfer)
                                @if($transfer->canBeApproved())
                                <a href="{{ route('transfer.approve', $transfer->id) }}" class="w-full flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                                    Setujui Transfer
                                </a>
                                @endif
                            @endcan

                            @can('send', $transfer)
                                @if($transfer->canBeSent())
                                <a href="{{ route('transfer.send', $transfer->id) }}" class="w-full flex items-center justify-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                                    Kirim Barang
                                </a>
                                @endif
                            @endcan
                            @can('manage-ruko')
                            @can('receive', $transfer)
                                @if($transfer->canBeReceived())
                                <a href="{{ route('transfer.receive', $transfer->id) }}" class="w-full flex items-center justify-center px-6 py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-100">
                                    Terima Barang
                                </a>
                                @endif
                            @endcan
                            @endcan
                            
                            @can('manage-users')
                            @if(in_array($transfer->status, ['in_transit', 'received']))
                                <a href="{{ route('transfer.surat-jalan', $transfer->id) }}"
                                   target="_blank"
                                   class="w-full flex items-center justify-center px-6 py-3 bg-white border border-gray-200 text-gray-600 font-bold rounded-2xl hover:bg-gray-50 transition-all">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                    Cetak Surat Jalan
                                </a>
                            @endif
                            @endcan
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-8">Riwayat Transfer</h3>
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                <li>
                                    <div class="relative pb-8">
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-emerald-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-0.5">
                                                <p class="text-sm font-bold text-gray-900">Transfer Diajukan</p>
                                                <p class="text-xs text-gray-500">{{ $transfer->requestedBy->name }} • {{ $transfer->created_at->format('d M, H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="relative pb-8">
                                        @if($transfer->sent_at) <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span> @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full {{ $transfer->approved_at ? 'bg-emerald-500' : 'bg-gray-200' }} flex items-center justify-center ring-8 ring-white transition-colors">
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-0.5">
                                                <p class="text-sm font-bold {{ $transfer->approved_at ? 'text-gray-900' : 'text-gray-400' }}">Verifikasi & Approval</p>
                                                @if($transfer->approved_at)
                                                    <p class="text-xs text-gray-500">{{ $transfer->approvedBy->name }} • {{ $transfer->approved_at->format('d M, H:i') }}</p>
                                                @else
                                                    <p class="text-xs text-gray-400 italic">Menunggu verifikasi...</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="relative pb-8">
                                        @if($transfer->received_at) <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span> @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full {{ $transfer->sent_at ? 'bg-emerald-500' : 'bg-gray-200' }} flex items-center justify-center ring-8 ring-white transition-colors">
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"></path></svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-0.5">
                                                <p class="text-sm font-bold {{ $transfer->sent_at ? 'text-gray-900' : 'text-gray-400' }}">Pengiriman Barang</p>
                                                @if($transfer->sent_at)
                                                    <p class="text-xs text-gray-500">{{ $transfer->sentBy->name }} • {{ $transfer->sent_at->format('d M, H:i') }}</p>
                                                @else
                                                    <p class="text-xs text-gray-400 italic text-balance">Menunggu kurir...</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="relative">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full {{ $transfer->received_at ? 'bg-emerald-500' : 'bg-gray-200' }} flex items-center justify-center ring-8 ring-white transition-colors">
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 13l4 4L19 7" clip-rule="evenodd"></path></svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-0.5">
                                                <p class="text-sm font-bold {{ $transfer->received_at ? 'text-gray-900' : 'text-gray-400' }}">Selesai / Diterima</p>
                                                @if($transfer->received_at)
                                                    <p class="text-xs text-gray-500">{{ $transfer->receivedBy->name }} • {{ $transfer->received_at->format('d M, H:i') }}</p>
                                                @else
                                                    <p class="text-xs text-gray-400 italic">Menunggu konfirmasi penerimaan...</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>