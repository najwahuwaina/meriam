<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AkunResource\Pages;
use App\Filament\Resources\AkunResource\RelationManagers;
use App\Models\Akun;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AkunResource extends Resource
{
    protected static ?string $model = Akun::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('kode_akun')
                ->label('Kode Akun')
                ->required()
                ->unique(ignoreRecord: true),
// test perubahan
            Forms\Components\TextInput::make('nama_akun')
                ->label('Nama Akun')
                ->required(),

            Forms\Components\Select::make('header_akun')
                ->label('Header Akun')
                ->options([
                    'Aset' => 'Aset',
                    'Liabilitas' => 'Liabilitas',
                    'Ekuitas' => 'Ekuitas',
                    'Pendapatan' => 'Pendapatan',
                    'Beban' => 'Beban',
                ])
                ->required(),
        ]);
}

   public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('kode_akun')
                ->label('Kode Akun')
                ->searchable(),
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
            'index' => Pages\ListAkuns::route('/'),
            'create' => Pages\CreateAkun::route('/create'),
            'edit' => Pages\EditAkun::route('/{record}/edit'),
        ];
    }
}
