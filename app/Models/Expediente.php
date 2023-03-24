<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_expediente';
    protected $table = 'expediente';
    protected $fillable = [
        'id_expediente',
        'expediente',
        'expediente_completo',
        'expediente_nombre_file',
        'expediente_requerido',
        'expediente_tipo',
        'expediente_estado',
    ];

    public $timestamps = false;

    public function expediente_tipo_evaluacion()
    {
        return $this->hasMany(ExpedienteTipoEvaluacion::class, 'id_expediente', 'id_expediente');
    }

    public function expediente_tipo_seguimiento()
    {
        return $this->hasMany(ExpedienteTipoSeguimiento::class, 'id_expediente', 'id_expediente');
    }
}
