<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrabajadorTipoTrabajador extends Model
{
    use HasFactory;

    protected $primaryKey = "trabajador_tipo_trabajador_id";
    protected $table = 'trabajador_tipo_trabajador';

    protected $fillable = [
        'trabajador_tipo_trabajador_id',
        'trabajador_id',
        'tipo_trabajador_id',
        'trabajador_tipo_trabajador_estado',
    ];

    public $timestamps = false;

    // Trabajador
    public function trabajador(){
        return $this->belongsTo(Trabajador::class,
        'trabajador_id','trabajador_id');
    }

    // Tipo de Trabajador
    public function tipo_trabajador(){
        return $this->belongsTo(TipoTrabajador::class,
        'tipo_trabajador_id','tipo_trabajador_id');
    }

    // Usuario
    public function usuario(){
        return $this->hasOne(Usuario::class,
        'trabajador_tipo_trabajador_id','trabajador_tipo_trabajador_id');
    }
}
