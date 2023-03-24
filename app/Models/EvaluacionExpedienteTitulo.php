<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionExpedienteTitulo extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_evaluacion_expediente_titulo';
    protected $table = 'evaluacion_expediente_titulo';
    protected $fillable = [
        'id_evaluacion_expediente_titulo',
        'evaluacion_expediente_titulo',
        'evaluacion_expediente_titulo_puntaje',
        'id_tipo_evaluacion'
    ];

    public $timestamps = false;

    // Tipo Evaluacion
    public function tipo_evaluacion(){
        return $this->belongsTo(TipoEvaluacion::class, 'id_tipo_evaluacion', 'id_tipo_evaluacion');
    }
}
