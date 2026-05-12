<?php

namespace App\Filament\Resources\KaryawanResource\Pages;

use App\Filament\Resources\KaryawanResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListKaryawan extends ListRecords
{
    protected static string $resource = KaryawanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New karyawan')
                ->color('warning'),
        ];
    }
}