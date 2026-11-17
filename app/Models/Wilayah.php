<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayah';
    protected $primaryKey = 'id_wilayah';
    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'kode_wilayah',
        'nama_wilayah',
        'level',
        'jumlah_penduduk',
        'latitude',
        'longitude',
    ];

    public function parent()
    {
        return $this->belongsTo(Wilayah::class, 'parent_id', 'id_wilayah');
    }

    public function children()
    {
        return $this->hasMany(Wilayah::class, 'parent_id', 'id_wilayah');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_wilayah', 'id_wilayah');
    }
}
