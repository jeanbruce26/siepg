<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correo extends Model
{
    use HasFactory;

    protected $table = 'correo';
    protected $primaryKey = 'id_correo';
    protected $fillable = [
        'correo_asunto',
        'correo_mensaje',
        'correo_enviados',
        'correo_estado'
    ];

    public function getEnviadosAttribute()
    {
        ///...
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('correo_asunto', 'LIKE', "%$search%")
                ->orWhere('correo_mensaje', 'LIKE', "%$search%");
        }
    }
}
