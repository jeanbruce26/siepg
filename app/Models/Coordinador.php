<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coordinador extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_coordinador';
    protected $table = 'coordinador';
    protected $fillable = [
        'id_coordinador',
        'id_categoria_docente',
        'coordinador_estado',
        'id_facultad',
        'id_trabajador',
    ];

    public $timestamps = false;

    public function categoria_docente(){
        return $this->belongsTo(CategoriaDocente::class,
        'id_categoria_docente','id_conceid_categoria_docentepto_pago');
    }

    public function facultad(){
        return $this->belongsTo(Facultad::class,
        'id_facultad','id_facultad');
    }

    public function trabajador(){
        return $this->belongsTo(Trabajador::class,
        'id_trabajador','id_trabajador');
    }
}
