<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionEntrevistaItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_evaluacion_entrevista_item';
    protected $table = 'evaluacion_entrevista_item';
    protected $fillable = [
        'id_evaluacion_entrevista_item',
        'evaluacion_entrevista_item',
        'evaluacion_entrevista_item_puntaje',
        'id_tipo_evaluacion',
    ];

    public $timestamps = false;

    // Tipo Evaluacion
    public function tipo_evaluacion(){
        return $this->belongsTo(TipoEvaluacion::class, 'id_tipo_evaluacion', 'id_tipo_evaluacion');
    }
}
