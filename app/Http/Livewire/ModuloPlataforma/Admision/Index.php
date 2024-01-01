<?php

namespace App\Http\Livewire\ModuloPlataforma\Admision;
use App\Models\Admision;
use App\Models\Admitido;
use App\Models\Admitidos;
use App\Models\Encuesta;
use App\Models\EncuestaDetalle;
use App\Models\Evaluacion;
use App\Models\Inscripcion;
use App\Models\Persona;
use App\Models\ProgramaProceso;
use Carbon\Carbon;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $admision = Admision::where('admision_estado',1)->first(); // admision activa
        $admision_fecha_admitidos = Carbon::parse(Admision::where('admision_estado',1)->first()->admision_fecha_resultados); //fecha de admision de admitidos
        $admision_fecha_admitidos->locale('es'); // seteamos el idioma
        $admision_fecha_admitidos = $admision_fecha_admitidos->isoFormat('LL'); // formateamos la fecha

        $usuario = auth('plataforma')->user(); // obtenemos el usuario autenticado en la plataforma
        $persona = Persona::where('id_persona', $usuario->id_persona)->first(); // obtenemos la persona del usuario autenticado en la plataforma
        $admitido = Admitido::where('id_persona', $persona->id_persona)->orderBy('id_admitido', 'desc')->first(); // obtenemos el admitido de la inscripcion de la persona del usuario autenticado en la plataforma
        $inscripcion_ultima = Inscripcion::where('id_persona', $persona->id_persona)->orderBy('id_inscripcion', 'desc')->first(); // inscripcion del usuario logueado
        if ($inscripcion_ultima == null){
            abort(403, 'Pagina no autorizada');
        }
        $inscripcion_admision = ProgramaProceso::where('id_programa_proceso', $inscripcion_ultima->id_programa_proceso)->first(); // admision de la inscripcion del usuario logueado
        $evaluacion = $admitido ? Evaluacion::where('id_evaluacion', $admitido->id_evaluacion)->first() : $inscripcion_ultima->evaluacion()->orderBy('id_evaluacion', 'desc')->first(); // evaluacion de la inscripcion del usuario logueado

        return view('livewire.modulo-plataforma.admision.index', [
            'admision' => $admision,
            'admision_fecha_admitidos' => $admision_fecha_admitidos,
            'inscripcion_ultima' => $inscripcion_ultima,
            'inscripcion_admision' => $inscripcion_admision,
            'evaluacion' => $evaluacion,
            'admitido' => $admitido
        ]);
    }
}
