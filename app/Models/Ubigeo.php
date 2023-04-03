<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubigeo extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_ubigeo';
    protected $table = 'ubigeo';
    protected $fillable = [
        'id_ubigeo',
        'ubigeo',
        'ubigeo_departamento',
        'ubigeo_provincia',
        'ubigeo_distrito',
        'departamento',
        'provincia',
        'distrito',
        'ubigeo_estado',
    ];

    public $timestamps = false;
}
