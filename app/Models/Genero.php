<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genero extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_genero';
    protected $table = 'genero';
    protected $fillable = [
        'id_genero',
        'genero',
        'genero_estado',
    ];

    public $timestamps = false;

    // Persona
    public function persona()
    {
        return $this->hasMany(Persona::class, 'id_genero', 'id_genero');
    }
}
