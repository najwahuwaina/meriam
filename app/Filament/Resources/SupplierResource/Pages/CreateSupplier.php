<?php

namespace App\Filament\Resources\SupplierResource\Pages;

use App\Filament\Resources\SupplierResource;
// tambahan
use App\Http\Controllers\NotificationController;

use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSupplier extends CreateRecord
{
    protected static string $resource = SupplierResource::class;

    /**
     * Hook yang dijalankan tepat setelah data supplier berhasil disimpan ke database
     */
    protected function afterCreate(): void
    {
        // 1. Ambil data supplier yang baru saja disimpan
        $supplier = $this->record;

        // 2. Siapkan nomor telepon
        $nomorWa = preg_replace('/[^0-9]/', '', $supplier->no_telp);

        // 3. Susun pesan
        $pesan = "Halo *{$supplier->nama_supplier}*,\n\n" .
                 "Data supplier telah berhasil ditambahkan.\n" .
                 "Kode Supplier: {$supplier->kode_supplier}\n" .
                 "Nama Supplier: {$supplier->nama_supplier}\n" .
                 "Alamat: {$supplier->alamat_supplier}\n" .
                 "No Telepon: {$supplier->no_telp}\n\n" .
                 "Terima kasih.";
    }
}