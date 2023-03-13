<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioEstudiante extends Model
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
    ];

    public $timestamps = false;
}
