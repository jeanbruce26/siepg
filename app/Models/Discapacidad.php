<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discapacidad extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_discapacidad';
    protected $table = 'discapacidad';
    protected $fillable = [
        'id_discapacidad',
        'discapacidad',
        'discapacidad_estado',
    ];

    public $timestamps = false;

    // Persona
    public function persona()
    {
        return $this->hasMany(Persona::class, 'id_discapacidad', 'id_discapacidad');
    }
}
