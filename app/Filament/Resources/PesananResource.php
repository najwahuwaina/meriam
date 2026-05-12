<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesananResource\Pages;
use App\Models\Pesanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Pesanan';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('id_pelanggan')
                ->label('Pelanggan')
                ->relationship('pelanggan', 'nama_pelanggan')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Select::make('id_karyawan')
                ->label('Karyawan')
                ->relationship('karyawan', 'nama_karyawan')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\DatePicker::make('tgl_pesanan')
                ->label('Tanggal Pesanan')
                ->required(),

            Forms\Components\TextInput::make('total_harga')
                ->label('Total Harga')
                ->numeric()
                ->default(0)
                ->disabled()
                ->dehydrated(),

            Forms\Components\Repeater::make('detailPesanan')
                ->relationship()
                ->schema([
                    Forms\Components\Select::make('id_menu')
                        ->label('Menu')
                        ->relationship('menu', 'nama_menu')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $menu = \App\Models\Menu::find($state);
                            if ($menu) {
                                $set('harga', $menu->harga);
                            }
                        }),

                    Forms\Components\TextInput::make('harga')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->required(),

                    Forms\Components\TextInput::make('jumlah')
                        ->numeric()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $harga = $get('harga') ?? 0;
                            $set('subtotal', $harga * $state);
                        }),

                    Forms\Components\TextInput::make('subtotal')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->required(),
                ])
                ->columns(4)
                ->createItemButtonLabel('Tambah Menu')
                ->live()
                ->afterStateUpdated(function ($state, callable $set) {
                    $total = 0;

                    foreach ($state ?? [] as $item) {
                        $total += (int) ($item['subtotal'] ?? 0);
                    }

                    $set('total_harga', $total);
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_pesanan')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pelanggan.nama_pelanggan')
                    ->label('Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('karyawan.nama_karyawan')
                    ->label('Karyawan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tgl_pesanan')
                    ->label('Tanggal')
                    ->date(),

                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesanans::route('/'),
            'create' => Pages\CreatePesanan::route('/create'),
            'edit' => Pages\EditPesanan::route('/{record}/edit'),
        ];
    }
}