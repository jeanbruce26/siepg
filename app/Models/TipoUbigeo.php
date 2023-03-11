<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoUbigeo extends Model
{
    use HasFactory;

    protected $primaryKey = "cod_tipo";
    protected $table = 'tipo_ubigeo';
    protected $fillable = [
        'cod_tipo',
        'tipo_ubigeo',
    ];

    public $timestamps = false;

    public function ubigeo_persona()
    {
        return $this->hasMany(UbigeoPersona::class, 'tipo_ubigeo_cod_tipo');
    }
}
