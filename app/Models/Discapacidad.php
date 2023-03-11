<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discapacidad extends Model
{
    use HasFactory;

    protected $primaryKey = "cod_disc";
    protected $table = 'discapacidad';
    protected $fillable = [
        'cod_disc',
        'discapacidad'
    ];

    public $timestamps = false;

    public function persona()
    {
        return $this->hasMany(Persona::class, 'discapacidad_cod_disc');
    }
}
