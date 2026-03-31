<div class="flex items-center h-16 px-6 overflow-hidden">
    <div class="flex items-center space-x-3 min-w-max">
        <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-lg shadow-sm">
            <img src="{{ asset('images/icon.jpg') }}" alt="" class=" rounded-full">
        </div>
        <div x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-x-2"
            x-transition:enter-end="opacity-100 transform translate-x-0" class="flex flex-col">
            <h1 class="text-sm font-bold leading-none text-gray-900">MEKSIKO GROUB</h1>
            <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-wider">Distribution System</p>
        </div>
    </div>
</div>

<nav class="px-3 py-4 space-y-1 overflow-y-auto">
    @can('view-audit')
    <a href="{{ route('dashboard') }}"
        class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-xl group
                  {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white shadow-md shadow-primary-100' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-primary-600' }}"
            :class="!{{ $isMobile ? 'true' : 'false' }} && sidebarMinimized ? 'mx-auto' : 'mr-3'" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}" class="truncate">Dashboard</span>
    </a>

    @if(auth()->user()->outlet_id)
    <a href="{{ route('pos') }}"
        class="flex items-center px-3 py-2 mt-1 text-sm font-medium rounded-lg transition-all
                      {{ request()->routeIs('pos*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-50' }}">
        <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('pos*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-primary-600' }}"
            :class="!{{ $isMobile ? 'true' : 'false' }} && sidebarMinimized ? 'mx-auto' : 'mr-3'" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <span x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}" class="truncate">Point of Sale</span>
    </a>
    @endif

    <div x-data="{ open: {{ request()->routeIs('stock.*') ? 'true' : 'false' }} }">
        <button
            @click="{{ !$isMobile ? 'sidebarMinimized ? sidebarMinimized = false : open = !open' : 'open = !open' }}"
            class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-700 transition-all rounded-xl hover:bg-gray-100 group">
            <div class="flex items-center min-w-max">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400 transition-colors group-hover:text-primary-600"
                    :class="!{{ $isMobile ? 'true' : 'false' }} && sidebarMinimized ? 'mx-auto' : 'mr-3'" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}" class="truncate">Manajemen Stok</span>
            </div>
            <svg x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}"
                class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="open && {{ $isMobile ? 'true' : '!sidebarMinimized' }}" x-collapse class="mt-1 ml-8 space-y-1">
            <a href="{{ route('stock.list') }}"
                class="block px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('stock.list') ? 'text-primary-700 bg-primary-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Daftar Stok
            </a>
            <a href="{{ route('stock.low-stock') }}"
                class="flex items-center justify-between px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('stock.low-stock') ? 'text-primary-700 bg-primary-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Stok Menipis
                @livewire('components.low-stock-badge')
            </a>
            @can('manage-users')
            <a href="{{ route('admin.goods-receipt') }}"
                class="block px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.goods-receipt') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50' }}">
                Penerimaan Barang
            </a>
            @endcan
        </div>
    </div>

    <div x-data="{ open: {{ request()->routeIs('transfer.*') ? 'true' : 'false' }} }">
        <button
            @click="{{ !$isMobile ? 'sidebarMinimized ? sidebarMinimized = false : open = !open' : 'open = !open' }}"
            class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-700 transition-all rounded-xl hover:bg-gray-100 group">
            <div class="flex items-center min-w-max">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400 transition-colors group-hover:text-primary-600"
                    :class="!{{ $isMobile ? 'true' : 'false' }} && sidebarMinimized ? 'mx-auto' : 'mr-3'" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <span x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}" class="truncate">Manajemen Transfer</span>
            </div>
            <svg x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}"
                class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div x-show="open && {{ $isMobile ? 'true' : '!sidebarMinimized' }}" x-collapse class="mt-1 ml-8 space-y-1">
            @can('create-transfer')
            <a href="{{ route('transfer.create') }}"
                class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('transfer.create') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50' }}">
                Buat Transfer
            </a>
            <a href="{{ route('sales.list') }}"
                class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('sales.list') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50' }}">
                Daftar Transaksi
            </a>
            @endcan
            @can('manage-audit')
            <a href="{{ route('transfer.list') }}"
                class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('transfer.list') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50' }}">
                Daftar Transfer
            </a>
            <a href="{{ route('transfer.pending') }}"
                class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('transfer.pending') ? 'bg-primary-50 text-primary-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <span>Pending Approval</span>
                @livewire('components.pending-transfer-badge')
            </a>
            @endcan
        </div>
    </div>

    <a href="{{ route('shipment.list') }}"
        class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-xl group
                  {{ request()->routeIs('shipment.*') ? 'bg-primary-100 text-slate-600' : 'text-gray-700 hover:bg-gray-100' }}">
        <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('shipment.*') ? 'text-slate-600' : 'text-gray-400 group-hover:text-slate-600' }}"
            :class="!{{ $isMobile ? 'true' : 'false' }} && sidebarMinimized ? 'mx-auto' : 'mr-3'" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
        </svg>
        <span x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}" class="truncate">Pelacakan Pengiriman</span>
    </a>

    @can('manage-audit')
    <div x-data="{ open: {{ request()->routeIs('audit.*') ? 'true' : 'false' }} }">
        <button
            @click="{{ !$isMobile ? 'sidebarMinimized ? sidebarMinimized = false : open = !open' : 'open = !open' }}"
            class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-700 transition-all rounded-xl hover:bg-gray-100 group">
            <div class="flex items-center min-w-max">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400 transition-colors group-hover:text-primary-600"
                    :class="!{{ $isMobile ? 'true' : 'false' }} && sidebarMinimized ? 'mx-auto' : 'mr-3'" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <span x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}" class="truncate">Audit Barang</span>
            </div>
            <svg x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}"
                class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div x-show="open && {{ $isMobile ? 'true' : '!sidebarMinimized' }}" x-collapse class="mt-1 ml-8 space-y-1">
            <a href="{{ route('audit.create') }}"
                class="block px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('audit.create') ? 'text-primary-700 bg-primary-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Buat Audit
            </a>
            <a href="{{ route('audit.list') }}"
                class="block px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('audit.list') ? 'text-primary-700 bg-primary-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Riwayat Audit
            </a>
        </div>
    </div>
    @endcan

    @can('manage-users')
    <div
        x-data="{ open: {{ request()->routeIs('admin.data.*') || request()->routeIs('permissions.*') ? 'true' : 'false' }} }">
        <button
            @click="{{ !$isMobile ? 'sidebarMinimized ? sidebarMinimized = false : open = !open' : 'open = !open' }}"
            class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-700 transition-all rounded-xl hover:bg-gray-100 group">
            <div class="flex items-center min-w-max">
                <svg class="w-5 h-5 flex-shrink-0 text-gray-400 transition-colors group-hover:text-primary-600"
                    :class="!{{ $isMobile ? 'true' : 'false' }} && sidebarMinimized ? 'mx-auto' : 'mr-3'" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}" class="truncate">Data Master</span>
            </div>
            <svg x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}"
                class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        <div x-show="open && {{ $isMobile ? 'true' : '!sidebarMinimized' }}" x-collapse class="mt-1 ml-8 space-y-1">
            <a href="{{ route('admin.data.products') }}"
                class="block px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.data.products') ? 'text-primary-700 bg-primary-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Kelola Produk
            </a>
            <a href="{{ route('admin.data.outlets') }}"
                class="block px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.data.outlets') ? 'text-primary-700 bg-primary-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Kelola Outlet
            </a>
            <a href="{{ route('admin.data.users') }}"
                class="block px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ request()->routeIs('admin.data.users') ? 'text-primary-700 bg-primary-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                Kelola User
            </a>
        </div>
    </div>
    @endcan
    @endcan

    @can('manage-pegadaian')
    <div class="pt-4 mt-4 border-t border-gray-200">
        <div x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}"
            x-transition:enter="transition ease-out duration-200" class="px-3 mb-2">
            <p class="text-xs font-semibold tracking-wider text-gray-400 uppercase">Pegadaian</p>
        </div>

        <div class="space-y-1">
            @can('create-pegadaian')
            <a href="{{ route('pegadaian.dashboard') }}"
                class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-xl group
                  {{ request()->routeIs('pegadaian.dashboard') ? 'bg-slate-100 text-slate-600' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('pegadaian.dashboard') ? 'text-slate-600' : 'text-gray-400 group-hover:text-slate-600' }}"
                    :class="!{{ $isMobile ? 'true' : 'false' }} && sidebarMinimized ? 'mx-auto' : 'mr-3'" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}" class="truncate">Dashboard
                    Pegadaian</span>
            </a>
            <a href="{{ route('pegadaian.create') }}"
                class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-xl group
                  {{ request()->routeIs('pegadaian.create') ? 'bg-slate-100 text-slate-600' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('pegadaian.create') ? 'text-slate-600' : 'text-gray-400 group-hover:text-slate-600' }}"
                    :class="!{{ $isMobile ? 'true' : 'false' }} && sidebarMinimized ? 'mx-auto' : 'mr-3'" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}" class="truncate">Pos Gadai</span>
            </a>
            @endcan
            <a href="{{ route('pegadaian.list') }}"
                class="flex items-center px-3 py-2 text-sm font-medium transition-all duration-200 rounded-xl group
                  {{ request()->routeIs('pegadaian.list') ? 'bg-slate-100 text-slate-600' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 flex-shrink-0 transition-colors {{ request()->routeIs('pegadaian.list') ? 'text-slate-600' : 'text-gray-400 group-hover:text-slate-600' }}"
                    :class="!{{ $isMobile ? 'true' : 'false' }} && sidebarMinimized ? 'mx-auto' : 'mr-3'" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span x-show="{{ $isMobile ? 'true' : '!sidebarMinimized' }}" class="truncate">Daftar Pegadaian</span>
            </a>
        </div>
    </div>
    @endcan

    <!-- ===========================================================================================
                                Sidebar Clean
     =========================================================================================== -->

    @if(auth()->user()->isMeksikoClean() || auth()->user()->isAdminPusat())
    @include('layouts.sidebar-clean', ['isMobile' => $isMobile])
    @endif
</nav>