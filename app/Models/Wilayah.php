<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    protected $primaryKey = 'id_wilayah';
    public $timestamps = false;

    protected $fillable = [
        'parent_id',
        'kode_wilayah',
        'nama_wilayah',
        'level',
    ];

    public function parent()
    {
        return $this->belongsTo(Wilayah::class, 'parent_id', 'id_wilayah');
    }

    public function children()
    {
        return $this->hasMany(Wilayah::class, 'parent_id', 'id_wilayah');
    }
}
