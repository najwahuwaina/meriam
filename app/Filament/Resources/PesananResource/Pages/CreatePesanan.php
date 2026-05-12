<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Pembayaran;

class CreatePesanan extends CreateRecord
{
    protected static string $resource = PesananResource::class;

    protected function afterCreate(): void
    {
        $pesanan = $this->record->load('detailPesanan');

        $subtotal = $pesanan->detailPesanan->sum('subtotal');
        $ppn = $subtotal * 0.11;
        $total = $subtotal + $ppn;

        $pesanan->update([
            'total_harga' => $subtotal,
        ]);

        Pembayaran::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'tgl_bayar' => now(),
            'subtotal' => $subtotal,
            'ppn' => $ppn,
            'total_bayar' => $total,
        ]);
    }
}