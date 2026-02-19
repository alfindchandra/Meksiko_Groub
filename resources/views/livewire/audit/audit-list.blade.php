<div class="p-4 sm:p-6 lg:p-8 bg-gray-50/50 min-h-screen">
    @section('page-title', 'Riwayat Audit Stok')

    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Audit Stok</h2>
            <p class="mt-1 text-sm text-gray-500">Pantau hasil rekonsiliasi stok fisik dan sistem di setiap outlet.</p>
        </div>
        @can('conduct-audit')
        <div class="flex-shrink-0">
            <a href="{{ route('audit.create') }}" 
               class="inline-flex items-center px-5 py-3 bg-indigo-600 border border-transparent rounded-2xl font-bold text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Audit Baru
            </a>
        </div>
        @endcan
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-12 items-end">
            <div class="md:col-span-4">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Cari Data</label>
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400 group-focus-within:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           placeholder="Nomor audit, SKU, atau nama produk..." 
                           class="block w-full pl-10 pr-3 py-2.5 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all shadow-sm">
                </div>
            </div>

            @can('access-all-outlets')
            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Outlet</label>
                <select wire:model.live="outletFilter" class="block w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 shadow-sm">
                    <option value="">Semua Outlet</option>
                    @foreach($outlets as $outlet)
                    <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                    @endforeach
                </select>
            </div>
            @endcan

            <div class="md:col-span-3">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Status Selisih</label>
                <select wire:model.live="differenceFilter" class="block w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 shadow-sm">
                    <option value="">Semua Status</option>
                    <option value="surplus">🟢 Surplus (Lebih)</option>
                    <option value="deficit">🔴 Defisit (Kurang)</option>
                    <option value="match">⚪ Sesuai (Match)</option>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Periode</label>
                <input type="date" wire:model.live="dateFrom" 
                       class="block w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5 shadow-sm">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Audit & Justifikasi</th>
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Produk</th>
                        @can('access-all-outlets')
                        <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Outlet</th>
                        @endcan
                        <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Sistem vs Fisik</th>
                        <th class="px-6 py-4 text-center text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Selisih</th>
                        <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Pelaksana</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($audits as $audit)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $audit->audit_number }}</span>
                                @if($audit->reason)
                                <span class="text-[11px] text-gray-400 mt-1 italic leading-tight max-w-[200px]" title="{{ $audit->reason }}">
                                    "{{ Str::limit($audit->reason, 50) }}"
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-800">{{ $audit->product->name }}</span>
                                <span class="text-[10px] font-mono text-gray-400 uppercase tracking-tighter">{{ $audit->product->sku }}</span>
                            </div>
                        </td>
                        @can('access-all-outlets')
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <span class="text-sm text-gray-600 font-medium">{{ $audit->outlet->name }}</span>
                            </div>
                        </td>
                        @endcan
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-3">
                                <div class="text-center">
                                    <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Sistem</span>
                                    <span class="text-sm font-bold text-gray-600">{{ number_format($audit->system_quantity) }}</span>
                                </div>
                                <div class="h-8 w-px bg-gray-100"></div>
                                <div class="text-center">
                                    <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Fisik</span>
                                    <span class="text-sm font-black text-gray-900">{{ number_format($audit->physical_quantity) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            @if($audit->difference > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    +{{ number_format($audit->difference) }}
                                </span>
                            @elseif($audit->difference < 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-rose-50 text-rose-700 border border-rose-100">
                                    {{ number_format($audit->difference) }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-50 text-gray-400 border border-gray-200 uppercase tracking-widest text-[9px]">
                                    Match
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex flex-col items-end">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-bold text-gray-800">{{ $audit->auditedBy->name }}</span>
                                   
                                </div>
                                <span class="text-[10px] text-gray-400 mt-1 font-medium">
                                    {{ $audit->audited_at->format('d M Y') }} • {{ $audit->audited_at->format('H:i') }}
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="100%" class="px-6 py-20 text-center">
                            <div class="max-w-xs mx-auto">
                                <div class="bg-gray-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">Belum ada audit</h3>
                                <p class="mt-1 text-sm text-gray-500">Sistem belum menemukan data riwayat audit stok untuk kriteria ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($audits->hasPages())
        <div class="px-6 py-6 bg-gray-50 border-t border-gray-100">
            {{ $audits->links() }}
        </div>
        @endif
    </div>
</div>