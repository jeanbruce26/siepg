<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionInvestigacionItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_evaluacion_investigacion_item';
    protected $table = 'evaluacion_investigacion_item';
    protected $fillable = [
        'id_evaluacion_investigacion_item',
        'evaluacion_investigacion_item',
        'evaluacion_investigacion_item_puntaje',
        'id_tipo_evaluacion',
    ];

    public $timestamps = false;

    // Tipo Evaluacion
    public function tipo_evaluacion(){
        return $this->belongsTo(TipoEvaluacion::class, 'id_tipo_evaluacion', 'id_tipo_evaluacion');
    }
}
