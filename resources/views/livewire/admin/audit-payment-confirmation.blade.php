<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Konfirmasi Pembayaran Audit Stok</h1>
            <p class="mt-2 text-lg text-gray-600">Daftar outlet yang telah menyubmit pembayaran audit / penyelesaian selisih stok.</p>
        </div>

        @if($groupedAudits->count() > 0)
            <div class="space-y-8">
                @foreach($groupedAudits as $outletId => $audits)
                    @php
                        $outlet = $audits->first()->outlet;
                        $totalPayment = $audits->sum('payment_amount');
                        // Ambil bukti bayar terakhir, misal dari audit pertama yang punya
                        $proof = $audits->whereNotNull('proof_of_payment')->first()->proof_of_payment ?? null;
                    @endphp
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Outlet {{ $outlet->name }}</h2>
                                <p class="text-sm text-gray-500">{{ $audits->count() }} item audit menunggu konfirmasi.</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Total Nett Pembayaran</p>
                                <p class="text-lg font-bold {{ $totalPayment > 0 ? 'text-red-600' : ($totalPayment < 0 ? 'text-green-600' : 'text-gray-900') }}">
                                    @if($totalPayment > 0)
                                        Rp {{ number_format($totalPayment, 0, ',', '.') }}
                                    @elseif($totalPayment < 0)
                                        Potongan Rp {{ number_format(abs($totalPayment), 0, ',', '.') }}
                                    @else
                                        Rp 0
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Detail Produk -->
                            <div class="lg:col-span-2">
                                <h3 class="text-md font-semibold text-gray-900 mb-4">Rincian Selisih Stok</h3>
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                            <th scope="col" class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Selisih</th>
                                            <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Nilai (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($audits as $audit)
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900">{{ $audit->product->name ?? '-' }}</td>
                                                <td class="px-4 py-2 text-sm text-center">
                                                    @if($audit->difference > 0)
                                                        <span class="text-green-600 text-xs font-bold">+{{ abs($audit->difference) }} (Surplus)</span>
                                                    @elseif($audit->difference < 0)
                                                        <span class="text-red-600 text-xs font-bold">-{{ abs($audit->difference) }} (Defisit)</span>
                                                    @else
                                                        0
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 text-sm text-right">
                                                    @if($audit->payment_amount > 0)
                                                        {{ number_format($audit->payment_amount, 0, ',', '.') }}
                                                    @elseif($audit->payment_amount < 0)
                                                        <span class="text-green-600">-{{ number_format(abs($audit->payment_amount), 0, ',', '.') }}</span>
                                                    @else
                                                        0
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Bukti Bayar & Aksi -->
                            <div class="bg-gray-50 p-4 rounded-lg flex flex-col items-center border border-gray-100">
                                <h3 class="text-md font-semibold text-gray-900 mb-4 w-full text-center">Bukti Pembayaran</h3>
                                @if($proof)
                                    <a href="{{ Storage::url($proof) }}" target="_blank" class="block">
                                        <img src="{{ Storage::url($proof) }}" alt="Bukti Pembayaran" class="w-full max-h-48 object-cover rounded shadow-sm hover:opacity-75 transition-opacity">
                                    </a>
                                    <p class="text-xs text-center text-gray-500 mt-2">Klik gambar untuk memperbesar</p>
                                @else
                                    <div class="h-32 w-full flex items-center justify-center border-2 border-dashed border-gray-300 rounded-md">
                                        <span class="text-gray-400 text-sm">Tidak ada bukti (Nilai <= 0)</span>
                                    </div>
                                @endif

                                <button wire:click="confirmAllFromOutlet({{ $outletId }})" onclick="confirm('Apakah Anda yakin pembayaran dari outlet ini sudah sesuai? Stok fisik akan diperbarui.') || event.stopImmediatePropagation()" class="mt-auto w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Konfirmasi & Update Stok
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                </svg>
                <h3 class="mt-4 text-xl font-medium text-gray-900">Tidak Ada Konfirmasi Pembayaran</h3>
                <p class="mt-2 text-gray-500">Saat ini tidak ada outlet yang menyubmit bukti pembayaran audit stok.</p>
            </div>
        @endif
    </div>
</div>
