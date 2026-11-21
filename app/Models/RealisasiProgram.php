<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RealisasiProgram extends Model
{
    use HasFactory;

    protected $table = 'realisasi_program';
    protected $primaryKey = 'id_realisasi';
    public $timestamps = false;

    protected $fillable = [
        'id_program_sasaran',
        'id_user',
        'tanggal',
        'jumlah_realisasi',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jumlah_realisasi' => 'integer',
        ];
    }

    public function programSasaran(): BelongsTo
    {
        return $this->belongsTo(ProgramSasaran::class, 'id_program_sasaran', 'id_program_sasaran');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}