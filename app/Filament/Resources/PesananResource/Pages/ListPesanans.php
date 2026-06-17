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

                    $jumlahPesanan = Pesanan::count();

                    $totalPendapatan = Pesanan::sum('total_harga');

                    $prompt = "

                        Anda adalah analis bisnis profesional untuk restoran ayam geprek.

                        Data Penjualan:

                        Jumlah Pesanan:
                        {$jumlahPesanan}

                        Total Pendapatan:
                        Rp {$totalPendapatan}

                        Buat laporan yang rapi dan mudah dibaca dengan format berikut:

                        📊 ANALISIS PENJUALAN AI

                        📌 Ringkasan
                        (Jelaskan kondisi penjualan secara singkat)

                        📈 Kondisi Penjualan
                        (Jelaskan kondisi penjualan saat ini berdasarkan data)

                        💡 Rekomendasi
                        1. ...
                        2. ...
                        3. ...

                        ✅ Kesimpulan
                        (Berikan kesimpulan singkat)

                        Gunakan bahasa Indonesia yang formal dan profesional.

                        Jangan gunakan markdown seperti:
                        ###, **, ---, atau simbol teknis lainnya.

                        Buat hasil terlihat seperti laporan bisnis yang siap dibaca pemilik usaha.

                        ";

                    $apiKey = env('GEMINI_API_KEY');

                    $response = Http::post(
                        "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                        [
                            'contents' => [
                                [
                                    'parts' => [
                                        [
                                            'text' => $prompt,
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    );

                    $data = $response->json();

                    if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {

                        Notification::make()
                            ->title('Gagal menghubungi Gemini AI')
                            ->body('Gemini AI sedang sibuk. Silakan coba lagi beberapa menit.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $hasil =
                        $data['candidates'][0]['content']['parts'][0]['text'];

                    AiInsight::create([
                        'hasil_analisis' => $hasil,
                    ]);

                    Notification::make()
                        ->title('Analisis AI berhasil dibuat')
                        ->success()
                        ->send();
                }),

        ];
    }
}