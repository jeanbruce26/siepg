<?php

namespace App\Http\Livewire\ModuloAdministrador\Configuracion\Expediente;

use App\Models\Expediente;
use App\Models\ExpedienteTipoEvaluacion;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class GestionVistasEvaluacion extends Component
{
    use WithPagination;

    public $titulo = 'Agregar Vista de Evaluación';//Titulo del modal
    public $modo = 1;//Variable para cambiar el modo del formulario | 1 = agregar | 2 = modificar

    //Variables del modelo de expediente tipo evaluacion
    public $id_expediente_tipo_evaluacion;//Variable para el id del expediente tipo evaluacion
    public $id_expediente;//id del expediente que se encuentra cargado en el formulario de gestion de vistas de evaluacion
    public $tipo_evaluacion;
    public $estado_evaluacion;

    protected $listeners = ['render', 'cambiarEstado'];//Escuchar evento para que se actualice el componente

    //Validaciones en tiempo real para los campos del formulario de Vistas de Evaluación del Expediente
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'tipo_evaluacion' => 'required|int',
        ]);
    }

    //Limpiar los campos del formulario del modal
    public function limpiar()
    {
        $this->resetErrorBag();//Limpiar los errores
        $this->reset('tipo_evaluacion');//Limpiar el campo de tipo de evaluacion
        $this->modo = 1;//Asignamos el modo de agregar
    }

    //Mostrar modal de agregar Vistas de Evaluación del Expediente
    public function modo()
    {
        $this->limpiar();
        $this->modo = 1;
        $this->titulo = 'Agregar Vista de Evaluación del Expediente';//Asignamos el titulo 
    }

    //Alerta de confirmacion
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

    //Alertas de exito o error
    public function alertaExpedienteVistasEva($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta-expediente-gestion-vistas-evaluacion', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    public function cargarAlertaEstado($id_expediente_tipo_evaluacion)
    {
        $expedienteTipoEvaluacionModel = ExpedienteTipoEvaluacion::where('id_expediente_tipo_evaluacion',$id_expediente_tipo_evaluacion)->first();
        if($expedienteTipoEvaluacionModel->expediente_tipo_evaluacion == 1){
            $nombreEvalucion = 'Evaluación de Expediente';
        }else if($expedienteTipoEvaluacionModel->expediente_tipo_evaluacion == 2){
            $nombreEvalucion = 'Evaluación de Tema Tentativo de Tesis';
        }else if($expedienteTipoEvaluacionModel->expediente_tipo_evaluacion == 3){
            $nombreEvalucion = 'Evaluación de Entrevista';
        }
        $this->alertaConfirmacion('¿Estás seguro?','¿Desea cambiar el estado de la vista de '. $nombreEvalucion .' del expediente?','question','Modificar','Cancelar','primary','danger','cambiarEstado',$id_expediente_tipo_evaluacion);
    }

    public function cambiarEstado($id_expediente_tipo_evaluacion)
    {
        $expedienteTipoEvaluacionModel = ExpedienteTipoEvaluacion::where('id_expediente_tipo_evaluacion',$id_expediente_tipo_evaluacion)->first();
        if($expedienteTipoEvaluacionModel->expediente_tipo_evaluacion_estado == 1){//Si el estado es 1 (Activo) se cambia a 0 (Inactivo)
            $expedienteTipoEvaluacionModel->expediente_tipo_evaluacion_estado = 0;
        }else if($expedienteTipoEvaluacionModel->expediente_tipo_evaluacion_estado == 0){//Si el estado es 0 (Inactivo) se cambia a 1 (Activo)
            $expedienteTipoEvaluacionModel->expediente_tipo_evaluacion_estado = 1;
        }
        $expedienteTipoEvaluacionModel->save();

        if($expedienteTipoEvaluacionModel->expediente_tipo_evaluacion == 1){//Si el tipo de evaluacion es 1 (Evaluacion de Expediente)
            $nombreEvalucion = 'Evaluación de Expediente';
        }else if($expedienteTipoEvaluacionModel->expediente_tipo_evaluacion == 2){//Si el tipo de evaluacion es 2 (Evaluacion de Tema Tentativo de Tesis)
            $nombreEvalucion = 'Evaluación de Tema Tentativo de Tesis';
        }else if($expedienteTipoEvaluacionModel->expediente_tipo_evaluacion == 3){//Si el tipo de evaluacion es 3 (Evaluacion de Entrevista)
            $nombreEvalucion = 'Evaluación de Entrevista';
        }

        $this->alertaExpedienteVistasEva('¡Éxito!', 'El estado de la vista de '.$nombreEvalucion.' del expediente ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');
    }

    //Metodo para cargar el formulario de gestion de vistas de evaluacion
    public function cargarVistasEvaluacion($id_tipo_evaluacion)
    {
        $this->limpiar();
        $this->modo = 2;//Asignamos el modo de modificar
        $this->titulo = 'Actualizar Vista de Evaluación del Expediente';//Asignamos el titulo
        $expedienteTipoEvaluacionModel = ExpedienteTipoEvaluacion::where('id_expediente',$this->id_expediente)->where('id_expediente_tipo_evaluacion',$id_tipo_evaluacion)->first();//Buscamos el expediente tipo evaluacion por su id
        $this->id_expediente_tipo_evaluacion = $expedienteTipoEvaluacionModel->id_expediente_tipo_evaluacion;//Asignamos el id del expediente tipo evaluacion
        $this->tipo_evaluacion = $expedienteTipoEvaluacionModel->expediente_tipo_evaluacion;//Asignamos el tipo de evaluacion
    }

    //Metodo para registrar o modificar el expediente tipo evaluacion
    public function guardarExpedienteTipoEvaluacion()
    {
        $this->validate([
            'tipo_evaluacion' => 'required|int',
        ]);

        //Validar si ya se encuentra registrado el tipo de evaluacion en el expediente
        $validarTipoEvaluacion = ExpedienteTipoEvaluacion::where('id_expediente',$this->id_expediente)->where('expediente_tipo_evaluacion',$this->tipo_evaluacion)->first();
        if($validarTipoEvaluacion){
            $this->alertaExpedienteVistasEva('¡Información!', 'El tipo de evaluación ya se encuentra registrado en el expediente.', 'info', 'Aceptar', 'info');
            //Cerrar modal
            $this->dispatchBrowserEvent('modal', [
                'titleModal' => '#modalExpedienteVistasEva',
            ]);
            return;
        }

        if($this->modo == 1){//Si el modo es 1 se registra el expediente tipo evaluacion
            $expedienteTipoEvaluacionModel = new ExpedienteTipoEvaluacion();
            $expedienteTipoEvaluacionModel->id_expediente = $this->id_expediente;
            $expedienteTipoEvaluacionModel->expediente_tipo_evaluacion = $this->tipo_evaluacion;
            $expedienteTipoEvaluacionModel->expediente_tipo_evaluacion_estado = 1;
            $expedienteTipoEvaluacionModel->save();
            $this->alertaExpedienteVistasEva('¡Éxito!', 'La vista de evaluación del expediente ha sido registrado satisfactoriamente.', 'success', 'Aceptar', 'success');
        }else if($this->modo == 2){//Si el modo es 2 se modifica el expediente tipo evaluacion
            $expedienteTipoEvaluacionModel = ExpedienteTipoEvaluacion::where('id_expediente_tipo_evaluacion',$this->id_expediente_tipo_evaluacion)->first();
            $expedienteTipoEvaluacionModel->expediente_tipo_evaluacion = $this->tipo_evaluacion;
            $expedienteTipoEvaluacionModel->save();
            $this->alertaExpedienteVistasEva('¡Éxito!', 'La vista de evaluación del expediente ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');
        }

        $this->limpiar();//Limpiar campos del formulario
        //Cerramos el modal
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalExpedienteVistasEva',
        ]);
    }

    public function render()
    {
        $expedienteModel = Expediente::where('id_expediente',$this->id_expediente)->first();//Obtenemos el expediente que se encuentra cargado en el formulario de gestion de vistas de evaluacion
        $validarTipoEvaluacion = ExpedienteTipoEvaluacion::all();//Obtenemos todos los tipos de evaluacion para validar si ya se encuentra registrado en el expediente
        $expedienteEvaluacionModel = ExpedienteTipoEvaluacion::join('expediente','expediente.id_expediente','=','expediente_tipo_evaluacion.id_expediente')
                                        ->where('expediente_tipo_evaluacion.id_expediente',$this->id_expediente)
                                        ->orderBy('expediente_tipo_evaluacion.id_expediente_tipo_evaluacion','desc')
                                        ->paginate(10);

        return view('livewire.modulo-administrador.configuracion.expediente.gestion-vistas-evaluacion',[
            'expedienteEvaluacionModel' => $expedienteEvaluacionModel,
            'expedienteModel' => $expedienteModel,
            'validarTipoEvaluacion' => $validarTipoEvaluacion
        ]);

    }
}
