<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggajianResource\Pages;
use App\Models\Penggajian;
use App\Models\Presensi;

use Barryvdh\DomPDF\Facade\Pdf;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Support\Collection;

class PenggajianResource extends Resource
{
    protected static ?string $model = Penggajian::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $maxContentWidth = 'full';

    public static function form(Form $form): Form
    {
        return $form->columns(1)->schema([
            Wizard::make([

                Wizard\Step::make('Data Penggajian')
                    ->icon('heroicon-m-document-text')
                    ->schema([
                        Section::make('Informasi Karyawan & Periode')
                            ->schema([
                                Select::make('id_karyawan')
                                    ->relationship('karyawan', 'nama_karyawan')
                                    ->required()
                                    ->label('Karyawan')
                                    ->searchable()
                                    ->preload(),

                                Select::make('bulan')
                                    ->options([
                                        1  => 'Januari',  2  => 'Februari', 3  => 'Maret',
                                        4  => 'April',    5  => 'Mei',      6  => 'Juni',
                                        7  => 'Juli',     8  => 'Agustus',  9  => 'September',
                                        10 => 'Oktober',  11 => 'November', 12 => 'Desember',
                                    ])
                                    ->required()
                                    ->label('Bulan'),

                                TextInput::make('tahun')
                                    ->numeric()
                                    ->required()
                                    ->label('Tahun')
                                    ->default(now()->year),

                                TextInput::make('gaji_per_hari')
                                    ->numeric()
                                    ->required()
                                    ->label('Gaji per Hari')
                                    ->prefix('Rp')
                                    ->live(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),

                Wizard\Step::make('Tunjangan')
                    ->icon('heroicon-m-gift')
                    ->schema([
                        Section::make('Komponen Tunjangan')
                            ->description('Isi tunjangan yang diterima karyawan pada periode ini.')
                            ->schema([
                                TextInput::make('tunjangan_transport')
                                    ->label('Tunjangan Transport')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('Rp')
                                    ->live(),

                                TextInput::make('tunjangan_makan')
                                    ->label('Tunjangan Makan')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('Rp')
                                    ->live(),

                                Placeholder::make('preview_total_tunjangan')
                                    ->label('Total Tunjangan')
                                    ->content(function ($get) {
                                        $total = ((float) $get('tunjangan_transport'))
                                               + ((float) $get('tunjangan_makan'));
                                        return 'Rp ' . number_format($total, 0, ',', '.');
                                    })
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),

                Wizard\Step::make('Ringkasan')
                    ->icon('heroicon-m-clipboard-document-check')
                    ->schema([
                        Section::make('Ringkasan Penggajian')
                            ->schema([
                                Placeholder::make('ringkasan_gaji_pokok')
                                    ->label('Gaji Pokok')
                                    ->content(function ($get) {
                                        $hadir = 0;
                                        if ($get('id_karyawan') && $get('bulan') && $get('tahun')) {
                                            $hadir = Presensi::where('id_karyawan', $get('id_karyawan'))
                                                ->whereMonth('tanggal', (int) $get('bulan'))
                                                ->whereYear('tanggal', (int) $get('tahun'))
                                                ->whereRaw('LOWER(status) = ?', ['hadir'])
                                                ->count();
                                        }
                                        $pokok = $hadir * ((float) $get('gaji_per_hari'));
                                        return 'Rp ' . number_format($pokok, 0, ',', '.');
                                    }),

                                Placeholder::make('ringkasan_tunjangan_transport')
                                    ->label('Tunjangan Transport')
                                    ->content(fn ($get) =>
                                        'Rp ' . number_format((float) $get('tunjangan_transport'), 0, ',', '.')
                                    ),

                                Placeholder::make('ringkasan_tunjangan_makan')
                                    ->label('Tunjangan Makan')
                                    ->content(fn ($get) =>
                                        'Rp ' . number_format((float) $get('tunjangan_makan'), 0, ',', '.')
                                    ),

                                Placeholder::make('ringkasan_total_tunjangan')
                                    ->label('Total Tunjangan')
                                    ->content(function ($get) {
                                        $total = ((float) $get('tunjangan_transport'))
                                               + ((float) $get('tunjangan_makan'));
                                        return 'Rp ' . number_format($total, 0, ',', '.');
                                    }),

                                Placeholder::make('ringkasan_total_gaji')
                                    ->label('💰 Total Gaji (Gaji Pokok + Tunjangan)')
                                    ->content(function ($get) {
                                        $hadir = 0;
                                        if ($get('id_karyawan') && $get('bulan') && $get('tahun')) {
                                            $hadir = Presensi::where('id_karyawan', $get('id_karyawan'))
                                                ->whereMonth('tanggal', (int) $get('bulan'))
                                                ->whereYear('tanggal', (int) $get('tahun'))
                                                ->whereRaw('LOWER(status) = ?', ['hadir'])
                                                ->count();
                                        }
                                        $pokok     = $hadir * ((float) $get('gaji_per_hari'));
                                        $tunjangan = ((float) $get('tunjangan_transport'))
                                                   + ((float) $get('tunjangan_makan'));
                                        return 'Rp ' . number_format($pokok + $tunjangan, 0, ',', '.');
                                    })
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),

            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.nama_karyawan')
                    ->label('Karyawan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('bulan')
                    ->formatStateUsing(function ($state) {
                        $bulan = [
                            1  => 'Januari',  2  => 'Februari', 3  => 'Maret',
                            4  => 'April',    5  => 'Mei',      6  => 'Juni',
                            7  => 'Juli',     8  => 'Agustus',  9  => 'September',
                            10 => 'Oktober',  11 => 'November', 12 => 'Desember',
                        ];
                        return $bulan[$state] ?? $state;
                    })
                    ->label('Bulan'),

                Tables\Columns\TextColumn::make('tahun')->label('Tahun'),
                Tables\Columns\TextColumn::make('jumlah_hadir')->label('Hadir'),
                Tables\Columns\TextColumn::make('jumlah_izin')->label('Izin'),
                Tables\Columns\TextColumn::make('jumlah_sakit')->label('Sakit'),
                Tables\Columns\TextColumn::make('jumlah_alpa')->label('Alpa'),

                Tables\Columns\TextColumn::make('tunjangan_transport')
                    ->label('T. Transport')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('tunjangan_makan')
                    ->label('T. Makan')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('total_gaji')
                    ->label('Total Gaji')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function ($record) {
                        $pdf = Pdf::loadView('pdf.slip-gaji', ['penggajian' => $record]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'slip-gaji-' . $record->karyawan->nama_karyawan . '.pdf'
                        );
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('export_semua_pdf')
                    ->label('Export PDF Semua')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function (Collection $records) {
                        $pdf = Pdf::loadView('pdf.semua-slip-gaji', ['records' => $records]);
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'semua-slip-gaji.pdf'
                        );
                    }),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPenggajians::route('/'),
            'create' => Pages\CreatePenggajian::route('/create'),
            'edit'   => Pages\EditPenggajian::route('/{record}/edit'),
        ];
    }
}