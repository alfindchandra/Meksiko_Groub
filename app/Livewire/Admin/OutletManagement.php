<?php

namespace App\Livewire\Admin;

use App\Models\Outlet;
use Livewire\Component;
use Livewire\WithPagination;

class OutletManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';
    public $showModal = false;
    public $editMode = false;
    
    public $outletId;
    public $code;
    public $name;
    public $address;
    public $city;
    public $phone;
    public $type = 'ruko';
    public $is_active = true;

    protected $rules = [
        'code' => 'required|string|max:20|unique:outlets,code',
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:500',
        'city' => 'required|string|max:100',
        'phone' => 'nullable|string|max:20',
        'type' => 'required|in:ruko,warehouse',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'code.required' => 'Kode outlet wajib diisi',
        'code.unique' => 'Kode outlet sudah digunakan',
        'name.required' => 'Nama outlet wajib diisi',
        'address.required' => 'Alamat wajib diisi',
        'city.required' => 'Kota wajib diisi',
        'type.required' => 'Tipe outlet wajib dipilih',
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
        $this->reset([
            'outletId', 'code', 'name', 'address', 
            'city', 'phone', 'type', 'is_active', 'editMode'
        ]);
        $this->type = 'ruko';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function edit($outletId)
    {
        $outlet = Outlet::findOrFail($outletId);
        
        $this->outletId = $outlet->id;
        $this->code = $outlet->code;
        $this->name = $outlet->name;
        $this->address = $outlet->address;
        $this->city = $outlet->city;
        $this->phone = $outlet->phone;
        $this->type = $outlet->type;
        $this->is_active = $outlet->is_active;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editMode) {
            $this->validate([
                'code' => 'required|string|max:20|unique:outlets,code,' . $this->outletId,
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'city' => 'required|string|max:100',
                'phone' => 'nullable|string|max:20',
                'type' => 'required|in:ruko,warehouse',
                'is_active' => 'boolean',
            ]);

            $outlet = Outlet::findOrFail($this->outletId);
            $outlet->update([
                'code' => $this->code,
                'name' => $this->name,
                'address' => $this->address,
                'city' => $this->city,
                'phone' => $this->phone,
                'type' => $this->type,
                'is_active' => $this->is_active,
            ]);

            $message = 'Outlet berhasil diupdate!';
        } else {
            $this->validate();

            Outlet::create([
                'code' => $this->code,
                'name' => $this->name,
                'address' => $this->address,
                'city' => $this->city,
                'phone' => $this->phone,
                'type' => $this->type,
                'is_active' => $this->is_active,
            ]);

            $message = 'Outlet berhasil ditambahkan!';
        }

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => $message
        ]);

        $this->closeModal();
    }

    public function toggleActive($outletId)
    {
        $outlet = Outlet::findOrFail($outletId);
        $outlet->update(['is_active' => !$outlet->is_active]);

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Status outlet berhasil diubah!'
        ]);
    }

    public function delete($outletId)
    {
        try {
            Outlet::findOrFail($outletId)->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Outlet berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Gagal menghapus outlet. Mungkin masih ada data terkait.'
            ]);
        }
    }

    public function render()
    {
        $query = Outlet::withCount(['users', 'stocks'])->latest();

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('city', 'like', '%' . $this->search . '%');
            });
        }

        // Type filter
        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        $outlets = $query->paginate(15);

        return view('livewire.admin.data.outlet-management', [
            'outlets' => $outlets,
        ])->layout('layouts.app');
    }
}