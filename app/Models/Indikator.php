<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indikator extends Model
{
    protected $table = 'indikator';
    protected $primaryKey = 'id_indikator';
    public $timestamps = false;

    protected $fillable = [
        'kode',
        'nama_indikator',
        'dimensi',
        'satuan',
        'bobot',
    ];
}
