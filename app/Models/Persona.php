<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = "id_persona";
    protected $dates = ['fecha_nacimiento'];
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

    // TipoDocumento
    public function tipo_documento(){
        return $this->belongsTo(TipoDocumento::class,
        'id_tipo_documento','id_tipo_documento');
    }

    // Genero
    public function genero(){
        return $this->belongsTo(Genero::class,
        'id_genero','id_genero');
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
        return $this->belongsTo(Ubigeo::class,
        'id_ubigeo','ubigeo_direccion');
    }

    // Ubigeo Nacimiento
    public function ubigeo_nacimiento(){
        return $this->belongsTo(Ubigeo::class,
        'id_ubigeo','ubigeo_nacimiento');
    }

    // Inscripcion
    public function inscripcion(){
        return $this->hasMany(Inscripcion::class,
        'id_persona','id_persona');
    }

    // Admitidos
    public function admitido(){
        return $this->hasMany(Admitido::class,
        'id_persona','id_persona');
    }

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });

        static::deleting(function ($model) {
            $model->deleted_by = auth()->id();
            $model->save();
        });
    }
}
