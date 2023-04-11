<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionInvestigacion extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_evaluacion_investigacion';
    protected $table = 'evaluacion_investigacion';
    protected $fillable = [
        'id_evaluacion_investigacion',
        'evaluacion_investigacion_puntaje',
        'id_evaluacion_investigacion_item',
        'id_evaluacion'
    ];

    public $timestamps = false;

    // Evaluacion
    public function evaluacion(){
        return $this->belongsTo(Evaluacion::class, 'id_evaluacion', 'id_evaluacion');
    }

    // Evaluacion Investigacion Item
    public function evaluacion_investigacion_item(){
        return $this->belongsTo(EvaluacionInvestigacionItem::class, 'id_evaluacion_investigacion_item', 'id_evaluacion_investigacion_item');
    }
}
