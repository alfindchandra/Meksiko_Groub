<?php

namespace App\Livewire\MeksikoClean;

use App\Models\McPartner;
use App\Models\McService;
use App\Models\McTransaction;
use App\Models\McTransactionItem;
use Livewire\Component;

class CreateTransaction extends Component
{
    public $customer_name;
    public $customer_phone;
    public $order_type = 'online'; // Default online
    public $partner_id;
    public $payment_status = 'unpaid';
    public $amount_paid = 0;

    // The items to order
    public $items = [];

    protected $rules = [
        'customer_name' => 'required|string|max:255',
        'customer_phone' => 'nullable|string|max:255',
        'order_type' => 'required|in:online,mitra,offline',
        'partner_id' => 'required_if:order_type,mitra|nullable|exists:mc_partners,id',
        'payment_status' => 'required|in:unpaid,paid',
        'amount_paid' => 'nullable|numeric|min:0',
        'items' => 'required|array|min:1',
        'items.*.category' => 'required|string',
        'items.*.service_id' => 'required|exists:mc_services,id',
        'items.*.qty' => 'required|integer|min:1',
    ];

    public function mount()
    {
        // Add one empty row initially
        $this->addItem();
    }

    public function addItem()
    {
        $this->items[] = [
            'category' => '',
            'service_id' => '',
            'qty' => 1,
            'price' => 0,
            'subtotal' => 0,
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function updatedItems($value, $key)
    {
        $parts = explode('.', $key);
        if (count($parts) === 2) {
            $index = $parts[0];
            $field = $parts[1];

            if ($field === 'category') {
                $this->items[$index]['service_id'] = '';
                $this->items[$index]['price'] = 0;
                $this->items[$index]['subtotal'] = 0;
            } elseif (in_array($field, ['service_id', 'qty'])) {
                $serviceId = $this->items[$index]['service_id'];
                
                if ($serviceId) {
                    $service = McService::find($serviceId);
                    $this->items[$index]['price'] = $service->price;
                    $this->items[$index]['subtotal'] = $service->price * $this->items[$index]['qty'];
                }
            }
        }
    }

    public function getTotalAmountProperty()
    {
        return collect($this->items)->sum('subtotal');
    }

    public function getChangeAmountProperty()
    {
        if ($this->payment_status === 'unpaid') return 0;
        return max(0, (float)$this->amount_paid - $this->total_amount);
    }

    public function save()
    {
        $this->validate();

        $transaction = McTransaction::create([
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'order_type' => $this->order_type,
            'partner_id' => $this->order_type === 'mitra' ? $this->partner_id : null,
            'total_amount' => $this->total_amount,
            'payment_status' => $this->payment_status,
        ]);

        foreach ($this->items as $item) {
            McTransactionItem::create([
                'transaction_id' => $transaction->id,
                'service_id' => $item['service_id'],
                'item_name' => McService::find($item['service_id'])->name . ' (' . ucfirst($item['category']) . ')',
                'qty' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['subtotal'],
            ]);
        }

        session()->flash('message', 'Transaksi berhasil dibuat. Nomor: ' . $transaction->transaction_number);
        return redirect()->route('meksikoclean.transactions.index');
    }

    public function render()
    {
        $categories = McService::select('category')->distinct()->pluck('category');

        return view('livewire.meksiko-clean.create-transaction', [
            'services' => McService::orderBy('category')->get(),
            'partners' => McPartner::orderBy('name')->get(),
            'categories' => $categories,
            'totalAmount' => $this->total_amount,
            'changeAmount' => $this->change_amount
        ])->layout('layouts.app');
    }
}
