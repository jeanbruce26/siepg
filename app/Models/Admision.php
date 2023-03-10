<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admision extends Model
{
    use HasFactory;

    protected $primaryKey = "cod_admi";
    protected $table = 'admision';
    protected $fillable = [
        'cod_admi',
        'admision',
        'admision_year',
        'admision_convocatoria',
        'estado',
        'fecha_fin',
        'fecha_evaluacion_expediente_inicio',
        'fecha_evaluacion_expediente_fin',
        'fecha_evaluacion_entrevista_inicio',
        'fecha_evaluacion_entrevista_fin',
        'fecha_admitidos',
        'estado_matricula',

    ];

    public $timestamps = false;

    public function inscripcion(){
        return $this->hasMany(Inscripcion::class,
        'admision_cod_admi','cod_admi');
    }
}
