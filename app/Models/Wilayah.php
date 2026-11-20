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
        'nama_wilayah',
        'level',
        'jumlah_penduduk',
        'latitude',
        'longitude',
    ];

    public function getProvinsiAttribute(): ?string
    {
        if ((int) $this->level === 1) {
            return $this->nama_wilayah;
        }
        if ((int) $this->level === 2) {
            return $this->parent ? $this->parent->nama_wilayah : $this->nama_wilayah;
        }
        return ($this->parent && $this->parent->parent) ? $this->parent->parent->nama_wilayah : ($this->parent->nama_wilayah ?? $this->nama_wilayah);
    }

    public function getKabupatenKotaAttribute(): ?string
    {
        if ((int) $this->level === 2) {
            return $this->nama_wilayah;
        }
        if ((int) $this->level === 3) {
            return $this->parent ? $this->parent->nama_wilayah : '-';
        }
        return '-';
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