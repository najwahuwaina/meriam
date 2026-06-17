<?php

namespace App\Filament\Widgets;

use App\Models\PembelianBahan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PembelianTerakhirTable extends BaseWidget
{
    protected static ?string $heading = '🛒 Pembelian Bahan Terakhir';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PembelianBahan::query()->latest()->limit(5)
            )
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->columns([

                Tables\Columns\TextColumn::make('kode_pembelian')
                    ->label('Kode')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('bahanBaku.nama_bahan')
                    ->label('Nama Bahan')
                    ->icon('heroicon-m-cube')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 100 => 'success',
                        $state >= 50 => 'warning',
                        default => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pembelian')
                    ->dateTime('d M Y H:i')
                    ->icon('heroicon-m-calendar-days')
                    ->color('gray'),

            ]);
    }
}