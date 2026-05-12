<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// tambahan
use Illuminate\Support\Facades\DB;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers'; // disamakan dengan migration

    protected $guarded = []; // semua kolom boleh diisi

    public static function getKodeSupplier()
    {
        // query kode supplier terakhir
        $sql = "SELECT IFNULL(MAX(kode_supplier), 'S-00000') as kode_supplier 
                FROM suppliers";
        $kodesupplier = DB::select($sql);

        foreach ($kodesupplier as $kdspl) {
            $kd = $kdspl->kode_supplier;
        }

        $noawal = substr($kd, -5);
        $noakhir = $noawal + 1;

        $noakhir = 'S-' . str_pad($noakhir, 5, "0", STR_PAD_LEFT);

        return $noakhir;
    }

    // relasi ke tabel pembelian
    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'supplier_id');
    }
}