<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionPagos\Pago;

use App\Models\Admision;
use App\Models\CanalPago;
use App\Models\ConceptoPago;
use App\Models\Inscripcion;
use App\Models\Pago;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';//paginacion de bootstrap
    
    // Para poder agregar los parámetros de búsqueda en la URL 
    protected $queryString = [
        'search' => ['except' => ''],
        'filtroProceso' => ['except' => ''],
    ];

    // Definimos las variables para la vista del componente Livewire
    public $search = '';
    public $modo = 1; // Modo 1 = Agregar o nuevo | Modo 2 = Actualizar o editar
    public $pago_id;
    public $titulo = 'Crear Pago';
    public $iteracion = 0;

    // Variables de la tabla pagos
    public $documento;
    public $numero_operacion;
    public $monto;
    public $fecha_pago;
    public $voucher_url;
    public $canal_pago;
    public $concepto_pago;

    // Variables para filtrar los pagos
    public $filtroProceso;
    public $filtro_proceso;

    protected $listeners = ['render', 'deletePago'];// Para escuchar estos dos eventos

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'numero_operacion' => 'required|numeric',
            'documento' => 'required|digits_between:8,9|numeric',
            'monto' => 'required|numeric',
            'fecha_pago' => 'required|date',
            'voucher_url' => 'required',
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
        $this->resetErrorBag();// Eliminamos los errores de la validación
        $this->reset('documento','numero_operacion','monto','fecha_pago','voucher_url','canal_pago','concepto_pago'); // Reseteamos las vatiables de la vista
        $this->modo = 1;// Modo nuevo o agregar
        $this->titulo = "Crear Pago";
    }

    public function alertaConfirmacion($title, $text, $icon, $confirmButtonText, $cancelButtonText, $confimrColor, $cancelColor, $metodo, $id)
    {
        $this->dispatchBrowserEvent('alertaConfirmacion', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'cancelButtonText' => $cancelButtonText,
            'confimrColor' => $confimrColor,
            'cancelColor' => $cancelColor,
            'metodo' => $metodo,
            'id' => $id,
        ]);
    }

    public function alertaPago($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta-pago', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    public function cargarIdPago(Pago $pago)
    {
        $this->limpiar();
        $this->modo = 2;// Modo actualizar o editar
        $this->titulo = 'Actualizar Pago - Nro Operación: '  . $pago->pago_operacion;
        $this->pago_id = $pago->id_pago;
        
        $this->documento = $pago->pago_documento;
        $this->numero_operacion = $pago->pago_operacion;
        $this->monto = number_format($pago->pago_monto,2);//Formateamos el monto con dos decimales
        $this->fecha_pago = $pago->pago_fecha;
        $this->canal_pago = $pago->id_canal_pago;
        $this->concepto_pago = $pago->id_concepto_pago;
    }

    //Filtra los pagos por proceso
    public function filtrar()
    {
        $this->resetear_filtro();
        $this->filtroProceso = $this->filtro_proceso;
    }

    //Limpiar el filtro de proceso
    public function resetear_filtro()
    {
        $this->reset('filtroProceso','filtro_proceso');
    }

    public function validacionDatos()
    {
        // Validación de número de operación, dni y fecha repetidos
        $validar = Pago::where('pago_operacion', $this->numero_operacion)->first();
        if ($validar) {
            if($validar->pago_documento == $this->documento && $validar->pago_fecha == $this->fecha_pago){
                $this->alertaPago('¡Información!', 'El número de operación y el DNI ya fueron registrados en el sistema.', 'info', 'Aceptar', 'info');
                return back();// Retornamos
            }else if ($validar->pago_fecha == $this->fecha_pago) {
                $this->alertaPago('¡Información!', 'El número de operación ya se encuentra registrado en el sistema.', 'info', 'Aceptar', 'info');
                return back();// Retornamos
            }else if($validar->pago_documento == $this->documento){
                $this->alertaPago('¡Información!', 'El número de operación y el DNI ya existen en el sistema.', 'info', 'Aceptar', 'info');
                return back();// Retornamos
            }
        }
    }

    public function guardarPago()
    {
        if ($this->modo == 1) {// Modo nuevo o agregar
            // Validamos los campos de la vista
            $this->validate([
                'numero_operacion' => 'required|numeric',
                'documento' => 'required|digits_between:8,9|numeric',
                'monto' => 'required|numeric',
                'fecha_pago' => 'required|date',
                'voucher_url' => 'required',
                'canal_pago' => 'required|numeric',
                'concepto_pago' => 'required|numeric'
            ]);

            $this->validacionDatos();// Validamos los datos repetidos de DNI, nro de operación y fecha

            // validar si el monto ingresado es igual al monto por concepto de seleccionado
            $concepto_pago_monto = ConceptoPago::where('id_concepto_pago', $this->concepto_pago)->first()->concepto_pago_monto;
            if($this->monto_operacion != $concepto_pago_monto)
            {
                $this->alertaPago('¡Error!', 'El monto ingresado no es igual al monto por concepto seleccionado', 'info', 'Cerrar', 'danger');
                return redirect()->back();// Retornamos
            }

            // Crear el pago con los datos ingresados en el sistema
            $pago = new Pago();
            $pago->pago_documento = $this->documento_identidad;
            $pago->pago_operacion = $this->numero_operacion;
            $pago->pago_monto = $this->monto_operacion;
            $pago->pago_fecha = $this->fecha_pago;
            $pago->pago_estado = 1;
            $pago->pago_verificacion = 1;
            if($this->voucher_url)
            {
                $admision = Admision::where('admision_estado', 1)->first()->admision;
                $path = 'Posgrado/' . $admision . '/' . $this->documento_identidad . '/' . 'Voucher/';// Ruta del voucher
                $filename = 'voucher-pago.' . $this->voucher_url->getClientOriginalExtension();// Nombre del voucher con su extención
                $nombre_db = $path.$filename;
                $data = $this->voucher_url;
                $data->storeAs($path, $filename, 'files_publico');// Guardamos el voucher
                $pago->pago_voucher_url = $nombre_db;
            }
            $pago->id_canal_pago = $this->canal_pago;
            $pago->id_concepto_pago = 1;
            $pago->save();            

            // Si el concepto de pago es "Inscripción", creamos su inscripción
            if($pago->id_concepto_pago == 1){
                // Obtener el último código de inscripción
                $ultimo_codifo_inscripcion = Inscripcion::orderBy('inscripcion_codigo','DESC')->first();
                // Generar el código de inscripción
                if($ultimo_codifo_inscripcion == null)
                {
                    $codigo_inscripcion = 'IN0001';// Si no existen códigos anteriores 
                }else
                {
                    $codigo_inscripcion = $ultimo_codifo_inscripcion->inscripcion_codigo;
                    $codigo_inscripcion = substr($codigo_inscripcion, 2, 6);// Obtenemos el código apartir del 3er caracter, agarrando los 6 primeros caracteres de la cadena
                    $codigo_inscripcion = intval($codigo_inscripcion) + 1;// Convertimos la variable en entero, y le incrementamos 1 al valor obtenido
                    $codigo_inscripcion = str_pad($codigo_inscripcion, 4, "0", STR_PAD_LEFT);// Formateamos la cadena con una longitud de 4, agregando ceros "0" a la izquierda si es necesario, o la cadena es menor que 4
                    $codigo_inscripcion = 'IN'.$codigo_inscripcion;// Concatenamos el código, con el formato requerido "IN"
                }

                // Crear la inscripcion
                $inscripcion = new Inscripcion();
                $inscripcion->inscripcion_codigo = $codigo_inscripcion;
                $inscripcion->inscripcion_estado = 1;
                $inscripcion->id_pago = $pago->id_pago;
                $inscripcion->id_programa_proceso = null;
                $inscripcion->save();
            }

            $this->alertaPago('¡Éxito!', 'El pago ' . $pago->pago_operacion . ' por concepto de ' . $pago->concepto_pago->concepto_pago . ' ha sido creado satisfactoriamente.', 'success', 'Aceptar', 'success');

        }else{// Modo actualizar o editar
            // Validamos los campos de la vista
            $this->validate([
                'numero_operacion' => 'required|numeric',
                'documento' => 'required|digits_between:8,9|numeric',
                'monto' => 'required|numeric',
                'fecha_pago' => 'required|date',
                'voucher_url' => 'nullable',
                'canal_pago' => 'required|numeric',
                'concepto_pago' => 'required|numeric'
            ]);

            $this->validacionDatos();// Validamos los datos repetidos de DNI, nro de operación y fecha

            $pago = Pago::find($this->pago_id);
            $pago->pago_documento = $this->documento;
            $pago->pago_operacion = $this->numero_operacion;
            $pago->pago_monto = $this->monto;
            $pago->pago_fecha = $this->fecha_pago;
            if($this->voucher_url)
            {
                if (file_exists($pago->pago_voucher_url)) {
                    unlink($pago->pago_voucher_url);// Si el voucher existe, se elimina para que no haya problemas al momento de asignar un nuevo voucher de pago
                }
                $admision = Admision::where('admision_estado', 1)->first()->admision;
                $path = 'Posgrado/' . $admision . '/' . $this->documento_identidad . '/' . 'Voucher/';// Ruta del voucher
                $filename = 'voucher-pago.' . $this->voucher_url->getClientOriginalExtension();// Nombre del voucher con su extención
                $nombre_db = $path.$filename;
                $data = $this->voucher_url;
                $data->storeAs($path, $filename, 'files_publico');// Guardamos el voucher
                $pago->pago_voucher_url = $nombre_db;
            }
            // $pago->pago_voucher_url = $this->voucher_url;
            $pago->id_canal_pago = $this->canal_pago;
            $pago->id_concepto_pago = $this->concepto_pago;
            $pago->save();

            $this->alertaPago('¡Éxito!', 'El pago ' . $pago->pago_operacion . ' por concepto de ' . $pago->concepto_pago->concepto_pago . ' ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');
        }

        // Cerramos el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalPago'
        ]);

        $this->limpiar();
    }

    public function eliminar($pago_id)
    {
        $this->alertaConfirmacion('¿Estás seguro?', '¿Desea eliminar el pago seleccionado?', 'question', 'Eliminar', 'Cancelar', 'primary', 'danger', 'deletePago', $pago_id);
    }

    public function deletePago(Pago $pago)
    {
        $pago->delete();
        $this->alertaPago('¡Éxito!', 'El pago ' . $pago->pago_operacion . ' por concepto de ' . $pago->concepto_pago->concepto_pago . ' ha sido eliminado satisfactoriamente.', 'success', 'Aceptar', 'success');
    }

    public function render()
    {
        $pago_model = Pago::where(function ($query){
                            $query->where('pago_fecha','LIKE',"%{$this->search}%")
                            ->orWhere('pago_documento','LIKE',"%{$this->search}%")
                            ->orWhere('pago_operacion','LIKE',"%{$this->search}%")
                            ->orWhere('id_pago','LIKE',"%{$this->search}%");
                        })
                        ->whereYear('pago_fecha', $this->filtroProceso == null ? '!=' : '=', strval($this->filtroProceso))
                        ->orderBy('id_pago','DESC')
                        ->paginate(200);

        $canalPago = CanalPago::all();
        $conceptoPago = ConceptoPago::all();
        $aniosUnicos = Pago::selectRaw('YEAR(pago_fecha) as anio')
                            ->groupBy('anio')
                            ->pluck('anio');

        return view('livewire.modulo-administrador.gestion-pagos.pago.index', [
            'pago_model' => $pago_model,
            'canalPago' => $canalPago,
            'conceptoPago' => $conceptoPago,
            'aniosUnicos' => $aniosUnicos
        ]);
    }

}
