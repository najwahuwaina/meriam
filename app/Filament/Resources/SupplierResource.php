<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// untuk form dan table
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    // merubah nama label menjadi Supplier
    protected static ?string $navigationLabel = 'Supplier';

    // tambahan buat grup masterdata
    protected static ?string $navigationGroup = 'Masterdata';

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('kode_supplier')
                    ->default(fn () => Supplier::getKodeSupplier())
                    ->label('Kode Supplier')
                    ->required()
                    ->readonly(),

                TextInput::make('nama_supplier')
                    ->label('Nama Supplier')
                    ->required()
                    ->placeholder('Masukkan nama supplier'),

                TextInput::make('alamat_supplier')
                    ->label('Alamat Supplier')
                    ->required()
                    ->placeholder('Masukkan alamat supplier'),

                TextInput::make('no_telp')
                    ->label('No Telepon')
                    ->required()
                    ->placeholder('Masukkan nomor telepon')
                    ->numeric()
                    ->prefix('+62')
                    ->extraAttributes([
                        'pattern' => '^[0-9]+$',
                        'title' => 'Masukkan angka yang diawali dengan 0'
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_supplier')
                    ->label('Kode Supplier')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_supplier')
                    ->label('Nama Supplier')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('alamat_supplier')
                    ->label('Alamat Supplier')
                    ->searchable(),

                TextColumn::make('no_telp')
                    ->label('No Telepon')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}