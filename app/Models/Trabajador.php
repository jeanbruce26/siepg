<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    use HasFactory;

    protected $primaryKey = "trabajador_id";

    protected $table = 'trabajador';
    protected $fillable = [
        'trabajador_id',
        'trabajador_nombres',
        'trabajador_apellidos',
        'trabajador_numero_documento',
        'trabajador_correo',
        'trabajador_direccion',
        'trabajador_grado',
        'trabajador_perfil',
        'trabajador_estado',
    ];

    public $timestamps = false;

    // Trabajador Tipo Trabajador
    public function trabajador_tipo_trabajador(){
        return $this->hasOne(TrabajadorTipoTrabajador::class,
        'trabajador_id','trabajador_id');
    }

    // Administrativo
    public function administrativo(){
        return $this->hasOne(Administrativo::class,
        'trabajador_id','trabajador_id');
    }
}
