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
        'order_id',
        'snap_token',
        'transaction_status',
    ];

    public function pesanan()
    {
        return $this->belongsTo(
            Pesanan::class,
            'id_pesanan'
        );
    }
}