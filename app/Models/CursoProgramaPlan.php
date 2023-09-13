<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CursoProgramaPlan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_curso_programa_plan';
    protected $table = 'curso_programa_plan';
    protected $fillable = [
        'id_curso_programa_plan',
        'id_curso',
        'id_programa_plan',
        'curso_programa_plan_fecha_creacion',
        'curso_programa_plan_estado'
    ];

    public $timestamps = false;

    // curso
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }

    //programa proceso
    public function programa_plan(): BelongsTo
    {
        return $this->belongsTo(ProgramaPlan::class, 'id_programa_plan');
    }
}
