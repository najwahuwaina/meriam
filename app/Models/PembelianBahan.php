<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;
use App\Models\BahanBaku;

class PembelianBahan extends Model
{
    use HasFactory;

    protected $table = 'pembelian_bahan';

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($pembelian) {

            $total = $pembelian->jumlah * $pembelian->harga_beli;
            $sisa  = $total - $pembelian->dibayar;

            $pembelian->update([
                'tagihan' => $total,
                'sisa'    => $sisa,
            ]);

            if ($pembelian->bahanBaku) {
                $pembelian->bahanBaku->increment(
                    'stok',
                    $pembelian->jumlah
                );
            }
        });

        static::updating(function ($pembelian) {

            $oldJumlah = $pembelian->getOriginal('jumlah');
            $newJumlah = $pembelian->jumlah;

            $selisih = $newJumlah - $oldJumlah;

            if ($pembelian->bahanBaku && $selisih != 0) {
                $pembelian->bahanBaku->increment(
                    'stok',
                    $selisih
                );
            }

            $pembelian->tagihan =
                $pembelian->jumlah * $pembelian->harga_beli;

            $pembelian->sisa =
                $pembelian->tagihan - $pembelian->dibayar;
        });

        static::deleted(function ($pembelian) {

            if ($pembelian->bahanBaku) {
                $pembelian->bahanBaku->decrement(
                    'stok',
                    $pembelian->jumlah
                );
            }
        });
    }

    public function bahanBaku()
    {
        return $this->belongsTo(
            BahanBaku::class,
            'bahan_baku_id'
        );
    }

    public function supplier()
    {
        return $this->belongsTo(
            Supplier::class,
            'supplier_id'
        );
    }

    public static function generateKode()
    {
        $last = self::latest()->first();

        if (!$last) {
            return 'PB001';
        }

        $num = (int) substr($last->kode_pembelian, 2);

        return 'PB' . str_pad(
            $num + 1,
            3,
            '0',
            STR_PAD_LEFT
        );
    }
}