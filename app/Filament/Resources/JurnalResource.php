<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalResource\Pages;
use App\Models\Jurnal;
use App\Models\Akun;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class JurnalResource extends Resource
{
    protected static ?string $model = Jurnal::class;

    protected static ?string $navigationIcon  = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Jurnal Umum';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([

                    Step::make('Header Jurnal')
                        ->icon('heroicon-o-document-text')
                        ->description('Informasi dasar transaksi')
                        ->schema([
                            Section::make('Header Jurnal')
                                ->icon('heroicon-o-document-text')
                                ->description('Isi informasi dasar transaksi jurnal.')
                                ->schema([
                                    DatePicker::make('tanggal')
                                        ->label('Tanggal Transaksi')
                                        ->required()
                                        ->default(now())
                                        ->displayFormat('d/m/Y')
                                        ->native(false)
                                        ->columnSpan(1),

                                    TextInput::make('no_bukti')
                                        ->label('No. Referensi / Bukti')
                                        ->placeholder('Contoh: BKK-2026-001')
                                        ->maxLength(100)
                                        ->columnSpan(1),

                                    Textarea::make('keterangan')
                                        ->label('Keterangan / Memo')
                                        ->placeholder('Deskripsi singkat transaksi...')
                                        ->rows(2)
                                        ->columnSpan('full'),
                                ])
                                ->columns(2)
                                ->collapsible()
                                ->collapsed(false),
                        ]),

                    Step::make('Baris Jurnal')
                        ->icon('heroicon-o-table-cells')
                        ->description('Input debit & kredit')
                        ->schema([
                            Section::make('Baris Jurnal (Debit & Kredit)')
                                ->icon('heroicon-o-table-cells')
                                ->description('Minimal 2 baris. Total debit harus sama dengan total kredit.')
                                ->schema([
                                    Repeater::make('jurnaldetail')
                                        ->label('')
                                        ->relationship('jurnaldetail')
                                        ->schema([
                                            Select::make('akun')
                                                ->label('Akun')
                                                ->searchable()
                                                ->preload()
                                                ->options(
                                                    Akun::query()
                                                        ->orderBy('kode_akun')
                                                        ->get()
                                                        ->mapWithKeys(fn ($a) => [
                                                            $a->kode_akun => "[{$a->kode_akun}] {$a->nama_akun} — {$a->header_akun}",
                                                        ])
                                                )
                                                ->required()
                                                ->columnSpan(2),

                                            Textarea::make('deskripsi')
                                                ->label('Keterangan Baris')
                                                ->rows(1)
                                                ->placeholder('Opsional...')
                                                ->columnSpan(2),

                                            TextInput::make('debit')
                                                ->label('Debit (D)')
                                                ->helperText('Isi jika akun ini di sisi Debit')
                                                ->numeric()
                                                ->default(0)
                                                ->minValue(0)
                                                ->prefix('Rp')
                                                ->required()
                                                ->live(debounce: 500)
                                                ->afterStateUpdated(function ($state, Set $set) {
                                                    if ((float) $state > 0) {
                                                        $set('kredit', 0);
                                                    }
                                                })
                                                ->columnSpan(1),

                                            TextInput::make('kredit')
                                                ->label('Kredit (K)')
                                                ->helperText('Isi jika akun ini di sisi Kredit')
                                                ->numeric()
                                                ->default(0)
                                                ->minValue(0)
                                                ->prefix('Rp')
                                                ->required()
                                                ->live(debounce: 500)
                                                ->afterStateUpdated(function ($state, Set $set) {
                                                    if ((float) $state > 0) {
                                                        $set('debit', 0);
                                                    }
                                                })
                                                ->columnSpan(1),
                                        ])
                                        ->columns(6)
                                        ->minItems(2)
                                        ->defaultItems(2)
                                        ->addActionLabel('+ Tambah Baris')
                                        ->reorderableWithButtons()
                                        ->cloneable()
                                        ->live(),

                                    Section::make('Ringkasan')
                                        ->schema([
                                            Placeholder::make('total_debit_preview')
                                                ->label('Total Debit')
                                                ->content(function (Get $get) {
                                                    $total = collect($get('jurnaldetail') ?? [])
                                                        ->sum(fn ($row) => (float) ($row['debit'] ?? 0));
                                                    return new HtmlString(
                                                        '<span class="text-lg font-bold text-success-600">Rp ' .
                                                        number_format($total, 0, ',', '.') . '</span>'
                                                    );
                                                }),

                                            Placeholder::make('total_kredit_preview')
                                                ->label('Total Kredit')
                                                ->content(function (Get $get) {
                                                    $total = collect($get('jurnaldetail') ?? [])
                                                        ->sum(fn ($row) => (float) ($row['kredit'] ?? 0));
                                                    return new HtmlString(
                                                        '<span class="text-lg font-bold text-danger-600">Rp ' .
                                                        number_format($total, 0, ',', '.') . '</span>'
                                                    );
                                                }),

                                            Placeholder::make('selisih_preview')
                                                ->label('Selisih (harus Rp 0)')
                                                ->content(function (Get $get) {
                                                    $debit  = collect($get('jurnaldetail') ?? [])
                                                        ->sum(fn ($row) => (float) ($row['debit'] ?? 0));
                                                    $kredit = collect($get('jurnaldetail') ?? [])
                                                        ->sum(fn ($row) => (float) ($row['kredit'] ?? 0));
                                                    $selisih = $debit - $kredit;
                                                    $color   = $selisih === 0.0 ? 'text-success-600' : 'text-danger-600';
                                                    $icon    = $selisih === 0.0 ? '✔' : '✘';
                                                    return new HtmlString(
                                                        "<span class=\"text-lg font-bold {$color}\">{$icon} Rp " .
                                                        number_format(abs($selisih), 0, ',', '.') . '</span>'
                                                    );
                                                }),
                                        ])
                                        ->columns(3)
                                        ->collapsed(false),
                                ])
                                ->collapsible()
                                ->collapsed(false),
                        ]),

                ])
                ->skippable()
                ->columnSpanFull(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('no_bukti')
                    ->label('No. Bukti')
                    ->searchable()
                    ->copyable()
                    ->placeholder('-'),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->keterangan)
                    ->placeholder('-'),

                TextColumn::make('jumlah_baris')
                    ->label('Baris')
                    ->getStateUsing(fn ($record) => $record->jurnaldetail()->count() . ' baris')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('total_debit')
                    ->label('Total Debit')
                    ->getStateUsing(fn ($record) => $record->jurnaldetail()->sum('debit'))
                    ->money('IDR')
                    ->alignEnd()
                    ->color('success'),

                TextColumn::make('total_kredit')
                    ->label('Total Kredit')
                    ->getStateUsing(fn ($record) => $record->jurnaldetail()->sum('kredit'))
                    ->money('IDR')
                    ->alignEnd()
                    ->color('danger'),

                TextColumn::make('balance_status')
                    ->label('Balance')
                    ->getStateUsing(function ($record) {
                        $d = $record->jurnaldetail()->sum('debit');
                        $k = $record->jurnaldetail()->sum('kredit');
                        return $d == $k ? 'Balance' : 'Tidak Balance';
                    })
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Balance' ? 'success' : 'danger'),
            ])
            ->filters([
                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('dari')->label('Dari Tanggal')->native(false),
                        DatePicker::make('sampai')->label('Sampai Tanggal')->native(false),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['dari'],   fn ($q) => $q->whereDate('tanggal', '>=', $data['dari']))
                            ->when($data['sampai'], fn ($q) => $q->whereDate('tanggal', '<=', $data['sampai']));
                    }),

                Filter::make('tidak_balance')
                    ->label('Hanya Tidak Balance')
                    ->query(function (Builder $query) {
                        return $query->whereHas('jurnaldetail', function ($q) {
                            $q->selectRaw('jurnal_id')
                              ->groupBy('jurnal_id')
                              ->havingRaw('SUM(debit) != SUM(kredit)');
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tanggal', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListJurnals::route('/'),
            'create' => Pages\CreateJurnal::route('/create'),
            'edit'   => Pages\EditJurnal::route('/{record}/edit'),
        ];
    }
}