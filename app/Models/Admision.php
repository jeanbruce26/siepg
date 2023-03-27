<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admision extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_admision';
    protected $table = 'admision';
    protected $fillable = [
        'id_admision',
        'admision',
        'admision_año',
        'admision_convocatoria',
        'admision_estado',
        'admision_fecha_fin_inscripcion',
        'admision_fecha_inicio_expediente',
        'admision_fecha_fin_expediente',
        'admision_fecha_inicio_entrevista',
        'admision_fecha_fin_entrevista',
        'admision_fecha_resultados',
        'admision_fecha_resultados',
        'admision_fecha_matricula',
        'admision_fecha_matricula_extemporanea',
    ];

    public $timestamps = false;

    public function inscripcion(){
        return $this->hasMany(Inscripcion::class,
        'id_admision','id_admision');
    }
}
