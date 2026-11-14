<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    protected $primaryKey = 'id_wilayah';
    public $timestamps = false;

    // Arti angka level (asumsi, sesuaikan dengan kesepakatan tim)
    public const LEVEL = [
        1 => 'Provinsi',
        2 => 'Kabupaten/Kota',
        3 => 'Kecamatan',
        4 => 'Desa/Kelurahan',
    ];

    protected $fillable = [
        'parent_id',
        'kode_wilayah',
        'nama_wilayah',
        'level',
        'jumlah_penduduk',
        'latitude',
        'longitude',
    ];
}