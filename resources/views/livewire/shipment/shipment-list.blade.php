<div class="min-h-screen py-8 bg-gray-50/50">
    @section('page-title', 'Pelacakan Pengiriman')

    <div class="mx-auto">
        

        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-12 items-end">
                <div class="md:col-span-4">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Cari Pengiriman</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search"
                               placeholder="No. Pengiriman / No. Transfer..." 
                               class="block w-full pl-10 pr-3 py-2.5 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all">
                    </div>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Status</label>
                    <select wire:model.live="statusFilter" class="block w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5">
                        <option value="">Semua Status</option>
                        <option value="prepared">📦 Prepared</option>
                        <option value="on_the_way">🚚 On The Way</option>
                        <option value="delivered">✅ Delivered</option>
                    </select>
                </div>

                @can('access-all-outlets')
                <div class="md:col-span-3">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Outlet</label>
                    <select wire:model.live="outletFilter" class="block w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5">
                        <option value="">Semua Outlet</option>
                        @foreach($outlets as $outlet)
                        <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endcan

                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Sejak Tanggal</label>
                    <input type="date" wire:model.live="dateFrom"
                           class="block w-full border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-2.5">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            @forelse($shipments as $shipment)
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
                <div class="flex flex-col md:flex-row">
                    <div class="p-6 md:w-1/3 bg-gray-50/50 border-b md:border-b-0 md:border-r border-gray-100">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="p-3 bg-indigo-600 rounded-2xl text-white shadow-lg shadow-indigo-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $shipment->shipment_number }}</h3>
                                <p class="text-xs font-mono text-gray-400">Ref: {{ $shipment->stockTransfer->transfer_number ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-400">Status</span>
                                @php
                                    $statusStyle = [
                                        'prepared' => 'bg-amber-50 text-amber-700 border-amber-100',
                                        'on_the_way' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                        'delivered' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    ][$shipment->status] ?? 'bg-gray-50 text-gray-600';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase border {{ $statusStyle }}">
                                    {{ str_replace('_', ' ', $shipment->status) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-400">Kurir</span>
                                <span class="font-bold text-gray-700">{{ $shipment->courier_name ?? 'Internal' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 md:w-2/3 flex flex-col justify-between">
                        <div class="flex items-center justify-between relative mb-10">
                            <div class="text-left relative z-10">
                                <p class="text-[10px] font-black text-gray-300 uppercase mb-1">Origin</p>
                                <p class="font-bold text-gray-900 leading-tight">{{ $shipment->fromOutlet ? $shipment->fromOutlet->name : 'Warehouse' }}</p>
                                <p class="text-[10px] text-gray-500">{{ $shipment->fromOutlet->code ?? 'SUP' }}</p>
                            </div>

                            <div class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex items-center justify-center px-20">
                                <div class="w-full h-px bg-dashed bg-gray-200 relative">
                                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white px-2">
                                        <svg class="w-5 h-5 text-indigo-400 @if($shipment->status === 'on_the_way') animate-bounce @endif" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right relative z-10">
                                <p class="text-[10px] font-black text-gray-300 uppercase mb-1">Destination</p>
                                <p class="font-bold text-gray-900 leading-tight">{{ $shipment->toOutlet->name }}</p>
                                <p class="text-[10px] text-gray-500">{{ $shipment->toOutlet->code }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 relative">
                            <div class="absolute top-4 inset-x-0 h-0.5 bg-gray-100 rounded-full"></div>
                            
                            @php
                                $steps = [
                                    ['label' => 'Prepared', 'date' => $shipment->created_at, 'active' => true],
                                    ['label' => 'On Delivery', 'date' => $shipment->shipped_at, 'active' => in_array($shipment->status, ['on_the_way', 'delivered'])],
                                    ['label' => 'Received', 'date' => $shipment->delivered_at, 'active' => $shipment->status === 'delivered'],
                                ];
                            @endphp

                            @foreach($steps as $step)
                            <div class="relative flex flex-col items-center">
                                <div class="w-8 h-8 rounded-full border-4 border-white shadow-sm flex items-center justify-center z-10 transition-colors duration-500 {{ $step['active'] ? 'bg-emerald-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                                    @if($step['active'])
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                    @else
                                        <div class="w-2 h-2 bg-white rounded-full"></div>
                                    @endif
                                </div>
                                <p class="mt-2 text-[10px] font-bold {{ $step['active'] ? 'text-gray-900' : 'text-gray-400' }}">{{ $step['label'] }}</p>
                                <p class="text-[9px] text-gray-400">{{ $step['date'] ? $step['date']->format('d M, H:i') : '--:--' }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($shipment->notes)
                <div class="px-6 py-3 bg-indigo-50/30 border-t border-gray-100 flex items-center justify-between">
                    <div class="flex items-center text-xs text-indigo-600 italic">
                        <svg class="w-4 h-4 mr-2 opacity-50" fill="currentColor" viewBox="0 0 20 20"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
                        "{{ $shipment->notes }}"
                    </div>
                    <a href="{{ route('transfer.detail', $shipment->stock_transfer_id) }}" class="text-[10px] font-black text-indigo-600 hover:underline uppercase">Detail Transfer</a>
                </div>
                @endif
            </div>
            @empty
            <div class="bg-white rounded-3xl border border-dashed border-gray-300 py-16 text-center">
                <div class="bg-gray-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Tidak ada pengiriman</h3>
                <p class="text-sm text-gray-500 px-4">Kami tidak menemukan data pengiriman yang sesuai dengan kriteria filter Anda.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $shipments->links() }}
        </div>
    </div>
</div>