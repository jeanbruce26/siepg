<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionEntrevista extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_evaluacion_entrevista';

    protected $table = 'evaluacion_entrevista';
    protected $fillable = [
        'id_evaluacion_entrevista',
        'evaluacion_entrevista_puntaje',
        'id_evaluacion_entrevista_item',
        'id_evaluacion',
    ];

    public $timestamps = false;

    // Evaluacion Entrevista Item
    public function evaluacion_entrevista_item(){
        return $this->belongsTo(EvaluacionEntrevistaItem::class, 'id_evaluacion_entrevista_item', 'id_evaluacion_entrevista_item');
    }

    // Evaluacion
    public function evaluacion(){
        return $this->belongsTo(Evaluacion::class, 'id_evaluacion', 'id_evaluacion');
    }
}
