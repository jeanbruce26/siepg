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

    //Categoria Docente
    public function categoria_docente(){
        return $this->belongsTo(CategoriaDocente::class,
        'id_categoria_docente','id_categoria_docente');
    }

    //Facultad
    public function facultad(){
        return $this->belongsTo(Facultad::class,
        'id_facultad','id_facultad');
    }

    //Trabajador
    public function trabajador(){
        return $this->belongsTo(Trabajador::class,
        'id_trabajador','id_trabajador');
    }
}
