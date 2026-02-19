<div x-data="{ showModal: @entangle('showModal') }">
    @section('page-title', 'Manajemen User')

    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-900">Manajemen User</h2>
        <button @click="showModal = true" wire:click="openModal" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah User Baru
        </button>
    </div>

    <div class="card">
        <!-- Filters -->
        <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari User</label>
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="Nama atau email..." 
                       class="form-input">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select wire:model.live="roleFilter" class="form-input">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Outlet</label>
                <select wire:model.live="outletFilter" class="form-input">
                    <option value="">Semua Outlet</option>
                    @foreach($outlets as $outlet)
                    <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Outlet</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-semibold text-primary-600">
                                        {{ substr($user->name, 0, 2) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge badge-info">{{ $user->role->display_name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->outlet)
                            <p class="text-sm text-gray-900">{{ $user->outlet->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->outlet->code }}</p>
                            @else
                            <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button wire:click="toggleActive({{ $user->id }})"
                                    class="inline-flex items-center">
                                @if($user->is_active)
                                <span class="badge badge-success cursor-pointer">Active</span>
                                @else
                                <span class="badge badge-danger cursor-pointer">Inactive</span>
                                @endif
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <button wire:click="edit({{ $user->id }})"
                                        class="text-primary-600 hover:text-primary-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                @if($user->id !== auth()->id())
                                <button wire:click="delete({{ $user->id }})"
                                        wire:confirm="Yakin ingin menghapus user ini?"
                                        class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <p class="text-sm text-gray-500">Tidak ada user ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div x-show="showModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="showModal = false"></div>

            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $editMode ? 'Edit User' : 'Tambah User Baru' }}
                    </h3>
                </div>

                <form wire:submit.prevent="save" class="px-6 py-4 space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="name" class="form-input">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" wire:model="email" class="form-input">
                        @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Password @if(!$editMode)<span class="text-red-500">*</span>@endif
                        </label>
                        <input type="password" wire:model="password" class="form-input" 
                               placeholder="{{ $editMode ? 'Kosongkan jika tidak ingin mengubah' : '' }}">
                        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="role_id" class="form-input">
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                        @error('role_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Outlet -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Outlet</label>
                        <select wire:model="outlet_id" class="form-input">
                            <option value="">Pilih Outlet (Opsional)</option>
                            @foreach($outlets as $outlet)
                            <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                            @endforeach
                        </select>
                        @error('outlet_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input type="checkbox" wire:model="is_active" id="is_active" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">User Aktif</label>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                        <button type="button" @click="showModal = false" class="btn-secondary">
                            Batal
                        </button>
                        <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>{{ $editMode ? 'Update' : 'Simpan' }}</span>
                            <span wire:loading>Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>