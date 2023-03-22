<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionEntrevistaItem extends Model
{
    use HasFactory;

    protected $primaryKey = "evaluacion_entrevista_item_id";
    protected $table = 'evaluacion_entrevista_item';
    protected $fillable = [
        'evaluacion_entrevista_item_id',
        'evaluacion_entrevista_item',
        'evaluacion_entrevista_item_puntaje',
        'tipo_evaluacion_id'
    ];

    public $timestamps = false;

    // Evaluacion Entrevista Titulo
    public function evaluacion_entrevista_titulo(){
        return $this->belongsTo(EvaluacionEntrevistaTitulo::class,
        'evaluacion_entrevista_titulo_id','evaluacion_entrevista_titulo_id');
    }

    // Tipo Evaluacion
    public function tipo_evaluacion(){
        return $this->belongsTo(TipoEvaluacion::class,
        'tipo_evaluacion_id','tipo_evaluacion_id');
    }
}
