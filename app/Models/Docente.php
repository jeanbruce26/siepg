<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_docente';
    protected $table = 'docente';
    protected $fillable = [
        'id_docente',
        'docente_cv_url',
        'id_tipo_docente',
        'docente_estado',
        'id_trabajador',
    ];

    public $timestamps = false;

    public function tipo_docente()
    {
        return $this->belongsTo(TipoDocente::class, 'id_tipo_docente', 'id_tipo_docente');
    }

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class, 'id_trabajador', 'id_trabajador');
    }

    
}
