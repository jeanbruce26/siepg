<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $primaryKey = "idpersona";
    protected $table = 'persona';
    protected $fillable = [
        'idpersona',
        'num_doc',
        'apell_pater',
        'apell_mater',
        'nombres',
        'nombre_completo',
        'direccion',
        'celular1',
        'celular2',
        'sexo',
        'fecha_naci',
        'email',
        'email2',
        'año_egreso',
        'centro_trab',
        'tipo_doc_cod_tipo',
        'discapacidad_cod_disc',
        'est_civil_cod_est',
        'univer_cod_uni',
        'id_grado_academico',
        'especialidad',
        'pais_extra',
    ];

    public $timestamps = false;

    // Inscripcion
    public function inscripcion(){
        return $this->hasMany(Inscripcion::class,
        'persona_idpersona','idpersona');
    }

    // TipoDocumento
    public function tipo_documento(){
        return $this->belongsTo(TipoDocumento::class,
        'tipo_doc_cod_tipo','id_tipo_doc');
    }

    // EstadoCivil
    public function estado_civil(){
        return $this->belongsTo(EstadoCivil::class,
        'est_civil_cod_est','cod_est');
    }

    // Universidad
    public function universidad(){
        return $this->belongsTo(Universidad::class,
        'univer_cod_uni','cod_uni');
    }

    // Discapacidad
    public function discapacidad(){
        return $this->belongsTo(Discapacidad::class,
        'discapacidad_cod_disc','cod_disc');
    }

    // GradoAcademico
    public function grado_academico(){
        return $this->belongsTo(GradoAcademico::class,
        'id_grado_academico','id_grado_academico');
    }
}
