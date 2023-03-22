<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionInvetigacion extends Model
{
    use HasFactory;

    protected $primaryKey = "evaluacion_investigacion_id";
    protected $table = 'evaluacion_investigacion';
    protected $fillable = [
        'evaluacion_investigacion_id',
        'evaluacion_investigacion_puntaje',
        'evaluacion_investigacion_item_id',
        'evaluacion_id'
    ];

    public $timestamps = false;

    // Evaluacion
    public function evaluacion(){
        return $this->belongsTo(Evaluacion::class,
        'evaluacion_id','evaluacion_id');
    }

    // Evaluacion Investigacion Item
    public function evaluacion_investigacion_item(){
        return $this->belongsTo(EvaluacionInvestigacionItem::class,
        'evaluacion_investigacion_item_id','evaluacion_investigacion_item_id');
    }
}
