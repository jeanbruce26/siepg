<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEstudiante extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tipo_estudiante';
    protected $table = 'tipo_estudiante';
    protected $fillable = [
        'id_tipo_estudiante',
        'tipo_estudiante',
        'tipo_estudiante_estado'
    ];

    public $timestamps = false;
}
