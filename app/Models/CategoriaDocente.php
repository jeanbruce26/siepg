<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaDocente extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_categoria_docente';
    protected $table = 'categoria_docente';
    protected $fillable = [
        'id_categoria_docente',
        'categoria_docente',
        'categoria_docente_estado',
    ];

    public $timestamps = false;

    public function coordinador(){
        return $this->hasMany(Coordinador::class,
        'id_categoria_docente','id_categoria_docente');
    }
}
