<div class="min-h-screen bg-[#f8fafc] pb-12">
    @section('page-title', 'Riwayat Transaksi')

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 px-1">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Transaksi</h2>
            <p class="mt-1 text-sm text-slate-500 font-medium">Pantau dan kelola seluruh penjualan outlet Anda</p>
        </div>
        <div class="flex gap-3">
             <button wire:click="$refresh" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                Refresh
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="relative overflow-hidden bg-white p-6 rounded-3xl border border-slate-100 shadow-sm group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full transition-transform group-hover:scale-150 duration-500"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Transaksi</p>
                    <p class="text-3xl font-black text-slate-800 mt-1">{{ number_format($stats['total_sales']) }}</p>
                </div>
                <div class="p-4 bg-blue-500 rounded-2xl text-white shadow-lg shadow-blue-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden bg-white p-6 rounded-3xl border border-slate-100 shadow-sm group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full transition-transform group-hover:scale-150 duration-500"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Total Pendapatan</p>
                    <p class="text-3xl font-black text-slate-800 mt-1">
                        <span class="text-lg font-bold text-emerald-500">Rp</span> {{ number_format($stats['total_revenue'] / 1000000, 1) }}<span class="text-lg">Jt</span>
                    </p>
                </div>
                <div class="p-4 bg-emerald-500 rounded-2xl text-white shadow-lg shadow-emerald-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden bg-white p-6 rounded-3xl border border-slate-100 shadow-sm group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-50 rounded-full transition-transform group-hover:scale-150 duration-500"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Item Terjual</p>
                    <p class="text-3xl font-black text-slate-800 mt-1">{{ number_format($stats['total_items']) }}</p>
                </div>
                <div class="p-4 bg-purple-500 rounded-2xl text-white shadow-lg shadow-purple-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="relative">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Cari Transaksi</label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="No. Nota / Pelanggan..." 
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all font-medium">
                    <svg class="w-4 h-4 absolute left-3 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Rentang Tanggal</label>
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="dateFrom" class="w-full py-3 bg-slate-50 border-none rounded-2xl text-xs focus:ring-2 focus:ring-blue-500/20 font-medium">
                    <span class="text-slate-300">-</span>
                    <input type="date" wire:model.live="dateTo" class="w-full py-3 bg-slate-50 border-none rounded-2xl text-xs focus:ring-2 focus:ring-blue-500/20 font-medium">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Metode Bayar</label>
                <select wire:model.live="paymentMethodFilter" class="w-full py-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/20 font-medium">
                    <option value="">Semua Metode</option>
                    <option value="cash">💵 Cash (Tunai)</option>
                    <option value="transfer">🏦 Transfer Bank</option>
                </select>
            </div>
            
        </div>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nota & Kasir</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Waktu</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Pelanggan</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center text-center">Items</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Metode</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Total Akhir</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($sales as $sale)
                    <tr class="group hover:bg-slate-50/80 transition-all duration-200">
                        <td class="px-8 py-5">
                            <span class="font-mono font-bold text-slate-800 text-sm block tracking-tighter">{{ $sale->sale_number }}</span>
                            <span class="text-[11px] text-slate-400 font-medium flex items-center mt-1 uppercase">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                                {{ $sale->user->name }}
                            </span>
                        </td>
                        <td class="px-8 py-5 whitespace-nowrap">
                            <span class="text-sm font-bold text-slate-700 block">{{ $sale->sale_date->format('d M Y') }}</span>
                            <span class="text-[11px] text-slate-400 font-mono">{{ $sale->sale_date->format('H:i') }} WIB</span>
                        </td>
                        <td class="px-8 py-5">
                            @if($sale->customer_name)
                                <span class="text-sm font-bold text-slate-800 block">{{ $sale->customer_name }}</span>
                                <span class="text-[11px] text-slate-400">{{ $sale->customer_phone ?? '-' }}</span>
                            @else
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-400 text-[10px] font-black uppercase rounded-lg tracking-widest">Umum</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="text-sm font-black text-slate-800">{{ $sale->items->sum('quantity') }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase leading-none mt-1">Unit</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest
                                {{ $sale->payment_method === 'cash' 
                                    ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' 
                                    : 'bg-blue-50 text-blue-600 border border-blue-100' }}">
                                {{ $sale->payment_method }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <span class="text-sm font-black text-slate-900 block font-mono">Rp {{ number_format($sale->total, 0, ',', '.') }}</span>
                            @if($sale->discount > 0)
                                <span class="text-[10px] font-bold text-emerald-500 uppercase">Hemat Rp {{ number_format($sale->discount, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-center">
                            <a href="{{ route('pos.receipt', $sale->id) }}" target="_blank" 
                               class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-600 hover:shadow-lg hover:shadow-blue-100 transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                </div>
                                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Data transaksi tidak ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
            {{ $sales->links() }}
        </div>
        @endif
    </div>
</div>