<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEvaluacion extends Model
{
    use HasFactory;

    protected $primaryKey = 'tipo_evaluacion_id';
    protected $table = 'tipo_evaluacion';
    protected $fillable = [
        'tipo_evaluacion_id',
        'tipo_evaluacion',
        'tipo_evaluacion_estado'
    ];

    public $timestamps = false;
}
