<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatriculaGestion extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_matricula_gestion';
    protected $table = 'matricula_gestion';
    protected $fillable = [
        'id_matricula_gestion',
        'id_programa_proceso',
        'id_admision',
        'id_ciclo',
        'matricula_gestion_fecha_inicio',
        'matricula_gestion_fecha_fin',
        'matricula_gestion_fecha_extemporaneo_inicio',
        'matricula_gestion_fecha_extemporaneo_fin',
        'matricula_gestion_fecha_creacion',
        'matricula_gestion_estado',
        'matricula_gestion_minimo_alumnos'
    ];

    public $timestamps = false;

    // programa proceso
    public function programa_proceso()
    {
        return $this->belongsTo(ProgramaProceso::class, 'id_programa_proceso');
    }

    // admision
    public function admision()
    {
        return $this->belongsTo(Admision::class, 'id_admision');
    }

    // ciclo
    public function ciclo()
    {
        return $this->belongsTo(Ciclo::class, 'id_ciclo');
    }
}
