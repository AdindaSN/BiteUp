<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilIkp extends Model
{
    protected $table = 'hasil_ikp';
    protected $primaryKey = 'id_hasil_ikp';
    public $timestamps = false;

    protected $fillable = [
        'id_wilayah',
        'tahun',
        'skor_ikp',
        'prioritas',
        'kategori_prioritas',
        'waktu_proses',
    ];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id_wilayah');
    }
}