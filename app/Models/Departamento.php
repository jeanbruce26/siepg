<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $primaryKey = "id";
    protected $table = 'departamento';
    protected $fillable = [
        'id',
        'departamento',
        'ubigeo',
    ];

    public $timestamps = false;

    public function provincia()
    {
        return $this->hasMany(Provincia::class, 'id_departamento');
    }
}
