<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_curso';
    protected $table = 'curso';
    protected $fillable = [
        'id_curso',
        'curso_codigo',
        'curso_nombre',
        'curso_credito',
        'curso_horas',
        'curso_fecha_creacion',
        'curso_estado',
        'id_ciclo'
    ];

    public $timestamps = false;

    // ciclo
    public function ciclo()
    {
        return $this->belongsTo(Ciclo::class, 'id_ciclo');
    }
}
