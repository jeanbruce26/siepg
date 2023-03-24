<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionExpedienteItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_evaluacion_expediente_item';
    protected $table = 'evaluacion_expediente_item';
    protected $fillable = [
        'id_evaluacion_expediente_item',
        'evaluacion_expediente_item',
        'id_evaluacion_expediente_titulo',
        'evaluacion_expediente_item_puntaje',
    ];

    public $timestamps = false;

    // Evaluacion Expediente Titulo
    public function evaluacion_expediente_titulo(){
        return $this->belongsTo(EvaluacionExpedienteTitulo::class, 'id_evaluacion_expediente_titulo', 'id_evaluacion_expediente_titulo');
    }
}
