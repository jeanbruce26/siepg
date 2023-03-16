<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distrito extends Model
{
    use HasFactory;

    protected $primaryKey = "id";
    protected $table = 'distrito';
    protected $fillable = [
        'id',
        'distrito',
        'ubigeo',
        'id_provincia',
    ];

    public $timestamps = false;

    // Provincia
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'id_provincia');
    }

    // UbigeoPersona
    public function ubigeo_persona()
    {
        return $this->hasMany(UbigeoPersona::class, 'id_distrito');
    }
}
