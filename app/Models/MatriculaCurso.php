<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatriculaCurso extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id_matricula_curso';
    protected $table = 'matricula_curso';
    protected $fillable = [
        'id_matricula_curso',
        'id_matricula',
        'id_curso_programa_plan',
        'id_admision',
        'id_programa_proceso_grupo',
        'matricula_curso_fecha_creacion',
        'matricula_curso_estado'
    ];

    // matricula
    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'id_matricula');
    }

    // curso programa plan
    public function curso_programa_plan()
    {
        return $this->belongsTo(CursoProgramaPlan::class, 'id_curso_programa_plan');
    }

    // admision
    public function admision()
    {
        return $this->belongsTo(Admision::class, 'id_admision');
    }

    // programa proceso grupo
    public function programa_proceso_grupo()
    {
        return $this->belongsTo(ProgramaProcesoGrupo::class, 'id_programa_proceso_grupo');
    }

    // protected static function boot() {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->created_by = auth()->id();
    //     });

    //     static::updating(function ($model) {
    //         $model->updated_by = auth()->id();
    //     });

    //     static::deleting(function ($model) {
    //         $model->deleted_by = auth()->id();
    //         $model->save();
    //     });
    // }
}
