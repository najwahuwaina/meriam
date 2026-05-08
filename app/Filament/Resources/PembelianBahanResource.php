<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembelianBahanResource\Pages;
use App\Models\PembelianBahan;
use App\Models\BahanBaku;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Exports\PembelianBahanExporter;

class PembelianBahanResource extends Resource
{
    protected static ?string $model = PembelianBahan::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Pembelian Bahan';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $maxContentWidth = 'full';

    public static function form(Form $form): Form
    {
        return $form->columns(1)->schema([
            Wizard::make([
                Wizard\Step::make('Data Pembelian')
                    ->icon('heroicon-m-document-text')
                    ->schema([
                        Section::make('Informasi Pembelian')
                            ->schema([
                                TextInput::make('kode_pembelian')
                                    ->label('Kode Pembelian')
                                    ->default(fn () => PembelianBahan::generateKode())
                                    ->required()
                                    ->readOnly(),

                                DatePicker::make('tanggal')
                                    ->default(today())
                                    ->required(),

                                Select::make('bahan_baku_id')
                                    ->label('Bahan Baku')
                                    ->options(BahanBaku::pluck('nama_bahan', 'id')->toArray())
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                TextInput::make('jumlah')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live(),

                                TextInput::make('harga_beli')
                                    ->label('Harga Beli')
                                    ->numeric()
                                    ->required()
                                    ->prefix('Rp')
                                    ->live(),

                                Placeholder::make('total_harga_view')
                                    ->label('Total Harga')
                                    ->content(function ($get) {
                                        $total = ((float) $get('jumlah')) * ((float) $get('harga_beli'));
                                        return 'Rp ' . number_format($total, 0, ',', '.');
                                    }),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),

                Wizard\Step::make('Pembayaran')
                    ->icon('heroicon-m-banknotes')
                    ->schema([
                        Section::make('Detail Pembayaran')
                            ->schema([
                                Select::make('status_pembayaran')
                                    ->label('Status Pembayaran')
                                    ->options([
                                        'belum_bayar' => 'Belum Bayar',
                                        'sebagian' => 'Sebagian',
                                        'lunas' => 'Lunas',
                                    ])
                                    ->default('belum_bayar')
                                    ->required()
                                    ->live(),

                                Select::make('metode_pembayaran')
                                    ->label('Metode Pembayaran')
                                    ->options([
                                        'cash' => 'Cash',
                                        'debit' => 'Debit',
                                        'kredit' => 'Kredit',
                                    ])
                                    ->visible(fn ($get) => $get('status_pembayaran') !== 'belum_bayar'),

                                TextInput::make('dibayar')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('Rp')
                                    ->visible(fn ($get) => $get('status_pembayaran') !== 'belum_bayar')
                                    ->live(),

                                DatePicker::make('jatuh_tempo')
                                    ->visible(fn ($get) => $get('metode_pembayaran') === 'kredit'),

                                FileUpload::make('foto_struk')
                                    ->label('Foto Struk')
                                    ->image()
                                    ->disk('public')
                                    ->directory('struk-pembelian')
                                    ->visibility('public')
                                    ->nullable()
                                    ->preserveFilenames()
                                    ->columnSpanFull(),

                                TextInput::make('supplier')
                                    ->maxLength(255),

                                Textarea::make('keterangan')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->collapsible()
                            ->columnSpanFull(),
                    ]),

                Wizard\Step::make('Ringkasan')
                    ->icon('heroicon-m-clipboard-document-check')
                    ->schema([
                        Section::make('Ringkasan Pembayaran')
                            ->schema([
                                Placeholder::make('ringkasan_total')
                                    ->label('Total Harga')
                                    ->content(function ($get) {
                                        $total = ((float) $get('jumlah')) * ((float) $get('harga_beli'));
                                        return 'Rp ' . number_format($total, 0, ',', '.');
                                    }),

                                Placeholder::make('ringkasan_dibayar')
                                    ->label('Dibayar')
                                    ->content(function ($get) {
                                        return 'Rp ' . number_format((float) ($get('dibayar') ?? 0), 0, ',', '.');
                                    }),

                                Placeholder::make('ringkasan_sisa')
                                    ->label('Sisa Pembayaran')
                                    ->content(function ($get) {
                                        $total = ((float) $get('jumlah')) * ((float) $get('harga_beli'));
                                        $dibayar = (float) ($get('dibayar') ?? 0);
                                        return 'Rp ' . number_format($total - $dibayar, 0, ',', '.');
                                    }),
                            ])
                            ->columns(3)
                            ->columnSpanFull(),
                    ]),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('kode_pembelian')
                ->label('Kode')
                ->searchable(),

            TextColumn::make('bahanBaku.nama_bahan')
                ->label('Bahan Baku')
                ->searchable(),

            TextColumn::make('jumlah')
                ->sortable(),

            TextColumn::make('total_harga')
                ->label('Total')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                ->sortable()
                ->alignment('end'),

            TextColumn::make('dibayar')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                ->alignment('end'),

            TextColumn::make('sisa')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                ->color(fn ($state) => $state > 0 ? 'danger' : 'success')
                ->alignment('end'),

            TextColumn::make('foto_struk')
                ->label('Struk')
                ->formatStateUsing(fn ($state) => $state ? 'Ada' : 'Tidak Ada'),

            TextColumn::make('status_pembayaran')
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'lunas' => 'success',
                    'sebagian' => 'warning',
                    default => 'danger',
                }),

            TextColumn::make('tanggal')
                ->date(),
        ])
        ->filters([
            SelectFilter::make('status_pembayaran')
                ->options([
                    'belum_bayar' => 'Belum Bayar',
                    'sebagian' => 'Sebagian',
                    'lunas' => 'Lunas',
                ]),
        ])
        ->headerActions([
            ExportAction::make()->exporter(PembelianBahanExporter::class),
        ])
        ->actions([
            Action::make('export_pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->url(fn (PembelianBahan $record) => route('export.pembelian-bahan.pdf', $record->id))
                ->openUrlInNewTab(),
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
                ExportBulkAction::make()->exporter(PembelianBahanExporter::class),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelianBahans::route('/'),
            'create' => Pages\CreatePembelianBahan::route('/create'),
            'edit' => Pages\EditPembelianBahan::route('/{record}/edit'),
        ];
    }
}