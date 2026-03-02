<?php

namespace App\Livewire\Pegadaian;

use App\Models\PawnTransaction;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreatePawn extends Component
{
    use WithFileUploads;

    // Customer Properties
    public $customer_name = '';
    public $customer_id_number = '';
    public $customer_phone = '';
    public $customer_address = '';

    // Item Properties
    public $item_name = '';
    public $item_category = 'emas';
    public $item_description = '';
    public $item_weight = null;
    public $item_photos = [];

    // Transaction Properties (Wajib Public agar terbaca di Blade)
    public $appraisal_value = 0;
    public $loan_amount = 0;
    public $admin_fee = 0;
    public $interest_rate = 2;
    public $loan_period_days = 30;
    public $start_date = '';
    public $due_date = '';
    public $notes = '';

    // Calculation Results
    public $total_interest = 0;
    public $total_payment = 0;
    public $cash_received = 0;

    protected $rules = [
        'customer_name' => 'required|string|max:255',
        'customer_id_number' => 'required|string|max:20',
        'customer_phone' => 'required|string|max:20',
        'customer_address' => 'required|string',
        'item_name' => 'required|string|max:255',
        'item_category' => 'required|string',
        'item_description' => 'required|string',
        'item_weight' => 'nullable|numeric|min:0',
        'item_photos.*' => 'nullable|image|max:2048',
        'appraisal_value' => 'required|numeric|min:0',
        'loan_amount' => 'required|numeric|min:0',
        'admin_fee' => 'required|numeric|min:0',
        'interest_rate' => 'required|numeric|min:0|max:100',
        'loan_period_days' => 'required|integer|min:1',
        'start_date' => 'required|date',
        'notes' => 'nullable|string',
    ];

    protected $messages = [
        'customer_name.required' => 'Nama nasabah wajib diisi.',
        'customer_name.max' => 'Nama nasabah tidak boleh lebih dari 255 karakter.',
        'customer_id_number.required' => 'Nomor identitas (KTP) wajib diisi.',
        'customer_id_number.max' => 'Nomor identitas maksimal 20 karakter.',
        'customer_phone.required' => 'Nomor telepon wajib diisi.',
        'customer_phone.max' => 'Nomor telepon maksimal 20 karakter.',
        'customer_address.required' => 'Alamat nasabah wajib diisi.',
        'item_name.required' => 'Nama barang wajib diisi.',
        'item_name.max' => 'Nama barang maksimal 255 karakter.',
        'item_category.required' => 'Kategori barang wajib dipilih.',
        'item_description.required' => 'Deskripsi barang wajib diisi.',
        'item_weight.numeric' => 'Berat barang harus berupa angka.',
        'item_weight.min' => 'Berat barang tidak boleh kurang dari 0.',
        'item_photos.*.image' => 'File harus berupa gambar.',
        'item_photos.*.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        'appraisal_value.required' => 'Nilai taksiran wajib diisi.',
        'appraisal_value.numeric' => 'Nilai taksiran harus berupa angka.',
        'appraisal_value.min' => 'Nilai taksiran tidak boleh bernilai negatif.',
        'loan_amount.required' => 'Nominal pinjaman wajib diisi.',
        'loan_amount.numeric' => 'Nominal pinjaman harus berupa angka.',
        'loan_amount.min' => 'Nominal pinjaman tidak boleh bernilai negatif.',
        'admin_fee.required' => 'Biaya admin wajib diisi.',
        'admin_fee.numeric' => 'Biaya admin harus berupa angka.',
        'admin_fee.min' => 'Biaya admin tidak boleh bernilai negatif.',
        'interest_rate.required' => 'Suku bunga wajib diisi.',
        'interest_rate.numeric' => 'Suku bunga harus berupa angka.',
        'interest_rate.min' => 'Suku bunga minimal 0%.',
        'interest_rate.max' => 'Suku bunga maksimal 100%.',
        'loan_period_days.required' => 'Tenor pinjaman (hari) wajib diisi.',
        'loan_period_days.integer' => 'Tenor pinjaman harus berupa angka bulat.',
        'loan_period_days.min' => 'Tenor pinjaman minimal 1 hari.',
        'start_date.required' => 'Tanggal pinjaman wajib diisi.',
        'start_date.date' => 'Format tanggal tidak valid.',
        'appraisal_value.required' => 'Nilai taksiran wajib diisi.',
        'appraisal_value.numeric' => 'Nilai taksiran harus berupa angka.',
        'appraisal_value.min' => 'Nilai taksiran tidak boleh bernilai negatif.',
    ];

    public function mount()
    {
        $this->start_date = now()->format('Y-m-d');
        $this->calculateAll();
    }

    /**
     * Lifecycle Hook: Dijalankan setiap kali ada property yang berubah di Blade
     */
    public function updated($property)
    {
        // Konversi input ke tipe data angka agar aman untuk perhitungan
        $this->appraisal_value = (float) ($this->appraisal_value ?: 0);
        $this->loan_amount = (float) ($this->loan_amount ?: 0);
        $this->interest_rate = (float) ($this->interest_rate ?: 0);
        $this->loan_period_days = (int) ($this->loan_period_days ?: 0);

        // Fitur otomatis: Isi pinjaman 80% dari taksiran jika taksiran diubah
        if ($property === 'appraisal_value') {
            $this->loan_amount = $this->appraisal_value * 0.8;
        }

        $this->calculateAll();
    }

    private function calculateAll()
    {
        // 1. Hitung Admin (2% dari pinjaman)
        $this->admin_fee = $this->loan_amount * 0.02;

        // 2. Hitung Jatuh Tempo
        if ($this->start_date) {
            $this->due_date = Carbon::parse($this->start_date)
                ->addDays((int)$this->loan_period_days)
                ->format('Y-m-d');
        }

        // 3. Hitung Total Pelunasan
        $months = ceil((int)$this->loan_period_days / 30);
        $this->total_interest = $this->loan_amount * ($this->interest_rate / 100) * $months;
        $this->total_payment = $this->loan_amount + $this->total_interest;
        
        // 4. Hitung Uang yang dibawa pulang nasabah
        $this->cash_received = $this->loan_amount - $this->admin_fee;
    }

    public function submit()
    {
        $validated = $this->validate();

        try {
            DB::beginTransaction();

            // Generate No. Gadai Otomatis
            $pawnNumber = 'GADAI-' . date('Ymd') . '-' . str_pad(
                PawnTransaction::whereDate('created_at', today())->count() + 1,
                4, '0', STR_PAD_LEFT
            );

            // Upload Foto
            $photoPaths = [];
            if (!empty($this->item_photos)) {
                foreach ($this->item_photos as $photo) {
                    $photoPaths[] = $photo->store('pawn-items', 'public');
                }
            }

            $pawn = PawnTransaction::create([
                ...$validated,
                'pawn_number' => $pawnNumber,
                'outlet_id' => auth()->user()->outlet_id,
                'user_id' => auth()->id(),
                'item_photos' => $photoPaths,
                'status' => 'active',
                'due_date' => $this->due_date,
                'total_interest' => $this->total_interest,
                'total_payment' => $this->total_payment,
            ]);

            // Notify Admins
            try {
                $adminUsers = \App\Models\User::whereHas('role', function ($query) {
                    $query->where('name', 'admin_pusat');
                })->get();

                foreach ($adminUsers as $admin) {
                    \App\Models\Notification::create([
                        'user_id' => $admin->id,
                        'type' => 'pawn_created',
                        'title' => 'Gadai Baru',
                        'message' => "Transaksi gadai baru {$pawnNumber} dibuat oleh " . auth()->user()->name,
                        'reference_type' => 'PawnTransaction',
                        'reference_id' => $pawn->id,
                        'is_read' => false,
                    ]);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to send pawn notification: ' . $e->getMessage());
            }

            DB::commit();
            return redirect()->route('pegadaian.list');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', ['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.pegadaian.create-pawn')->layout('layouts.app');
    }
}