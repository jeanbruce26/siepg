<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puntaje extends Model
{
    use HasFactory;

    protected $primaryKey = "id_puntaje";
    protected $table = 'puntaje';
    protected $fillable = [
        'id_puntaje',
        'puntaje_maestria',
        'puntaje_doctorado',
        'puntaje_estado',
    ];

    public $timestamps = false;

}
