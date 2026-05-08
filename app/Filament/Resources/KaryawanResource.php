<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KaryawanResource\Pages;
use App\Models\Karyawan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KaryawanResource extends Resource
{
    protected static ?string $model = Karyawan::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Karyawan';

    // ✅ FORM
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_karyawan')
                    ->label('Nama Karyawan')
                    ->required(),

                Forms\Components\TextInput::make('no_telp')
                    ->label('No Telepon'),

                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat'),

                Forms\Components\TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->required(),

                Forms\Components\FileUpload::make('foto_ektp')
                    ->label('Foto e-KTP')
                    ->image()
                    ->directory('foto_ektp')
                    ->required()
                    ->rules('mimes:jpg,png'),
            ]);
    }

    // ✅ TABLE
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_karyawan')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('nama_karyawan')->label('Nama Karyawan')->searchable(),
                Tables\Columns\TextColumn::make('no_telp')->label('No Telepon'),
                Tables\Columns\TextColumn::make('alamat')->label('Alamat')->limit(30),
                Tables\Columns\TextColumn::make('jabatan')->label('Jabatan'),
                Tables\Columns\TextColumn::make('tanggal_lahir')->label('Tanggal Lahir')->date(),
                Tables\Columns\ImageColumn::make('foto_ektp')->label('Foto e-KTP'),
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

    // ✅ PAGES
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKaryawan::route('/'),
            'create' => Pages\CreateKaryawan::route('/create'),
            'edit' => Pages\EditKaryawan::route('/{record}/edit'),
        ];
    }
}
