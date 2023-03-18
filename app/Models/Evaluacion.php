<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $primaryKey = "evaluacion_id";
    protected $table = 'evaluacion';
    protected $fillable = [
        'evaluacion_id',
        'p_expediente',
        'p_entrevista',
        'p_investigacion',
        'p_final',
        'evaluacion_observacion',
        'evaluacion_estado',
        'evaluacion_estado_admitido',
        'puntaje_id',
        'inscripcion_id',
        'fecha_expediente',
        'fecha_entrevista',
        'fecha_investigacion',
        'tipo_evaluacion_id'
    ];

    public $timestamps = false;

    // Puntaje
    public function puntaje(){
        return $this->belongsTo(Puntaje::class,
        'puntaje_id','puntaje_id');
    }

    // Inscripcion
    public function inscripcion(){
        return $this->belongsTo(Inscripcion::class,
        'puntaje_id','puntaje_id');
    }

    // Tipo Evaluacion
    public function tipo_evaluacion(){
        return $this->belongsTo(TipoEvaluacion::class,
        'tipo_evaluacion_id','tipo_evaluacion_id');
    }

    // Admitidos
    public function admitidos(){
        return $this->hasMany(Admitidos::class,
        'evaluacion_id','evaluacion_id');
    }
}
