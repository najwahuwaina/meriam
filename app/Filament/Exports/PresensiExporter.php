<?php

namespace App\Filament\Exports;

use App\Models\Presensi;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class PresensiExporter extends Exporter
{
    protected static ?string $model = Presensi::class;

    // ✅ implementasi abstract method getColumns()
    public static function getColumns(): array
    {
        return [
            ExportColumn::make('karyawan.nama_karyawan')->label('Karyawan'),
            ExportColumn::make('tanggal')->label('Tanggal'),
            ExportColumn::make('jam_masuk')->label('Jam Masuk'),
            ExportColumn::make('jam_keluar')->label('Jam Keluar'),
            ExportColumn::make('status')->label('Status'),
            ExportColumn::make('created_at')->label('Created At'),
        ];
    }

    // ✅ implementasi abstract method getCompletedNotificationBody()
    public static function getCompletedNotificationBody(Export $export): string
    {
        return "Export Presensi selesai. File: presensi_export.xlsx";
    }

    // ✅ override getFileName() sesuai parent signature
    public function getFileName(Export $export): string
    {
        return 'presensi_export.xlsx';
    }
}
