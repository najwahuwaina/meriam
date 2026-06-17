<?php

namespace App\Filament\Widgets;

use App\Models\BahanBaku;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class StokMenipisTable extends BaseWidget
{
    protected static ?string $heading = '⚠️ Stok Bahan Baku Menipis';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $pollingInterval = '30s';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                BahanBaku::query()
                    ->whereColumn('stok', '<=', 'stok_minimum')
                    ->orderBy('stok')
            )
            ->striped()
            ->columns([

                Tables\Columns\TextColumn::make('nama_bahan')
                    ->label('Nama Bahan')
                    ->icon('heroicon-m-cube')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok Saat Ini')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state <= 5 => 'danger',
                        $state <= 10 => 'warning',
                        default => 'success',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('stok_minimum')
                    ->label('Stok Minimum')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('satuan')
                    ->label('Satuan')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('persentase_stok')
                    ->label('Kondisi')
                    ->state(function ($record) {
                        if ($record->stok_minimum == 0) {
                            return '0%';
                        }

                        return round(
                            ($record->stok / $record->stok_minimum) * 100
                        ) . '%';
                    })
                    ->badge()
                    ->color(function ($record) {
                        $persen = $record->stok_minimum > 0
                            ? ($record->stok / $record->stok_minimum) * 100
                            : 0;

                        return match (true) {
                            $persen <= 50 => 'danger',
                            $persen <= 80 => 'warning',
                            default => 'success',
                        };
                    }),

            ]);
    }
}