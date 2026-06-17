<?php

namespace App\Filament\Resources;

use App\Models\BukuBesar;
use App\Filament\Resources\BukuBesarResource\Pages;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;

class BukuBesarResource extends Resource
{
    protected static ?string $model = BukuBesar::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Laporan Buku Besar';
    protected static ?string $navigationGroup = 'Laporan';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([])
            ->filters([])
            ->actions([])
            ->bulkActions([])
            ->paginated(false);
    }

    public static function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\BukuBesar::class,
        ];
    }

    public static function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\BukuBesar::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBukuBesars::route('/'),
        ];
    }
}
