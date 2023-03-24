<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_departamento';
    protected $table = 'departamento';
    protected $fillable = [
        'id_departamento',
        'departamento',
        'ubigeo',
    ];

    public $timestamps = false;

    // Provincia
    public function provincia()
    {
        return $this->hasMany(Provincia::class,
        'id_departamento', 'id_departamento');
    }
}
