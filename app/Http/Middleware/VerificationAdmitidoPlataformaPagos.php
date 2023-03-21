<?php

namespace App\Http\Middleware;

use App\Models\Inscripcion;
use App\Models\Persona;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificationAdmitidoPlataformaPagos
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $persona = Persona::where('num_doc', auth('plataforma')->user()->usuario_estudiante)->first(); // persona del usuario logueado
        $inscripcion_ultima = Inscripcion::where('persona_idpersona', $persona->idpersona)->orderBy('id_inscripcion', 'desc')->first(); // inscripcion del usuario logueado
        $evaluacion = $inscripcion_ultima->evaluacion; // evaluacion de la inscripcion del usuario logueado
        if($evaluacion)
        {
            $admitido = $persona->admitidos->where('evaluacion_id', $evaluacion->evaluacion_id)->first(); // admitido de la inscripcion del usuario logueado
        }
        else
        {
            $admitido = null;
        }

        if($admitido)
        {
            return $next($request);
        }
        else
        {
            abort(403, 'No tiene permiso para acceder a esta ruta');
        }
    }
}
