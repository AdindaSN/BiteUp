<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id_roles';
    public $timestamps = false;

    protected $fillable = [
        'nama_role',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_roles', 'id_roles');
    }
}

