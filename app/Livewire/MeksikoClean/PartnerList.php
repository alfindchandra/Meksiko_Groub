<?php

namespace App\Livewire\MeksikoClean;

use App\Models\McPartner;
use Livewire\Component;
use Livewire\WithPagination;

class PartnerList extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';

    // Form fields
    public $partnerId;
    public $name;
    public $type = 'cafe';
    public $address;
    public $contact_person;
    public $phone;

    public $isModalOpen = false;
    public $isConfirmingDeletion = false;
    public $partnerToDelete;

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|string|max:100',
        'address' => 'nullable|string',
        'contact_person' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:255',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['partnerId', 'name', 'type', 'address', 'contact_person', 'phone']);
        $this->type = 'cafe'; // Default type
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function editPartner($id)
    {
        $this->resetValidation();
        $partner = McPartner::findOrFail($id);
        $this->partnerId = $partner->id;
        $this->name = $partner->name;
        $this->type = $partner->type;
        $this->address = $partner->address;
        $this->contact_person = $partner->contact_person;
        $this->phone = $partner->phone;

        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        McPartner::updateOrCreate(
            ['id' => $this->partnerId],
            [
                'name' => $this->name,
                'type' => $this->type,
                'address' => $this->address,
                'contact_person' => $this->contact_person,
                'phone' => $this->phone,
            ]
        );

        session()->flash('message', $this->partnerId ? 'Mitra berhasil diupdate.' : 'Mitra berhasil ditambahkan.');

        $this->closeModal();
    }

    public function confirmDeletion($id)
    {
        $this->partnerToDelete = $id;
        $this->isConfirmingDeletion = true;
    }

    public function deletePartner()
    {
        if ($this->partnerToDelete) {
            McPartner::findOrFail($this->partnerToDelete)->delete();
            session()->flash('message', 'Mitra berhasil dihapus.');
        }

        $this->isConfirmingDeletion = false;
        $this->partnerToDelete = null;
    }

    public function render()
    {
        $partners = McPartner::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('contact_person', 'like', '%' . $this->search . '%');
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.meksiko-clean.partner-list', [
            'partners' => $partners
        ])->layout('layouts.app');
    }
}
