<div class="p-4 sm:p-6 lg:p-8 bg-gray-50/50 min-h-screen">
    @section('page-title', 'Daftar Transfer')

    <div class="mb-8 sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Daftar Transfer</h2>
            <p class="mt-1 text-sm text-gray-500">Pantau dan kelola pergerakan stok antar outlet Anda.</p>
        </div>
        @can('create-transfer')
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('transfer.create') }}" 
               class="inline-flex items-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-bold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Transfer Baru
            </a>
        </div>
        @endcan
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 mb-6">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-12 items-end">
            <div class="md:col-span-4">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Cari Transfer</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 h-4 text-gray-400 group-focus-within:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           placeholder="Cari nomor transfer..." 
                           class="block w-full pl-10 pr-3 py-2.5 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">
                </div>
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Status</label>
                <select wire:model.live="statusFilter" class="block w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5">
                    <option value="">Semua Status</option>
                    <option value="pending">🟡 Pending</option>
                    <option value="approved">🔵 Approved</option>
                    <option value="in_transit">🚚 In Transit</option>
                    <option value="received">🟢 Received</option>
                    <option value="rejected">🔴 Rejected</option>
                </select>
            </div>

            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Outlet</label>
                <select wire:model.live="outletFilter" class="block w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5">
                    <option value="">Semua Outlet</option>
                    @foreach($outlets as $outlet)
                    <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 ml-1">Periode Dari</label>
                <input type="date" wire:model.live="dateFrom"
                       class="block w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Informasi Transfer</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Rute Logistik</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Peminta</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Waktu</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transfers as $transfer)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $transfer->transfer_number }}</span>
                                @if($transfer->notes)
                                <span class="text-xs text-gray-400 mt-0.5 truncate max-w-[150px]" title="{{ $transfer->notes }} italic">
                                    "{{ $transfer->notes }}"
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="flex flex-col items-end">
                                    <span class="text-xs font-bold text-gray-700">{{ $transfer->fromOutlet->code }}</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-gray-700">{{ $transfer->toOutlet->code }}</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-400 mt-1">{{ $transfer->fromOutlet->name }} → {{ $transfer->toOutlet->name }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex items-center">
                                
                                {{ $transfer->requestedBy->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700 block font-medium">{{ $transfer->created_at->format('d M, Y') }}</span>
                            <span class="text-[11px] text-gray-400">{{ $transfer->created_at->format('H:i') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
                                    'approved' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'in_transit' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                    'received' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'rejected' => 'bg-rose-50 text-rose-700 border-rose-100',
                                ];
                                $class = $statusClasses[$transfer->status] ?? 'bg-gray-50 text-gray-700 border-gray-100';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $class }}">
                                @if($transfer->status === 'in_transit')
                                    <span class="relative flex h-2 w-2 mr-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                                    </span>
                                @endif
                                {{ strtoupper(str_replace('_', ' ', $transfer->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('transfer.detail', $transfer->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 border border-gray-200 rounded-lg text-gray-600 bg-white hover:bg-gray-50 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm">
                                <span>Detail</span>
                                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="max-w-xs mx-auto">
                                <div class="bg-gray-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0l-8 4-8-4"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900">Tidak ada data</h3>
                                <p class="mt-1 text-xs text-gray-500">Kami tidak menemukan data transfer dengan kriteria pencarian Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transfers->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $transfers->links() }}
        </div>
        @endif
    </div>
</div>