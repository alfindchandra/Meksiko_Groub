<div class="min-h-screen bg-slate-50 py-10">
    @section('page-title', 'Transaksi Gadai Baru')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Buat Transaksi Gadai</h1>
                <p class="text-slate-500 mt-1">Input data nasabah dan detail barang jaminan dengan teliti.</p>
            </div>
            <div class="flex items-center gap-2 text-sm text-slate-500 bg-white px-4 py-2 rounded-lg shadow-sm border border-slate-200">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                Sistem Kasir Aktif
            </div>
        </div>
        @if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 text-sm rounded-lg shadow-sm">
        <p class="font-bold">Gagal Simpan:</p>
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <form wire:submit.prevent="submit">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- ================= LEFT SIDE (FORMS) ================= --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- CUSTOMER CARD --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center gap-3">
                            <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-slate-800">Informasi Nasabah</h2>
                        </div>
                        
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="text-sm font-medium text-slate-700">Nama Lengkap Sesuai KTP</label>
                                <input type="text" wire:model.blur="customer_name" placeholder="Contoh: Budi Santoso"
                                    class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all">
                                @error('customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium text-slate-700">Nomor NIK (KTP)</label>
                                <input type="text" wire:model.blur="customer_id_number" placeholder="16 digit nomor induk"
                                    class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all">
                                @error('customer_id_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium text-slate-700">Nomor WhatsApp</label>
                                <div class="relative">
                                    <input type="text" wire:model.blur="customer_phone" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all">
                                </div>
                                @error('customer_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium text-slate-700">Alamat Lengkap</label>
                                <textarea wire:model.blur="customer_address" rows="1" placeholder="Jl. Raya No. 123..."
                                    class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all"></textarea>
                                @error('customer_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ITEM CARD --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center gap-3">
                            <div class="p-2 bg-amber-100 text-amber-600 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-slate-800">Aset Jaminan</h2>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="text-sm font-medium text-slate-700">Kategori Barang</label>
                                <select wire:model.live="item_category" class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all cursor-pointer">
                                    <option value="emas">Emas / Logam Mulia</option>
                                    <option value="perhiasan">Perhiasan</option>
                                    <option value="elektronik">Elektronik</option>
                                    <option value="kendaraan">Kendaraan</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium text-slate-700">Nama & Merk Barang</label>
                                <input type="text" wire:model.blur="item_name" placeholder="Contoh: iPhone 13 Pro Max"
                                    class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all">
                                @error('item_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            @if(in_array($item_category, ['emas','perhiasan']))
                            <div class="space-y-1 animate-fadeIn">
                                <label class="text-sm font-medium text-slate-700">Berat (Gram)</label>
                                <div class="relative">
                                    <input type="number" step="0.01" wire:model.live="item_weight" class="w-full pr-12 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 text-sm italic">gr</span>
                                </div>
                            </div>
                            @endif

                            <div class="md:col-span-2 space-y-1">
                                <label class="text-sm font-medium text-slate-700">Kondisi & Deskripsi</label>
                                <textarea wire:model.blur="item_description" rows="3" placeholder="Jelaskan detail fisik, kelengkapan, dan minus..."
                                    class="w-full rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all"></textarea>
                                @error('item_description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-slate-700 block mb-2">Unggah Foto Barang</label>
                                
                                @if ($item_photos)
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                        @foreach ($item_photos as $photo)
                                            <div class="relative group aspect-square rounded-xl overflow-hidden border border-slate-200 shadow-sm bg-slate-100">
                                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div 
                                    x-data="{ isDropping: false }"
                                    @dragover.prevent="isDropping = true"
                                    @dragleave.prevent="isDropping = false"
                                    @drop.prevent="isDropping = false"
                                    :class="isDropping ? 'border-blue-500 bg-blue-50' : 'border-slate-300'"
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-xl hover:border-blue-400 transition-all group relative"
                                >
                                    <div class="space-y-1 text-center">
                                        <div wire:loading wire:target="item_photos" class="absolute inset-0 bg-white/80 z-10 flex flex-col items-center justify-center rounded-xl">
                                            <svg class="animate-spin h-8 w-8 text-blue-600 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <p class="text-sm font-medium text-blue-600">Mengunggah...</p>
                                        </div>

                                        <svg class="mx-auto h-12 w-12 text-slate-400 group-hover:text-blue-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-slate-600">
                                            <label class="relative cursor-pointer bg-white rounded-md font-semibold text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                                <span>Klik untuk unggah</span>
                                                <input type="file" wire:model="item_photos" multiple class="sr-only">
                                            </label>
                                            <p class="pl-1">atau tarik gambar ke sini</p>
                                        </div>
                                        <p class="text-xs text-slate-500 uppercase font-medium tracking-tighter">Maksimal 2MB per foto</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CALCULATION CARD --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center gap-3">
                            <div class="p-2 bg-indigo-100 text-indigo-600 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-slate-800">Detail Pinjaman</h2>
                        </div>

                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="text-sm font-medium text-slate-700">Nilai Taksir Barang</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 font-medium text-sm">Rp</span>
                                    <input type="number" wire:model.live.debounce.2000ms="appraisal_value" class="w-full pl-10 rounded-lg border-slate-200 bg-slate-50 focus:bg-white transition-all">
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="text-sm font-medium text-slate-700 text-blue-600 font-bold tracking-tight">Pencairan (Pinjaman)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-blue-400 font-medium text-sm">Rp</span>
                                    <input type="number" wire:model.live.debounce.500ms="loan_amount" class="w-full pl-10 rounded-lg border-blue-200 focus:ring-blue-500 font-semibold text-blue-700">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-sm font-medium text-slate-700">Bunga (%)</label>
                                    <input type="number" step="0.1" wire:model.live="interest_rate" class="w-full rounded-lg border-slate-200">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-medium text-slate-700">Tenor</label>
                                    <select wire:model.live="loan_period_days" class="w-full rounded-lg border-slate-200">
                                        <option value="15">15 Hari</option>
                                        <option value="30">30 Hari</option>
                                        <option value="60">60 Hari</option>
                                        <option value="90">90 Hari</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-sm font-medium text-slate-700">Tgl Mulai</label>
                                    <input type="date" wire:model.live="start_date" class="w-full rounded-lg border-slate-200 text-sm">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-medium text-slate-700">Jatuh Tempo</label>
                                    <input type="date" value="{{ $due_date }}" readonly class="w-full rounded-lg border-transparent bg-slate-100 text-slate-500 text-sm">
                                </div>
                            </div>

                            <div class="md:col-span-2 space-y-1">
                                <label class="text-sm font-medium text-slate-700">Catatan Internal</label>
                                <textarea wire:model.blur="notes" rows="2" class="w-full rounded-lg border-slate-200"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= RIGHT SIDE (SUMMARY) ================= --}}
                <div class="relative">
                    <div class="sticky top-8 space-y-6">
                        <div class="bg-slate-900 rounded-3xl p-8 shadow-2xl text-white overflow-hidden relative">
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl"></div>
                            
                            <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Ringkasan
                            </h2>

                            <div class="space-y-6 relative z-10">
                                <div class="flex justify-between items-end">
                                    <span class="text-slate-400 text-sm">Nilai Taksir</span>
                                    <span class="font-semibold">Rp {{ number_format((float)($appraisal_value ?? 0), 0, ',', '.') }}</span>
                                </div>

                                <div class="bg-white/5 rounded-2xl p-5 border border-white/10">
                                    <p class="text-slate-400 text-xs uppercase tracking-widest mb-1 font-bold">Pencairan Bersih</p>
                                    <div class="text-3xl font-black text-blue-400">
                                        Rp {{ number_format((float)($cash_received ?? 0), 0, ',', '.') }}
                                    </div>
                                    <div class="mt-3 text-[11px] text-slate-400 flex justify-between border-t border-white/5 pt-2">
                                        <span>Potongan Admin (2%)</span>
                                        <span class="text-red-400">- Rp {{ number_format((float)($admin_fee ?? 0), 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <p class="text-xs font-black text-slate-500 uppercase tracking-widest border-b border-white/10 pb-2">Estimasi Pelunasan</p>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-400">Pinjaman Pokok</span>
                                        <span class="font-bold">Rp {{ number_format((float)($loan_amount ?? 0), 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-400">Bunga ({{ $interest_rate }}%)</span>
                                        <span class="font-bold text-amber-400">+ Rp {{ number_format((float)($total_interest ?? 0), 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center bg-emerald-500/10 p-3 rounded-xl">
                                        <span class="text-xs font-bold text-emerald-400 uppercase">Total Tebus</span>
                                        <span class="text-xl font-black text-emerald-400">
                                            Rp {{ number_format((float)($total_payment ?? 0), 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <button type="submit"
                                    wire:loading.attr="disabled"
                                    class="w-full bg-blue-600 hover:bg-blue-500 text-white py-4 rounded-2xl font-black shadow-lg shadow-blue-600/30 flex items-center justify-center gap-2 transition-all transform hover:scale-[1.02] active:scale-95">
                                    <span wire:loading.remove>PROSES TRANSAKSI</span>
                                    <span wire:loading>SEDANG MEMPROSES...</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3 px-4 py-3 bg-amber-50 rounded-xl border border-amber-100">
                            <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-[10px] text-amber-700 leading-tight">
                                Pastikan semua data benar. Transaksi yang sudah diproses akan mencatat mutasi stok uang pada kasir outlet.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>