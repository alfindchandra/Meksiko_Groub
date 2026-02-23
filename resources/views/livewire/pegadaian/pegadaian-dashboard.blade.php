<div>
    @section('page-title', 'Dashboard Pegadaian')

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Dashboard Pegadaian</h1>
                        <p class="mt-2 text-sm text-gray-600">Monitoring transaksi gadai real-time</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <select wire:model.live="dateFilter" class="form-input rounded-xl border-gray-300 shadow-sm">
                            <option value="today">Hari Ini</option>
                            <option value="week">Minggu Ini</option>
                            <option value="month">Bulan Ini</option>
                            <option value="custom">Custom</option>
                        </select>
                        <a href="{{ route('pegadaian.create') }}" class="btn-primary whitespace-nowrap">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Gadai Baru
                        </a>
                    </div>
                </div>

                @if($dateFilter === 'custom')
                <div class="mt-4 flex items-center space-x-4">
                    <input type="date" wire:model.live="customDateFrom" class="form-input rounded-xl">
                    <span class="text-gray-500">s/d</span>
                    <input type="date" wire:model.live="customDateTo" class="form-input rounded-xl">
                </div>
                @endif
            </div>

            <!-- Main Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <!-- Active Loans -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-blue-100 text-sm font-medium">Gadai Aktif</p>
                            <p class="text-4xl font-black">{{ number_format($stats['total_active']) }}</p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-white border-opacity-20">
                        <p class="text-sm text-blue-100">Total Pinjaman</p>
                        <p class="text-xl font-bold">Rp {{ number_format($stats['total_active_amount'] / 1000000, 1) }}Jt</p>
                    </div>
                </div>

                <!-- Overdue -->
                <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-red-100 text-sm font-medium">Jatuh Tempo</p>
                            <p class="text-4xl font-black">{{ number_format($stats['total_overdue']) }}</p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-white border-opacity-20">
                        <p class="text-sm text-red-100">Total Pinjaman</p>
                        <p class="text-xl font-bold">Rp {{ number_format($stats['total_overdue_amount'] / 1000000, 1) }}Jt</p>
                    </div>
                </div>

                <!-- Period New -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-green-100 text-sm font-medium">Gadai Baru</p>
                            <p class="text-4xl font-black">{{ number_format($stats['period_new']) }}</p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-white border-opacity-20">
                        <p class="text-sm text-green-100">Nilai Transaksi</p>
                        <p class="text-xl font-bold">Rp {{ number_format($stats['period_loan_amount'] / 1000000, 1) }}Jt</p>
                    </div>
                </div>

                <!-- Revenue -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-purple-100 text-sm font-medium">Pendapatan</p>
                            <p class="text-4xl font-black">{{ number_format(($stats['period_admin_fee'] + $stats['period_interest']) / 1000000, 1) }}Jt</p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-white border-opacity-20 grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <p class="text-purple-100">Admin</p>
                            <p class="font-bold">Rp {{ number_format($stats['period_admin_fee'] / 1000, 0) }}K</p>
                        </div>
                        <div>
                            <p class="text-purple-100">Bunga</p>
                            <p class="font-bold">Rp {{ number_format($stats['period_interest'] / 1000, 0) }}K</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Secondary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-xl mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Ditebus Periode Ini</p>
                            <p class="text-2xl font-black text-gray-900">{{ number_format($stats['period_redeemed']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="bg-yellow-100 p-3 rounded-xl mr-4">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Diperpanjang</p>
                            <p class="text-2xl font-black text-gray-900">{{ number_format($stats['period_extended']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-xl mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Avg. Pinjaman</p>
                            <p class="text-2xl font-black text-gray-900">
                                Rp {{ $stats['period_new'] > 0 ? number_format($stats['period_loan_amount'] / $stats['period_new'] / 1000, 0) : 0 }}K
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Recent Transactions -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Transaksi Terbaru
                            </h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @forelse($recentPawns as $pawn)
                            <a href="{{ route('pegadaian.detail', $pawn->id) }}" 
                               class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold shadow-md">
                                        {{ strtoupper(substr($pawn->item_category, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $pawn->customer_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $pawn->item_name }} • {{ $pawn->pawn_number }}</p>
                                        <p class="text-xs text-gray-400">{{ $pawn->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">Rp {{ number_format($pawn->loan_amount, 0, ',', '.') }}</p>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $pawn->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $pawn->status === 'redeemed' ? 'bg-blue-100 text-blue-800' : '' }}">
                                        {{ ucfirst($pawn->status) }}
                                    </span>
                                </div>
                            </a>
                            @empty
                            <div class="p-12 text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-gray-500">Belum ada transaksi</p>
                            </div>
                            @endforelse
                        </div>
                        @if($recentPawns->count() > 0)
                        <div class="px-6 py-3 bg-gray-50 text-center">
                            <a href="{{ route('pegadaian.list') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                                Lihat Semua Transaksi →
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Due Soon Alert -->
                    @if($dueSoon->count() > 0)
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-2xl border-2 border-red-300 overflow-hidden">
                        <div class="px-6 py-4 bg-red-600 text-white">
                            <h3 class="text-lg font-bold flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Segera Jatuh Tempo
                            </h3>
                        </div>
                        <div class="p-4 space-y-2 max-h-96 overflow-y-auto">
                            @foreach($dueSoon as $pawn)
                            <a href="{{ route('pegadaian.detail', $pawn->id) }}"
                               class="block p-3 bg-white rounded-xl border border-red-200 hover:shadow-md transition-all">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-bold text-gray-900 text-sm">{{ $pawn->customer_name }}</p>
                                    <span class="text-xs font-bold text-red-600">
                                        {{ $pawn->due_date->diffInDays() }} hari
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600">{{ $pawn->item_name }}</p>
                                <p class="text-xs text-gray-500 mt-1">JT: {{ $pawn->due_date->format('d M Y') }}</p>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Category Breakdown -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900">Breakdown Kategori</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @forelse($categoryStats as $cat)
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-gray-700">
                                        @if($cat->item_category === 'emas') 💎
                                        @elseif($cat->item_category === 'perhiasan') 💍
                                        @elseif($cat->item_category === 'elektronik') 📱
                                        @elseif($cat->item_category === 'kendaraan') 🏍️
                                        @else 📦
                                        @endif
                                        {{ ucfirst($cat->item_category) }}
                                    </span>
                                    <span class="text-sm font-bold text-gray-900">{{ $cat->total }}</span>
                                </div>
                                <div class="relative pt-1">
                                    <div class="overflow-hidden h-2 text-xs flex rounded-full bg-gray-200">
                                        <div style="width:{{ $stats['total_active'] > 0 ? ($cat->total / $stats['total_active']) * 100 : 0 }}%" 
                                             class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center 
                                                    {{ $cat->item_category === 'emas' ? 'bg-yellow-500' : '' }}
                                                    {{ $cat->item_category === 'perhiasan' ? 'bg-purple-500' : '' }}
                                                    {{ $cat->item_category === 'elektronik' ? 'bg-blue-500' : '' }}
                                                    {{ $cat->item_category === 'kendaraan' ? 'bg-green-500' : '' }}
                                                    {{ !in_array($cat->item_category, ['emas', 'perhiasan', 'elektronik', 'kendaraan']) ? 'bg-gray-500' : '' }}">
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Rp {{ number_format($cat->amount / 1000000, 1) }}Jt
                                </p>
                            </div>
                            @empty
                            <p class="text-center text-sm text-gray-500 py-4">Belum ada data</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
                        <h3 class="text-lg font-bold mb-4">Quick Actions</h3>
                        <div class="space-y-2">
                            <a href="{{ route('pegadaian.create') }}" 
                               class="block w-full bg-white text-blue-600 py-3 rounded-xl font-bold text-center hover:shadow-lg transition-all">
                                + Gadai Baru
                            </a>
                            <a href="{{ route('pegadaian.list') }}" 
                               class="block w-full bg-white bg-opacity-20 backdrop-blur py-3 rounded-xl font-bold text-center hover:bg-opacity-30 transition-all">
                                📋 Lihat Semua
                            </a>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>