<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramaProceso extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_programa_proceso';
    protected $table = 'programa_proceso';
    protected $fillable = [
        'id_programa_proceso',
        'id_admision',
        'id_programa_plan',
        'programa_proceso_estado',
    ];

    public $timestamps = false;

    // admision
    public function admision(){
        return $this->belongsTo(Admision::class,'id_admision','id_admision');
    }

    // programa_plan
    public function programa_plan(){
        return $this->belongsTo(ProgramaPlan::class,'id_programa_plan','id_programa_plan');
    }

    // inscripcion
    public function inscripcion(){
        return $this->hasMany(Inscripcion::class,'id_programa_proceso','id_programa_proceso');
    }
}
