<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataIndikator extends Model
{
    protected $table = 'data_indikator';
    protected $primaryKey = 'id_data_indikator';
    public $timestamps = false;

    protected $fillable = [
        'id_wilayah',
        'id_indikator',
        'id_user',
        'tahun',
        'nilai',
        'sumber_data',
        'waktu_update',
    ];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id_wilayah');
    }

    public function indikator()
    {
        return $this->belongsTo(Indikator::class, 'id_indikator', 'id_indikator');
    }
}
