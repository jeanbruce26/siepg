<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaAdministrativo extends Model
{
    use HasFactory;

    protected $primaryKey = "area_id";
    protected $table = 'area_administrativo';
    protected $fillable = [
        'area_id',
        'area',
    ];

    public $timestamps = false;

    // Administrativo
    public function administrativo(){
        return $this->hasMany(Administrativo::class,
        'area_id','area_id');
    }
}
