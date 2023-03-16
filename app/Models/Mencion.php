<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mencion extends Model
{
    use HasFactory;

    protected $primaryKey = "id_mencion";
    protected $table = 'mencion';
    protected $fillable = [
        'id_mencion',
        'iniciales',
        'cod_mencion',
        'mencion',
        'id_subprograma',
        'id_plan',
        'mencion_estado'
    ];

    public $timestamps = false;
    
    //SubPrograma
    public function subprograma(){
        return $this->belongsTo(SubPrograma::class,
        'id_subprograma','id_subprograma');
    }

    //Plan
    public function plan(){
        return $this->belongsTo(Plan::class,
        'id_plan','id_plan');
    }
}
