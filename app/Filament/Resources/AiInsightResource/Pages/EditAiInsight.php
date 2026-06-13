<?php

namespace App\Filament\Resources\AiInsightResource\Pages;

use App\Filament\Resources\AiInsightResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAiInsight extends EditRecord
{
    protected static string $resource = AiInsightResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
