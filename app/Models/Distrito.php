<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_distrito';
    protected $table = 'distrito';
    protected $fillable = [
        'id_distrito',
        'distrito',
        'ubigeo',
        'id_provincia',
    ];

    public $timestamps = false;

    // Provincia
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'id_provincia', 'id_provincia');
    }

    // Persona direccion
    public function persona_direccion()
    {
        return $this->hasMany(Persona::class, 'id_distrito', 'ubigeo_direccion');
    }

    // Persona nacimiento
    public function persona_nacimiento()
    {
        return $this->hasMany(Persona::class, 'id_distrito', 'ubigeo_nacimiento');
    }
}
