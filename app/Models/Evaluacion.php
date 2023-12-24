<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluacion extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'evaluacion_observacion',
        'evaluacion_estado',
        'evaluacion_estado_admitido',
        'id_inscripcion',
        'id_tipo_evaluacion',
    ];

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

    // protected static function boot() {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->created_by = auth()->id();
    //     });

    //     static::updating(function ($model) {
    //         $model->updated_by = auth()->id();
    //     });

    //     static::deleting(function ($model) {
    //         $model->deleted_by = auth()->id();
    //         $model->save();
    //     });
    // }
}
