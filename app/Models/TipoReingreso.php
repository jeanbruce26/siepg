<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoReingreso extends Model
{
    use HasFactory;

    protected $primaryKey = "id_tipo_reingreso";
    protected $table = "tipo_reingreso";
    protected $fillable = [
        'id_tipo_reingreso',
        'tipo_reingreso',
        'tipo_reingreso_estado'
    ];

    public $timestamps = false;
}
