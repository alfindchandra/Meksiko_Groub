<div class="p-2 sm:p-3 lg:p-4 bg-gray-50/50 min-h-screen">
    @section('page-title', 'Manajemen Stok')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50/30">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                <div class="md:col-span-6">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5 ml-1">Cari Produk</label>
                    <div class="relative group">
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Cari nama produk, SKU, atau kode..."
                            class="w-full bg-white border-gray-200 rounded-xl pl-10 pr-10 focus:ring-primary-500 focus:border-primary-500 transition-all text-sm py-2.5 shadow-sm">
                        <div
                            class="absolute left-3 top-2.5 text-gray-400 group-focus-within:text-primary-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        @if($search)
                        <button wire:click="$set('search', '')"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>

                @if(auth()->user()->hasRole('admin_pusat') || auth()->user()->hasRole('auditor'))
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5 ml-1">Outlet</label>
                    <select wire:model.live="selectedOutlet"
                        class="w-full bg-white border-gray-200 rounded-xl text-sm py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                        <option value="">Semua Outlet</option>
                        @foreach($outlets as $outlet)
                        <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif


                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1.5 ml-1">Kategori</label>
                    <select wire:model.live="selectedCategory"
                        class="w-full bg-white border-gray-200 rounded-xl text-sm py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th
                            class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            <button wire:click="sortBy('product_name')"
                                class="flex items-center space-x-1 hover:text-primary-600 transition-colors">
                                <span>Produk</span>
                                @if($sortBy === 'product_name')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                </svg>
                                @endif
                            </button>
                        </th>
                        @can('manage-audit')
                        <th
                            class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            Outlet</th>
                        @endcan
                        <th
                            class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            Kategori</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-center">
                            Jumlah</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            Status</th>
                        <th
                            class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-right">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($stocks as $stock)
                    <tr class="hover:bg-blue-50/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div
                                    class="h-10 w-10 flex-shrink-0 bg-gray-100 rounded-lg mr-3 flex items-center justify-center text-gray-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div>
                                    <p
                                        class="font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                                        {{ $stock->product->name }}</p>
                                    <p class="text-xs text-gray-500 font-mono tracking-tighter">
                                        {{ $stock->product->sku }}</p>
                                </div>
                            </div>
                        </td>
                        @can('manage-audit')
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-800">{{ $stock->outlet->name }}</span>
                                <span class="text-xs text-gray-400">{{ $stock->outlet->code }}</span>
                            </div>
                        </td>
                        @endcan
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $stock->product->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-block">
                                <p class="text-base font-bold text-gray-900">{{ number_format($stock->quantity) }}</p>
                                @if($stock->reserved > 0)
                                <div class="flex items-center justify-center text-[10px] text-orange-500 font-medium">
                                    <span class="w-1.5 h-1.5 bg-orange-400 rounded-full mr-1 animate-pulse"></span>
                                    {{ $stock->reserved }} Dipesan
                                </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($stock->is_low_stock)
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-700 border border-red-100">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Stok Menipis
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span>
                                Aman
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('stock.detail', $stock->id) }}"
                                    class="p-2 text-green-400 hover:text-primary-600 hover:bg-gray-400 rounded-lg transition-all"
                                    title="Detail">
                                    <svg class="w-5 h-5 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="p-4 bg-gray-50 rounded-full mb-4">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">Tidak ada stok</h3>
                                <p class="text-gray-500 max-w-xs mx-auto">Kami tidak dapat menemukan data stok yang Anda
                                    cari dengan kriteria tersebut.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-gray-50/50 border-t items-center border-gray-100">
            {{ $stocks->links() }}
        </div>
    </div>
</div>