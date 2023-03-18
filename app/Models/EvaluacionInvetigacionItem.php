<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionInvetigacionItem extends Model
{
    use HasFactory;

    protected $primaryKey = "evaluacion_investigacion_item_id";
    protected $table = 'evaluacion_investigacion_item';
    protected $fillable = [
        'evaluacion_investigacion_item_id',
        'evaluacion_investigacion_item',
        'evaluacion_investigacion_item_puntaje',
        'evaluacion_investigacion_item_estado',
    ];

    public $timestamps = false;

    // Evaluacion Investigacion
    public function evaluacion_investigacion(){
        return $this->hasMany(EvaluacionInvestigacion::class,
        'evaluacion_investigacion_item_id','evaluacion_investigacion_item_id');
    }
}
