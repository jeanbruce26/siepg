<?php

namespace App\Http\Livewire\ModuloPlataforma\Inicio;
use App\Models\Admision;
use App\Models\Admitidos;
use App\Models\Inscripcion;
use App\Models\Persona;
use Carbon\Carbon;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $admision = Admision::where('estado',1)->first(); // admision activa
        $admision_fecha_admitidos = Carbon::parse(Admision::where('estado',1)->first()->fecha_admitidos); //fecha de admision de admitidos
        $admision_fecha_admitidos->locale('es'); // seteamos el idioma
        $admision_fecha_admitidos = $admision_fecha_admitidos->isoFormat('LL'); // formateamos la fecha

        $documento = auth('plataforma')->user()->usuario_estudiante; // documento del usuario logueado
        $id_persona = Persona::where('num_doc', $documento)->first()->idpersona; // id de la persona logueada
        $persona = Persona::where('idpersona', $id_persona)->first(); // persona logueada
        $inscripcion_ultima = Inscripcion::where('persona_idpersona', $id_persona)->orderBy('id_inscripcion', 'desc')->first(); // inscripcion del usuario logueado
        $inscripcion_admision = Admision::where('cod_admi', $inscripcion_ultima->admision_cod_admi)->first(); // admision de la inscripcion del usuario logueado
        $evaluacion = $inscripcion_ultima->evaluacion; // evaluacion de la inscripcion del usuario logueado
        if($evaluacion)
        {
            $admitido = $persona->admitidos->where('evaluacion_id', $evaluacion->evaluacion_id)->first(); // admitido de la inscripcion del usuario logueado
        }
        else
        {
            $admitido = null;
        }

        return view('livewire.modulo-plataforma.inicio.index', [
            'admision' => $admision,
            'admision_fecha_admitidos' => $admision_fecha_admitidos,
            'inscripcion_ultima' => $inscripcion_ultima,
            'inscripcion_admision' => $inscripcion_admision,
            'evaluacion' => $evaluacion,
            'admitido' => $admitido,
        ]);
    }
}
