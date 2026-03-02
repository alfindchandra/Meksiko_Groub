<div class="p-4 sm:p-6 lg:p-8 bg-gray-50 min-h-screen">
    @section('page-title', 'Daftar Gadai')

    <div class="mb-8 sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Daftar Transaksi Gadai</h2>
            <p class="mt-2 text-sm text-gray-600">Pusat pengelolaan data dan status gadai nasabah secara real-time.</p>
        </div>

    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">
        <div class="relative group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Gadai Aktif</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_active']) }}</p>
                </div>
            </div>
        </div>
        <div class="relative group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-red-50 text-red-600 group-hover:bg-red-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_overdue']) }}</p>
                </div>
            </div>
        </div>
        <div class="relative group bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
            <div class="flex items-center">
                <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="ml-5">
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pinjaman</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_loan'] / 1000000, 1) }} Jt</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                <label class="text-xs font-semibold text-gray-500 uppercase mb-1 block">Cari Nasabah</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="No. Gadai / Nama..." 
                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 uppercase mb-1 block">Status</label>
                <select wire:model.live="statusFilter" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="extended">Diperpanjang</option>
                    <option value="redeemed">Ditebus</option>
                    <option value="defaulted">Lelang</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 uppercase mb-1 block">Kategori</label>
                <select wire:model.live="categoryFilter" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                    <option value="">Semua Kategori</option>
                    <option value="emas">Emas</option>
                    <option value="perhiasan">Perhiasan</option>
                    <option value="elektronik">Elektronik</option>
                    <option value="kendaraan">Kendaraan</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 uppercase mb-1 block">Dari</label>
                <input type="date" wire:model.live="dateFrom" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-500 uppercase mb-1 block">Sampai</label>
                <input type="date" wire:model.live="dateTo" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No. Gadai</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nasabah</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Detail Barang</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Pinjaman</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($pawns as $pawn)
                    <tr class="hover:bg-indigo-50/30 transition-colors {{ $pawn->isOverdue() ? 'bg-red-50/40' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded bg-gray-100 text-xs font-mono font-bold text-gray-700 leading-tight">{{ $pawn->pawn_number }}</span>
                            <p class="text-[10px] mt-1 text-gray-400 font-medium italic">{{ $pawn->start_date->format('d M Y') }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-semibold text-gray-900">{{ $pawn->customer_name }}</p>
                            <p class="text-xs text-gray-500">{{ $pawn->customer_phone }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-900 font-medium">{{ $pawn->item_name }}</p>
                            <span class="inline-flex items-center text-[11px] text-gray-500">
                                {{ ucfirst($pawn->item_category) }} @if($pawn->item_weight) <span class="mx-1">•</span> {{ $pawn->item_weight }}gr @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <p class="text-sm font-bold text-indigo-600">Rp {{ number_format($pawn->loan_amount, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <p class="text-xs {{ $pawn->isOverdue() ? 'text-red-600 font-bold' : 'text-gray-900 font-medium' }}">
                                {{ $pawn->due_date->format('d M Y') }}
                            </p>
                            @if($pawn->isOverdue())
                                <span class="inline-block px-2 py-0.5 mt-1 rounded-full bg-red-100 text-[10px] text-red-700 font-bold tracking-tight">Terlambat {{ $pawn->days_overdue }} Hari</span>
                            @else
                                <p class="text-[10px] text-gray-400 italic">{{ $pawn->due_date->diffForHumans() }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $statusClasses = [
                                    'active' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'extended' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'redeemed' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'defaulted' => 'bg-gray-100 text-gray-700 border-gray-200',
                                ];
                                $currentClass = $statusClasses[$pawn->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold border {{ $currentClass }}">
                                {{ strtoupper($pawn->status === 'active' ? 'Aktif' : ($pawn->status === 'extended' ? 'Perpanjang' : ($pawn->status === 'redeemed' ? 'Lunas' : 'Lelang'))) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('pegadaian.detail', $pawn->id) }}" class="inline-flex p-2 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-lg transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                <p class="text-sm font-medium text-gray-500">Tidak ada data gadai ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pawns->hasPages())
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $pawns->links() }}
        </div>
        @endif
    </div>
</div>