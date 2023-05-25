<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocenteCurso extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_docente_curso';
    protected $table = 'docente_curso';
    protected $fillable = [
        'id_docente_curso',
        'id_docente',
        'id_curso_programa_plan',
        'docente_curso_fecha_creacion',
        'docente_curso_estado'
    ];

    public $timestamps = false;

    // docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente');
    }

    // curso programa plan
    public function curso_programa_plan()
    {
        return $this->belongsTo(CursoProgramaPlan::class, 'id_curso_programa_plan');
    }
}
