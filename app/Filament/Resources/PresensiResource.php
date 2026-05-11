<?php

namespace App\Filament\Resources;

use App\Models\Presensi;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\PresensiResource\Pages;
use App\Filament\Exports\PresensiExporter;

class PresensiResource extends Resource
{
    protected static ?string $model = Presensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
<<<<<<< HEAD
=======
    protected static ?string $navigationGroup = 'Transaksi';
>>>>>>> 4897bcbacaea23ed3e0292787bb48900fd92faa7
    protected static ?string $navigationLabel = 'Presensi';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_karyawan')
                    ->relationship('karyawan', 'nama_karyawan')
                    ->label('Karyawan')
                    ->required(),

                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required(),

                Forms\Components\TimePicker::make('jam_masuk')
                    ->label('Jam Masuk')
                    ->required(),

                Forms\Components\TimePicker::make('jam_keluar')
                    ->label('Jam Keluar')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'Hadir' => 'Hadir',
                        'Izin' => 'Izin',
                        'Sakit' => 'Sakit',
                        'Alpa' => 'Alpa',
                    ])
                    ->default('Hadir')
                    ->required(),

                Forms\Components\FileUpload::make('surat_sakit')
                    ->label('Foto Surat Sakit')
                    ->image()
                    ->directory('surat_sakit')
                    ->rules('mimes:jpg,png,pdf')
                    ->visible(fn (callable $get) => $get('status') === 'Sakit')
                    ->required(fn (callable $get) => $get('status') === 'Sakit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('karyawan.nama_karyawan')
                    ->label('Karyawan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal')->date(),
                Tables\Columns\TextColumn::make('jam_masuk'),
                Tables\Columns\TextColumn::make('jam_keluar'),

                // ✅ Status dengan badge berwarna
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'Hadir',   // hijau
                        'warning' => 'Izin',    // kuning
                        'info'    => 'Sakit',   // biru
                        'danger'  => 'Alpa',    // merah
                    ]),

                Tables\Columns\ImageColumn::make('surat_sakit')
                    ->label('Surat Sakit')
                    ->visible(fn ($record) => $record?->status === 'Sakit'),

                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPresensi::route('/'),
            'create' => Pages\CreatePresensi::route('/create'),
            'edit' => Pages\EditPresensi::route('/{record}/edit'),
        ];
    }

    public static function getExporters(): array
    {
        return [
            PresensiExporter::class,
        ];
    }
}
