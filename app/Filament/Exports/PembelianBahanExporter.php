<?php

namespace App\Filament\Exports;

use App\Models\PembelianBahan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PembelianBahanExporter extends Exporter
{
    protected static ?string $model = PembelianBahan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('kode_pembelian')
                ->label('Kode Pembelian'),

            ExportColumn::make('bahanBaku.nama_bahan') // ← ubah: gunakan dot notation relasi
                ->label('Bahan Baku'),

            ExportColumn::make('tanggal')
                ->label('Tanggal'),

            ExportColumn::make('jumlah')
                ->label('Jumlah'),

            ExportColumn::make('harga_beli')
                ->label('Harga Beli'),

            ExportColumn::make('tagihan')
                ->label('Tagihan'),

            ExportColumn::make('dibayar')
                ->label('Dibayar'),

            ExportColumn::make('sisa')
                ->label('Sisa'),

            ExportColumn::make('status_pembayaran')
                ->label('Status Pembayaran')
                ->state(fn (PembelianBahan $record) => match ($record->status_pembayaran) {
                    'belum_bayar' => 'Belum Bayar',
                    'sebagian'    => 'Sebagian',
                    'lunas'       => 'Lunas',
                    default       => '-',
                }),

            ExportColumn::make('metode_pembayaran')
                ->label('Metode Pembayaran')
                ->state(fn (PembelianBahan $record) => match ($record->metode_pembayaran) {
                    'cash'   => 'Cash',
                    'debit'  => 'Debit',
                    'kredit' => 'Kredit',
                    default  => '-', // ← ini yang bikin kosong sebelumnya, null jadi '-'
                }),

            ExportColumn::make('jatuh_tempo')
                ->label('Jatuh Tempo')
                ->state(fn (PembelianBahan $record) => $record->jatuh_tempo ?? '-'),

            ExportColumn::make('supplier')
                ->label('Supplier')
                ->state(fn (PembelianBahan $record) => $record->supplier ?? '-'),

            ExportColumn::make('keterangan')
                ->label('Keterangan')
                ->state(fn (PembelianBahan $record) => $record->keterangan ?? '-'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export pembelian bahan selesai. ' . number_format($export->successful_rows) . ' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}