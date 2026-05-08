<?php

namespace App\Filament\Resources\PembelianBahanResource\Pages;

use App\Filament\Resources\PembelianBahanResource;
use App\Models\PembelianBahan;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePembelianBahan extends CreateRecord
{
    protected static string $resource = PembelianBahanResource::class;

    /**
     * Sanitasi data sebelum create
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // generate kode otomatis
        if (empty($data['kode_pembelian'])) {
            $data['kode_pembelian'] = PembelianBahan::generateKode();
        }

        // hitung total harga
        $total = ($data['jumlah'] ?? 0) * ($data['harga_beli'] ?? 0);

        // dibayar default 0
        $dibayar = $data['dibayar'] ?? 0;

        // simpan total & sisa
        $data['total_harga'] = $total;
        $data['sisa'] = $total - $dibayar;

        return $data;
    }

    /**
     * Setelah create berhasil
     */
    protected function afterCreate(): void
    {
        $record = $this->getRecord();

        // update stok bahan baku
        if ($record->bahanBaku) {
            $record->bahanBaku->increment(
                'stok',
                $record->jumlah
            );
        }
    }

    /**
     * Redirect setelah create
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Notifikasi berhasil
     */
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Pembelian bahan berhasil disimpan')
            ->success();
    }
}