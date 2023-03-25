<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $primaryKey = "id_persona";
    protected $table = 'persona';
    protected $fillable = [
        'id_persona',
        'numero_documento',
        'apellido_paterno',
        'apellido_materno',
        'nombre',
        'nombre_completo',
        'id_genero',
        'fecha_nacimiento',
        'direccion',
        'celular',
        'celular_opcional',
        'correo',
        'correo_opcional',
        'año_egreso',
        'especialidad_carrera',
        'centro_trabajo',
        'id_tipo_documento',
        'id_discapacidad',
        'id_estado_civil',
        'id_grado_academico',
        'id_universidad',
        'ubigeo_direccion',
        'pais_direccion',
        'ubigeo_nacimiento',
        'pais_nacimiento',
    ];

    public $timestamps = false;

    // TipoDocumento
    public function tipo_documento(){
        return $this->belongsTo(TipoDocumento::class,
        'id_tipo_documento','id_tipo_documento');
    }

    // Discapacidad
    public function discapacidad(){
        return $this->belongsTo(Discapacidad::class,
        'id_discapacidad','id_discapacidad');
    }

    // EstadoCivil
    public function estado_civil(){
        return $this->belongsTo(EstadoCivil::class,
        'id_estado_civil','id_estado_civil');
    }

    // GradoAcademico
    public function grado_academico(){
        return $this->belongsTo(GradoAcademico::class,
        'id_grado_academico','id_grado_academico');
    }

    // Universidad
    public function universidad(){
        return $this->belongsTo(Universidad::class,
        'id_universidad','id_universidad');
    }
    
    // Ubigeo Direccion
    public function ubigeo_direccion(){
        return $this->belongsTo(Distrito::class,
        'id_distrito','ubigeo_direccion');
    }
    
    // Ubigeo Nacimiento
    public function ubigeo_nacimiento(){
        return $this->belongsTo(Distrito::class,
        'id_distrito','ubigeo_nacimiento');
    }

    // Inscripcion
    public function inscripcion(){
        return $this->hasMany(Inscripcion::class,
        'id_persona','id_persona');
    }

    // Admitidos
    public function admitidos(){
        return $this->hasMany(Admitidos::class,
        'id_persona','id_persona');
    }
}
