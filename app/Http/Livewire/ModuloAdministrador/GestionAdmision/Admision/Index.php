<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionAdmision\Admision;

use App\Models\Admision;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;
    
    protected $queryString = [
        'search' => ['except' => '']
    ];

    public $search = '';
    public $titulo = 'Crear Proceso de Admision';
    public $id_admision;

    public $modo = 1;

    public $año;
    public $convocatoria;
    public $fecha_inicio_inscripcion;
    public $fecha_fin_inscripcion;

    public $fecha_inicio_expediente;
    public $fecha_fin_expediente;

    public $fecha_inicio_entrevista;
    public $fecha_fin_entrevista;

    public $fecha_resultados;

    public $fecha_inicio_matricula;
    public $fecha_fin_matricula;

    public $fecha_inicio_extemporanea;
    public $fecha_fin_extemporanea;

    protected $listeners = ['render', 'cambiarEstado'];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'año' => 'required|numeric',
            'convocatoria' => 'nullable|string',
            'fecha_inicio_inscripcion' => 'required|date',
            'fecha_fin_inscripcion' => 'required|date',
            'fecha_inicio_expediente' => 'required|date',
            'fecha_fin_expediente' => 'required|date',
            'fecha_inicio_entrevista' => 'required|date',
            'fecha_fin_entrevista' => 'required|date',
            'fecha_resultados' => 'required|date',
            'fecha_inicio_matricula' => 'required|date',
            'fecha_fin_matricula' => 'required|date',
            'fecha_inicio_extemporanea' => 'required|date',
            'fecha_fin_extemporanea' => 'required|date',
        ]);
    }

    public function modo()
    {
        $this->limpiar();
        $this->modo = 1;
        $this->titulo = 'Crear Proceso de Admision';
    }

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset([
            'año',
            'convocatoria',
            'fecha_inicio_inscripcion',
            'fecha_fin_inscripcion',
            'fecha_inicio_expediente',
            'fecha_fin_expediente',
            'fecha_inicio_entrevista',
            'fecha_fin_entrevista',
            'fecha_resultados',
            'fecha_inicio_matricula',
            'fecha_fin_matricula',
            'fecha_inicio_extemporanea',
            'fecha_fin_extemporanea',
        ]);
        $this->modo = 1;
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

    public function alertaAdmision($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta-admision', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    public function cargarAlerta($id)
    {
        $admision = Admision::where('id_admision',$id)->first();
        $this->alertaConfirmacion(
            '¿Estás seguro?', 
            '¿Desea modificar el estado del proceso de admisión ' . $admision->admision . ' ?', 
            'question', 
            'Modificar', 
            'Cancelar', 
            'primary', 
            'danger', 
            'cambiarEstado',
            $id
        );
    }

    public function cambiarEstado(Admision $admision)
    {
        if ($admision->admision_estado == 1) {//Si el estado del admision es 1 (activo) cambiar a 0 (inactivo)
            $admision->admision_estado = 0;
        } else {//Si el estado del admision es 0 (inactivo) cambiar a 1 (activo)
            //Validar si hay otro admision activo
            if (Admision::where('admision_estado', 1)->count() > 0) {//Si hay otro admision activo
                $this->alertaAdmision('¡Error!', 'Ya existe un proceso de admision activo.', 'error', 'Aceptar', 'danger');
                return;
            }else{//Si no hay otro admision activo
                $admision->admision_estado = 1;//Cambiar el estado del admision a 1 (activo)
            }
        }
        $admision->save();

        $this->alertaAdmision('¡Éxito!', 'El estado del admisión ' . $admision->admision . ' ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');
    }

    public function cargarAdmision(Admision $admision)
    {
        $this->limpiar();
        $this->modo = 2;
        $this->titulo = 'Editar Proceso de Admision';
        $this->id_admision = $admision->id_admision;
        $this->año = $admision->admision_año;
        $this->convocatoria = $admision->admision_convocatoria;
        $this->fecha_inicio_inscripcion = $admision->admision_fecha_inicio_inscripcion;
        $this->fecha_fin_inscripcion = $admision->admision_fecha_fin_inscripcion;
        $this->fecha_inicio_expediente = $admision->admision_fecha_inicio_expediente;
        $this->fecha_fin_expediente = $admision->admision_fecha_fin_expediente;
        $this->fecha_inicio_entrevista = $admision->admision_fecha_inicio_entrevista;
        $this->fecha_fin_entrevista = $admision->admision_fecha_fin_entrevista;
        $this->fecha_resultados = $admision->admision_fecha_resultados;
        $this->fecha_inicio_matricula = $admision->admision_fecha_inicio_matricula;
        $this->fecha_fin_matricula = $admision->admision_fecha_fin_matricula;
        $this->fecha_inicio_extemporanea = $admision->admision_fecha_inicio_matricula_extemporanea;
        $this->fecha_fin_extemporanea = $admision->admision_fecha_fin_matricula_extemporanea;
    }

    public function guardarAdmision()
    {
        if ($this->modo == 1) {//Modo 1 = crear
            //Validamos los campos que se van a crear
            $this->validate([
                'año' => 'required|numeric',
                'convocatoria' => 'nullable|string',
                'fecha_inicio_inscripcion' => 'required|date',
                'fecha_fin_inscripcion' => 'required|date',
                'fecha_inicio_expediente' => 'required|date',
                'fecha_fin_expediente' => 'required|date',
                'fecha_inicio_entrevista' => 'required|date',
                'fecha_fin_entrevista' => 'required|date',
                'fecha_resultados' => 'required|date',
                'fecha_inicio_matricula' => 'required|date',
                'fecha_fin_matricula' => 'required|date',
                'fecha_inicio_extemporanea' => 'required|date',
                'fecha_fin_extemporanea' => 'required|date',
            ]);
    
            //Creamos el nuevo proceso de admision
            $admision = new Admision();
            //Validar si se ingreso una convocatoria con convocatoria o sin convocatoria
            if($this->convocatoria == null){
                $admision->admision = 'ADMISION ' . $this->año;
            }else{
                $admision->admision = 'ADMISION ' . $this->año . ' - ' . $this->convocatoria;
            }
            $admision->admision_año = $this->año;
            $admision->admision_convocatoria = $this->convocatoria;
            $admision->admision_estado = 0;
            $admision->admision_fecha_inicio_inscripcion = $this->fecha_inicio_inscripcion;
            $admision->admision_fecha_fin_inscripcion = $this->fecha_fin_inscripcion;
            $admision->admision_fecha_inicio_expediente = $this->fecha_inicio_expediente;
            $admision->admision_fecha_fin_expediente = $this->fecha_fin_expediente;
            $admision->admision_fecha_inicio_entrevista = $this->fecha_inicio_entrevista;
            $admision->admision_fecha_fin_entrevista = $this->fecha_fin_entrevista;
            $admision->admision_fecha_resultados = $this->fecha_resultados;
            $admision->admision_fecha_inicio_matricula = $this->fecha_inicio_matricula;
            $admision->admision_fecha_fin_matricula = $this->fecha_fin_matricula;
            $admision->admision_fecha_inicio_matricula_extemporanea = $this->fecha_inicio_extemporanea;
            $admision->admision_fecha_fin_matricula_extemporanea = $this->fecha_fin_extemporanea;
            $admision->save();//Guardamos los datos del nuevo proceso de admision

            //Alerta de exito al crear un nuevo proceso de admision
            $this->alertaAdmision('¡Éxito!', 'El proceso de admision ' . $admision->admision . ' ha sido creado satisfactoriamente.', 'success', 'Aceptar', 'success');
        }else{//Modo 2 = actualizar
            //Validamos los campos que se van a actualizar
            $this->validate([
                'año' => 'required|numeric',
                'convocatoria' => 'nullable|string',
                'fecha_inicio_inscripcion' => 'required|date',
                'fecha_fin_inscripcion' => 'required|date',
                'fecha_inicio_expediente' => 'required|date',
                'fecha_fin_expediente' => 'required|date',
                'fecha_inicio_entrevista' => 'required|date',
                'fecha_fin_entrevista' => 'required|date',
                'fecha_resultados' => 'required|date',
                'fecha_inicio_matricula' => 'required|date',
                'fecha_fin_matricula' => 'required|date',
                'fecha_inicio_extemporanea' => 'required|date',
                'fecha_fin_extemporanea' => 'required|date',
            ]);

            $admision = Admision::find($this->id_admision);

            //Validar si se realizo algun cambio en los datos del proceso de admision
            if($admision->admision_año != $this->año || $admision->admision_convocatoria != $this->convocatoria || 
                $admision->admision_fecha_inicio_inscripcion != $this->fecha_inicio_inscripcion ||
                $admision->admision_fecha_fin_inscripcion != $this->fecha_fin_inscripcion || $admision->admision_fecha_inicio_expediente != $this->fecha_inicio_expediente ||
                $admision->admision_fecha_fin_expediente != $this->fecha_fin_expediente || $admision->admision_fecha_inicio_entrevista != $this->fecha_inicio_entrevista ||
                $admision->admision_fecha_fin_entrevista != $this->fecha_fin_entrevista || $admision->admision_fecha_resultados != $this->fecha_resultados ||
                $admision->admision_fecha_inicio_matricula != $this->fecha_inicio_matricula || $admision->admision_fecha_fin_matricula != $this->fecha_fin_matricula ||
                $admision->admision_fecha_inicio_matricula_extemporanea != $this->fecha_inicio_extemporanea || $admision->admision_fecha_fin_matricula_extemporanea != $this->fecha_fin_extemporanea){//Si se realizo algun cambio en los datos del proceso de admision
                    //Validar si se ingreso una convocatoria con convocatoria o sin convocatoria para actualizar el nombre del proceso de admision
                    if($this->convocatoria == null){
                        $admision->admision = 'ADMISION ' . $this->año;
                    }else{
                        $admision->admision = 'ADMISION ' . $this->año . ' - ' . $this->convocatoria;
                    }
    
                    $admision->admision_año = $this->año;
                    $admision->admision_convocatoria = $this->convocatoria;
                    $admision->admision_fecha_inicio_inscripcion = $this->fecha_inicio_inscripcion;
                    $admision->admision_fecha_fin_inscripcion = $this->fecha_fin_inscripcion;
                    $admision->admision_fecha_inicio_expediente = $this->fecha_inicio_expediente;
                    $admision->admision_fecha_fin_expediente = $this->fecha_fin_expediente;
                    $admision->admision_fecha_inicio_entrevista = $this->fecha_inicio_entrevista;
                    $admision->admision_fecha_fin_entrevista = $this->fecha_fin_entrevista;
                    $admision->admision_fecha_resultados = $this->fecha_resultados;
                    $admision->admision_fecha_inicio_matricula = $this->fecha_inicio_matricula;
                    $admision->admision_fecha_fin_matricula = $this->fecha_fin_matricula;
                    $admision->admision_fecha_inicio_matricula_extemporanea = $this->fecha_inicio_extemporanea;
                    $admision->admision_fecha_fin_matricula_extemporanea = $this->fecha_fin_extemporanea;
                    $admision->save();
                    
                    //Alerta de exito al actualizar
                    $this->alertaAdmision('¡Éxito!', 'El proceso de admision ' . $admision->admision . ' ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');
            }else{//Si no se realizo ningun cambio en los datos del proceso de admision
                $this->alertaAdmision('¡Información!', 'No se realizaron cambios en los datos del proceso de admisión.', 'info', 'Aceptar', 'info');//Alerta de informacion
            }
        }

        //Cerrar modal de admision
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalAdmision'
        ]);

        $this->limpiar();
    }

    public function render()
    {
        $admision_model = Admision::where('admision', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('admision_año', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('id_admision', 'LIKE', '%' . $this->search . '%')
                    ->orderBy('id_admision', 'DESC')
                    ->paginate(10);

        return view('livewire.modulo-administrador.gestion-admision.admision.index', [
            'admision_model' => $admision_model
        ]);
    }

}
