<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use App\Models\Pesanan;
use App\Models\AiInsight;

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

                    $jumlahPesanan =
                        Pesanan::count();

                    $totalPendapatan =
                        Pesanan::sum('total_harga');

                    $prompt = "

                    Sistem penjualan ayam geprek.

                    Jumlah pesanan:
                    {$jumlahPesanan}

                    Total pendapatan:
                    Rp {$totalPendapatan}

                    Buat analisis bisnis singkat.
                    Sebutkan kondisi penjualan.
                    Berikan 3 rekomendasi untuk meningkatkan penjualan.

                    ";

                    $apiKey =
                        env('GEMINI_API_KEY');

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
                        ['content']['parts'][0]
                        ['text'];

                    AiInsight::create([

                        'hasil_analisis' => $hasil

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