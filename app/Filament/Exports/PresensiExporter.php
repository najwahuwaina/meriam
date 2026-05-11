<?php

namespace App\Filament\Exports;

use App\Models\Presensi;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PresensiExporter extends Exporter
{
    protected static ?string $model = Presensi::class;

    // Kolom yang bisa diexport
    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id_presensi')->label('ID Presensi'),
            ExportColumn::make('karyawan.nama_karyawan')->label('Nama Karyawan'),
            ExportColumn::make('tanggal')->label('Tanggal'),
            ExportColumn::make('jam_masuk')->label('Jam Masuk'),
            ExportColumn::make('jam_keluar')->label('Jam Keluar'),
            ExportColumn::make('status')->label('Status'),
        ];
    }

    // Pesan notifikasi setelah export selesai
    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'Export data presensi selesai. File sudah siap diunduh.';
    }
}
