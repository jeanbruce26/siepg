<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluacionDocenteDetalle extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_evaluacion_docente_detalle';
    protected $table = 'evaluacion_docente_detalle';
    protected $fillable = [
        'id_evaluacion_docente_detalle',
        'id_evaluacion_docente',
        'id_evaluacion_docente_pregunta',
        'respuesta'
    ];

    // relaciones
    public function evaluacion_docente(): BelongsTo
    {
        return $this->belongsTo(EvaluacionDocente::class, 'id_evaluacion_docente', 'id_evaluacion_docente');
    }

    public function evaluacion_docente_pregunta(): BelongsTo
    {
        return $this->belongsTo(EvaluacionDocentePregunta::class, 'id_evaluacion_docente_pregunta', 'id_evaluacion_docente_pregunta');
    }
}
