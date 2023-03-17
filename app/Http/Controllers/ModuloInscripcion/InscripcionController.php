<?php

namespace App\Http\Controllers\ModuloInscripcion;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessRegistroFichaInscripcion;
use App\Models\Admision;
use App\Models\Expediente;
use App\Models\ExpedienteInscripcion;
use App\Models\ExpedienteInscripcionSeguimiento;
use App\Models\Inscripcion;
use App\Models\InscripcionPago;
use App\Models\Mencion;
use App\Models\Persona;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;

class InscripcionController extends Controller
{
    public function auth()
    {
        $admision = Admision::where('estado', 1)->first()->admision;
        $admision_year = Admision::where('estado', 1)->first()->admision_year;
        $admision = ucwords(strtolower($admision));
        return view('modulo-inscripcion.auth', [
            'admision' => $admision,
            'admision_year' => $admision_year
        ]);
    }

    public function registro()
    {
        $pago_id = auth('inscripcion')->user()->pago_id;
        $inscripcion_pago = InscripcionPago::where('pago_id', $pago_id)->first();
        $id_inscripcion = $inscripcion_pago->inscripcion_id;
        return view('modulo-inscripcion.registro', [
            'id_inscripcion' => $id_inscripcion
        ]);
    }

    public function gracias($id_inscripcion)
    {
        $inscripcion = Inscripcion::find($id_inscripcion);
        if (!$inscripcion) {
            abort(404);
        }
        return view('modulo-inscripcion.gracias', [
            'id_inscripcion' => $id_inscripcion
        ]);
    }

    public function ficha_inscripcion_email($id)
    {
        // $inscripcion = Inscripcion::where('id_inscripcion',$id)->first(); // Datos de la inscripcion

        // $inscripcion_pago = InscripcionPago::where('inscripcion_id',$id)->first();
        // $monto_pago = $inscripcion_pago->pago->monto; // Monto del pago

        // $admision = Admision::where('estado',1)->first()->admision; // Proceso de admision actual
        // $admision_year = Admision::where('estado',1)->first()->admision_year; // Año del proceso de admision actual

        // $fecha_actual = date('h:i:s a d/m/Y', strtotime($inscripcion->fecha_inscripcion)); // Fecha de inscripcion
        // $fecha_actual2 = date('d-m-Y', strtotime($inscripcion->fecha_inscripcion)); // Fecha de inscripcion
        // $mencion = Mencion::where('id_mencion',$inscripcion->id_mencion)->first(); // Mencion de la inscripcion
        // $inscripcion_codigo = Inscripcion::where('id_inscripcion',$id)->first()->inscripcion_codigo;
        // $tiempo = 6;
        // $valor = '+ '.intval($tiempo).' month';
        // setlocale( LC_ALL,"es_ES@euro","es_ES","esp" );
        // $final = strftime('%d de %B del %Y', strtotime($fecha_actual2.$valor));
        // $persona = Persona::where('idpersona', $inscripcion->persona_idpersona)->first();
        // $expediente_inscripcion = ExpedienteInscripcion::where('id_inscripcion',$id)->get();
        // $expediente = Expediente::where('estado', 1)
        //             ->where(function($query) use ($inscripcion){
        //                 $query->where('expediente_tipo', 0)
        //                     ->orWhere('expediente_tipo', $inscripcion->tipo_programa);
        //             })
        //             ->get();

        // // verificamos si tiene expediente en seguimientos
        // $seguimiento_count = ExpedienteInscripcionSeguimiento::join('ex_insc', 'ex_insc.cod_ex_insc', '=', 'expediente_inscripcion_seguimiento.cod_ex_insc')
        //                                                 ->where('ex_insc.id_inscripcion', $id)
        //                                                 ->where('expediente_inscripcion_seguimiento.tipo_seguimiento', 1)
        //                                                 ->where('expediente_inscripcion_seguimiento.expediente_inscripcion_seguimiento_estado', 1)
        //                                                 ->count();

        // $data = [
        //     'persona' => $persona,
        //     'fecha_actual' => $fecha_actual,
        //     'mencion' => $mencion,
        //     'admision' => $admision,
        //     'inscripcion_pago' => $inscripcion_pago,
        //     'inscripcion' => $inscripcion,
        //     'inscripcion_codigo' => $inscripcion_codigo,
        //     'monto_pago' => $monto_pago,
        //     'final' => $final,
        //     'expediente_inscripcion' => $expediente_inscripcion,
        //     'expediente' => $expediente,
        //     'seguimiento_count' => $seguimiento_count
        // ];

        // $nombre_pdf = 'ficha-inscripcion-' . Str::slug($persona->nombre_completo, '-') . '.pdf';
        // $path = 'Posgrado/' . $admision. '/' . $persona->num_doc . '/' . 'Expedientes' . '/';
        // $pdf = PDF::loadView('modulo-inscripcion.ficha-inscripcion', $data)->save(public_path($path.$nombre_pdf));
        // $pdf2 = PDF::loadView('modulo-inscripcion.ficha-inscripcion', $data);
        // $pdf_email = $pdf2->output();

        // $inscripcion = Inscripcion::find($id);
        // $inscripcion->inscripcion = $path . $nombre_pdf;
        // $inscripcion->save();

        // // enviar ficha de inscripcion por correo
        // $detalle = [
        //     'nombre' => ucwords(strtolower($persona->nombre_completo)),
        //     'admision' => ucwords(strtolower($admision)),
        //     'email' => $persona->email,
        // ];

        // Mail::send('modulo-inscripcion.email', $detalle, function ($message) use ($detalle, $pdf_email, $nombre_pdf) {
        //     $message->to($detalle['email'])
        //             ->subject('Ficha de Inscripción')
        //             ->attachData($pdf_email, $nombre_pdf, ['mime' => 'application/pdf']);
        // });

        // Proceso para generar el pdf de inscripcion y enviarlo al correo
        $inscripcion = Inscripcion::find($id); // Datos de la inscripcion
        ProcessRegistroFichaInscripcion::dispatch($inscripcion); // Proceso para generar el pdf de inscripcion y enviarlo al correo

        // cerrar sesion
        auth('inscripcion')->logout();

        // redireccionar a la pagina final
        return redirect()->route('inscripcion.gracias', ['id' => $id]);
    }

    public function logout()
    {
        auth('inscripcion')->logout();
        return redirect()->route('inscripcion.auth');
    }
}
