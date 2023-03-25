<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEvaluacion extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tipo_evaluacion';
    protected $table = 'tipo_evaluacion';
    protected $fillable = [
        'id_tipo_evaluacion',
        'tipo_evaluacion',
        'tipo_evaluacion_estado'
    ];

    public $timestamps = false;

    // Evaluación
    public function evaluacion(){
        return $this->hasMany(Evaluacion::class,
        'id_tipo_evaluacion','id_tipo_evaluacion');
    }

    // Evaluación Observación
    public function evaluacion_observacion(){
        return $this->hasMany(EvaluacionObservacion::class,
        'id_tipo_evaluacion','id_tipo_evaluacion');
    }
    
    // Evaluación Entrevista Item
    public function evaluacion_entrevista_item(){
        return $this->hasMany(EvaluacionEntrevistaItem::class,
        'id_tipo_evaluacion','id_tipo_evaluacion');
    }

    // Evaluación Investigacion Item
    public function evaluacion_investigacion_item(){
        return $this->hasMany(EvaluacionInvetigacionItem::class,
        'id_tipo_evaluacion','id_tipo_evaluacion');
    }

    // Evaluación Investigacion Item
    public function evaluacion_expediente_titulo(){
        return $this->hasMany(EvaluacionExpedienteTitulo::class,
        'id_tipo_evaluacion','id_tipo_evaluacion');
    }
}
