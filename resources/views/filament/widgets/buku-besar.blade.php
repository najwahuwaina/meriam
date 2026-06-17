<x-filament-widgets::widget>
    <x-filament::section>
        <div class="overflow-x-auto">

            {{-- Filter --}}
            <form wire:submit.prevent="$refresh" class="flex flex-wrap gap-4 items-end mb-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Periode Awal</label>
                    <input type="month" wire:model="periode_awal"
                        class="border rounded px-3 py-1.5 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Periode Akhir</label>
                    <input type="month" wire:model="periode_akhir"
                        class="border rounded px-3 py-1.5 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Akun</label>
                    <input type="text" wire:model="akun" placeholder="Kode atau nama akun"
                        class="border rounded px-3 py-1.5 text-sm" />
                </div>
                <div>
                    <button type="submit"
                        class="bg-primary-600 text-white px-4 py-1.5 rounded text-sm hover:bg-primary-700">
                        Filter
                    </button>
                </div>
            </form>

            {{-- Header Laporan --}}
            <div class="text-center mb-4">
                <p class="font-bold text-lg">Laporan Buku Besar</p>
                @if($periode_awal && $periode_akhir)
                    <p class="text-sm text-gray-400">
                        Periode {{ \Carbon\Carbon::createFromFormat('Y-m', $periode_awal)->translatedFormat('F Y') }}
                        - {{ \Carbon\Carbon::createFromFormat('Y-m', $periode_akhir)->translatedFormat('F Y') }}
                    </p>
                @endif
            </div>

            {{-- Tabel --}}
            <table class="table-auto w-full border-collapse border border-gray-600 text-sm">
                <thead>
                    <tr class="bg-gray-600 text-white">
                        <th class="border border-gray-500 px-3 py-2 text-left">Tanggal</th>
                        <th class="border border-gray-500 px-3 py-2 text-left">Akun</th>
                        <th class="border border-gray-500 px-3 py-2 text-left">Reff</th>
                        <th class="border border-gray-500 px-3 py-2 text-right">Debet</th>
                        <th class="border border-gray-500 px-3 py-2 text-right">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Saldo Awal --}}
                    <tr class="bg-gray-700 text-white">
                        <td colspan="3" class="border border-gray-500 px-3 py-2 font-semibold">Saldo Awal</td>
                        <td colspan="2" class="border border-gray-500 px-3 py-2 text-right font-semibold">
                            Rp {{ number_format($saldoAwal, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- Data Jurnal --}}
                    @forelse($jurnals as $jurnal)
                        @foreach($jurnal->jurnaldetail as $detail)
                            <tr class="hover:bg-gray-700">
                                <td class="border border-gray-600 px-3 py-2">{{ $jurnal->tanggal }}</td>
                                <td class="border border-gray-600 px-3 py-2">{{ $detail->akun->nama_akun ?? $detail->akun }}</td>
                                <td class="border border-gray-600 px-3 py-2">{{ $jurnal->no_jurnal ?? '-' }}</td>
                                <td class="border border-gray-600 px-3 py-2 text-right">
                                    {{ $detail->debit > 0 ? 'Rp ' . number_format($detail->debit, 0, ',', '.') : '-' }}
                                </td>
                                <td class="border border-gray-600 px-3 py-2 text-right">
                                    {{ $detail->kredit > 0 ? 'Rp ' . number_format($detail->kredit, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="border border-gray-600 px-3 py-4 text-center text-gray-400">
                                Tidak ada data untuk periode ini.
                            </td>
                        </tr>
                    @endforelse

                    {{-- Hitung Total & Saldo Akhir --}}
                    @php
                        $totalDebit = $jurnals->flatMap->jurnaldetail->sum('debit');
                        $totalKredit = $jurnals->flatMap->jurnaldetail->sum('kredit');
                        $saldoAkhir = $saldoAwal + $totalDebit - $totalKredit;
                    @endphp

                    {{-- Total --}}
                    <tr class="bg-gray-600 text-white font-semibold">
                        <td colspan="3" class="border border-gray-500 px-3 py-2 text-right">Total</td>
                        <td class="border border-gray-500 px-3 py-2 text-right">Rp {{ number_format($totalDebit, 0, ',', '.') }}</td>
                        <td class="border border-gray-500 px-3 py-2 text-right">Rp {{ number_format($totalKredit, 0, ',', '.') }}</td>
                    </tr>

                    {{-- Saldo Akhir --}}
                    <tr class="bg-gray-700 text-white font-semibold">
                        <td colspan="3" class="border border-gray-500 px-3 py-2 text-right">Saldo Akhir</td>
                        <td colspan="2" class="border border-gray-500 px-3 py-2 text-right">
                            Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>