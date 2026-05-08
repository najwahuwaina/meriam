<?php
//cobalagi
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';
    protected $primaryKey = 'id_karyawan';

    protected $fillable = [
        'nama_karyawan',
        'no_telp',
        'alamat',
        'jabatan',
        'tanggal_lahir',
        'foto_ektp',
    ];
}
