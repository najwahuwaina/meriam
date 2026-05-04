<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku';
    protected $guarded = [];

    /**
     * Auto-generate kode bahan baku: BB001, BB002, dst.
     */
    public static function getKodeBahan()
    {
        $sql = "SELECT IFNULL(MAX(kode_bahan), 'BB000') as kode_bahan FROM bahan_baku";
        $result = DB::select($sql);

        foreach ($result as $row) {
            $kd = $row->kode_bahan;
        }

        $noawal  = substr($kd, -3);
        $noakhir = $noawal + 1;
        $noakhir = 'BB' . str_pad($noakhir, 3, "0", STR_PAD_LEFT);

        return $noakhir;
    }

    /**
     * Mutator: hapus titik dari input harga (format: 10.000 → 10000)
     */
    public function setHargaBeliAttribute($value)
    {
        $this->attributes['harga_beli'] = str_replace('.', '', $value);
    }

    // Relasi ke tabel pembelian_bahan (jika ada)
    public function pembelianBahan()
    {
        return $this->hasMany(PembelianBahan::class, 'bahan_baku_id');
    }

    // Relasi ke tabel pemakaian_bahan (jika ada)
    public function pemakaianBahan()
    {
        return $this->hasMany(PemakaianBahan::class, 'bahan_baku_id');
    }
}