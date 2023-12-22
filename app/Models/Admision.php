<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admision extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id_admision';
    protected $table = 'admision';
    protected $fillable = [
        'id_admision',
        'admision',
        'admision_año',
        'admision_convocatoria',
        'admision_estado',
        'admision_fecha_inicio_inscripcion',
        'admision_fecha_fin_inscripcion',
        'admision_fecha_inicio_expediente',
        'admision_fecha_fin_expediente',
        'admision_fecha_inicio_entrevista',
        'admision_fecha_fin_entrevista',
        'admision_fecha_resultados',
        'admision_fecha_matricula',
        'admision_fecha_matricula_extemporanea',
    ];

    public function inscripcion(){
        return $this->hasMany(Inscripcion::class,
        'id_admision','id_admision');
    }

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });

        static::deleting(function ($model) {
            $model->deleted_by = auth()->id();
            $model->save();
        });
    }
}
