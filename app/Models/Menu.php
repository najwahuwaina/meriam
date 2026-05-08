<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
<<<<<<< HEAD
        'nama_menu',
        'jenis_menu',
        'harga',
        'is_admin',
        'content',
    ];
}
=======
    'nama_menu',
    'jenis_menu',
    'harga',
    'is_admin',
    'content',
];

public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_menu');
    }
    
}
>>>>>>> f21a4d2 (nana)
