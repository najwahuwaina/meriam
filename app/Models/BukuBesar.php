<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuBesar extends Model
{
    use HasFactory;

    // pastikan menunjuk ke tabel yang benar
    protected $table = 'buku_besars';

    // izinkan semua kolom diisi
    protected $guarded = [];

    // atau kalau mau lebih ketat:
    // protected $fillable = ['kode_akun', 'nama_akun', 'tanggal', 'debit', 'kredit'];
}
