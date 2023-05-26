<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoProgramaProceso extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_curso_programa_proceso';
    protected $table = 'curso_programa_proceso';
    protected $fillable = [
        'id_curso_programa_proceso',
        'id_curso',
        'id_programa_proceso',
        'curso_programa_proceso_fecha_creacion',
        'curso_programa_proceso_estado'
    ];

    public $timestamps = false;

    // curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }

    //programa proceso
    public function programa_proceso()
    {
        return $this->belongsTo(ProgramaProceso::class, 'id_programa_proceso');
    }
}
