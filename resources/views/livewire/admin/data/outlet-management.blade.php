<div x-data="{ showModal: @entangle('showModal') }" class="min-h-screen py-8 bg-gray-50/50">
    @section('page-title', 'Kelola Outlet')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kelola Outlet</h2>
                <p class="mt-1 text-sm text-gray-500">Manajemen lokasi ruko, gudang, dan pemantauan statistik unit bisnis Anda.</p>
            </div>
            <button @click="showModal = true" wire:click="openModal" 
                class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-2xl font-bold text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Outlet Baru
            </button>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Cari Lokasi</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Cari nama, kode, atau kota..." 
                            class="block w-full pl-11 pr-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all sm:text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Tipe Properti</label>
                    <select wire:model.live="typeFilter" 
                        class="block w-full py-3 px-4 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all sm:text-sm">
                        <option value="">Semua Tipe</option>
                        <option value="ruko">🏪 Ruko</option>
                        <option value="warehouse">🏭 Warehouse</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            @forelse($outlets as $outlet)
            <div class="bg-white rounded-[2.5rem] border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group">
                <div class="p-6 pb-4 border-b border-gray-50 bg-gray-50/50">
                    <div class="flex items-center justify-between mb-4">
                        <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full uppercase tracking-tighter">
                            {{ $outlet->code }}
                        </span>
                        @if($outlet->type === 'warehouse')
                            <span class="px-3 py-1 text-[10px] font-black uppercase bg-slate-100 text-slate-600 rounded-full border border-slate-200">Warehouse</span>
                        @else
                            <span class="px-3 py-1 text-[10px] font-black uppercase bg-indigo-100 text-indigo-700 rounded-full border border-indigo-200">Ruko</span>
                        @endif
                    </div>
                    <h3 class="text-xl font-black text-gray-900 leading-tight group-hover:text-indigo-600 transition-colors">{{ $outlet->name }}</h3>
                </div>

                <div class="p-6 space-y-5 flex-1">
                    <div class="flex items-start">
                        <div class="mt-1 flex-shrink-0 p-2 bg-rose-50 text-rose-500 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $outlet->address }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $outlet->city }}</p>
                        </div>
                    </div>

                    @if($outlet->phone)
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-2 bg-emerald-50 text-emerald-500 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <p class="ml-3 text-sm font-bold text-gray-700 tracking-tight">{{ $outlet->phone }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div class="bg-blue-50/50 p-3 rounded-2xl border border-blue-100/50">
                            <p class="text-[9px] font-black text-blue-400 uppercase mb-1">Total Users</p>
                            <p class="text-xl font-black text-blue-700">{{ $outlet->users_count }}</p>
                        </div>
                        <div class="bg-indigo-50/50 p-3 rounded-2xl border border-indigo-100/50">
                            <p class="text-[9px] font-black text-indigo-400 uppercase mb-1">Inventory</p>
                            <p class="text-xl font-black text-indigo-700">{{ $outlet->stocks_count }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 pt-0 mt-auto">
                    <div class="flex items-center justify-between pt-5 border-t border-gray-50">
                        <button wire:click="toggleActive({{ $outlet->id }})" class="transition-transform active:scale-95">
                            @if($outlet->is_active)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700 uppercase tracking-widest border border-emerald-200">Active</span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-rose-100 text-rose-700 uppercase tracking-widest border border-rose-200">Inactive</span>
                            @endif
                        </button>
                        <div class="flex items-center space-x-2">
                            <button wire:click="edit({{ $outlet->id }})" class="p-2.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="delete({{ $outlet->id }})" wire:confirm="Yakin ingin menghapus outlet ini?" class="p-2.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-3xl border border-dashed border-gray-300 py-20 text-center">
                <div class="bg-gray-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 text-gray-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 tracking-tight">Tidak ada outlet</h3>
                <p class="text-sm text-gray-500">Sistem tidak menemukan data lokasi outlet yang sesuai filter.</p>
            </div>
            @endforelse
        </div>

        @if($outlets->hasPages())
        <div class="mt-12 px-6 py-4 bg-white rounded-2xl border border-gray-200 shadow-sm">
            {{ $outlets->links() }}
        </div>
        @endif
    </div>

    <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showModal = false"></div>

            <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-2xl w-full overflow-hidden border border-gray-100">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-2xl font-black text-gray-900">{{ $editMode ? 'Edit Unit Bisnis' : 'Unit Bisnis Baru' }}</h3>
                        <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest font-bold">Pastikan data lokasi akurat</p>
                    </div>
                    <button @click="showModal = false" class="p-2 text-gray-400 hover:text-gray-900 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kode Internal <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="code" placeholder="Misal: MX-001" 
                                class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all font-mono">
                            @error('code') <p class="text-[10px] text-rose-500 font-bold uppercase tracking-tight ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Tipe Lokasi <span class="text-rose-500">*</span></label>
                            <select wire:model="type" class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                                <option value="ruko">🏪 Ruko</option>
                                <option value="warehouse">🏭 Warehouse</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Outlet <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="name" placeholder="Misal: Meksiko Kemang" 
                                class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                            @error('name') <p class="text-[10px] text-rose-500 font-bold uppercase tracking-tight ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Alamat Lengkap <span class="text-rose-500">*</span></label>
                            <textarea wire:model="address" rows="2" placeholder="Sertakan nomor jalan..." 
                                class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all"></textarea>
                            @error('address') <p class="text-[10px] text-rose-500 font-bold uppercase tracking-tight ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kota <span class="text-rose-500">*</span></label>
                            <input type="text" wire:model="city" placeholder="Misal: Jakarta Selatan" 
                                class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">No. Telepon</label>
                            <input type="text" wire:model="phone" placeholder="021-XXXXXXX" 
                                class="w-full px-4 py-3 bg-gray-50 border-transparent rounded-2xl focus:bg-white focus:ring-2 focus:ring-indigo-500 transition-all">
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 pt-4">
                        <input type="checkbox" wire:model="is_active" id="is_active_form" class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="is_active_form" class="text-sm font-bold text-gray-700">Unit beroperasi secara aktif</label>
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-8 border-t border-gray-50">
                        <button type="button" @click="showModal = false" class="px-6 py-3 text-sm font-bold text-gray-400 hover:text-gray-900 transition-colors">Batal</button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-10 py-3 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-1 transition-all active:scale-95 disabled:opacity-50">
                            <span wire:loading.remove>{{ $editMode ? 'Update Lokasi' : 'Daftarkan Lokasi' }}</span>
                            <span wire:loading class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                Sinkronisasi...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>