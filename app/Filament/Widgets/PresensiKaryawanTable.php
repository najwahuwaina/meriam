<?php

namespace App\Filament\Widgets;

use App\Models\Presensi;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PresensiKaryawanTable extends BaseWidget
{
    protected static ?string $heading = '👨‍💼 Presensi Karyawan Hari Ini';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $pollingInterval = '30s';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Presensi::query()
                    ->whereDate('tanggal', today())
            )
            ->striped()
            ->defaultSort('jam_masuk', 'desc')
            ->columns([

                Tables\Columns\TextColumn::make('karyawan.nama_karyawan')
                    ->label('Karyawan')
                    ->icon('heroicon-m-user')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('jam_masuk')
                    ->label('Jam Masuk')
                    ->icon('heroicon-m-arrow-right-circle')
                    ->time('H:i')
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jam_keluar')
                    ->label('Jam Keluar')
                    ->icon('heroicon-m-arrow-left-circle')
                    ->time('H:i')
                    ->color('danger')
                    ->placeholder('-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Izin' => 'warning',
                        'Sakit' => 'info',
                        'Alpha' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'Hadir' => 'heroicon-m-check-circle',
                        'Izin' => 'heroicon-m-clock',
                        'Sakit' => 'heroicon-m-heart',
                        'Alpha' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    }),

            ]);
    }
}