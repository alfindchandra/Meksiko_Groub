<div class="px-4 py-6 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Daftar Transaksi</h1>
            <p class="mt-1 text-sm text-gray-500">Pantau dan kelola seluruh pesanan masuk Meksiko Clean.</p>
        </div>
        <div>
            @can('manage-clean')
            <a href="{{ route('meksikoclean.transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:border-primary-900 focus:ring ring-primary-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Transaksi
            </a>
            @endcan
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filters -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="w-full sm:w-1/3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari No. Order / Pelanggan..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
        </div>
        <div class="w-full sm:w-1/4">
            <select wire:model.live="statusFilter" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                <option value="">Semua Status Pengerjaan</option>
                <option value="pending">Menunggu / Pending</option>
                <option value="proses">Sedang Diproses</option>
                <option value="selesai">Selesai Dikerjakan</option>
                <option value="diambil">Sudah Diambil</option>
            </select>
        </div>
    </div>

    <!-- Cards (Responsive Approach) -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse ($transactions as $trx)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                <!-- Header Card -->
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ $trx->transaction_number }}</span>
                        <div class="text-sm text-gray-400">{{ $trx->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div>
                        @if($trx->status === 'pending')
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @elseif($trx->status === 'proses')
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Diproses</span>
                        @elseif($trx->status === 'selesai')
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Diambil</span>
                        @endif
                    </div>
                </div>

                <!-- Body Card -->
                <div class="px-6 py-5 flex-grow space-y-4">
                    <!-- Customer Details -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $trx->customer_name }}</p>
                            <p class="text-xs text-gray-500">{{ $trx->customer_phone ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Type/Source Details -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 capitalize">{{ $trx->order_type }}</p>
                            @if($trx->order_type === 'mitra' && $trx->partner)
                                <p class="text-xs text-gray-500">{{ $trx->partner->name }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 mt-4">
                        <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Layanan dipesan</p>
                        <ul class="space-y-1">
                            @foreach($trx->items as $item)
                                <li class="text-sm text-gray-800 flex justify-between">
                                    <span class="truncate pr-2">- {{ $item->item_name }} (x{{ $item->qty }})</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Footer Card -->
                <div class="px-6 py-4 bg-gray-50 flex justify-between items-center border-t border-gray-100">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500">Total</span>
                        <span class="font-bold text-gray-900">Rp{{ number_format($trx->total_amount, 0, ',', '.') }}</span>
                        @if($trx->payment_status === 'paid')
                            <span class="text-xs font-semibold text-green-600">LUNAS</span>
                        @else
                            <span class="text-xs font-semibold text-red-600">BELUM LUNAS</span>
                        @endif
                    </div>
                    
                    <!-- Action Menu -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                            <span class="sr-only">Buka menu</span>
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path></svg>
                        </button>
                        
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100" 
                             x-transition:enter-start="transform opacity-0 scale-95" 
                             x-transition:enter-end="transform opacity-100 scale-100" 
                             x-transition:leave="transition ease-in duration-75" 
                             x-transition:leave-start="transform opacity-100 scale-100" 
                             x-transition:leave-end="transform opacity-0 scale-95" 
                             style="display: none;"
                             class="absolute right-0 bottom-full mb-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20">
                            <div class="py-1" role="menu" aria-orientation="vertical">
                                <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">Ubah Status</div>
                                @if($trx->status !== 'pending')
                                    <button wire:click="updateStatus({{ $trx->id }}, 'pending')" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none" role="menuitem">Menunggu / Pending</button>
                                @endif
                                @if($trx->status !== 'proses')
                                    <button wire:click="updateStatus({{ $trx->id }}, 'proses')" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none" role="menuitem">Sedang Diproses</button>
                                @endif
                                @if($trx->status !== 'selesai')
                                    <button wire:click="updateStatus({{ $trx->id }}, 'selesai')" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none" role="menuitem">Selesai Dikerjakan</button>
                                @endif
                                @if($trx->status !== 'diambil')
                                    <button wire:click="updateStatus({{ $trx->id }}, 'diambil')" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none" role="menuitem">Sudah Diambil</button>
                                @endif
                                
                                @if($trx->payment_status === 'unpaid')
                                <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-t border-gray-100 mt-1">Pembayaran</div>
                                <button wire:click="markAsPaid({{ $trx->id }})" class="w-full text-left block px-4 py-2 text-sm text-green-700 font-bold hover:bg-green-50 rounded-b-md focus:outline-none" role="menuitem">
                                    Tandai Lunas
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 bg-white rounded-2xl shadow-sm border border-gray-100 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada transaksi</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai terima order cuci / repair baru.</p>
                <div class="mt-6">
                    <a href="{{ route('meksikoclean.transactions.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Buat Transaksi
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
