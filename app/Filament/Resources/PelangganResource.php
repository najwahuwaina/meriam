<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelangganResource\Pages;
use App\Filament\Resources\PelangganResource\RelationManagers;
use App\Models\Pelanggan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;
    protected static ?string $navigationLabel = 'Pelanggan';
    protected static ?int $navigationSort = 2;
    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
    Forms\Components\TextInput::make('nama_pelanggan')
        ->label('Nama Pelanggan')
        ->required(),

    Forms\Components\TextInput::make('no_telp')
        ->label('No Telepon')
        ->required(),

    Forms\Components\Textarea::make('alamat')
        ->label('Alamat')
        ->required(),
]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
    Tables\Columns\TextColumn::make('id_pelanggan')
        ->label('ID'),

    Tables\Columns\TextColumn::make('nama_pelanggan')
        ->searchable(),

    Tables\Columns\TextColumn::make('no_telp'),

    Tables\Columns\TextColumn::make('alamat')
        ->limit(30),
])
            ->filters([
                //
            ])
            ->actions([
    Tables\Actions\EditAction::make(),
    Tables\Actions\DeleteAction::make(),
])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }
}
