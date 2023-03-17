<?php

namespace App\Jobs;

use App\Models\Admision;
use App\Models\Expediente;
use App\Models\ExpedienteInscripcion;
use App\Models\ExpedienteInscripcionSeguimiento;
use App\Models\Inscripcion;
use App\Models\InscripcionPago;
use App\Models\Mencion;
use App\Models\Persona;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProcessUpdateFichaInscripcion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $inscripcion;

    /**
     * Create a new job instance.
     */
    public function __construct($inscripcion)
    {
        $this->inscripcion = $inscripcion;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $id = $this->inscripcion->id_inscripcion;
        $inscripcion = Inscripcion::where('id_inscripcion',$id)->first(); // Datos de la inscripcion

        $inscripcion_pago = InscripcionPago::where('inscripcion_id',$id)->first();
        $monto_pago = $inscripcion_pago->pago->monto; // Monto del pago

        $admision = Admision::where('estado',1)->first()->admision; // Proceso de admision actual
        $admision_year = Admision::where('estado',1)->first()->admision_year; // Año del proceso de admision actual

        $fecha_actual = date('h:i:s a d/m/Y', strtotime($inscripcion->fecha_inscripcion)); // Fecha de inscripcion
        $fecha_actual2 = date('d-m-Y', strtotime($inscripcion->fecha_inscripcion)); // Fecha de inscripcion
        $mencion = Mencion::where('id_mencion',$inscripcion->id_mencion)->first(); // Mencion de la inscripcion
        $inscripcion_codigo = Inscripcion::where('id_inscripcion',$id)->first()->inscripcion_codigo;
        $tiempo = 6;
        $valor = '+ '.intval($tiempo).' month';
        setlocale( LC_ALL,"es_ES@euro","es_ES","esp" );
        $final = strftime('%d de %B del %Y', strtotime($fecha_actual2.$valor));
        $persona = Persona::where('idpersona', $inscripcion->persona_idpersona)->first();
        $expediente_inscripcion = ExpedienteInscripcion::where('id_inscripcion',$id)->get();
        $expediente = Expediente::where('estado', 1)
                    ->where(function($query) use ($inscripcion){
                        $query->where('expediente_tipo', 0)
                            ->orWhere('expediente_tipo', $inscripcion->tipo_programa);
                    })
                    ->get();

        // verificamos si tiene expediente en seguimientos
        $seguimiento_count = ExpedienteInscripcionSeguimiento::join('ex_insc', 'ex_insc.cod_ex_insc', '=', 'expediente_inscripcion_seguimiento.cod_ex_insc')
                                                        ->where('ex_insc.id_inscripcion', $id)
                                                        ->where('expediente_inscripcion_seguimiento.tipo_seguimiento', 1)
                                                        ->where('expediente_inscripcion_seguimiento.expediente_inscripcion_seguimiento_estado', 1)
                                                        ->count();

        $data = [
            'persona' => $persona,
            'fecha_actual' => $fecha_actual,
            'mencion' => $mencion,
            'admision' => $admision,
            'inscripcion_pago' => $inscripcion_pago,
            'inscripcion' => $inscripcion,
            'inscripcion_codigo' => $inscripcion_codigo,
            'monto_pago' => $monto_pago,
            'final' => $final,
            'expediente_inscripcion' => $expediente_inscripcion,
            'expediente' => $expediente,
            'seguimiento_count' => $seguimiento_count
        ];

        $nombre_pdf = 'ficha-inscripcion-' . Str::slug($persona->nombre_completo, '-') . '.pdf';
        $path = 'Posgrado/' . $admision. '/' . $persona->num_doc . '/' . 'Expedientes' . '/';
        $pdf = PDF::loadView('modulo-inscripcion.ficha-inscripcion', $data)->save(public_path($path . $nombre_pdf));

        $inscripcion = Inscripcion::find($id);
        $inscripcion->inscripcion = $path . $nombre_pdf;
        $inscripcion->save();

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
    }
}
