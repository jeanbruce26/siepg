<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UsuarioEstudiante extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'id_usuario_estudiante';
    protected $table = 'usuario_estudiante';
    protected $fillable = [
        'id_usuario_estudiante',
        'usuario_estudiante',
        'usuario_estudiante_password',
        'usuario_estudiante_creacion',
        'usuario_estudiante_estado',
        'id_persona',
        'usuario_estudiante_perfil_url',
    ];

    public $timestamps = false;

    // model persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }
}
