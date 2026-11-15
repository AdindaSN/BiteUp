<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KelompokSasaran extends Model
{
    protected $table = 'kelompok_sasaran';
    protected $primaryKey = 'id_kelompok_sasaran';
    public $timestamps = false;

    protected $fillable = ['id_wilayah', 'id_jenis_kelompok', 'jumlah', 'tahun'];

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id_wilayah');
    }

    public function jenisKelompok(): BelongsTo
    {
        return $this->belongsTo(JenisKelompok::class, 'id_jenis_kelompok', 'id_jenis_kelompok');
    }
}