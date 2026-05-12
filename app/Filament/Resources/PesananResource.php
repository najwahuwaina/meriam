<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesananResource\Pages;
use App\Mail\InvoicePesanan;
use App\Models\Menu;
use App\Models\Pesanan;

use Barryvdh\DomPDF\Facade\Pdf;

use Filament\Forms;
use Filament\Forms\Form;

use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;

use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Support\Facades\Mail;

class PesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Pesanan';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $maxContentWidth = 'Full';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Wizard::make([

                    Step::make('Pesanan')
                        ->schema([

                            Forms\Components\Select::make('id_pelanggan')
                                ->label('Pelanggan')
                                ->relationship('pelanggan', 'nama_pelanggan')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Forms\Components\Select::make('id_karyawan')
                                ->label('Karyawan')
                                ->relationship('karyawan', 'nama_karyawan')
                                ->searchable()
                                ->preload()
                                ->required(),

                            Forms\Components\DatePicker::make('tgl_pesanan')
                                ->label('Tanggal Pesanan')
                                ->required(),

                        ])
                        ->columns(2),

                    Step::make('Detail Pesanan')
                        ->schema([

                            Forms\Components\Repeater::make('detailPesanan')
                                ->relationship()

                                ->schema([

                                    Forms\Components\Select::make('id_menu')
                                        ->label('Menu')
                                        ->relationship('menu', 'nama_menu')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->reactive()

                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {

                                            $menu = Menu::find($state);

                                            if ($menu) {

                                                $jumlah = (int) ($get('jumlah') ?? 1);

                                                $subtotal = $menu->harga * $jumlah;

                                                $set('subtotal', $subtotal);

                                                $detailPesanan = $get('../../detailPesanan');

                                                $total = 0;

                                                if ($detailPesanan) {

                                                    foreach ($detailPesanan as $item) {

                                                        if (($item['id_menu'] ?? null) == $state) {

                                                            $total += $subtotal;

                                                        } else {

                                                            $total += (int) ($item['subtotal'] ?? 0);
                                                        }
                                                    }
                                                }

                                                $set('../../total_harga', $total);
                                            }
                                        }),

                                    Forms\Components\TextInput::make('jumlah')
                                        ->label('Jumlah')
                                        ->numeric()
                                        ->default(1)
                                        ->required()
                                        ->live()

                                        ->afterStateUpdated(function ($state, callable $set, callable $get) {

                                            $menu = Menu::find($get('id_menu'));

                                            if ($menu) {

                                                $jumlah = (int) $state;

                                                $subtotal = $menu->harga * $jumlah;

                                                $set('subtotal', $subtotal);

                                                $detailPesanan = $get('../../detailPesanan');

                                                $total = 0;

                                                if ($detailPesanan) {

                                                    foreach ($detailPesanan as $item) {

                                                        if (($item['id_menu'] ?? null) == $get('id_menu')) {

                                                            $total += $subtotal;

                                                        } else {

                                                            $total += (int) ($item['subtotal'] ?? 0);
                                                        }
                                                    }
                                                }

                                                $set('../../total_harga', $total);
                                            }
                                        }),

                                    Forms\Components\TextInput::make('subtotal')
                                        ->label('Subtotal')
                                        ->numeric()
                                        ->readOnly()
                                        ->dehydrated(),

                                ])

                                ->columns(3)

                                ->live()

                                ->addActionLabel('Tambah Menu')

                                ->afterStateUpdated(function ($state, callable $set) {

                                    $total = 0;

                                    if ($state) {

                                        foreach ($state as $item) {

                                            $total += (int) ($item['subtotal'] ?? 0);
                                        }
                                    }

                                    $set('total_harga', $total);
                                }),

                        ]),

                    Step::make('Pembayaran')
                        ->schema([

                            Forms\Components\TextInput::make('total_harga')
                                ->label('Total Harga')
                                ->numeric()
                                ->readOnly()
                                ->dehydrated()
                                ->default(0),

                            Forms\Components\Select::make('status')
                                ->label('Status Pembayaran')
                                ->options([
                                    'pending' => 'Pending',
                                    'paid' => 'Paid',
                                    'failed' => 'Failed',
                                ])
                                ->default('pending')
                                ->required(),

                        ]),

                ])
                    ->columnSpanFull(),

            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->headerActions([

                Tables\Actions\Action::make('downloadPdf')

                    ->label('Download PDF')

                    ->icon('heroicon-o-arrow-down-tray')

                    ->color('success')

                    ->action(function () {

                        $pesanan = Pesanan::with([
                            'pelanggan',
                            'karyawan',
                        ])->get();

                        $pdf = Pdf::loadView(
                            'pdf.pesanan-pdf',
                            compact('pesanan')
                        );

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'laporan-pesanan.pdf'
                        );
                    }),

            ])

            ->columns([

                Tables\Columns\TextColumn::make('id_pesanan')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pelanggan.nama_pelanggan')
                    ->label('Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('karyawan.nama_karyawan')
                    ->label('Karyawan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tgl_pesanan')
                    ->label('Tanggal Pesanan')
                    ->date(),

                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                    ]),

            ])

            ->actions([

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),

                Tables\Actions\Action::make('bayar')
                    ->label('Bayar')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->url(fn ($record) => route('payment', $record->id_pesanan))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('kirimInvoice')
                    ->label('Kirim Invoice')
                    ->icon('heroicon-o-envelope')
                    ->color('warning')

                    ->action(function ($record) {

                        Mail::to($record->pelanggan->email)
                            ->send(new InvoicePesanan($record));

                    })

                    ->requiresConfirmation()

                    ->successNotificationTitle(
                        'Invoice berhasil dikirim'
                    ),

            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPesanans::route('/'),
            'create' => Pages\CreatePesanan::route('/create'),
            'edit'   => Pages\EditPesanan::route('/{record}/edit'),
        ];
    }
}