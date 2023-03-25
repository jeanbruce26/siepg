<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrabajadorTipoTrabajador extends Model
{
    use HasFactory;

    protected $primaryKey = "id_trabajador_tipo_trabajador";
    protected $table = 'trabajador_tipo_trabajador';

    protected $fillable = [
        'id_trabajador_tipo_trabajador',
        'id_tipo_trabajador',
        'id_trabajador',
        'trabajador_tipo_trabajador_estado',
    ];

    public $timestamps = false;

    // Trabajador
    public function trabajador(){
        return $this->belongsTo(Trabajador::class,
        'id_trabajador','id_trabajador');
    }

    // Tipo de Trabajador
    public function tipo_trabajador(){
        return $this->belongsTo(TipoTrabajador::class,
        'id_tipo_trabajador','id_tipo_trabajador');
    }

    // Usuario
    public function usuario(){
        return $this->hasOne(Usuario::class,
        'id_trabajador_tipo_trabajador','id_trabajador_tipo_trabajador');
    }
}
