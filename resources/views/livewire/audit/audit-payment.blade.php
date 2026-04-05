<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pembayaran Audit Stok</h1>
            <p class="mt-2 text-lg text-gray-600">Daftar stok bermasalah yang menunggu penyelesaian (minus / plus).</p>
            @if($isAdmin)
            <p class="mt-2 text-sm text-blue-600">✓ Mode Admin - Lihat semua audit dari semua outlet.</p>
            @else
            <p class="mt-2 text-sm text-green-600">✓ Mode Outlet - Lihat audit outlet Anda saja.</p>
            @endif
        </div>

        @if(count($payAudits) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @if($isAdmin)
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Outlet</th>
                            @endif
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sistem</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fisik</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selisih</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($payAudits as $audit)
                            <tr>
                                @if($isAdmin)
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $audit->outlet->name ?? '-' }}</td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $audit->product->name ?? '-' }} <br><span class="text-xs text-gray-500">{{ $audit->audit_number }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $audit->system_quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $audit->physical_quantity }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($audit->difference > 0)
                                        <span class="text-green-600 font-bold">+{{ abs($audit->difference) }} (Plus)</span>
                                    @elseif($audit->difference < 0)
                                        <span class="text-red-600 font-bold">{{ $audit->difference }} (Minus)</span>
                                    @else
                                        <span class="text-gray-600">0</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium">
                                    @if($audit->payment_amount > 0)
                                        <span class="text-red-600">💰 Rp {{ number_format($audit->payment_amount, 0, ',', '.') }}</span>
                                    @elseif($audit->payment_amount < 0)
                                        <span class="text-green-600">✓ Rp {{ number_format(abs($audit->payment_amount), 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-gray-500">0</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <!-- Summary -->
                <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-200">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Total Harus Dibayar (Minus):</h3>
                        <span class="text-2xl font-bold text-red-600">Rp {{ number_format($totalPayment, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Total Diskon (Plus):</h3>
                        <span class="text-2xl font-bold text-green-600">Rp {{ number_format($totalCredit, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form wire:submit.prevent="submitPayment">
                    @if($totalPayment > 0)
                        <div class="mb-4">
                            <label for="proof" class="block text-sm font-medium text-gray-700">Upload Bukti Pembayaran <span class="text-red-500">*</span></label>
                            <p class="mt-1 text-xs text-gray-500">Outlet harus bayar Rp {{ number_format($totalPayment, 0, ',', '.') }} untuk menutup selisih stok (minus).</p>
                            <input type="file" wire:model="proof" id="proof" class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            <p class="mt-1 text-sm text-gray-500">Maksimal ukuran 5MB. Format gambar (.jpg, .jpeg, .png)</p>
                            @error('proof') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        @if ($proof)
                            <div class="mt-2 mb-4">
                                <p class="text-sm text-gray-500">Preview Bukti:</p>
                                <img src="{{ $proof->temporaryUrl() }}" class="mt-2 w-32 object-cover rounded-md shadow-sm">
                            </div>
                        @endif
                    @elseif($totalPayment == 0 && $totalCredit > 0)
                        <div class="mb-4 bg-green-50 p-4 rounded-md border border-green-200">
                            <p class="text-sm text-green-700">✓ Stok plus (surplus) senilai Rp {{ number_format($totalCredit, 0, ',', '.') }} akan dikurangi dari pembayaran outlet. Tidak perlu upload bukti - submit langsung.</p>
                        </div>
                    @else
                        <div class="mb-4 bg-blue-50 p-4 rounded-md border border-blue-200">
                            <p class="text-sm text-blue-700">ℹ Total pembayaran adalah 0. Anda dapat submit tanpa bukti pembayaran, dan Admin akan mengevaluasi.</p>
                        </div>
                    @endif

                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('audit.list') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            Kembali
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="submitPayment">Submit & Selesaikan Audit</span>
                            <span wire:loading wire:target="submitPayment">Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada Audit Pending</h3>
                <p class="mt-1 text-sm text-gray-500">Outlet ini tidak memiliki audit yang menunggu pembayaran.</p>
                <div class="mt-6">
                    <a href="{{ route('audit.list') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Ke Daftar Audit
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
