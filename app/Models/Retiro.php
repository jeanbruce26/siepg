<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Retiro extends Model
{
    use HasFactory;

    protected $primaryKey = "id_retiro";
    protected $table = "retiro";
    protected $fillable = [
        'id_retiro',
        'id_admitido',
        'retiro_fecha_creacion',
        'retiro_estado',
    ];

    public $timestamps = false;

    // admitido
    public function admitido(): BelongsTo
    {
        return $this->belongsTo(Admitido::class, 'id_admitido');
    }
}
