<?php

namespace App\Filament\Resources\JurnalResource\Pages;

use App\Filament\Resources\JurnalResource;
use App\Filament\Widgets\LaporanJurnalUmum;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJurnals extends ListRecords
{
    protected static string $resource = JurnalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('export_pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->url(route('jurnal.pdf.transaksi'))
                ->openUrlInNewTab(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            LaporanJurnalUmum::class,
        ];
    }
}