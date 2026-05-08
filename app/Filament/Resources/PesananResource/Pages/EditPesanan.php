<?php

namespace App\Filament\Resources\PesananResource\Pages;

use App\Filament\Resources\PesananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPesanan extends EditRecord
{
    protected static string $resource = PesananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $pesanan = $this->record->load('detailPesanan');

        $subtotal = $pesanan->detailPesanan->sum('subtotal');
        $ppn = $subtotal * 0.11;
        $total = $subtotal + $ppn;

        $pesanan->update([
            'total_harga' => $subtotal,
        ]);

        \App\Models\Pembayaran::updateOrCreate(
            ['id_pesanan' => $pesanan->id_pesanan],
            [
                'tgl_bayar' => now(),
                'subtotal' => $subtotal,
                'ppn' => $ppn,
                'total_bayar' => $total,
            ]
        );
    }
}