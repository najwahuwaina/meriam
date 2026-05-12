<?php

namespace App\Models;

use App\Models\DetailPesanan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [

        'nama_menu',
        'jenis_menu',
        'harga',
        'is_admin',
        'content',

    ];

    public function detailPesanan()
    {
        return $this->hasMany(
            DetailPesanan::class,
            'id_menu'
        );
    }
}