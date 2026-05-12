<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'id_pesanan',
        'tgl_bayar',
        'subtotal',
        'tarif_ppn',
        'subtotal_stlh_ppn',
        'jumlah',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }
}