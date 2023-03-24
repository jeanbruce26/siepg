<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MencionPlan extends Model
{
    use HasFactory;

    protected $primaryKey = "id_mencion_plan";
    protected $table = 'mencion_plan';
    protected $fillable = [
        'id_mencion_plan',
        'mencion_codigo',
        'id_mencion',
        'id_plan',
        'mencion_plan_creacion',
        'mencion_plan_estado',
    ];

    public function mencion(){
        return $this->belongsTo(Mencion::class,
        'id_mencion','id_mencion');
    }

    public function plan(){
        return $this->belongsTo(Plan::class,
        'id_plan','id_plan');
    }

    public function programa_proceso(){
        return $this->hasMany(ProgramaProceso::class,
        'id_mencion_plan','id_mencion_plan');
    }
}
