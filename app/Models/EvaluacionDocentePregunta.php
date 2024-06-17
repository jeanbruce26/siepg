<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionDocentePregunta extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_evaluacion_docente_pregunta';
    protected $table = 'evaluacion_docente_pregunta';
    protected $fillable = [
        'id_evaluacion_docente_pregunta',
        'evaluacion_docente_pregunta',
        'evaluacion_docente_pregunta_estado'
    ];
}
