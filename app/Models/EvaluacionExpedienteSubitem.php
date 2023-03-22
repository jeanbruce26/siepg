<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionExpedienteSubitem extends Model
{
    use HasFactory;

    protected $primaryKey = "evaluacion_expediente_subitem_id";
    protected $table = 'evaluacion_expediente_subitem';
    protected $fillable = [
        'evaluacion_expediente_subitem_id',
        'evaluacion_expediente_subitem',
        'evaluacion_expediente_item_id',
        'evaluacion_expediente_subitem_puntaje',
    ];

    public $timestamps = false;

    // Evaluacion Expediente Item
    public function evaluacion_expediente_item(){
        return $this->belongsTo(EvaluacionExpedienteItem::class,
        'evaluacion_expediente_item_id','evaluacion_expediente_item_id');
    }
}
