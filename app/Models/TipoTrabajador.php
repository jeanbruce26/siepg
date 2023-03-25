<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoTrabajador extends Model
{
    use HasFactory;

    protected $primaryKey = "id_tipo_trabajador";
    protected $table = 'tipo_trabajador';
    
    protected $fillable = [
        'id_tipo_trabajador',
        'tipo_trabajador',
        'tipo_trabajador_estado',
    ];

    public $timestamps = false;

    // Trabajador Tipo Trabajador
    public function trabajador_tipo_trabajador(){
        return $this->hasMany(TrabajadorTipoTrabajador::class,
        'id_tipo_trabajador','id_tipo_trabajador');
    }
}
