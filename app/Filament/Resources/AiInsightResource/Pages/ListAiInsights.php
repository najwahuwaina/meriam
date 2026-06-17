<?php

namespace App\Filament\Resources\AiInsightResource\Pages;

use App\Filament\Resources\AiInsightResource;
use Filament\Resources\Pages\ListRecords;

class ListAiInsights extends ListRecords
{
    protected static string $resource =
        AiInsightResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}