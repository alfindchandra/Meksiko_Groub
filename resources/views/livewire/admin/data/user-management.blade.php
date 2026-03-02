<div x-data="{ showModal: @entangle('showModal') }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @section('page-title', 'Manajemen User')

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen User</h2>
            <p class="mt-1 text-sm text-gray-500">Kelola hak akses, peran, dan penempatan outlet karyawan Anda.</p>
        </div>
        <button @click="showModal = true" wire:click="openModal" 
                class="inline-flex items-center px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl shadow-lg shadow-primary-200 transition-all active:scale-95">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah User Baru
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="p-6 border-b border-gray-50 bg-gray-50/30">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Cari User</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nama atau email..." 
                               class="block w-full pl-10 pr-4 py-2 text-sm border-gray-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 transition-colors">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Filter Role</label>
                    <select wire:model.live="roleFilter" class="block w-full py-2 text-sm border-gray-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        <option value="">Semua Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Filter Outlet</label>
                    <select wire:model.live="outletFilter" class="block w-full py-2 text-sm border-gray-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 transition-colors">
                        <option value="">Semua Outlet</option>
                        @foreach($outlets as $outlet)
                            <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">User Info</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Akses / Role</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Penempatan</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-primary-50 border border-primary-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <span class="text-sm font-bold text-primary-600 uppercase">
                                        {{ substr($user->name, 0, 2) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-bold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $user->role->display_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->outlet)
                                <div class="text-sm text-gray-900 font-medium">{{ $user->outlet->name }}</div>
                                <div class="text-[10px] text-gray-400 uppercase tracking-tighter">{{ $user->outlet->code }}</div>
                            @else
                                <span class="text-xs italic text-gray-400">Pusat / General</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <button wire:click="toggleActive({{ $user->id }})" class="focus:outline-none">
                                @if($user->is_active)
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg bg-green-100 text-green-700 border border-green-200">Active</span>
                                @else
                                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg bg-red-100 text-red-700 border border-red-200">Inactive</span>
                                @endif
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-3">
                                <button wire:click="edit({{ $user->id }})" class="text-gray-400 hover:text-primary-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                @if($user->id !== auth()->id())
                                <button wire:click="delete({{ $user->id }})" wire:confirm="Yakin ingin menghapus user ini?" class="text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <p class="text-gray-500 font-medium">Tidak ada data user ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>

    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                <div class="px-8 py-6 bg-white">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $editMode ? 'Edit User' : 'Tambah User Baru' }}
                        </h3>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="save" class="space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="block w-full px-4 py-2.5 bg-gray-50 border-gray-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-all">
                            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" wire:model="email" class="block w-full px-4 py-2.5 bg-gray-50 border-gray-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-all">
                            @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Password @if(!$editMode)<span class="text-red-500">*</span>@endif</label>
                            <input type="password" wire:model="password" class="block w-full px-4 py-2.5 bg-gray-50 border-gray-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-all" placeholder="{{ $editMode ? 'Kosongkan jika tidak diubah' : 'Min. 8 karakter' }}">
                            @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Role <span class="text-red-500">*</span></label>
                                <select wire:model.live="role_id" class="block w-full px-4 py-2.5 bg-gray-50 border-gray-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                    <option value="">Pilih Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                    @endforeach
                                </select>
                                @error('role_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Outlet</label>
                                <select wire:model="outlet_id" class="block w-full px-4 py-2.5 bg-gray-50 border-gray-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                                    <option value="">Opsional</option>
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <input type="checkbox" wire:model="is_active" id="is_active_modal" class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <label for="is_active_modal" class="ml-3 text-sm font-medium text-gray-700">Aktifkan akun ini segera</label>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-6">
                            <button type="button" @click="showModal = false" class="px-5 py-2.5 text-sm font-semibold text-gray-500 hover:text-gray-700 transition-colors">Batal</button>
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-primary-200 transition-all">
                                <span wire:loading.remove>{{ $editMode ? 'Update User' : 'Simpan User' }}</span>
                                <span wire:loading class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>