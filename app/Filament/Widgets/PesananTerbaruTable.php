<?php

namespace App\Filament\Widgets;

use App\Models\Pesanan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PesananTerbaruTable extends BaseWidget
{
    protected static ?string $heading = '🛍️ Pesanan Terbaru';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $pollingInterval = '30s';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pesanan::query()
                    ->latest()
                    ->limit(5)
            )
            ->striped()
            ->defaultSort('tgl_pesanan', 'desc')
            ->columns([

                Tables\Columns\TextColumn::make('id_pesanan')
                    ->label('ID Pesanan')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('pelanggan.nama_pelanggan')
                    ->label('Pelanggan')
                    ->icon('heroicon-m-user')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR')
                    ->icon('heroicon-m-banknotes')
                    ->color('success')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'Pending',
                        'info' => 'Diproses',
                        'success' => 'Selesai',
                        'danger' => 'Dibatalkan',
                    ])
                    ->icons([
                        'heroicon-m-clock' => 'Pending',
                        'heroicon-m-cog-6-tooth' => 'Diproses',
                        'heroicon-m-check-circle' => 'Selesai',
                        'heroicon-m-x-circle' => 'Dibatalkan',
                    ]),

                Tables\Columns\TextColumn::make('tgl_pesanan')
                    ->label('Waktu Pesan')
                    ->dateTime('d M Y H:i')
                    ->icon('heroicon-m-calendar-days')
                    ->color('gray')
                    ->sortable(),

            ]);
    }
}