<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    use HasFactory;

    protected $primaryKey = "cod_exp";
    protected $table = 'expediente';
    protected $fillable = [
        'cod_exp',
        'tipo_doc',
        'complemento',
        'exp_nombre',
        'requerido',
        'expediente_tipo',
        'estado',
    ];

    public $timestamps = false;

    public function expediente_tipo_evaluacion()
    {
        return $this->hasMany(ExpedienteTipoEvaluacion::class, 'cod_exp');
    }

    public function expediente_tipo_seguimiento()
    {
        return $this->hasMany(ExpedienteTipoSeguimiento::class, 'cod_exp');
    }
}
