<?php

namespace App\Filament\Resources\PembelianBahanResource\Pages;

use App\Filament\Resources\PembelianBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPembelianBahans extends ListRecords
{
    protected static string $resource = PembelianBahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
