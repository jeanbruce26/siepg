<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaAdministrativo extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_area_administrativo';
    protected $table = 'area_administrativo';
    protected $fillable = [
        'id_area_administrativo',
        'area_administrativo',
        'area_administrativo_estado'
    ];

    public $timestamps = false;

    // Administrativo
    public function administrativo(){
        return $this->hasMany(Administrativo::class,
        'id_area_administrativo','id_area_administrativo');
    }
}
