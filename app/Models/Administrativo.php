<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrativo extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_administrativo';
    protected $table = 'administrativo';
    protected $fillable = [
        'id_administrativo',
        'id_area_administrativo',
        'administrativo_estado',
        'id_trabajador',
    ];

    public $timestamps = false;

    // Trabajador
    public function trabajador(){
        return $this->belongsTo(Trabajador::class,
        'id_trabajador','id_trabajador');
    }
    // Area
    public function area_administrativo(){
        return $this->belongsTo(AreaAdministrativo::class,
        'id_area_administrativo','id_area_administrativo');
    }
}
