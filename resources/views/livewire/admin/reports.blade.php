<div>
    @section('page-title', 'Laporan & Analisis')

    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Laporan & Analisis</h1>
                        <p class="mt-2 text-sm text-gray-600">Analisis bisnis komprehensif dengan visualisasi data</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button wire:click="exportExcel" class="btn-secondary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Excel
                        </button>
                        <button wire:click="exportPDF" class="btn-primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            PDF
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Laporan</label>
                        <select wire:model.live="reportType" class="form-input rounded-xl">
                            <option value="sales">📊 Penjualan</option>
                            <option value="inventory">📦 Inventori</option>
                            <option value="pawn">💎 Pegadaian</option>
                            <option value="comparison">📈 Perbandingan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Dari Tanggal</label>
                        <input type="date" wire:model.live="dateFrom" class="form-input rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Sampai Tanggal</label>
                        <input type="date" wire:model.live="dateTo" class="form-input rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Outlet</label>
                        <select wire:model.live="selectedOutlet" class="form-input rounded-xl">
                            <option value="">Semua Outlet</option>
                            @foreach($outlets as $outlet)
                            <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kategori</label>
                        <select wire:model.live="selectedCategory" class="form-input rounded-xl">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Report Content -->
            @if($reportType === 'sales')
                @include('livewire.admin.reports.sales-report')
            @elseif($reportType === 'inventory')
                @include('livewire.admin.reports.inventory-report')
            @elseif($reportType === 'pawn')
                @include('livewire.admin.reports.pawn-report')
            @elseif($reportType === 'comparison')
                @include('livewire.admin.reports.comparison-report')
            @endif

        </div>
    </div>
</div>