<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BukuBesar;

class BukuBesarSeeder extends Seeder
{
    public function run(): void
    {
        BukuBesar::create([
            'kode_akun' => '1001',
            'nama_akun' => 'Kas',
            'tanggal'   => '2026-06-01',
            'debit'     => 500000,
            'kredit'    => 0,
        ]);

        BukuBesar::create([
            'kode_akun' => '2001',
            'nama_akun' => 'Utang Dagang',
            'tanggal'   => '2026-06-02',
            'debit'     => 0,
            'kredit'    => 300000,
        ]);
    }
}
