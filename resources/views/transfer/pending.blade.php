<x-app-layout>
    @php
        $query = \App\Models\StockTransfer::with(['fromOutlet', 'toOutlet', 'requestedBy'])
            ->where('status', 'pending');

        if (auth()->user()->isKepalaRuko()) {
            $query->where('to_outlet_id', auth()->user()->outlet_id);
        }

        $pendingTransfers = $query->latest()->get();
    @endphp

    <div class="max-w-6xl mx-auto">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Transfer Menunggu Persetujuan</h2>
            <p class="mt-1 text-sm text-gray-600">Daftar transfer yang membutuhkan persetujuan Anda</p>
        </div>

        @if($pendingTransfers->count() > 0)
        <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm font-medium text-yellow-800">
                    Terdapat {{ $pendingTransfers->count() }} transfer yang menunggu persetujuan Anda
                </p>
            </div>
        </div>
        @endif

        <div class="space-y-4">
            @forelse($pendingTransfers as $transfer)
            <div class="card hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $transfer->transfer_number }}</h3>
                        <p class="text-sm text-gray-500">{{ $transfer->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="badge badge-warning text-lg">Pending</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Dari Outlet</p>
                        <p class="font-semibold text-gray-900">{{ $transfer->fromOutlet->name }}</p>
                        <p class="text-xs text-gray-500">{{ $transfer->fromOutlet->code }}</p>
                    </div>
                    <div class="flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Ke Outlet</p>
                        <p class="font-semibold text-gray-900">{{ $transfer->toOutlet->name }}</p>
                        <p class="text-xs text-gray-500">{{ $transfer->toOutlet->code }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-xs font-semibold text-blue-600">
                                {{ substr($transfer->requestedBy->name, 0, 2) }}
                            </span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $transfer->requestedBy->name }}</p>
                            <p class="text-xs text-gray-500">{{ $transfer->items->count() }} items</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('transfer.detail', $transfer->id) }}" 
                           class="btn-secondary text-sm">
                            Lihat Detail
                        </a>
                        <a href="{{ route('transfer.approve', $transfer->id) }}" 
                           class="btn-primary text-sm">
                            Review & Setujui
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="card text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="mt-4 text-lg font-semibold text-gray-900">Tidak Ada Transfer Pending</p>
                <p class="mt-2 text-sm text-gray-500">Semua transfer sudah diproses</p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>