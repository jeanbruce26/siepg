<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_evaluacion';
    protected $table = 'evaluacion';
    protected $fillable = [
        'id_evaluacion',
        'puntaje_expediente',
        'fecha_expediente',
        'puntaje_investigacion',
        'fecha_investigacion',
        'puntaje_entrevista',
        'fecha_entrevista',
        'puntaje_final',
        'evaluacion_estado',
        'evaluacion_estado_admitido',
        'id_inscripcion',
        'id_tipo_evaluacion',
    ];

    public $timestamps = false;

    // Inscripcion
    public function inscripcion(){
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion', 'id_inscripcion');
    }

    // Tipo Evaluacion
    public function tipo_evaluacion(){
        return $this->belongsTo(TipoEvaluacion::class, 'id_tipo_evaluacion', 'id_tipo_evaluacion');
    }

    // Admitidos
    public function admitido(){
        return $this->hasMany(Admitido::class, 'id_evaluacion', 'id_evaluacion');
    }

    // evaluacion expediente
    public function evaluacion_expediente(){
        return $this->hasMany(EvaluacionExpediente::class, 'id_evaluacion', 'id_evaluacion');
    }

    // evaluacion investigacion
    public function evaluacion_investigacion(){
        return $this->hasMany(EvaluacionInvestigacion::class, 'id_evaluacion', 'id_evaluacion');
    }

    // evaluacion entrevista
    public function evaluacion_entrevista(){
        return $this->hasMany(EvaluacionEntrevista::class, 'id_evaluacion', 'id_evaluacion');
    }
}
