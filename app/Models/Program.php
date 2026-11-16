<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Program extends Model
{
    use HasFactory;

    protected $table = 'program';
    protected $primaryKey = 'id_program';

    protected $fillable = [
        'id_user',
        'id_jenis_bantuan',
        'id_wilayah',
        'nama_program',
        'deskripsi',
        'tanggal_pelaksanaan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    protected $attributes = [
        'status' => 'Aktif',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pelaksanaan' => 'date',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function jenisBantuan(): BelongsTo
    {
        return $this->belongsTo(JenisBantuan::class, 'id_jenis_bantuan', 'id_jenis_bantuan');
    }

    public function wilayah(): BelongsTo
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id_wilayah');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
