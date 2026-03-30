<div
    class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] ring-1 ring-gray-100 p-2 mb-8 flex overflow-x-auto gap-2">
    @php
    $currentRoute = request()->route()->getName();
    @endphp

    <a href="{{ route('admin.reports.sales') }}" class="flex flex-1 items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-bold transition-all whitespace-nowrap
              {{ $currentRoute === 'admin.reports.sales' 
                  ? 'bg-blue-50 text-blue-600 shadow-sm ring-1 ring-blue-100' 
                  : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
        📊 Penjualan
    </a>

    <!-- <a href="{{ route('admin.reports.inventory') }}" 
       class="flex flex-1 items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-bold transition-all whitespace-nowrap
              {{ $currentRoute === 'admin.reports.inventory' 
                  ? 'bg-emerald-50 text-emerald-600 shadow-sm ring-1 ring-emerald-100' 
                  : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
        📦 Inventori
    </a> -->

    <a href="{{ route('admin.reports.pawn') }}" class="flex flex-1 items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-bold transition-all whitespace-nowrap
              {{ $currentRoute === 'admin.reports.pawn' 
                  ? 'bg-amber-50 text-amber-600 shadow-sm ring-1 ring-amber-100' 
                  : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
        💎 Pegadaian
    </a>

    <a href="{{ route('admin.reports.clean') }}" class="flex flex-1 items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-bold transition-all whitespace-nowrap
              {{ $currentRoute === 'admin.reports.clean' 
                  ? 'bg-cyan-50 text-cyan-600 shadow-sm ring-1 ring-cyan-100' 
                  : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
        ✨ Clean
    </a>
    <a href="{{ route('admin.reports.comparison') }}" class="flex flex-1 items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-bold transition-all whitespace-nowrap
              {{ $currentRoute === 'admin.reports.comparison' 
                  ? 'bg-indigo-50 text-indigo-600 shadow-sm ring-1 ring-indigo-100' 
                  : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
        📈 Komparasi
    </a>
</div>