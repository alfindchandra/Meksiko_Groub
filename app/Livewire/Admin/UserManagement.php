<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Role;
use App\Models\Outlet;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $outletFilter = '';
    public $showModal = false;
    public $editMode = false;
    
    public $userId;
    public $name;
    public $email;
    public $password;
    public $role_id;
    public $outlet_id;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'role_id' => 'required|exists:roles,id',
        'outlet_id' => 'nullable|exists:outlets,id',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'Nama wajib diisi',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah digunakan',
        'password.required' => 'Password wajib diisi',
        'password.min' => 'Password minimal 8 karakter',
        'role_id.required' => 'Role wajib dipilih',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['userId', 'name', 'email', 'password', 'role_id', 'outlet_id', 'is_active', 'editMode']);
        $this->resetValidation();
    }

    public function edit($userId)
    {
        $user = User::findOrFail($userId);
        
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->outlet_id = $user->outlet_id;
        $this->is_active = $user->is_active;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editMode) {
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $this->userId,
                'password' => 'nullable|min:8',
                'role_id' => 'required|exists:roles,id',
                'outlet_id' => 'nullable|exists:outlets,id',
                'is_active' => 'boolean',
            ]);

            $user = User::findOrFail($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'role_id' => $this->role_id,
                'outlet_id' => $this->outlet_id,
                'is_active' => $this->is_active,
            ]);

            if ($this->password) {
                $user->update(['password' => Hash::make($this->password)]);
            }

            $message = 'User berhasil diupdate!';
        } else {
            $this->validate();

            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role_id' => $this->role_id,
                'outlet_id' => $this->outlet_id,
                'is_active' => $this->is_active,
            ]);

            $message = 'User berhasil ditambahkan!';
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $message
        ]);

        $this->closeModal();
    }

    public function toggleActive($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => !$user->is_active]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Status user berhasil diubah!'
        ]);
    }

    public function delete($userId)
    {
        if ($userId === auth()->id()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Anda tidak dapat menghapus akun sendiri!'
            ]);
            return;
        }

        User::findOrFail($userId)->delete();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'User berhasil dihapus!'
        ]);
    }

    public function render()
    {
        $query = User::with(['role', 'outlet'])->latest();

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Role filter
        if ($this->roleFilter) {
            $query->where('role_id', $this->roleFilter);
        }

        // Outlet filter
        if ($this->outletFilter) {
            $query->where('outlet_id', $this->outletFilter);
        }

        $users = $query->paginate(15);
        $roles = Role::all();
        $outlets = Outlet::active()->get();

        return view('livewire.admin.data.user-management', [
            'users' => $users,
            'roles' => $roles,
            'outlets' => $outlets,
        ])->layout('layouts.app');
    }
}