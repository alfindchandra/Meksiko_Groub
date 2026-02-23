<div>
<div x-data="{ 
    showRedemption: @entangle('showRedemptionModal'), 
    showExtension: @entangle('showExtensionModal'),
    selectedPhoto: null 
}" class="p-4 sm:p-6 lg:p-8 bg-gray-50 min-h-screen">
    @section('page-title', 'Detail Gadai')

    <div class="max-w-6xl mx-auto">
        <nav class="flex mb-4 text-gray-500 text-xs tracking-widest uppercase font-bold" aria-label="Breadcrumb">
            <a href="{{ route('pegadaian.list') }}" class="hover:text-indigo-600 transition-colors">Daftar Gadai</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">Detail Transaksi</span>
        </nav>

        <div class="mb-8 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                    {{ $pawn->pawn_number }}
                </h2>
                <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:space-x-6 text-sm text-gray-500">
                    <div class="mt-2 flex items-center">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Outlet: {{ $pawn->outlet->name }}
                    </div>
                    <div class="mt-2 flex items-center">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Petugas: {{ $pawn->user->name }}
                    </div>
                </div>
            </div>
            <div class="mt-5 flex lg:mt-0 lg:ml-4 space-x-3">
                <a href="{{ route('pegadaian.list') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all">
                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali
                </a>
                <a href="{{ route('pegadaian.receipt', $pawn->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Bukti
                </a>
            </div>
        </div>

        @php
            $statusStyles = [
                'active' => 'bg-emerald-50 border-emerald-200 text-emerald-800 ring-emerald-600/20',
                'extended' => 'bg-amber-50 border-amber-200 text-amber-800 ring-amber-600/20',
                'redeemed' => 'bg-blue-50 border-blue-200 text-blue-800 ring-blue-600/20',
                'defaulted' => 'bg-red-50 border-red-200 text-red-800 ring-red-600/20',
            ];
            $currentStyle = $statusStyles[$pawn->status] ?? $statusStyles['active'];
        @endphp

        <div class="mb-8 p-6 rounded-2xl border-2 {{ $currentStyle }} shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="p-3 rounded-full bg-white/50 shadow-inner">
                        <div class="h-4 w-4 rounded-full animate-pulse 
                            {{ $pawn->status === 'active' ? 'bg-emerald-500' : '' }}
                            {{ $pawn->status === 'extended' ? 'bg-amber-500' : '' }}
                            {{ $pawn->status === 'redeemed' ? 'bg-blue-500' : '' }}
                            {{ $pawn->status === 'defaulted' ? 'bg-red-500' : '' }}">
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest opacity-70">Status Transaksi</p>
                        <p class="text-xl font-bold italic">
                            @if($pawn->status === 'active') AKTIF
                            @elseif($pawn->status === 'extended') DIPERPANJANG
                            @elseif($pawn->status === 'redeemed') SUDAH DITEBUS (LUNAS)
                            @else LELANG
                            @endif
                        </p>
                    </div>
                </div>
                
                @if($pawn->isOverdue())
                <div class="flex items-center px-4 py-2 bg-red-600 text-white rounded-xl animate-bounce shadow-lg">
                    <span class="font-black text-sm tracking-tighter">⚠️ TERLAMBAT {{ $pawn->days_overdue }} HARI</span>
                </div>
                @endif

                @if($pawn->isActive())
                <div class="flex items-center space-x-3">
                    <button wire:click="openExtensionModal" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition-all shadow-sm">
                        Perpanjang
                    </button>
                    <button wire:click="openRedemptionModal" class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg">
                        Tebus Sekarang
                    </button>
                </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Data Nasabah</h3>
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase">Nama Lengkap</label>
                                <p class="text-sm font-bold text-gray-900">{{ $pawn->customer_name }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase">No. Identitas (KTP)</label>
                                <p class="text-sm font-mono font-medium text-indigo-600">{{ $pawn->customer_id_number }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase">Kontak</label>
                                <p class="text-sm font-bold text-gray-900">{{ $pawn->customer_phone }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase">Alamat</label>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $pawn->customer_address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Barang Jaminan</h3>
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-6 space-y-4">
                                <div class="pt-4 border-t border-gray-50">
                                    <label class="text-[10px] font-black text-gray-400 uppercase block mb-3">Foto Jaminan</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        @if($pawn->item_photos && count($pawn->item_photos) > 0)
                                            @foreach($pawn->item_photos as $photo)
                                            <div @click="selectedPhoto = '{{ asset('storage/' . $photo) }}'" 
                                                class="relative aspect-square rounded-lg overflow-hidden border border-gray-100 cursor-pointer hover:ring-2 hover:ring-indigo-500 transition-all group">
                                                <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="col-span-3 py-4 text-center border-2 border-dashed border-gray-100 rounded-xl">
                                                <p class="text-xs text-gray-400">Tidak ada foto barang</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase">Nama Barang</label>
                                <p class="text-sm font-bold text-gray-900">{{ $pawn->item_name }}</p>
                            </div>
                            <div class="flex justify-between">
                                <div>
                                    <label class="text-[10px] font-black text-gray-400 uppercase block">Kategori</label>
                                    <span class="px-2 py-0.5 rounded-lg bg-gray-100 text-[10px] font-bold text-gray-600">{{ ucfirst($pawn->item_category) }}</span>
                                </div>
                                @if($pawn->item_weight)
                                <div class="text-right">
                                    <label class="text-[10px] font-black text-gray-400 uppercase block">Berat</label>
                                    <p class="text-sm font-bold text-gray-900">{{ $pawn->item_weight }} gr</p>
                                </div>
                                @endif
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase">Deskripsi Kondisi</label>
                                <p class="text-sm text-gray-600 italic">"{{ $pawn->item_description ?? 'Tidak ada deskripsi' }}"</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 border-l-4 border-emerald-500 pl-4">Rincian Keuangan</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-500 font-medium">Nilai Taksir Barang</span>
                                <span class="text-sm font-bold text-gray-900">Rp {{ number_format($pawn->appraisal_value, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-t border-gray-50">
                                <span class="text-sm text-gray-500 font-medium">Pinjaman Pokok</span>
                                <span class="text-lg font-black text-emerald-600">Rp {{ number_format($pawn->loan_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-t border-gray-50">
                                <span class="text-sm text-gray-500 font-medium">Biaya Admin (Dipotong awal)</span>
                                <span class="text-sm font-bold text-red-500">- Rp {{ number_format($pawn->admin_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-4 bg-indigo-50 rounded-2xl border border-indigo-100 mt-4">
                                <div>
                                    <p class="text-xs font-black text-indigo-700 uppercase tracking-widest">Pinjaman Diterima Bersih</p>
                                    <p class="text-xs text-indigo-500 mt-1 italic">*Jumlah yang diberikan ke nasabah di awal</p>
                                </div>
                                <span class="text-xl font-black text-indigo-700">
                                    Rp {{ number_format($pawn->loan_amount - $pawn->admin_fee, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-8 pt-6 border-t border-gray-100">
                            <div class="p-4 bg-gray-50 rounded-2xl">
                                <p class="text-[10px] font-black text-gray-400 uppercase">Bunga per Bulan</p>
                                <p class="text-lg font-bold text-gray-900">{{ $pawn->interest_rate }}%</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-2xl">
                                <p class="text-[10px] font-black text-gray-400 uppercase">Masa Tenor</p>
                                <p class="text-lg font-bold text-gray-900">{{ $pawn->loan_period_days }} Hari</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($pawn->payments->count() > 0 || $pawn->extensions->count() > 0)
                <div class="space-y-6">
                    <h3 class="text-lg font-extrabold text-gray-900">Log Aktivitas Transaksi</h3>
                    
                    @if($pawn->extensions->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <p class="text-xs font-black text-amber-600 uppercase tracking-widest mb-4 flex items-center">
                            <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span> Riwayat Perpanjangan
                        </p>
                        <div class="space-y-3">
                            @foreach($pawn->extensions as $ext)
                            <div class="flex items-center justify-between p-4 bg-amber-50/50 border border-amber-100 rounded-xl">
                                <div>
                                    <p class="text-sm font-bold text-gray-900">+{{ $ext->extension_days }} Hari</p>
                                    <p class="text-[10px] text-gray-500">{{ $ext->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-amber-700">Rp {{ number_format($ext->extension_fee, 0, ',', '.') }}</p>
                                    <p class="text-[10px] font-medium text-gray-500 uppercase tracking-tighter">JT Baru: {{ $ext->new_due_date->format('d M Y') }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($pawn->payments->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <p class="text-xs font-black text-emerald-600 uppercase tracking-widest mb-4 flex items-center">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span> Riwayat Pembayaran / Tebus
                        </p>
                        <div class="space-y-3">
                            @foreach($pawn->payments as $payment)
                            <div class="flex items-center justify-between p-4 bg-emerald-50/50 border border-emerald-100 rounded-xl">
                                <div class="flex items-center">
                                    <div class="p-2 bg-white rounded-lg mr-4 shadow-sm">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">
                                            @if($payment->payment_type === 'full_redemption') Pelunasan Gadai
                                            @elseif($payment->payment_type === 'interest') Bayar Bunga
                                            @else Bayar Cicilan Pokok
                                            @endif
                                        </p>
                                        <p class="text-[10px] text-gray-500 uppercase font-bold tracking-tight">{{ strtoupper($payment->payment_method) }} • {{ $payment->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <p class="text-sm font-black text-emerald-700">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-900 text-white">
                        <h3 class="text-sm font-black uppercase tracking-widest">Timeline Tenor</h3>
                    </div>
                    <div class="p-6 relative">
                        <div class="absolute left-9 top-10 bottom-10 w-0.5 bg-gray-100"></div>
                        
                        <div class="space-y-8 relative">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-500 border-4 border-white shadow-md z-10"></div>
                                <div class="ml-4">
                                    <p class="text-[10px] font-black text-gray-400 uppercase">Awal Gadai</p>
                                    <p class="text-sm font-bold text-gray-900">{{ $pawn->start_date->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 rounded-full {{ $pawn->isOverdue() ? 'bg-red-500 animate-pulse' : 'bg-amber-500' }} border-4 border-white shadow-md z-10"></div>
                                <div class="ml-4">
                                    <p class="text-[10px] font-black text-gray-400 uppercase">Jatuh Tempo</p>
                                    <p class="text-sm font-bold {{ $pawn->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                                        {{ $pawn->due_date->format('d M Y') }}
                                    </p>
                                    <p class="text-[11px] mt-1 italic text-gray-500">{{ $pawn->due_date->diffForHumans() }}</p>
                                </div>
                            </div>
                            @if($pawn->redeemed_at)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-500 border-4 border-white shadow-md z-10"></div>
                                <div class="ml-4">
                                    <p class="text-[10px] font-black text-gray-400 uppercase">Selesai/Tebus</p>
                                    <p class="text-sm font-bold text-blue-600">{{ $pawn->redeemed_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($pawn->isActive())
                <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl shadow-xl p-6 text-white overflow-hidden relative group">
                    <svg class="absolute -right-10 -bottom-10 w-40 h-40 opacity-10 group-hover:rotate-12 transition-transform duration-700" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                    
                    <h3 class="text-xs font-black uppercase tracking-[0.2em] mb-6 opacity-80 border-b border-indigo-400 pb-4">Estimasi Pelunasan</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="opacity-70">Pokok Pinjaman</span>
                            <span class="font-bold">Rp {{ number_format($pawn->loan_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="opacity-70">Bunga Akumulasi</span>
                            <span class="font-bold text-amber-300">Rp {{ number_format($pawn->calculateInterest(), 0, ',', '.') }}</span>
                        </div>
                        <div class="pt-4 border-t border-indigo-400 flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-black uppercase opacity-60 leading-none">Total Tebus</p>
                                <p class="text-xs mt-1 italic opacity-60">*Update per hari ini</p>
                            </div>
                            <p class="text-2xl font-black tracking-tighter">
                                Rp {{ number_format($pawn->getTotalRedemptionAmount(), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="p-4 bg-white rounded-2xl border border-gray-100 flex items-center shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-3 text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="text-[10px] text-gray-500 leading-tight">
                        Sistem mencatat transaksi ini pada <strong>{{ $pawn->created_at->format('d/m/Y H:i') }}</strong>. 
                        Pastikan data sesuai dengan fisik barang di gudang.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div x-show="selectedPhoto" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-10" 
         style="display: none;">
        
        <div class="fixed inset-0 bg-black/90 backdrop-blur-md" @click="selectedPhoto = null"></div>
        
        <button @click="selectedPhoto = null" class="fixed top-5 right-5 text-white/70 hover:text-white z-[70] p-2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="relative z-[70] max-w-full max-h-full">
            <img :src="selectedPhoto" class="max-w-full max-h-[85vh] rounded-xl shadow-2xl border-4 border-white/10 object-contain" 
                 @click.away="selectedPhoto = null">

        </div>
    </div>


    <div x-show="openExtensionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showRedemption = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">
            <div class="px-8 py-6 bg-indigo-600 text-white flex justify-between items-center">
                <h3 class="text-lg font-black uppercase tracking-widest">Proses Pelunasan</h3>
                <button @click="showRedemption = false" class="hover:rotate-90 transition-transform"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-8">
                <div class="mb-6 p-5 bg-indigo-50 rounded-2xl border border-indigo-100">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-600"><span>Pokok</span><span class="font-bold text-gray-900">Rp {{ number_format($pawn->loan_amount, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between text-gray-600"><span>Bunga</span><span class="font-bold text-indigo-600">+ Rp {{ number_format($pawn->calculateInterest(), 0, ',', '.') }}</span></div>
                        <div class="flex justify-between pt-3 border-t border-indigo-200 text-xl font-black text-indigo-700"><span>TOTAL</span><span>Rp {{ number_format($redemptionAmount, 0, ',', '.') }}</span></div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Metode Bayar</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" wire:click="$set('paymentMethod', 'cash')" class="p-4 rounded-2xl border-2 font-bold transition-all {{ $paymentMethod === 'cash' ? 'border-indigo-600 bg-indigo-50 text-indigo-700 shadow-inner' : 'border-gray-100 text-gray-400' }}">CASH</button>
                        <button type="button" wire:click="$set('paymentMethod', 'transfer')" class="p-4 rounded-2xl border-2 font-bold transition-all {{ $paymentMethod === 'transfer' ? 'border-indigo-600 bg-indigo-50 text-indigo-700 shadow-inner' : 'border-gray-100 text-gray-400' }}">TRANSFER</button>
                    </div>
                    <textarea wire:model="redemptionNotes" placeholder="Tambahkan catatan jika perlu..." class="w-full rounded-2xl border-gray-200 focus:ring-indigo-500 text-sm p-4 h-24"></textarea>
                </div>
                
                <button wire:click="processRedemption" class="w-full mt-8 py-4 bg-indigo-600 text-white rounded-2xl font-black shadow-lg hover:shadow-indigo-500/30 transition-all flex items-center justify-center space-x-2">
                    <span wire:loading.remove>KONFIRMASI PELUNASAN</span>
                    <span wire:loading class="animate-spin">⌛</span>
                </button>
            </div>
        </div>
    </div>

    <div x-show="openRedemptionModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showExtension = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">
            <div class="px-8 py-6 bg-amber-500 text-white flex justify-between items-center">
                <h3 class="text-lg font-black uppercase tracking-widest">Perpanjangan</h3>
                <button @click="showExtension = false"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Pilih Durasi</label>
                        <select wire:model.live="extensionDays" class="w-full rounded-2xl border-gray-200 py-3 font-bold text-gray-700">
                            <option value="15">15 HARI</option>
                            <option value="30">30 HARI (1 BULAN)</option>
                            <option value="60">60 HARI (2 BULAN)</option>
                        </select>
                    </div>
                    
                    <div class="p-6 bg-amber-50 rounded-2xl border-2 border-amber-200 text-center">
                        <p class="text-[10px] font-black text-amber-600 uppercase mb-1">Biaya Administrasi</p>
                        <p class="text-3xl font-black text-amber-700">Rp {{ number_format($extensionFee, 0, ',', '.') }}</p>
                        <div class="mt-4 pt-4 border-t border-amber-200 flex justify-between text-xs font-bold text-amber-600">
                            <span>JT BARU</span>
                            <span>{{ $pawn->due_date->copy()->addDays($extensionDays)->format('d M Y') }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Metode Bayar</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" wire:click="$set('paymentMethod', 'cash')" class="py-3 rounded-xl border-2 font-bold transition-all {{ $paymentMethod === 'cash' ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-gray-100 text-gray-400' }}">CASH</button>
                            <button type="button" wire:click="$set('paymentMethod', 'transfer')" class="py-3 rounded-xl border-2 font-bold transition-all {{ $paymentMethod === 'transfer' ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-gray-100 text-gray-400' }}">TRANSFER</button>
                        </div>
                    </div>
                </div>
                
                <button wire:click="processExtension" class="w-full mt-8 py-4 bg-amber-500 text-white rounded-2xl font-black shadow-lg hover:shadow-amber-500/30 transition-all">
                    PROSES PERPANJANGAN
                </button>
            </div>
        </div>
    </div>
</div>

