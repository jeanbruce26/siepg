<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoTrabajador extends Model
{
    use HasFactory;

    protected $primaryKey = "tipo_trabajador_id";

    protected $table = 'tipo_trabajador';
    protected $fillable = [
        'tipo_trabajador_id',
        'tipo_trabajador',
    ];

    public $timestamps = false;
}
