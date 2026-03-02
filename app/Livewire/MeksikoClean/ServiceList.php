<?php

namespace App\Livewire\MeksikoClean;

use App\Models\McService;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceList extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    
    // Form fields
    public $serviceId;
    public $name;
    public $category = 'sepatu';
    public $price;
    public $description;

    public $isModalOpen = false;
    public $isConfirmingDeletion = false;
    public $serviceToDelete;

    protected $rules = [
        'name' => 'required|string|max:255',
        'category' => 'required|in:sepatu,tas,helm,dompet,repair,lainnya',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['serviceId', 'name', 'category', 'price', 'description']);
        $this->category = 'sepatu'; // Default
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function editService($id)
    {
        $this->resetValidation();
        $service = McService::findOrFail($id);
        $this->serviceId = $service->id;
        $this->name = $service->name;
        $this->category = $service->category;
        $this->price = $service->price;
        $this->description = $service->description;

        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        McService::updateOrCreate(
            ['id' => $this->serviceId],
            [
                'name' => $this->name,
                'category' => $this->category,
                'price' => $this->price,
                'description' => $this->description,
            ]
        );

        session()->flash('message', $this->serviceId ? 'Layanan berhasil diupdate.' : 'Layanan berhasil ditambahkan.');

        $this->closeModal();
    }

    public function confirmDeletion($id)
    {
        $this->serviceToDelete = $id;
        $this->isConfirmingDeletion = true;
    }

    public function deleteService()
    {
        if ($this->serviceToDelete) {
            McService::findOrFail($this->serviceToDelete)->delete();
            session()->flash('message', 'Layanan berhasil dihapus.');
        }

        $this->isConfirmingDeletion = false;
        $this->serviceToDelete = null;
    }

    public function render()
    {
        $services = McService::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category', $this->categoryFilter);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.meksiko-clean.service-list', [
            'services' => $services
        ])->layout('layouts.app');
    }
}
