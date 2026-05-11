<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PresensiResource\Pages;
use App\Models\Presensi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Exports\PresensiExporter;

class PresensiResource extends Resource
{
    protected static ?string $model = Presensi::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Manajemen Karyawan';
    protected static ?string $navigationLabel = 'Presensi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_karyawan')
                    ->relationship('karyawan', 'nama_karyawan')
                    ->required()
                    ->label('Karyawan'),
                Forms\Components\DatePicker::make('tanggal')->required(),
                Forms\Components\TimePicker::make('jam_masuk'),
                Forms\Components\TimePicker::make('jam_keluar'),
                Forms\Components\Select::make('status')
                    ->options([
                        'Hadir' => 'Hadir',
                        'Izin' => 'Izin',
                        'Sakit' => 'Sakit',
                        'Alpa' => 'Alpa',
                    ])
                    ->default('Hadir'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_presensi')->sortable(),
                Tables\Columns\TextColumn::make('karyawan.nama_karyawan')->label('Karyawan')->searchable(),
                Tables\Columns\TextColumn::make('tanggal')->date(),
                Tables\Columns\TextColumn::make('jam_masuk'),
                Tables\Columns\TextColumn::make('jam_keluar'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'Hadir',
                        'warning' => 'Izin',
                        'danger' => 'Alpa',
                        'info' => 'Sakit',
                    ]),
            ])
            ->headerActions([
                ExportAction::make()->exporter(PresensiExporter::class),
            ])
            ->bulkActions([
                ExportBulkAction::make()->exporter(PresensiExporter::class),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPresensis::route('/'),
            'create' => Pages\CreatePresensi::route('/create'),
            'edit' => Pages\EditPresensi::route('/{record}/edit'),
        ];
    }
}
