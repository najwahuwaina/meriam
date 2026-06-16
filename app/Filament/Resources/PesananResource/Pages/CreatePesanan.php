<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use App\Models\Jurnal;
use App\Models\JurnalDetail;
use Filament\Resources\Pages\CreateRecord;

class CreatePesanan extends CreateRecord
{
    protected static string $resource = PesananResource::class;

    protected function afterCreate(): void
    {
        $pesanan = $this->record;

        $jurnal = Jurnal::create([
            'tanggal' => $pesanan->tgl_pesanan,
            'no_bukti' => 'PNJ-' . $pesanan->id_pesanan,
            'keterangan' => 'Penjualan Pesanan #' . $pesanan->id_pesanan,
        ]);

        // Debit Kas
        JurnalDetail::create([
            'jurnal_id' => $jurnal->id,
            'akun' => 'Kas',
            'debit' => $pesanan->total_harga,
            'kredit' => 0,
        ]);

        // Kredit Pendapatan
        JurnalDetail::create([
            'jurnal_id' => $jurnal->id,
            'akun' => 'Pendapatan Penjualan',
            'debit' => 0,
            'kredit' => $pesanan->total_harga,
        ]);
    }
}