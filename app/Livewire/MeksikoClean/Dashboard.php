<?php

namespace App\Livewire\MeksikoClean;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $metrics = [
            'total_transactions' => \App\Models\McTransaction::count(),
            'total_partners' => \App\Models\McPartner::count(),
            'pending_orders' => \App\Models\McTransaction::where('status', 'pending')->count(),
            'completed_orders' => \App\Models\McTransaction::where('status', 'diambil')->count(),
            'revenue_this_month' => \App\Models\McTransaction::where('payment_status', 'paid')
                                            ->whereMonth('created_at', now()->month)
                                            ->sum('total_amount'),
        ];

        // Fetch latest 5 transactions
        $latest_transactions = \App\Models\McTransaction::with('partner')
            ->latest()
            ->take(5)
            ->get();

        // Calculate most popular services based on transaction items
        $popular_services = \App\Models\McTransactionItem::with('service')
            ->select('service_id', \DB::raw('sum(qty) as total_sold'))
            ->groupBy('service_id')
            ->orderByDesc('total_sold')
            ->take(4)
            ->get();

        $total_sold_all = \App\Models\McTransactionItem::sum('qty');

        return view('livewire.meksiko-clean.dashboard', [
            'metrics' => $metrics,
            'latest_transactions' => $latest_transactions,
            'popular_services' => $popular_services,
            'total_sold_all' => $total_sold_all
        ])->layout('layouts.app');
    }
}
