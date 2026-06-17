<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Akun;

class JurnalDetail extends Model
{
    use HasFactory;

    protected $table = 'jurnal_details';

    protected $guarded = [];

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class);
    }

    public function akun()
    {
        return $this->belongsTo(Akun::class);
    }
}