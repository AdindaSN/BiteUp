<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = 'program';
    protected $primaryKey = 'id_program';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_jenis_bantuan',
        'nama_program',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function jenisBantuan()
    {
        return $this->belongsTo(JenisBantuan::class, 'id_jenis_bantuan', 'id_jenis_bantuan');
    }

    public function programSasaran()
    {
        return $this->hasMany(ProgramSasaran::class, 'id_program', 'id_program');
    }

    public function sasaran()
    {
        return $this->hasMany(ProgramSasaran::class, 'id_program', 'id_program');
    }
}
