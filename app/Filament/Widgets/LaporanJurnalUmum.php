<?php

namespace App\Filament\Widgets;

use App\Models\JurnalDetail;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LaporanJurnalUmum extends TableWidget
{
    protected static ?string $heading = 'Laporan Jurnal Umum';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                JurnalDetail::query()
                    ->with('jurnal')
            )

            ->columns([

                Tables\Columns\TextColumn::make('jurnal.tanggal')
                    ->label('Tanggal'),

                Tables\Columns\TextColumn::make('jurnal.no_bukti')
                    ->label('No Bukti'),

                Tables\Columns\TextColumn::make('jurnal.keterangan')
                    ->label('Keterangan'),

                Tables\Columns\TextColumn::make('akun'),

                Tables\Columns\TextColumn::make('debit')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('kredit')
                    ->money('IDR'),

            ])

            ->filters([

                Tables\Filters\Filter::make('tanggal')
                    ->form([

                        DatePicker::make('dari'),

                        DatePicker::make('sampai'),

                    ])

                    ->query(function ($query, array $data) {

                        return $query
                            ->when(
                                $data['dari'],
                                fn ($q) =>
                                $q->whereHas(
                                    'jurnal',
                                    fn ($x) =>
                                    $x->whereDate(
                                        'tanggal',
                                        '>=',
                                        $data['dari']
                                    )
                                )
                            )

                            ->when(
                                $data['sampai'],
                                fn ($q) =>
                                $q->whereHas(
                                    'jurnal',
                                    fn ($x) =>
                                    $x->whereDate(
                                        'tanggal',
                                        '<=',
                                        $data['sampai']
                                    )
                                )
                            );
                    })

            ]);
    }
}