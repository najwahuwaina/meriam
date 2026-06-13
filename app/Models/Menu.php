<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use App\Models\Pesanan;
use App\Models\AiInsight;
use App\Models\DetailPesanan;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

use Illuminate\Support\Facades\Http;

class ListPesanans extends ListRecords
{
    protected static string $resource = PesananResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\CreateAction::make(),

            Actions\Action::make('analisisAI')

                ->label('Analisis AI')

                ->icon('heroicon-o-sparkles')

                ->color('success')

                ->requiresConfirmation()

                ->action(function () {

                    $jumlahPesanan = Pesanan::count();

                    $totalPendapatan = Pesanan::sum(
                        'total_harga'
                    );

                    $menuTerlaris =
                        DetailPesanan::selectRaw(
                            'id_menu, SUM(jumlah) as total_terjual'
                        )
                        ->with('menu')
                        ->groupBy('id_menu')
                        ->orderByDesc('total_terjual')
                        ->first();

                    $namaMenu =
                        $menuTerlaris?->menu?->nama_menu
                        ?? 'Belum ada data';

                    $totalTerjual =
                        $menuTerlaris?->total_terjual
                        ?? 0;

                    $prompt = "

                    Anda adalah analis bisnis restoran ayam geprek.

                    Data penjualan:

                    Jumlah pesanan:
                    {$jumlahPesanan}

                    Total pendapatan:
                    Rp {$totalPendapatan}

                    Menu terlaris:
                    {$namaMenu}

                    Jumlah terjual:
                    {$totalTerjual} porsi

                    Buat analisis bisnis singkat.

                    Berikan:

                    1. Kondisi penjualan saat ini.
                    2. Analisis menu terlaris.
                    3. Tiga rekomendasi untuk meningkatkan penjualan.

                    Gunakan bahasa Indonesia yang formal.

                    ";

                    $apiKey = env(
                        'GEMINI_API_KEY'
                    );

                    $response = Http::post(
                        "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                        [
                            'contents' => [
                                [
                                    'parts' => [
                                        [
                                            'text' => $prompt
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    );

                    $hasil =
                        $response->json()
                        ['candidates'][0]
                        ['content'][0] ?? null;

                    if (!$hasil) {

                        $hasil =
                            $response->json()
                            ['candidates'][0]
                            ['content']['parts'][0]
                            ['text']
                            ?? 'Analisis gagal dibuat.';
                    }

                    AiInsight::create([

                        'hasil_analisis' => $hasil,

                    ]);

                    Notification::make()

                        ->title(
                            'Analisis AI berhasil dibuat'
                        )

                        ->success()

                        ->send();
                }),

        ];
    }
}