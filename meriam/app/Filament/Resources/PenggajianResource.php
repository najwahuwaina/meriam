<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenggajianResource\Pages;
use App\Models\Penggajian;
use App\Models\Presensi;

use Barryvdh\DomPDF\Facade\Pdf;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Support\Collection;

class PenggajianResource extends Resource
{
    protected static ?string $model = Penggajian::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('id_karyawan')
                    ->relationship('karyawan', 'nama_karyawan')
                    ->required()
                    ->label('Karyawan'),

                Forms\Components\Select::make('bulan')
                    ->options([
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ])
                    ->required()
                    ->label('Bulan'),

                Forms\Components\TextInput::make('tahun')
                    ->numeric()
                    ->required()
                    ->label('Tahun'),

                Forms\Components\TextInput::make('gaji_per_hari')
                    ->numeric()
                    ->required()
                    ->label('Gaji per Hari'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('karyawan.nama_karyawan')
                    ->label('Karyawan'),

                Tables\Columns\TextColumn::make('bulan')
                    ->formatStateUsing(function ($state) {

                        $bulan = [
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ];

                        return $bulan[$state] ?? $state;
                    })
                    ->label('Bulan'),

                Tables\Columns\TextColumn::make('tahun')
                    ->label('Tahun'),

                Tables\Columns\TextColumn::make('jumlah_hadir')
                    ->label('Jumlah Hadir'),

                Tables\Columns\TextColumn::make('jumlah_izin')
                    ->label('Izin'),

                Tables\Columns\TextColumn::make('jumlah_sakit')
                    ->label('Sakit'),

                Tables\Columns\TextColumn::make('jumlah_alpa')
                    ->label('Alpa'),

                Tables\Columns\TextColumn::make('total_gaji')
                    ->money('IDR')
                    ->label('Total Gaji'),

            ])

            ->actions([

                Tables\Actions\Action::make('pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')

                    ->action(function ($record) {

                        $pdf = Pdf::loadView(
                            'pdf.slip-gaji',
                            [
                                'penggajian' => $record
                            ]
                        );

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

                        $pdf = Pdf::loadView(
                            'pdf.semua-slip-gaji',
                            [
                                'records' => $records
                            ]
                        );

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'semua-slip-gaji.pdf'
                        );
                    }),

                Tables\Actions\DeleteBulkAction::make(),

            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        // Hitung jumlah hadir
        $jumlahHadir = Presensi::where('id_karyawan', $data['id_karyawan'])
            ->whereMonth('tanggal', (int) $data['bulan'])
            ->whereYear('tanggal', (int) $data['tahun'])
            ->whereRaw('LOWER(status) = ?', ['hadir'])
            ->count();

        // Hitung jumlah izin
        $jumlahIzin = Presensi::where('id_karyawan', $data['id_karyawan'])
            ->whereMonth('tanggal', (int) $data['bulan'])
            ->whereYear('tanggal', (int) $data['tahun'])
            ->whereRaw('LOWER(status) = ?', ['izin'])
            ->count();

        // Hitung jumlah sakit
        $jumlahSakit = Presensi::where('id_karyawan', $data['id_karyawan'])
            ->whereMonth('tanggal', (int) $data['bulan'])
            ->whereYear('tanggal', (int) $data['tahun'])
            ->whereRaw('LOWER(status) = ?', ['sakit'])
            ->count();

        // Hitung jumlah alpa
        $jumlahAlpa = Presensi::where('id_karyawan', $data['id_karyawan'])
            ->whereMonth('tanggal', (int) $data['bulan'])
            ->whereYear('tanggal', (int) $data['tahun'])
            ->whereRaw('LOWER(status) = ?', ['alpa'])
            ->count();

        // Simpan hasil
        $data['jumlah_hadir'] = $jumlahHadir;
        $data['jumlah_izin'] = $jumlahIzin;
        $data['jumlah_sakit'] = $jumlahSakit;
        $data['jumlah_alpa'] = $jumlahAlpa;

        // Hitung total gaji otomatis
        $data['total_gaji'] = $jumlahHadir * $data['gaji_per_hari'];

        return $data;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenggajians::route('/'),
            'create' => Pages\CreatePenggajian::route('/create'),
            'edit' => Pages\EditPenggajian::route('/{record}/edit'),
        ];
    }
}