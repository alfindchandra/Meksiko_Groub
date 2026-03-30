<x-app-layout>
    @php
        $query = \App\Models\StockTransfer::with(['fromOutlet', 'toOutlet', 'requestedBy', 'items'])
            ->where('status', 'pending');

        if (auth()->user()->isRider()) {
            $query->where('to_outlet_id', auth()->user()->outlet_id);
        }

        $pendingTransfers = $query->latest()->get();
    @endphp

    <div class="max-w-5xl mx-auto px-4 py-8 sm:px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Persetujuan Transfer</h2>
                <p class="mt-2 text-base text-gray-600">Kelola dan tinjau permintaan perpindahan stok antar outlet.</p>
            </div>
            
            @if($pendingTransfers->count() > 0)
            <div class="flex items-center space-x-2 bg-orange-50 px-4 py-2 rounded-lg border border-orange-100">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                </span>
                <span class="text-sm font-bold text-orange-700">{{ $pendingTransfers->count() }} Menunggu Tindakan</span>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            @forelse($pendingTransfers as $transfer)
            <div class="group bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-xl hover:border-primary-200 transition-all duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-gray-100 rounded-lg group-hover:bg-primary-50 transition-colors">
                                <svg class="w-6 h-6 text-gray-500 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 leading-none">{{ $transfer->transfer_number }}</h3>
                                <p class="text-xs text-gray-500 mt-1.5 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Diajukan {{ $transfer->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-100 text-amber-700 border border-amber-200">
                            Pending Approval
                        </span>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 border border-gray-100">
                        <div class="flex-1 text-center sm:text-left">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Asal</p>
                            <p class="font-bold text-gray-800">{{ $transfer->fromOutlet->name }}</p>
                            <p class="text-xs font-mono text-gray-500">{{ $transfer->fromOutlet->code }}</p>
                        </div>
                        
                        <div class="flex items-center justify-center">
                            <div class="h-8 w-8 rounded-full bg-white shadow-sm border border-gray-200 flex items-center justify-center">
                                <svg class="w-4 h-4 text-primary-500 rotate-90 sm:rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </div>
                        </div>

                        <div class="flex-1 text-center sm:text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tujuan</p>
                            <p class="font-bold text-gray-800">{{ $transfer->toOutlet->name }}</p>
                            <p class="text-xs font-mono text-gray-500">{{ $transfer->toOutlet->code }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-4 border-t border-gray-100">
                        <div class="flex items-center w-full sm:w-auto">
                            <div class="relative">
                                <img class="h-10 w-10 rounded-full object-cover border-2 border-white shadow-sm" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode($transfer->requestedBy->name) }}&color=7F9CF5&background=EBF4FF" 
                                     alt="">
                                <div class="absolute -bottom-1 -right-1 bg-green-500 h-3 w-3 rounded-full border-2 border-white"></div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-900">{{ $transfer->requestedBy->name }}</p>
                                <p class="text-xs font-medium text-primary-600 bg-primary-50 px-2 py-0.5 rounded inline-block mt-0.5">
                                    {{ $transfer->items->count() }} Variasi Produk
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3 w-full sm:w-auto">
                            <a href="{{ route('transfer.detail', $transfer->id) }}" 
                               class="flex-1 sm:flex-none text-center px-5 py-2.5 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                                Detail
                            </a>
                            <a href="{{ route('transfer.approve', $transfer->id) }}" 
                               class="flex-1 sm:flex-none text-center px-5 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-md shadow-primary-100 transition-all transform active:scale-95 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Review & Setujui
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-3xl border-2 border-dashed border-gray-200 p-16 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-50 rounded-full mb-6">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">Kotak Masuk Bersih!</h3>
                <p class="mt-2 text-gray-500 max-w-xs mx-auto">Tidak ada permintaan transfer stok yang menunggu persetujuan Anda saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>