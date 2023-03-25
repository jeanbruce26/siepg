<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramaProceso extends Model
{
    use HasFactory;

    protected $primaryKey = "id_programa_proceso";
    protected $table = 'programa_proceso';
    protected $fillable = [
        'id_programa_proceso',
        'id_modalidad',
        'id_admision',
        'id_mencion_plan',
        'programa_proceso_estado',
    ];

    //Modalidad
    public function modalidad(){
        return $this->belongsTo(Modalidad::class,
        'id_modalidad',  'id_modalidad');
    }

    //Admision
    public function admision(){
        return $this->belongsTo(Admision::class,
        'id_admision',  'id_admision');
    }

    //Mencion Plan
    public function mencion_plan(){
        return $this->belongsTo(MencionPlan::class,
        'id_mencion_plan',  'id_mencion_plan');
    }

    // Inscripcion
    public function inscripcion(){
        return $this->hasMany(Inscripcion::class,
        'id_programa_proceso', 'id_programa_proceso');
    }
}
