<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\KelompokSasaranController;

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

    public function program()
    {
        return $this->belongsTo(
            Program::class,
            'id_program',
            'id_program'
        );
    }

    public function kelompokSasaran()
    {
        return $this->belongsTo(
            KelompokSasaran::class,
            'id_kelompok',
            'id_kelompok_sasaran'
        );
    }
}
