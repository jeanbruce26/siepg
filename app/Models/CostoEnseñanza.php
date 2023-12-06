<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CostoEnseñanza extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_costo_enseñanza';
    protected $table = 'costo_enseñanza';
    protected $fillable = [
        'id_costo_enseñanza',
        'id_plan',
        'programa_tipo',
        'costo_credito',
        'costo_enseñanza_fecha_creacion',
        'costo_enseñanza_estado',
    ];

    public $timestamps = false;

    // Plan
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'id_plan');
    }
}
