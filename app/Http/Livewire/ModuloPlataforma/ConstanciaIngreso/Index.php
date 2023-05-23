<?php

namespace App\Http\Livewire\ModuloPlataforma\ConstanciaIngreso;

use App\Models\Admitido;
use App\Models\ConstanciaIngreso;
use App\Models\Evaluacion;
use App\Models\Persona;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class Index extends Component
{
    public $persona; // persona del usuario logueado
    public $admitido; // admitido del usuario logueado
    public $constancia; // constancia de ingreso del usuario logueado
    public $pago; // pago de la constancia de ingreso del usuario logueado
    public $verificacion_pago = false; // variable para verificar si el pago se ha realizado

    protected $listeners = [
        'generar_constancia' => 'generar_constancia',
        'recargar_vista' => '$refresh'
    ];

    public function mount()
    {
        $this->persona = Persona::where('numero_documento', auth('plataforma')->user()->usuario_estudiante)->first(); // persona del usuario logueado
        $this->admitido = Admitido::where('id_persona', $this->persona->id_persona)->orderBy('id_admitido', 'desc')->first(); // admitido del usuario logueado
        $this->constancia = ConstanciaIngreso::where('id_admitido', $this->admitido->id_admitido)->orderBy('id_constancia_ingreso', 'desc')->first(); // constancia de ingreso del usuario logueado
        if ($this->constancia == null) {
            abort(403, 'No se ha generado el pago de la constancia de ingreso');
        }
        $this->pago = $this->constancia->pago; // pago de la constancia de ingreso del usuario logueado
        if($this->pago->pago_verificacion == 2)
        {
            $this->verificacion_pago = true;
        }
        else
        {
            $this->verificacion_pago = false;
        }
    }

    public function alerta_generar_constancia()
    {
        $this->dispatchBrowserEvent('alerta_final_constancia', [
            'title' => '¡Exito!',
            'text' => 'Se ha generado la constancia de ingreso correctamente',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);
    }

    public function generar_constancia()
    {
        $datos = Evaluacion::join('inscripcion', 'inscripcion.id_inscripcion', '=', 'evaluacion.id_inscripcion')
                ->join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                ->join('admision', 'admision.id_admision', '=', 'programa_proceso.id_admision')
                ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                ->join('modalidad', 'modalidad.id_modalidad', '=', 'programa.id_modalidad')
                ->where('evaluacion.id_evaluacion', $this->admitido->id_evaluacion)
                ->first();

        $nombre = $this->persona->apellido_paterno . ' ' . $this->persona->apellido_materno . ', ' . $this->persona->nombre;
        $codigo = 'N° ' . $this->admitido->admitido_codigo;
        $admision = ucwords(strtolower($datos->admision));
        if($datos->programa_tipo == 2)
        {
            $programa = 'el DOCTORADO EN ' . $datos->subprograma;
        }
        else if($datos->programa_tipo == 1)
        {
            if($datos->mencion == null)
            {
                $programa = 'la MAESTRIA EN ' . $datos->subprograma;
            }
            else
            {
                $programa = 'la MAESTRIA EN ' . $datos->subprograma . ' CON MENCIÓN EN ' . $datos->mencion;
            }
        }
        if($datos->id_modalidad == 1)
        {
            $modalidad = 'PRESENCIAL';
        }
        else
        {
            $modalidad = 'a DISTANCIA';
        }
        $fecha = Carbon::parse(today());
        $fecha->locale('es');
        $fecha = 'Pucallpa, ' . $fecha->isoFormat('LL');
        if($this->admitido->id_admitido < 10)
        {
            $codigo_constancia = substr($this->admitido->admitido_codigo, 1, 1) . substr($this->admitido->admitido_codigo, 5, 9) . '000' . $this->admitido->id_admitido;
        }
        else if($this->admitido->id_admitido < 100)
        {
            $codigo_constancia = substr($this->admitido->admitido_codigo, 1, 1) . substr($this->admitido->admitido_codigo, 5, 9) . '00' . $this->admitido->id_admitido;
        }
        else if($this->admitido->id_admitido < 1000)
        {
            $codigo_constancia = substr($this->admitido->admitido_codigo, 1, 1) . substr($this->admitido->admitido_codigo, 5, 9) . '0' . $this->admitido->id_admitido;
        }
        else if($this->admitido->id_admitido < 10000)
        {
            $codigo_constancia = substr($this->admitido->admitido_codigo, 1, 1) . substr($this->admitido->admitido_codigo, 5, 9) . $this->admitido->id_admitido;
        }
        $codigo_constancia_qr = QrCode::size(100)->generate($codigo_constancia);

        $data = [
            'nombre' => $nombre,
            'codigo' => $codigo,
            'admision' => $admision,
            'programa' => $programa,
            'modalidad' => $modalidad,
            'fecha' => $fecha,
            'codigo_constancia' => $codigo_constancia_qr
        ];

        $nombre_pdf = 'constancia-ingreso-' . $codigo_constancia . '-' . Str::slug($this->persona->nombre_completo, '-') . '.pdf';
        $path = 'Posgrado/' . $admision . '/' . $this->persona->numero_documento . '/' . 'Expedientes' . '/';
        // $pdf = Pdf::loadView('modulo_administrador.Evaluacion.Admitidos.constancia', $data)->save(public_path($datos->admision.'/'.$datos->id_inscripcion.'/'). $nombre_pdf);
        $pdf = Pdf::loadView('modulo-plataforma.constancia-ingreso.ficha-constancia-ingreso', $data)->save(public_path($path . $nombre_pdf));

        $constancia = ConstanciaIngreso::where('id_admitido', $this->admitido->id_admitido)->first();
        $constancia->constancia_ingreso_codigo = $codigo_constancia;
        $constancia->constancia_ingreso_url = $path . $nombre_pdf;
        $constancia->constancia_ingreso_fecha = Carbon::now();
        $constancia->save();

        // emitir evento para recargar el modulo livewire
        $this->emit('recargar_vista');
    }

    public function render()
    {
        return view('livewire.modulo-plataforma.constancia-ingreso.index');
    }
}
