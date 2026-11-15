<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKelompok extends Model
{
    protected $table = 'jenis_kelompok';
    protected $primaryKey = 'id_jenis_kelompok';
    public $timestamps = false;

    protected $fillable = ['nama_jenis'];
}