<div>
    @section('page-title', 'Stok Menipis')

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0 w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-200">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Peringatan Stok</h2>
                <p class="text-sm text-gray-500 font-medium italic">Monitor produk di bawah ambang batas minimum</p>
            </div>
        </div>

        @can('access-all-outlets')
        <div class="w-full md:w-64">
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">Filter Outlet</label>
            <div class="relative">
                <select wire:model.live="outletFilter" class="block w-full pl-3 pr-10 py-2.5 text-base border-gray-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-xl shadow-sm appearance-none bg-white">
                    <option value="">Semua Outlet</option>
                    @foreach($outlets as $outlet)
                    <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
            </div>
        </div>
        @endcan
    </div>

    @if($lowStocks->count() > 0)
    <div class="mb-8 overflow-hidden bg-white border border-red-100 rounded-2xl shadow-sm flex">
        <div class="w-2 bg-red-500"></div>
        <div class="p-4 flex items-center space-x-4">
            <div class="bg-red-50 p-2 rounded-full">
                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900">
                    Perhatian! Ada <span class="text-red-600">{{ $lowStocks->count() }} produk</span> yang butuh perhatian segera.
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($lowStocks as $stock)
        <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-red-200 transition-all duration-300 overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600 mb-2">
                            {{ $stock->product->sku }}
                        </span>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-red-600 transition-colors">{{ $stock->product->name }}</h3>
                        @can('access-all-outlets')
                        <div class="flex items-center mt-1 text-gray-500">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span class="text-xs font-medium">{{ $stock->outlet->name }}</span>
                        </div>
                        @endcan
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="bg-red-50 text-red-700 text-[10px] font-extrabold px-2 py-1 rounded-md uppercase tracking-wider border border-red-100">
                            Kritis
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3 mb-5">
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Stok</p>
                        <p class="text-xl font-black text-red-600">{{ number_format($stock->quantity) }}</p>
                        <p class="text-[10px] text-gray-400">{{ $stock->product->unit }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Min</p>
                        <p class="text-xl font-black text-gray-700">{{ number_format($stock->product->min_stock) }}</p>
                        <p class="text-[10px] text-gray-400">{{ $stock->product->unit }}</p>
                    </div>
                    <div class="bg-orange-50 p-3 rounded-xl border border-orange-100">
                        <p class="text-[10px] font-bold text-orange-400 uppercase">Kurang</p>
                        <p class="text-xl font-black text-orange-600">
                            {{ number_format($stock->product->min_stock - $stock->quantity) }}
                        </p>
                        <p class="text-[10px] text-orange-400">{{ $stock->product->unit }}</p>
                    </div>
                </div>

                <div class="mb-5">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-bold text-gray-500 uppercase">Kapasitas Stok</span>
                        <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-lg">
                            {{ round(($stock->quantity / $stock->product->min_stock) * 100) }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 p-0.5 border border-gray-200">
                        <div class="bg-gradient-to-r from-red-400 to-red-600 h-full rounded-full transition-all duration-500 shadow-sm" 
                             style="width: {{ min(100, ($stock->quantity / $stock->product->min_stock) * 100) }}%">
                        </div>
                    </div>
                </div>

                @can('create-transfer')
                <a href="{{ route('transfer.create') }}" 
                   class="inline-flex items-center justify-center w-full px-4 py-2.5 text-sm font-bold text-white bg-gray-900 rounded-xl hover:bg-red-600 transition-colors duration-200 group">
                    <svg class="w-4 h-4 mr-2 transform group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Ajukan Transfer Stok
                </a>
                @endcan
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-3xl p-12 text-center border-2 border-dashed border-gray-200">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-50 rounded-full mb-4">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Semua Aman!</h3>
            <p class="text-gray-500 max-w-xs mx-auto mt-2">Gudang dalam kondisi optimal. Tidak ada stok yang berada di bawah limit saat ini.</p>
        </div>
        @endforelse
    </div>
</div>