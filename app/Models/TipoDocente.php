<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocente extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tipo_docente';
    protected $table = 'tipo_docente';
    protected $fillable = [
        'id_tipo_docente',
        'tipo_docente',
        'tipo_docente_estado',
    ];

    public $timestamps = false;

    // Docente
    public function docente()
    {
        return $this->hasMany(Docente::class, 'id_tipo_docente', 'id_tipo_docente');
    }
}
