<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBantuan extends Model
{
    use HasFactory;

    protected $table = 'jenis_bantuan';
    protected $primaryKey = 'id_jenis_bantuan';
    public $timestamps = false;

    protected $fillable = [
        'nama_bantuan',
    ];

    public function program()
    {
        return $this->hasMany(Program::class, 'id_jenis_bantuan', 'id_jenis_bantuan');
    }
}
