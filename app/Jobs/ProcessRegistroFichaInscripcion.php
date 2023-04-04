<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Admision;
use App\Models\Expediente;
use App\Models\ExpedienteAdmision;
use App\Models\ExpedienteInscripcion;
use App\Models\ExpedienteInscripcionSeguimiento;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\Persona;
use App\Models\ProgramaProceso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProcessRegistroFichaInscripcion implements ShouldQueue
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

        $pago = Pago::where('id_pago',$inscripcion->id_pago)->first();
        $pago_monto = $pago->pago_monto; // Monto del pago

        $admision = $inscripcion->programa_proceso->admision->admision; // Admision de la inscripcion

        $fecha_actual = date('h:i:s a d/m/Y', strtotime($inscripcion->inscripcion_fecha)); // Fecha de inscripcion
        $fecha_actual2 = date('d-m-Y', strtotime($inscripcion->inscripcion_fecha)); // Fecha de inscripcion
        $programa = ProgramaProceso::where('id_programa_proceso',$inscripcion->id_programa_proceso)->first(); // Programa de la inscripcion
        $inscripcion_codigo = Inscripcion::where('id_inscripcion',$id)->first()->inscripcion_codigo;
        $tiempo = 6;
        $valor = '+ '.intval($tiempo).' month';
        setlocale( LC_ALL,"es_ES@euro","es_ES","esp" );
        $final = strftime('%d de %B del %Y', strtotime($fecha_actual2.$valor));
        $persona = Persona::where('id_persona', $inscripcion->id_persona)->first();
        $expediente_inscripcion = ExpedienteInscripcion::where('id_inscripcion',$id)->get();
        $expediente = ExpedienteAdmision::join('expediente', 'expediente.id_expediente', '=', 'expediente_admision.id_expediente')
                    ->where('expediente_admision.expediente_admision_estado', 1)
                    ->where('expediente.expediente_estado', 1)
                    ->where(function($query) use ($inscripcion){
                        $query->where('expediente.expediente_tipo', 0)
                            ->orWhere('expediente.expediente_tipo', $inscripcion->inscripcion_tipo_programa);
                    })
                    ->get();

        // verificamos si tiene expediente en seguimientos
        $seguimiento_count = ExpedienteInscripcionSeguimiento::join('expediente_inscripcion', 'expediente_inscripcion.id_expediente_inscripcion', '=', 'expediente_inscripcion_seguimiento.id_expediente_inscripcion')
                                                        ->where('expediente_inscripcion.id_inscripcion', $id)
                                                        ->where('expediente_inscripcion_seguimiento.tipo_seguimiento', 1)
                                                        ->where('expediente_inscripcion_seguimiento.expediente_inscripcion_seguimiento_estado', 1)
                                                        ->count();

        $data = [
            'persona' => $persona,
            'fecha_actual' => $fecha_actual,
            'programa' => $programa,
            'admision' => $admision,
            'pago' => $pago,
            'inscripcion' => $inscripcion,
            'inscripcion_codigo' => $inscripcion_codigo,
            'pago_monto' => $pago_monto,
            'expediente_inscripcion' => $expediente_inscripcion,
            'expediente' => $expediente,
            'seguimiento_count' => $seguimiento_count
        ];

        $nombre_pdf = 'ficha-inscripcion-' . Str::slug($persona->nombre_completo, '-') . '.pdf';
        $pdf2 = PDF::loadView('modulo-inscripcion.ficha-inscripcion', $data);
        $pdf_email = $pdf2->output();

        // enviar ficha de inscripcion por correo
        $detalle = [
            'nombre' => ucwords(strtolower($persona->nombre_completo)),
            'admision' => ucwords(strtolower($admision)),
            'correo' => $persona->correo,
        ];

        Mail::send('modulo-inscripcion.email', $detalle, function ($message) use ($detalle, $pdf_email, $nombre_pdf) {
            $message->to($detalle['correo'])
                    ->subject('Ficha de Inscripción - Escuela de Posgrado')
                    ->attachData($pdf_email, $nombre_pdf, ['mime' => 'application/pdf']);
        });
    }
}
