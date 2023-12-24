<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpedienteInscripcion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id_expediente_inscripcion';
    protected $table = 'expediente_inscripcion';
    protected $fillable = [
        'id_expediente_inscripcion',
        'expediente_inscripcion_url',
        'expediente_inscripcion_estado',
        'expediente_inscripcion_fecha',
        'expediente_inscripcion_verificacion',
        'id_expediente_admision',
        'id_inscripcion'
    ];

    public function inscripcion(){
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion','id_inscripcion');
    }

    public function expediente_admision(){
        return $this->belongsTo(ExpedienteAdmision::class, 'id_expediente_admision','id_expediente_admision');
    }

    public function expediente_inscripcion_seguimiento()
    {
        return $this->hasMany(ExpedienteInscripcionSeguimiento::class, 'id_expediente_inscripcion', 'id_expediente_inscripcion');
    }

    // protected static function boot() {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->created_by = auth()->id();
    //     });

    //     static::updating(function ($model) {
    //         $model->updated_by = auth()->id();
    //     });

    //     static::deleting(function ($model) {
    //         $model->deleted_by = auth()->id();
    //         $model->save();
    //     });
    // }
}
