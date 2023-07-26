<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigoEstudiante extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_codigo_estudiante';
    protected $table = 'codigo_estudiante';
    protected $fillable = [
        'id_codigo_estudiante',
        'codigo_estudiante',
        'codigo_estudiante_nombre',
        'codigo_estudiante_estado',
    ];

    public $timestamps = false;
}
