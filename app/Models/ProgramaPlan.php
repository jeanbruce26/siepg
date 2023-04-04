<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramaPlan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_programa_plan';
    protected $table = 'mencion_plan';
    protected $fillable = [
        'id_programa_plan',
        'programa_codigo',
        'id_programa',
        'id_plan',
        'programa_plan_creacion',
        'programa_plan_estado',
    ];

    // programa
    public function programa(){
        return $this->belongsTo(Programa::class,'id_programa','id_programa');
    }

    // plan
    public function plan(){
        return $this->belongsTo(Plan::class,'id_plan','id_plan');
    }
}
