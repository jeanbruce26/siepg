<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    use HasFactory;

    protected $primaryKey = "id_trabajador";
    protected $table = 'trabajador';
    
    protected $fillable = [
        'id_trabajador',
        'trabajador_apellido',
        'trabajador_nombre',
        'trabajador_nombre_completo',
        'trabajador_numero_documento',
        'trabajador_correo',
        'trabajador_direccion',
        'id_grado_academico',
        'trabajador_perfil_url',
        'trabajador_estado',
    ];

    public $timestamps = false;

    // Grado Académico
    public function grado_academico(){
        return $this->belongsTo(GradoAcademico::class,
        'id_grado_academico','id_grado_academico');
    }


    // Trabajador Tipo Trabajador
    public function trabajador_tipo_trabajador(){
        return $this->hasOne(TrabajadorTipoTrabajador::class,
        'id_trabajador','id_trabajador');
    }

    // Administrativo
    public function administrativo(){
        return $this->hasOne(Administrativo::class,
        'id_trabajador','id_trabajador');
    }

    // Docente
    public function docente()
    {
        return $this->hasOne(Docente::class, 'id_trabajador', 'id_trabajador');
    }
}
