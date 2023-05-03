<?php

namespace App\Http\Livewire\ModuloAdministrador\Inscripcion\Pago;

use App\Models\Admision;
use App\Models\CanalPago;
use App\Models\Inscripcion;
use App\Models\Pago;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => '']
    ];

    public $search = '';
    public $modo = 1;
    public $pago_id;
    public $titulo = 'Crear Pago';

    public $documento;
    public $numero_operacion;
    public $monto;
    public $fecha_pago;
    public $canal_pago;
    public $concepto_pago;
    
    protected $listeners = ['render', 'deletePago'];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'numero_operacion' => 'required|numeric',
            'documento' => 'required|digits_between:8,9|numeric',
            'monto' => 'required|numeric',
            'fecha_pago' => 'required|date',
            'canal_pago' => 'required|numeric',
            'concepto_pago' => 'required|numeric'
        ]);
    }

    public function modo()  
    {
        $this->limpiar();
        $this->modo = 1;
    }

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset('documento','numero_operacion','monto','fecha_pago','canal_pago','concepto_pago');
        $this->modo = 1;
        $this->titulo = "Crear Pago";
    }

    public function cargarIdPago(Pago $pago)
    {
        $this->limpiar();
        $this->modo = 2;
        $this->titulo = 'Actualizar Pago - Nro Operación: '  . $pago->pago_operacion;
        $this->pago_id = $pago->id_pago;
        
        $this->documento = $pago->pago_documento;
        $this->numero_operacion = $pago->pago_operacion;
        $this->monto = number_format($pago->pago_monto,2);
        $this->fecha_pago = $pago->pago_fecha;
        $this->canal_pago = $pago->id_canal_pago;
        $this->concepto_pago = $pago->id_concepto_pago;
    }

    public function guardarPago()
    {
        $this->validate([
            'numero_operacion' => 'required|numeric',
            'documento' => 'required|digits_between:8,9|numeric',
            'monto' => 'required|numeric',
            'fecha_pago' => 'required|date',
            'canal_pago' => 'required|numeric',
            'concepto_pago' => 'required|numeric'
        ]);

        //validacion de numero de operacion repetido y dni repetido en el mismo dia
        if ($this->modo == 1) {
            $validar = Pago::where('pago_operacion', $this->numero_operacion)->first();
            
            if ($validar) {
                if($validar->pago_documento == $this->documento && $validar->pago_fecha == $this->fecha_pago){
                    $this->dispatchBrowserEvent('alertaPago', [
                        'titulo' => '¡Alerta!',
                        'subtitulo' => 'El número de operación y el DNI ya se encuentran registrados en el sistema.',
                        'icon' => 'error'
                    ]);
                    return back();
                }else if ($validar->pago_fecha == $this->fecha_pago) {
                    $this->dispatchBrowserEvent('alertaPago', [
                        'titulo' => '¡Alerta!',
                        'subtitulo' => 'El número de operación ya ha sido ingresado en la fecha seleccionada.',
                        'icon' => 'error'
                    ]);
                    return back();
                }else if($validar->pago_documento == $this->documento){
                    $this->dispatchBrowserEvent('alertaPago', [
                        'titulo' => '¡Alerta!',
                        'subtitulo' => 'El número de operación y el DNI ya existen en el registro de pagos.',
                        'icon' => 'error'
                    ]);
                    return back();
                }
            }
        }

        if ($this->modo == 1) {
            $pago = Pago::create([
                "pago_documento" => $this->documento,
                "pago_operacion" => $this->numero_operacion,
                "pago_monto" => $this->monto,
                "pago_fecha" => $this->fecha_pago,
                "pago_estado" => 2,
                "id_canal_pago" => $this->canal_pago,
                "id_canal_pago" => $this->concepto_pago,
            ]);

            //  obtener el ultimo codigo de inscripcion
            $ultimo_codifo_inscripcion = Inscripcion::orderBy('inscripcion_codigo','DESC')->first();
            if($ultimo_codifo_inscripcion == null)
            {
                $codigo_inscripcion = 'IN0001';
            }else
            {
                $codigo_inscripcion = $ultimo_codifo_inscripcion->inscripcion_codigo;
                $codigo_inscripcion = substr($codigo_inscripcion, 2, 6);
                $codigo_inscripcion = intval($codigo_inscripcion) + 1;
                $codigo_inscripcion = str_pad($codigo_inscripcion, 4, "0", STR_PAD_LEFT);
                $codigo_inscripcion = 'IN'.$codigo_inscripcion;
            }

            // crear la inscripcion
            $inscripcion = new Inscripcion();
            $inscripcion->inscripcion_codigo = $codigo_inscripcion;
            $inscripcion->estado = 'activo';
            $inscripcion->admision_cod_admi = Admision::where('estado', 1)->first()->cod_admi;
            $inscripcion->save();

            // asigar el pago creado a la tabla de inscripcion pago
            $inscripcion_pago = new InscripcionPago();
            $inscripcion_pago->pago_id = $pago->pago_id;
            $inscripcion_pago->inscripcion_id = $inscripcion->id_inscripcion;
            $inscripcion_pago->concepto_pago_id = 1;
            $inscripcion_pago->save();

            $this->dispatchBrowserEvent('notificacionPago', ['message' =>'Pago creado satisfactoriamente.', 'color' => '#2eb867']);
        }else{
            $pago = Pago::find($this->pago_id);
            $pago->dni = $this->documento;
            $pago->nro_operacion = $this->numero_operacion;
            $pago->monto = $this->monto;
            $pago->fecha_pago = $this->fecha_pago;
            $pago->canal_pago_id = $this->canal_pago;
            $pago->canal_pago_id = $this->concepto_pago;
            $pago->save();

            $this->dispatchBrowserEvent('notificacionPago', ['message' =>'Pago '.$this->numero_operacion.' actualizado satisfactoriamente.', 'color' => '#2eb867']);
        }

        $this->dispatchBrowserEvent('modalPago');

        $this->limpiar();
    }

    public function eliminar($pago_id)
    {
        $this->dispatchBrowserEvent('deletePago', ['id' => $pago_id]);
    }

    public function deletePago(Pago $pago)
    {
        $pago->delete();
        $this->dispatchBrowserEvent('notificacionPago', ['message' =>'Pago eliminado satisfactoriamente.', 'color' => '#ea4b43']);
    }

    public function render()
    {
        $buscar = $this->search;
        $pago = Pago::where('fecha_pago','LIKE',"%{$buscar}%")
                ->orWhere('dni','LIKE',"%{$buscar}%")
                ->orWhere('nro_operacion','LIKE',"%{$buscar}%")
                ->orWhere('pago_id','LIKE',"%{$buscar}%")
                ->orderBy('pago_id','DESC')->paginate(200);
        $canalPago = CanalPago::all();
        return view('livewire.modulo-administrador.inscripcion.pago.index', [
            'pago' => $pago,
            'canalPago' => $canalPago
        ]);
    }
}
