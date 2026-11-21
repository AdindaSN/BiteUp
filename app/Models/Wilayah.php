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
        3 => 'Kecamatan',
    ];

    protected $fillable = [
        'parent_id',
        'kode_wilayah',
        'provinsi',
        'kabupaten_kota',
        'tahun',
    ];

    // Fallback agar Blade view modul lain yang masih membaca $wilayah->nama_wilayah tetap tampil
    public function getNamaWilayahAttribute(): ?string
    {
        if (!empty($this->kabupaten_kota) && $this->kabupaten_kota !== '-') {
            return $this->kabupaten_kota;
        }

        return $this->provinsi;
    }

    // Fallback agar Blade view modul lain yang masih membaca $wilayah->level tetap jalan
    public function getLevelAttribute(): int
    {
        return (empty($this->kabupaten_kota) || $this->kabupaten_kota === '-') ? 1 : 2;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'parent_id', 'id_wilayah');
    }

    public function induk(): BelongsTo
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