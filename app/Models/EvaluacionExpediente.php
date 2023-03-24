<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionExpediente extends Model
{
    use HasFactory;


    protected $primaryKey = 'id_evaluacion_expediente';
    protected $table = 'evaluacion_expediente';
    protected $fillable = [
        'id_evaluacion_expediente',
        'evaluacion_expediente_puntaje',
        'id_evaluacion_expediente_titulo',
        'id_evaluacion',
    ];

    public $timestamps = false;

    // Evaluacion Expediente Titulo
    public function evaluacion_expediente_titulo(){
        return $this->belongsTo(EvaluacionExpedienteTitulo::class, 'id_evaluacion_expediente_titulo', 'id_evaluacion_expediente_titulo');
    }

    // Evaluacion
    public function evaluacion(){
        return $this->belongsTo(Evaluacion::class, 'id_evaluacion', 'id_evaluacion');
    }
}
