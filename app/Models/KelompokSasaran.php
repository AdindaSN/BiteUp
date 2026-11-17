<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokSasaran extends Model
{
    use HasFactory;

    protected $table = 'kelompok_sasaran';
    protected $primaryKey = 'id_kelompok_sasaran';
    public $timestamps = false;

    protected $fillable = [
        'id_wilayah',
        'id_jenis_kelompok',
        'jumlah',
        'tahun',
    ];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id_wilayah');
    }

    public function jenisKelompok()
    {
        return $this->belongsTo(JenisKelompok::class, 'id_jenis_kelompok', 'id_jenis_kelompok');
    }

    public function programSasaran()
    {
        return $this->hasMany(ProgramSasaran::class, 'id_kelompok', 'id_kelompok_sasaran');
    }
}
