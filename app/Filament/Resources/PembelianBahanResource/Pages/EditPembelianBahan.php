<?php

namespace App\Filament\Resources\PembelianBahanResource\Pages;

use App\Filament\Resources\PembelianBahanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembelianBahan extends EditRecord
{
    protected static string $resource = PembelianBahanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
