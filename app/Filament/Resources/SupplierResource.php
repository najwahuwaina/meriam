<?php

namespace App\Filament\Resources;

use App\Models\Supplier;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\SupplierResource\Pages;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Supplier';
<<<<<<< HEAD
    protected static ?string $navigationGroup = 'Masterdata';
=======

    // tambahan buat grup masterdata
    protected static ?string $navigationGroup = 'Master Data';

    public static function canViewAny(): bool
    {
        return true;
    }
>>>>>>> d2eb4235d9082af4dca34dcd4ccf71007c933b38

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_supplier')
                    ->label('Nama Supplier')
                    ->required(),

                Forms\Components\TextInput::make('alamat')
                    ->label('Alamat'),

                Forms\Components\TextInput::make('telepon')
                    ->label('Telepon'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_supplier')
                    ->label('Nama Supplier')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('alamat')
                    ->label('Alamat'),

                Tables\Columns\TextColumn::make('telepon')
                    ->label('Telepon'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupplier::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
