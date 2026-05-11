<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BahanBakuResource\Pages;
use App\Models\BahanBaku;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BahanBakuResource extends Resource
{
    protected static ?string $model = BahanBaku::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Bahan Baku';
    protected static ?string $pluralModelLabel = 'Bahan Baku';
    protected static ?string $navigationGroup = 'Master Data'; // kelompokkan di sidebar

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Informasi Bahan Baku')
                ->columns(2)
                ->schema([

                    Forms\Components\TextInput::make('kode_bahan')
    ->label('Kode Bahan')
    ->default(fn () => \App\Models\BahanBaku::generateKodeBahan())  // ✅ UBAH INI
    ->disabled()
    ->dehydrated()
    ->required(),

                    Forms\Components\TextInput::make('nama_bahan')
                        ->label('Nama Bahan')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('satuan')
                        ->label('Satuan (kg/liter/pcs)')
                        ->required(),

                    Forms\Components\Select::make('kategori')
                        ->label('Kategori')
                        ->options([
                            'Bahan Pokok'   => 'Bahan Pokok',
                            'Bumbu'         => 'Bumbu',
                            'Kemasan'       => 'Kemasan',
                            'Bahan Tambahan'=> 'Bahan Tambahan',
                        ])
                        ->searchable(),

                    Forms\Components\TextInput::make('stok')
                        ->label('Stok Awal')
                        ->numeric()
                        ->required()
                        ->default(0),

                    Forms\Components\TextInput::make('stok_minimum')
                        ->label('Stok Minimum (alert)')
                        ->numeric()
                        ->required()
                        ->default(10),

                    Forms\Components\FileUpload::make('foto')
                        ->label('Foto Bahan')
                        ->image()
                        ->directory('bahan-baku')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular(),

                Tables\Columns\TextColumn::make('kode_bahan')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_bahan')
                    ->label('Nama Bahan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('satuan')
                    ->label('Satuan'),

                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge(),


                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable()
                    ->color(fn ($record) => $record->stok <= $record->stok_minimum ? 'danger' : 'success'),

                Tables\Columns\TextColumn::make('stok_minimum')
                    ->label('Min. Stok')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->options([
                        'Bahan Pokok'    => 'Bahan Pokok',
                        'Bumbu'          => 'Bumbu',
                        'Kemasan'        => 'Kemasan',
                        'Bahan Tambahan' => 'Bahan Tambahan',
                    ]),
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBahanBakus::route('/'),
            'create' => Pages\CreateBahanBaku::route('/create'),
            'edit'   => Pages\EditBahanBaku::route('/{record}/edit'),
        ];
    }
}