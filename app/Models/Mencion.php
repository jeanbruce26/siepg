<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mencion extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_mencion';
    protected $table = 'mencion';
    protected $fillable = [
        'id_mencion',
        'mencion_iniciales',
        'mencion',
        'mencion_estado',
        'id_subprograma',
    ];

    public $timestamps = false;

    // Subprograma
    public function subprograma(){
        return $this->belongsTo(SubPrograma::class, 'id_subprograma','id_subprograma');
    }

    // mencion plan
    public function mencion_plan(){
        return $this->hasMany(MencionPlan::class, 'id_mencion', 'id_mencion');
    }
}
