<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionObservacion extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_evaluacion_observacion';
    protected $table = 'evaluacion_observacion';
    protected $fillable = [
        'id_evaluacion_observacion',
        'evaluacion_observacion',
        'id_tipo_evaluacion',
        'evaluacion_observacion_fecha',
        'id_evaluacion',
    ];

    public $timestamps = false;

    public function evaluacion()
    {
        return $this->belongsTo(Evaluacion::class, 'id_evaluacion');
    }

    public function tipo_evaluacion()
    {
        return $this->belongsTo(TipoEvaluacion::class, 'id_tipo_evaluacion');
    }
}
