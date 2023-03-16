<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoCivil extends Model
{
    use HasFactory;

    protected $primaryKey = "cod_est";
    protected $table = 'est_civil';
    protected $fillable = [
        'cod_est',
        'est_civil'
    ];

    public $timestamps = false;

    // Persona
    public function persona()
    {
        return $this->hasMany(Persona::class, 'est_civil_cod_est');
    }
}
