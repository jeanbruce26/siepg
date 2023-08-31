<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionPagos\Pago;

use App\Models\Admision;
use App\Models\Admitido;
use App\Models\AdmitidoCiclo;
use App\Models\CanalPago;
use App\Models\ConceptoPago;
use App\Models\ConstanciaIngreso;
use App\Models\Inscripcion;
use App\Models\Matricula;
use App\Models\Mensualidad;
use App\Models\Pago;
use App\Models\PagoObservacion;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;
    use WithFileUploads;
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
    
    //Variable para modal de Ver Pago
    public $observacion;
    public $valida_pago_verificacion;
    public $valida_pago_estado;

    // Variables para filtrar los pagos
    public $filtroProceso;
    public $filtro_proceso;

    public $validarDatosModal = false;

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
        $this->reset('documento','numero_operacion','monto','fecha_pago','voucher_url','canal_pago','concepto_pago','observacion');// Limpiamos las variables
        $this->modo = 1;// Modo nuevo o agregar
        $this->validarDatosModal = false;
        $this->iteracion++;// Para que se limpie el campo de archivo
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

    public function cargarVerPago(Pago $pago)
    {
        $this->limpiar();
        $this->modo = 3;// Modo ver
        $this->titulo = 'Ver Pago';
        $this->pago_id = $pago->id_pago;
        $this->voucher_url = $pago->pago_voucher_url;

        $this->valida_pago_verificacion = $pago->pago_verificacion;
        $this->valida_pago_estado = $pago->pago_estado;

        $this->documento = $pago->pago_documento;
        $this->numero_operacion = $pago->pago_operacion;
        $this->monto = number_format($pago->pago_monto,2);//Formateamos el monto con dos decimales
        $this->fecha_pago = date('d/m/Y', strtotime($pago->pago_fecha));
        $this->canal_pago = $pago->canal_pago->canal_pago;
        
        if ($pago->pago_observacion->count() > 0) {
            if($pago->pago_observacion->first()->pago_observacion_estado == 1)
            {
                $this->observacion = $pago->pago_observacion->first()->pago_observacion;
            }elseif($pago->pago_observacion->first()->pago_observacion_estado == 2){
                $this->observacion = $pago->pago_observacion->first()->pago_observacion;
            }else{
                $this->observacion = '';
            }
        }else{
            $this->observacion = '';
        }
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
        //Si el modo es actualizar, validamos que no se hayan realizado cambios
        if($this->modo == 2)
        {
            $validarPago = Pago::find($this->pago_id);
            if(($validarPago->pago_documento != $this->documento || $validarPago->pago_operacion != $this->numero_operacion || $validarPago->monto != $this->monto || $validarPago->pago_fecha != $this->fecha_pago || $validarPago->id_canal_pago != $this->canal_pago || $validarPago->id_concepto_pago != $this->concepto_pago) && $this->voucher_url == null)
            {
                $this->alertaPago('¡Información!', 'No se realizaron cambios en el pago seleccionado.', 'info', 'Aceptar', 'info');
                $this->validarDatosModal = true;
                return back();// Retornamos
            }

            // Validación de número de operación, dni y fecha repetidos
            $validar1 = Pago::where('pago_operacion', $this->numero_operacion)
                    ->where('pago_documento', $this->documento)
                    ->where('pago_fecha', $this->fecha_pago)
                    ->where('pago_estado', '!=', '0')
                    ->where('id_pago', '!=' , strval($this->pago_id))->get();
            $validar2 = Pago::where('pago_operacion', $this->numero_operacion)
                    ->where('pago_fecha', $this->fecha_pago)
                    ->where('pago_estado', '!=', '0')
                    ->where('id_pago', '!=', strval($this->pago_id))->get();
            $validar3 = Pago::where('pago_operacion', $this->numero_operacion)
                    ->where('pago_documento', $this->documento)
                    ->where('pago_estado', '!=', '0')
                    ->where('id_pago', '!=', strval($this->pago_id))->get();
        }
        else
        {
            // Validación de número de operación, dni y fecha repetidos
            $validar1 = Pago::where('pago_operacion', $this->numero_operacion)
                    ->where('pago_documento', $this->documento)
                    ->where('pago_estado', '!=', '0')
                    ->where('pago_fecha', $this->fecha_pago)->get();
            $validar2 = Pago::where('pago_operacion', $this->numero_operacion)
                    ->where('pago_estado', '!=', '0')
                    ->where('pago_fecha', $this->fecha_pago)->get();
            $validar3 = Pago::where('pago_operacion', $this->numero_operacion)
                    ->where('pago_estado', '!=', '0')
                    ->where('pago_documento', $this->documento)->get();
        }

        if($validar1->count() > 0){
            $this->alertaPago('¡Información!', 'El número de operación y el DNI ya fueron registrados en el sistema.', 'info', 'Aceptar', 'info');
            $this->validarDatosModal = true;
            return back();// Retornamos
        }else if ($validar2->count() > 0){
            $this->alertaPago('¡Información!', 'El número de operación ya se encuentra registrado en el sistema.', 'info', 'Aceptar', 'info');
            $this->validarDatosModal = true;
            return back();// Retornamos
        }else if($validar3->count() > 0){
            $this->alertaPago('¡Información!', 'El número de operación y el DNI ya existen en el sistema.', 'info', 'Aceptar', 'info');
            $this->validarDatosModal = true;
            return back();// Retornamos
        }
    }

    public function asignarConceptoPago($pago, $admitido)
    {
        if($pago->id_concepto_pago == 1)// Si el concepto de pago es "Inscripción"
        {
            // Obtener el último código de inscripción
            $ultimo_codido_inscripcion = Inscripcion::orderBy('inscripcion_codigo','DESC')->first();
            // Generar el código de inscripción
            if($ultimo_codido_inscripcion == null)
            {
                $codigo_inscripcion = 'IN0001';// Si no existen códigos anteriores
            }else
            {
                $codigo_inscripcion = $ultimo_codido_inscripcion->inscripcion_codigo;
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
        else if($pago->id_concepto_pago == 2 || $pago->id_concepto_pago == 4 || $pago->id_concepto_pago == 6)//Si el concepto de pago es "Constancia de Ingreso"
        {   
            // registrar constancia de ingreso
            $constancia = new ConstanciaIngreso();
            $constancia->constancia_ingreso_fecha = date('Y-m-d');
            $constancia->id_pago = $pago->id_pago;
            $constancia->id_admitido = $admitido->id_admitido;
            $constancia->constancia_ingreso_estado = 1;
            $constancia->save();
        }
        else if($pago->id_concepto_pago == 7)//Si el concepto de pago es "Costo por Enseñanza"
        {
            // buscar la matricula del admitido
            $ciclo = AdmitidoCiclo::where('id_admitido', $admitido->id_admitido)->where('admitido_ciclo_estado', 1)->orderBy('id_admitido_ciclo', 'desc')->first();
            $matricula = Matricula::where('id_admitido', $admitido->id_admitido)->where('id_ciclo', $ciclo->id_ciclo)->where('matricula_estado', 1)->first();
            // registrar mensualidad
            $mensualidad = new Mensualidad();
            $mensualidad->id_matricula = $matricula->id_matricula;
            $mensualidad->id_pago = $pago->id_pago;
            $mensualidad->id_admitido = $admitido->id_admitido;
            $mensualidad->mensualidad_fecha_creacion = date('Y-m-d H:i:s');
            $mensualidad->mensualidad_estado = 1;
            $mensualidad->save();
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
                'canal_pago' => 'required|numeric',
                'concepto_pago' => 'required|numeric',
                'voucher_url' => 'required',
            ]);

            $this->validacionDatos();// Validamos los datos repetidos de DNI, nro de operación y fecha

            if($this->validarDatosModal == false)
            {
                // validar si el monto ingresado es igual al monto por concepto de seleccionado
                $concepto_pago_monto = ConceptoPago::where('id_concepto_pago', $this->concepto_pago)->first();
                if($this->monto < $concepto_pago_monto->concepto_pago_monto)
                {
                    $this->alertaPago('¡Error!', 'El monto ingresado es menor al monto por concepto seleccionado', 'error', 'Cerrar', 'danger');
                    return redirect()->back();// Retornamos
                }

                $validarAdmitido = Admitido::join('persona', 'admitido.id_persona', '=', 'persona.id_persona')
                                            ->where('numero_documento', $this->documento)
                                            ->first();
                if($validarAdmitido == null && $this->concepto_pago != 1)
                {
                    $this->alertaPago('¡Error!', 'El DNI ingresado no pertenece a ningún estudiante admitido para el concepto de '.$concepto_pago_monto->concepto_pago.'.', 'error', 'Cerrar', 'danger');
                    return redirect()->back();// Retornamos
                }
                if($validarAdmitido != null && $this->concepto_pago == 7)
                {
                    // buscar la matricula del admitido
                    $ciclo = AdmitidoCiclo::where('id_admitido', $validarAdmitido->id_admitido)->where('admitido_ciclo_estado', 1)->orderBy('id_admitido_ciclo', 'desc')->first();
                    if($ciclo == null)
                    {
                        $this->alertaPago('¡Error!', 'El estudiante no tiene matrícula activa.', 'error', 'Cerrar', 'danger');
                        return redirect()->back();// Retornamos
                    }
                    $matricula = Matricula::where('id_admitido', $validarAdmitido->id_admitido)->where('id_ciclo', $ciclo->id_ciclo)->where('matricula_estado', 1)->first();
                    if($matricula == null)
                    {
                        $this->alertaPago('¡Error!', 'El estudiante no tiene matrícula activa.', 'error', 'Cerrar', 'danger');
                        return redirect()->back();// Retornamos
                    }
                }

                // Crear el pago con los datos ingresados en el sistema
                $pago = new Pago();
                $pago->pago_documento = $this->documento;
                $pago->pago_operacion = $this->numero_operacion;
                $pago->pago_monto = $this->monto;
                $pago->pago_fecha = $this->fecha_pago;
                $pago->pago_estado = 1;
                $pago->pago_verificacion = 1;
                if($this->voucher_url)
                {
                    $admision = Admision::where('admision_estado', 1)->first()->admision;
                    $path = 'Posgrado/' . $admision . '/' . $this->documento . '/' . 'Voucher/';// Ruta del voucher
                    $filename = 'voucher-pago.' . $this->voucher_url->getClientOriginalExtension();// Nombre del voucher con su extención
                    $nombre_db = $path.$filename;
                    $data = $this->voucher_url;
                    $data->storeAs($path, $filename, 'files_publico');// Guardamos el voucher
                    $pago->pago_voucher_url = $nombre_db;
                }
                $pago->id_canal_pago = $this->canal_pago;
                $pago->id_concepto_pago = $this->concepto_pago;
                $pago->save();

                $this->asignarConceptoPago($pago, $validarAdmitido);

                $this->alertaPago('¡Éxito!', 'El pago ' . $pago->pago_operacion . ' por concepto de ' . $pago->concepto_pago->concepto_pago . ' ha sido creado satisfactoriamente.', 'success', 'Aceptar', 'success');
            }

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
            
            if($this->validarDatosModal == false)
            {
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
                    $path = 'Posgrado/' . $admision . '/' . $this->documento . '/' . 'Voucher/';// Ruta del voucher
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
        }

        // Cerramos el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalPago'
        ]);

        $this->limpiar();
    }

    public function validar_pago()
    {
        // validacion de los campos
        $this->validate([
            'observacion' => 'nullable|max:255',
        ]);

        // almacenar los datos
        $pago = Pago::find($this->pago_id);
        $pago->pago_verificacion = 2;
        $pago->save();

        // almacenar los datos de observacion
        $observacion = PagoObservacion::where('id_pago', $this->pago_id)->where('pago_observacion_estado', 1)->orderBy('id_pago_observacion', 'desc')->first();
        if ($observacion) {
            $observacion->pago_observacion_estado = 0;
            $observacion->save();
        }else{
            if ($this->observacion != '' || $this->observacion != null) {
                $observacion = new PagoObservacion();
                $observacion->pago_observacion = $this->observacion;
                $observacion->id_pago = $this->pago_id;
                $observacion->pago_observacion_creacion = now();
                $observacion->pago_observacion_estado = 1;
                $observacion->save();
            }
        }

        //Mostramos alerta de confirmacion
        $this->alertaPago('¡Validado!', 'El pago ha sido validado satisfactoriamente.', 'success', 'Aceptar', 'success');

        // cerra el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalVerPago'
        ]);

        // limpiar los campos
        $this->limpiar();
    }

    public function observar_pago()
    {
        // validacion de los campos
        $this->validate([
            'observacion' => 'required|max:255',
        ]);

        // almacenar los datos
        $pago = Pago::find($this->pago_id);
        $pago->pago_verificacion = 0;
        $pago->save();

        // almacenar los datos de observacion
        $observacion = PagoObservacion::where('id_pago', $this->pago_id)->where('pago_observacion_estado', 1)->orderBy('id_pago_observacion', 'desc')->first();
        if ($observacion) {
            $observacion->pago_observacion = $this->observacion;
            $observacion->pago_observacion_estado = 1;
            $observacion->save();
        }else{
            if ($this->observacion != '' || $this->observacion != null) {
                $observacion = new PagoObservacion();
                $observacion->pago_observacion = $this->observacion;
                $observacion->id_pago = $this->pago_id;
                $observacion->pago_observacion_creacion = now();
                $observacion->pago_observacion_estado = 1;
                $observacion->save();
            }
        }

        // mostramos alerta de confirmacion
        $this->alertaPago('¡Observado!', 'El pago ha sido observado satisfactoriamente.', 'success', 'Aceptar', 'success');

        // cerra el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalVerPago'
        ]);

        // limpiar los campos
        $this->limpiar();
    }

    public function rechazar_pago()
    {
        $this->validate([
            'observacion' => 'required|max:255',
        ]);

        $pago = Pago::find($this->pago_id);
        $pago->pago_estado = 0;
        $pago->pago_verificacion = 0;
        $pago->pago_leido = 1;
        if($pago->pago_voucher_url)
        {
            File::delete($pago->pago_voucher_url);
        }
        $pago->pago_voucher_url = null;
        $pago->save();

        // eliminar la constancia de ingreso
        $constancia = ConstanciaIngreso::where('id_pago', $this->pago_id)->orderBy('id_constancia_ingreso')->first();
        if($constancia)
        {
            $constancia->constancia_ingreso_codigo = null;
            if($constancia->constancia_ingreso_url)
            {
                File::delete($constancia->constancia_ingreso_url);
            }
            $constancia->constancia_ingreso_url = null;
            $constancia->save();
        }

        // almacenar los datos de observacion
        $observacion = PagoObservacion::where('id_pago', $this->pago_id)->where('pago_observacion_estado', 1)->orderBy('id_pago_observacion', 'desc')->first();
        if ($observacion) {
            $observacion->pago_observacion = $this->observacion;
            $observacion->pago_observacion_estado = 2;// 2 = Rechazado
            $observacion->save();
        }else{
            if ($this->observacion != '' || $this->observacion != null) {
                $observacion = new PagoObservacion();
                $observacion->pago_observacion = $this->observacion;
                $observacion->id_pago = $this->pago_id;
                $observacion->pago_observacion_creacion = now();
                $observacion->pago_observacion_estado = 2;// 2 = Rechazado
                $observacion->save();
            }
        }

        $this->alertaPago('¡Rechazado!', 'El pago ha sido rechazado satisfactoriamente.', 'success', 'Aceptar', 'success');

        // cerra el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalVerPago'
        ]);

        // limpiar los campos
        $this->limpiar();
    }

    public function eliminar($pago_id)
    {
        $this->alertaConfirmacion('¿Estás seguro?', '¿Desea eliminar el pago seleccionado?', 'question', 'Eliminar', 'Cancelar', 'primary', 'danger', 'deletePago', $pago_id);
    }

    public function deleteConceptoPago($pago)
    {
        if($pago->id_concepto_pago == 1)// Si el concepto de pago es "Inscripción"
        {
            //Borrar la inscripción del pago seleccionado
            $deleteInscripcion = Inscripcion::where('id_pago', $pago->id_pago)->first();
            $deleteInscripcion->delete();
        }
        else if($pago->id_concepto_pago == 2 || $pago->id_concepto_pago == 4 || $pago->id_concepto_pago == 6)//Si el concepto de pago es "Constancia de Ingreso"
        {
            //Borrar la constancia de ingreso del pago seleccionado
            $deleteConstancia = ConstanciaIngreso::where('id_pago', $pago->id_pago)->first();
            $deleteConstancia->delete();
        }
        else if($pago->id_concepto_pago == 7)//Si el concepto de pago es "Costo por Enseñanza"
        {
            //Borrar la mensualidad del pago seleccionado
            $deleteMensualidad = Mensualidad::where('id_pago', $pago->id_pago)->first();
            $deleteMensualidad->delete();
        }
    }

    public function deletePago(Pago $pago)
    {
        $this->deleteConceptoPago($pago);
        //Borramos todas las observaciones del pago seleccionado
        $observaciones = PagoObservacion::where('id_pago', $pago->id_pago)->get();
        foreach ($observaciones as $observacion) {
            $observacion->delete();
        }
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
