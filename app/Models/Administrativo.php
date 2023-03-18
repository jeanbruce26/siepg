<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrativo extends Model
{
    use HasFactory;

    protected $primaryKey = "administrativo_id";
    protected $table = 'administrativo';
    protected $fillable = [
        'administrativo_id',
        'area_id',
        'trabajador_id',
        'administrativo_estado',
    ];

    public $timestamps = false;

    // Trabajador
    public function trabajador(){
        return $this->belongsTo(Trabajador::class,
        'trabajador_id','trabajador_id');
    }
    // Area
    public function area_administrativo(){
        return $this->belongsTo(AreaAdministrativo::class,
        'area_id','area_id');
    }
}
