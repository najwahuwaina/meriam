<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku';
    
    protected $fillable = [
        'kode_bahan', 'nama_bahan', 'satuan',
        'stok', 'stok_minimum', 'kategori', 'foto'
    ];

    public static function generateKodeBahan()
    {
        $last = self::latest()->first();
        if (!$last) return 'BB001';
        
        $num = (int) substr($last->kode_bahan, 2);
        return 'BB' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
    }

    public function pembelianBahan()
    {
        return $this->hasMany(PembelianBahan::class);
    }

}