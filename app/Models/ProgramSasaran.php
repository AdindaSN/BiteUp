<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramSasaran extends Model
{
    use HasFactory;

    protected $table = 'program_sasaran';
    protected $primaryKey = 'id_program_sasaran';
    public $timestamps = false;

    protected $fillable = [
        'id_program',
        'id_kelompok',
        'target_jumlah',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(
            Program::class,
            'id_program',
            'id_program'
        );
    }

    public function kelompokSasaran(): BelongsTo
    {
        return $this->belongsTo(
            KelompokSasaran::class,
            'id_kelompok',
            'id_kelompok_sasaran'
        );
    }

    public function realisasiProgram(): HasMany
    {
        return $this->hasMany(
            RealisasiProgram::class,
            'id_program_sasaran',
            'id_program_sasaran'
        );
    }

    public function getPersentaseCapaianAttribute(): float
    {
        $target = (int) $this->target_jumlah;
        if ($target <= 0) {
            return 0.0;
        }

        $realisasi = $this->relationLoaded('realisasiProgram')
            ? (int) $this->realisasiProgram->sum('jumlah_realisasi')
            : (int) $this->realisasiProgram()->sum('jumlah_realisasi');

        return round(($realisasi / $target) * 100, 1);
    }
}