<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoCivil extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_estado_civil';
    protected $table = 'estado_civil';
    protected $fillable = [
        'id_estado_civil',
        'estado_civil',
        'estado_civil_estado',
    ];

    public $timestamps = false;

    // Persona
    public function persona()
    {
        return $this->hasMany(Persona::class, 'id_estado_civil', 'id_estado_civil');
    }
}
