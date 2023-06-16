<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoCursos extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_estado_cursos';
    protected $table = 'estado_cursos';
    protected $fillable = [
        'id_estado_cursos',
        'estado_cursos',
        'estado_cursos_estado',
    ];

    public $timestamps = false;
}
