<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $primaryKey = 'id_usuario';
    protected $table = 'usuario';
    protected $fillable = [
        'id_usuario',
        'usuario_nombre',
        'usuario_correo',
        'usuario_password',
        'id_trabajador_tipo_trabajador',
        'usuario_estado',
    ];

    public $timestamps = false;

    // Trabajador Tipo Trabajador
    public function trabajador_tipo_trabajador(){
        return $this->belongsTo(TrabajadorTipoTrabajador::class,
        'id_trabajador_tipo_trabajador','id_trabajador_tipo_trabajador');
    }
}
