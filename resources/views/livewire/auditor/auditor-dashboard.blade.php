<div class="p-6 bg-gray-50 min-h-screen">
    @section('page-title', 'Auditor Intelligence Dashboard')

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Dashboard Auditor</h2>
            <p class="text-gray-500">Analisis integritas stok dan validasi penjualan real-time.</p>
        </div>
        
        <div class="flex items-center bg-white p-2 rounded-xl shadow-sm border border-gray-200">
            <div class="px-3">
                <label class="block text-[10px] uppercase font-bold text-gray-400">Periode Audit</label>
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="dateFrom" class="border-none p-0 focus:ring-0 text-sm font-semibold">
                    <span class="text-gray-300">→</span>
                    <input type="date" wire:model.live="dateTo" class="border-none p-0 focus:ring-0 text-sm font-semibold">
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute right-0 top-0 p-4 opacity-10">
                <svg class="w-16 h-16 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
            </div>
            <p class="text-sm font-medium text-gray-500">Total Penjualan (Revenue)</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
            <p class="text-xs text-green-600 mt-2 font-semibold">{{ $stats['total_sales_count'] }} Transaksi</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-sm font-medium text-gray-500">Varians Audit (Qty)</p>
            <p class="text-2xl font-bold {{ $stats['stock_variance'] > 0 ? 'text-orange-600' : 'text-gray-900' }} mt-1">
                {{ number_format($stats['stock_variance']) }} Unit
            </p>
            <div class="w-full bg-gray-100 h-1.5 mt-4 rounded-full">
                <div class="bg-orange-500 h-1.5 rounded-full" style="width: 45%"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-sm font-medium text-gray-500">Stock Transfer</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_transfers']) }}</p>
            <p class="text-xs text-gray-400 mt-2">Perpindahan antar outlet</p>
        </div>

        <div class="bg-red-50 p-6 rounded-2xl border border-red-100">
            <p class="text-sm font-medium text-red-600">Stok Kritis</p>
            <p class="text-2xl font-bold text-red-700 mt-1">{{ number_format($stats['low_stock_items']) }} SKU</p>
            <p class="text-xs text-red-500 mt-2 font-medium">Segera Cek Fisik!</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <div class="xl:col-span-2 space-y-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">Transaksi Penjualan Terbaru</h3>
                    <span class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded-md font-bold">LIVE</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase tracking-widest">
                            <tr>
                                <th class="px-6 py-3">No. Nota</th>
                                <th class="px-6 py-3">Outlet</th>
                                <th class="px-6 py-3 text-right">Total</th>
                                <th class="px-6 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            @foreach($recentSales as $sale)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="px-6 py-4 font-semibold text-gray-700">{{ $sale->sale_number }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $sale->outlet->name }}</td>
                                <td class="px-6 py-4 text-right font-bold">Rp {{ number_format($sale->total) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-bold">COMPLETED</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-50">
                    <h3 class="font-bold text-gray-800">Mutasi Stok Terakhir</h3>
                </div>
                <div class="p-0">
                    @foreach($stockHistories as $history)
                    <div class="flex items-center justify-between p-4 border-b border-gray-50 hover:bg-gray-50 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ in_array($history->type, ['in', 'transfer_in']) ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                @if(in_array($history->type, ['in', 'transfer_in']))
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $history->product->name }}</p>
                                <p class="text-xs text-gray-400">{{ $history->outlet->name }} • {{ $history->user->name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black {{ in_array($history->type, ['in', 'transfer_in']) ? 'text-green-600' : 'text-red-600' }}">
                                {{ in_array($history->type, ['in', 'transfer_in']) ? '+' : '-' }}{{ abs($history->quantity_change) }}
                            </p>
                            <p class="text-[10px] text-gray-400 uppercase font-bold">{{ $history->type_display }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
                <div class="p-5 bg-red-600 flex items-center justify-between">
                    <h3 class="font-bold text-white">Anomali Terdeteksi</h3>
                    <span class="animate-pulse bg-white/20 p-1 rounded-full"><div class="w-2 h-2 bg-white rounded-full"></div></span>
                </div>
                <div class="p-4 space-y-4">
                    @forelse($suspiciousActivities as $activity)
                    <div class="p-4 bg-red-50 rounded-xl border-l-4 border-red-500">
                        <div class="flex justify-between items-start">
                            <span class="text-xs font-bold text-red-600 uppercase tracking-tighter">Large Adjustment</span>
                            <span class="text-[10px] text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm font-bold text-gray-900 mt-1">{{ $activity->product->name }}</p>
                        <p class="text-xs text-gray-600 mt-1">
                            Perubahan: <span class="font-bold">{{ $activity->quantity_change }} qty</span> di {{ $activity->outlet->name }}
                        </p>
                        <button class="mt-3 w-full py-2 bg-white border border-red-200 text-red-600 text-xs font-bold rounded-lg hover:bg-red-600 hover:text-white transition">Investigasi</button>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <div class="text-gray-300 mb-2">✅</div>
                        <p class="text-sm text-gray-400 font-medium">Tidak ada aktivitas mencurigakan</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>