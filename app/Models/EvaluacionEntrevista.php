<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionEntrevista extends Model
{
    use HasFactory;
    protected $primaryKey = "evaluacion_entrevista_id";

    protected $table = 'evaluacion_entrevista';
    protected $fillable = [
        'evaluacion_entrevista_id',
        'evaluacion_entrevista_puntaje',
        'evaluacion_entrevista_item_id',
        'evaluacion_id',
    ];

    public $timestamps = false;

    // Evaluacion Entrevista Item
    public function evaluacion_entrevista_item(){
        return $this->belongsTo(EvaluacionEntrevistaItem::class,
        'evaluacion_entrevista_item_id','evaluacion_entrevista_item_id');
    }

    // Evaluacion
    public function evaluacion(){
        return $this->belongsTo(Evaluacion::class,
        'evaluacion_id','evaluacion_id');
    }
}
