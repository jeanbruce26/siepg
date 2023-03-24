<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionExpedienteSubitem extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_evaluacion_expediente_subitem';
    protected $table = 'evaluacion_expediente_subitem';
    protected $fillable = [
        'id_evaluacion_expediente_subitem',
        'evaluacion_expediente_subitem',
        'id_evaluacion_expediente_item',
        'evaluacion_expediente_subitem_puntaje',
    ];

    public $timestamps = false;

    // Evaluacion Expediente Item
    public function evaluacion_expediente_item(){
        return $this->belongsTo(EvaluacionExpedienteItem::class, 'id_evaluacion_expediente_item', 'id_evaluacion_expediente_item');
    }
}
