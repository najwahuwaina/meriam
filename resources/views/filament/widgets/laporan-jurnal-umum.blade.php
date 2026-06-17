<x-filament-widgets::widget>
    <x-filament::section>

        {{-- Filter Periode --}}
        <div class="flex items-center gap-3 mb-4">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Periode:</label>
            <input
                type="month"
                wire:model="periode"
                class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1.5 text-sm
                       bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                       focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
            <button
                wire:click="filter"
                class="px-4 py-1.5 bg-primary-600 hover:bg-primary-500 text-white text-sm rounded-lg transition">
                Filter
            </button>
        
        </div>

        {{-- Header --}}
        <div class="text-center mb-4">
            <div class="text-base font-bold text-gray-900 dark:text-white">Geprek Meriam</div>
            <div class="text-base font-bold text-gray-900 dark:text-white">Jurnal Umum</div>
            <div class="text-base font-bold text-gray-900 dark:text-white">Periode {{ $this->getNamaBulan() }}</div>
        </div>

        {{-- Tabel --}}
        @php
            $rows        = $this->getRows();
            $totalDebit  = $rows->sum('debit');
            $totalKredit = $rows->sum('kredit');
        @endphp

        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">ID Jurnal</th>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">Kode Akun</th>
                        <th class="px-4 py-2 text-left">Nama Akun</th>
                        <th class="px-4 py-2 text-left">Reff</th>
                        <th class="px-4 py-2 text-right">Debit</th>
                        <th class="px-4 py-2 text-right">Kredit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($rows as $row)
                        <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-800/50
                                   hover:bg-primary-50 dark:hover:bg-primary-900/20 transition">
                            <td class="px-4 py-2 text-gray-500 dark:text-gray-400">{{ $row->jurnal_id }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-gray-500 dark:text-gray-400">{{ $row->kode_akun }}</td>
                            <td class="px-4 py-2 font-medium">{{ $row->nama_akun }}</td>
                            <td class="px-4 py-2 text-gray-500 dark:text-gray-400">{{ $row->reff ?? '-' }}</td>
                            <td class="px-4 py-2 text-right text-green-600 dark:text-green-400">
                                {{ $row->debit > 0 ? 'Rp ' . number_format($row->debit, 0, ',', '.') : '' }}
                            </td>
                            <td class="px-4 py-2 text-right text-red-500 dark:text-red-400">
                                {{ $row->kredit > 0 ? 'Rp ' . number_format($row->kredit, 0, ',', '.') : '' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-400">
                                Tidak ada data untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-100 dark:bg-gray-700 font-semibold">
                    <tr>
                        <td colspan="5" class="px-4 py-2 text-right text-gray-700 dark:text-gray-200">Total</td>
                        <td class="px-4 py-2 text-right text-green-600 dark:text-green-400">
                            Rp {{ number_format($totalDebit, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 text-right text-red-500 dark:text-red-400">
                            Rp {{ number_format($totalKredit, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </x-filament::section>
</x-filament-widgets::widget>