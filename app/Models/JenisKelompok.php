<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKelompok extends Model
{
    use HasFactory;

    protected $table = 'jenis_kelompok';
    protected $primaryKey = 'id_jenis_kelompok';
    public $timestamps = false;

    protected $fillable = [
        'nama_jenis',
    ];

    public function kelompokSasaran()
    {
        return $this->hasMany(KelompokSasaran::class, 'id_jenis_kelompok', 'id_jenis_kelompok');
    }
}
