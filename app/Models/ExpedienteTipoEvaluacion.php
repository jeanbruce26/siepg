<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpedienteTipoEvaluacion extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_expediente_tipo_evaluacion';
    protected $table = 'expediente_tipo_evaluacion';
    protected $fillable = [
        'id_expediente_tipo_evaluacion',
        'id_expediente',
        'expediente_tipo_evaluacion',
        'expediente_tipo_evaluacion_estado',
    ];

    public $timestamps = false;

    public function expediente()
    {
        return $this->belongsTo(Expediente::class, 'id_expediente', 'id_expediente');
    }
}
