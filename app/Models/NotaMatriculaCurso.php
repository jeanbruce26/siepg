<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaMatriculaCurso extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_nota_matricula_curso';
    protected $table = 'nota_matricula_curso';
    protected $fillable = [
        'id_nota_matricula_curso',
        'id_matricula_curso',
        'nota',
        'nota_matricula_curso_fecha_creacion',
        'nota_matricula_curso_estado'
    ];

    public $timestamps = false;

    // matricula curso
    public function matricula_curso()
    {
        return $this->belongsTo(MatriculaCurso::class, 'id_matricula_curso');
    }
}
