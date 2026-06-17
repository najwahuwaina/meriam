<x-filament-widgets::widget>
    <x-filament::section>
        <div class="overflow-x-auto">
            <h2 class="text-lg font-bold mb-4">Laporan Buku Besar</h2>

            <table class="table-auto w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-2 py-1">Tanggal</th>
                        <th class="border px-2 py-1">Akun</th>
                        <th class="border px-2 py-1">Debet</th>
                        <th class="border px-2 py-1">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jurnals as $jurnal)
                        @foreach($jurnal->jurnaldetail as $detail)
                            <tr>
                                <td class="border px-2 py-1">{{ $jurnal->tanggal }}</td>
                                <td class="border px-2 py-1">{{ $detail->akun->nama_akun ?? $detail->akun }}</td>
                                <td class="border px-2 py-1">{{ $detail->debit }}</td>
                                <td class="border px-2 py-1">{{ $detail->kredit }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <p><strong>Saldo Awal:</strong> {{ $saldoAwal }}</p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
