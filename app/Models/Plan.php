<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $primaryKey = "id_plan";
    protected $table = 'plan';
    protected $fillable = [
        'id_plan',
        'plan_codigo',
        'plan',
        'plan_estado',
        'plan_resolucion',
        'plan_fecha_resolucion'
    ];

    public $timestamps = false;

    // Mencion
    public function mencion(){
        return $this->hasMany(Mencion::class,
        'id_plan','id_plan');
    }
}
