<?php

namespace App\Filament\Resources\PresensiResource\Pages;

use App\Filament\Resources\PresensiResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use App\Filament\Exports\PresensiExporter;

class ListPresensi extends ListRecords
{
    protected static string $resource = PresensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(), // tombol Tambah Presensi
            Actions\ExportAction::make()
                ->exporter(PresensiExporter::class), // tombol Export pakai exporter ini
        ];
    }
}
