<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tipo_documento';
    protected $table = 'tipo_documento';
    protected $fillable = [
        'id_tipo_documento',
        'tipo_documento',
        'tipo_documento_estado',
    ];

    public $timestamps = false;

    // Persona
    public function persona()
    {
        return $this->hasMany(Persona::class,
        'id_tipo_documento', 'id_tipo_documento');
    }
}
