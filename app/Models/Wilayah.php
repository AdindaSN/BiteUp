<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayah';
    protected $primaryKey = 'id_wilayah';
    public $timestamps = false;

    public const LEVEL = [
        1 => 'Provinsi',
        2 => 'Kabupaten/Kota',
    ];

    protected $fillable = [
        'kode_wilayah',
        'provinsi',
        'kabupaten_kota',
        'tahun',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'parent_id', 'id_wilayah');
    }

    public function induk()
    {
        return $this->belongsTo(Wilayah::class, 'parent_id', 'id_wilayah');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Wilayah::class, 'parent_id', 'id_wilayah');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_wilayah', 'id_wilayah');
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'id_wilayah', 'id_wilayah');
    }
}