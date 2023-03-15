<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UsuarioEstudiante extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'usuario_estudiante_id';
    protected $table = 'usuario_estudiante';
    protected $fillable = [
        'usuario_estudiante_id',
        'usuario_estudiante',
        'usuario_estudiante_password',
        'usuario_estudiante_created_at',
        'usuario_estudiante_estado',
        'usuario_estudiante_perfil',
    ];

    public $timestamps = false;
}
