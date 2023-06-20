<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostoEnseñanza extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_costo_enseñanza';
    protected $table = 'costo_enseñanza';
    protected $fillable = [
        'id_costo_enseñanza',
        'id_ciclo',
        'id_programa_plan',
        'costo_credito',
        'numero_credito',
        'costo_ciclo',
        'costo_enseñanza_fecha_creacion',
        'costo_enseñanza_estado',
    ];

    public $timestamps = false;

    //Ciclo
    public function ciclo(){
        return $this->belongsTo(Ciclo::class,
        'id_ciclo','id_ciclo');
    }

    //Programa Plan
    public function programa_plan(){
        return $this->belongsTo(ProgramaPlan::class,
        'id_programa_plan','id_programa_plan');
    }
}
