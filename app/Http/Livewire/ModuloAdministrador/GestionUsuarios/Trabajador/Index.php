<?php

namespace App\Http\Livewire\ModuloAdministrador\GestionUsuarios\Trabajador;

use App\Models\Administrativo;
use App\Models\AreaAdministrativo;
use App\Models\CategoriaDocente;
use App\Models\Coordinador;
use App\Models\Docente;
use App\Models\Facultad;
use App\Models\GradoAcademico;
use App\Models\TipoDocente;
use App\Models\TipoDocumento;
use App\Models\TipoTrabajador;
use App\Models\Trabajador as TrabajadorModel;
use App\Models\TrabajadorTipoTrabajador;
use App\Models\Usuario;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'tipo' => ['except' => 'all', 'as' => 't'],
        'data_tipo' => ['except' => 'all', 'as' => 'dt'],
    ];

    public $search = '';
    public $tipo = 'all';
    public $data_tipo = 'all';
    public $titulo_modal = 'Crear Trabajador';

    public $tipo_documento;
    public $documento;
    public $nombres;
    public $apellidos;
    public $nombre_completo;
    public $direccion;
    public $correo;
    public $grado;
    public $perfil;
    public $iteration;
    
    public $trabajador_id;

    public $modo = 1;
    public $disabled = 'enabled';

    public $docente_check = false;
    public $coordinador_check = false; 
    public $administrativo_check = false;
    public $trabajador_tipo_trabajador_docente_select;
    public $trabajador_tipo_trabajador_coordinador_select;
    public $trabajador_tipo_trabajador_administrativo_select;

    public $cambios_docente = false;
    public $cambios_coordinador = false;
    public $cambios_administrativo = false;

    public $tipo_docente = 0;
    public $tipo_coordinador = 0;
    public $tipo_administrativo = 0;

    //DOCENTE
    public $tipo_docentes;
    public $cv;
    public $categoria_docente;
    public $usuario_antiguo_docente;
    public $usuario_docente;
    public $nullable = false;

    //COORDINADOR
    public $facultad;
    public $facultad_antiguo;
    public $facultad_model = null;
    public $usuario_model = null;
    public $usuario;
    public $usuario_antiguo;
    public $categoria;

    //ADMINISTRATIVO
    public $area_model;
    public $area;

    //TRABAJADOR TIPO TRABAJADOR
    public $trabajador_tipo_trabajador;
    public $trabajador_docente;
    public $trabajador_coordinador;
    public $trabajador_administrativo;
    public $trabajador_model;
    public $docente_model;
    public $coordinador_model;
    public $administrativo_model;
    public $user_model;
    public $user_model_docente;
    public $user_model_coordinador;
    public $user_model_administrativo;
    
    protected $listeners = ['render', 'cambiarEstado', 'desasignarTrabajador'];

    public function updated($propertyName)
    {
        if($this->modo == 3){//EDITAR
            if($this->docente_check == true){
                $this->validateOnly($propertyName, [
                    'tipo_docentes' => 'required|numeric',
                    'categoria_docente' => 'required|numeric',
                    'usuario_docente' => [
                        'required',
                        'string'
                    ]
                ]);
                $this->tipo_docente = 1;
                $this->usuario_model = Usuario::all();
            }else{
                $this->tipo_docente = 0;
            }
    
            if($this->coordinador_check == true){
                $this->validateOnly($propertyName, [
                    'categoria' => 'required|string',
                    'facultad' => 'required|numeric',
                    'usuario' => [
                        'required',
                        'string'
                    ]
                ]);
                $this->tipo_coordinador = 1;
                $this->facultad_model = Facultad::all();
                $this->usuario_model = Usuario::all();
            }else{
                $this->tipo_coordinador = 0;
            }
    
            if($this->administrativo_check == true){
                $this->validateOnly($propertyName, [
                    'area' => 'required|numeric',
                    'usuario' => [
                        'required',
                        'string'
                    ]
                ]);
                $this->tipo_administrativo = 1;
                $this->area_model = AreaAdministrativo::all();
                $this->usuario_model = Usuario::all();
            }else{
                $this->tipo_administrativo = 0;
            }
        }else{
            if($this->tipo_documento == 2){
                $this->validateOnly($propertyName, [
                    'tipo_documento' => 'required|numeric',
                    'documento' => 'required|digits:9',
                    'nombres' => 'required|string',
                    'apellidos' => 'required|string',
                    'direccion' => 'required|string',
                    'correo' => 'required|email|unique:trabajador,trabajador_correo',
                    'grado' => 'required|string',
                    'perfil' => 'nullable|file|mimes:jpg,png,jpeg',
                ]);
            }else{
                $this->validateOnly($propertyName, [
                    'tipo_documento' => 'required|numeric',
                    'documento' => 'required|digits:8',
                    'nombres' => 'required|string',
                    'apellidos' => 'required|string',
                    'direccion' => 'required|string',
                    'correo' => 'required|email|unique:trabajador,trabajador_correo',
                    'grado' => 'required|string',
                    'perfil' => 'nullable|file|mimes:jpg,png,jpeg',
                ]);
            }
        }
    }

    public function modo()
    {
        $this->modo = 1;
        $this->titulo_modal = 'Crear Trabajador';
        $this->limpiar();
    }

    public function limpiar()
    {
        $this->resetErrorBag();
        $this->reset('tipo_documento','documento','nombres','apellidos','direccion','correo','grado','perfil','docente_check','coordinador_check','administrativo_check');
        $this->modo = 1;
        $this->perfil = null;
        $this->iteration++;
        $this->docente_check = false;
        $this->coordinador_check = false;
    }

    public function cancelar()
    {
        $this->limpiar();
        $this->mount();
    }

    public function cargarAlerta($id)
    {
        $trabajador_tipo_trabajador = TrabajadorTipoTrabajador::where('id_trabajador',$id)->where('trabajador_tipo_trabajador_estado',1)->count();
        if($trabajador_tipo_trabajador == 0){
            $this->dispatchBrowserEvent('alertaConfirmacion', [
                'title' => '¿Estás seguro?',
                'text' => '¿Desea modificar el estado del trabajador?',
                'icon' => 'question',
                'confirmButtonText' => 'Modificar',
                'cancelButtonText' => 'Cancelar',
                'confimrColor' => 'primary',
                'cancelColor' => 'danger',
                'metodo' => 'cambiarEstado',
                'id' => $id,
            ]);
        }else{
            $this->dispatchBrowserEvent('alerta-trabajador', [
                'title' => '¡Advertencia!',
                'text' => 'Para desactivar un trabajador primero debes desasignar sus cargos.',
                'icon' => 'warning',
                'confirmButtonText' => 'Entendido',
                'color' => 'warning'
            ]);
        }
    }

    //Alertas de exito o error
    public function alertaTrabajador($title, $text, $icon, $confirmButtonText, $color)
    {
        $this->dispatchBrowserEvent('alerta-trabajador', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
            'confirmButtonText' => $confirmButtonText,
            'color' => $color
        ]);
    }

    public function cambiarEstado(TrabajadorModel $trabajador)
    {
        $trabajador_tipo_trabajador = TrabajadorTipoTrabajador::where('id_trabajador',$trabajador->id_trabajador)->where('trabajador_tipo_trabajador_estado',1)->count();
        if($trabajador_tipo_trabajador == 0){
            if($trabajador->trabajador_estado == 1){//Si el estado es activo (1), lo desactivamos (0)
                $trabajador->trabajador_estado = 0;
            }else{//Si el estado es inactivo (0), lo activamos (1)
                $trabajador->trabajador_estado = 1;
            }
        }
        $trabajador->save();

        $this->dispatchBrowserEvent('alerta-trabajador', [
            'title' => '¡Éxito!',
            'text' => 'Estado del trabajador '.$trabajador->trabajador_nombre_completo.' ha sido actualizado satisfactoriamente.',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);
    }

    public function cargarTrabajador(TrabajadorModel $trabajador)
    {
        $this->modo = 2;
        $this->titulo_modal = 'Editar Trabajador - ' . $trabajador->trabajador_apellido . ' '  . $trabajador->trabajador_nombre;
        $this->trabajador_id = $trabajador->id_trabajador;
        
        if(strlen($trabajador->trabajador_numero_documento) == 8){
            $this->tipo_documento = 1;
        }else{
            $this->tipo_documento = 2;
        }
        $this->documento = $trabajador->trabajador_numero_documento;
        $this->nombres = $trabajador->trabajador_nombre;
        $this->apellidos = $trabajador->trabajador_apellido;
        $this->direccion = $trabajador->trabajador_direccion;
        $this->correo = $trabajador->trabajador_correo;
        $this->grado = $trabajador->id_grado_academico;
    }

    public function guardarTrabajador()
    {
        $id_trabajador = 0;

        if ($this->modo == 1) {//Si el modo es crear
            if($this->tipo_documento == 1){
                $this->validate([
                    'tipo_documento' => 'required|numeric',
                    'documento' => 'required|digits:8|numeric|unique:trabajador,trabajador_numero_documento',
                    'nombres' => 'required|string',
                    'apellidos' => 'required|string',
                    'direccion' => 'required|string',
                    'correo' => 'required|email',
                    'grado' => 'required|numeric',
                    'perfil' => 'nullable|file|mimes:jpg,png,jpeg',
                ]);
            }else{
                $this->validate([
                    'tipo_documento' => 'required|numeric',
                    'documento' => 'required|digits:9|numeric|unique:trabajador,trabajador_numero_documento',
                    'nombres' => 'required|string',
                    'apellidos' => 'required|string',
                    'direccion' => 'required|string',
                    'correo' => 'required|email',
                    'grado' => 'required|numeric',
                    'perfil' => 'nullable|file|mimes:jpg,png,jpeg',
                ]);
            }

            //Creamos el trabajador
            $trabajador = new TrabajadorModel();
            $trabajador->trabajador_nombre = $this->nombres;
            $trabajador->trabajador_apellido = $this->apellidos;
            $trabajador->trabajador_nombre_completo = $this->nombres.' '.$this->apellidos;
            $trabajador->trabajador_numero_documento = $this->documento;
            $trabajador->trabajador_correo = $this->correo;
            $trabajador->trabajador_direccion = $this->direccion;
            $trabajador->id_grado_academico = $this->grado;
            $trabajador->trabajador_estado = 1;
            $trabajador->save();

            $id_trabajador = $trabajador->id_trabajador;
    
            $this->dispatchBrowserEvent('alerta-trabajador', [
                'title' => '¡Éxito!',
                'text' => 'Trabajador creado satisfactoriamente.',
                'icon' => 'success',
                'confirmButtonText' => 'Aceptar',
                'color' => 'success'
            ]);

        }else{//Si el modo es editar
            if($this->tipo_documento == 1){
                $this->validate([
                    'tipo_documento' => 'required|numeric',
                    'documento' => "required|digits:8|numeric|unique:trabajador,trabajador_numero_documento,{$this->trabajador_id},id_trabajador",
                    'nombres' => 'required|string',
                    'apellidos' => 'required|string',
                    'direccion' => 'required|string',
                    'correo' => 'required|email',
                    'grado' => 'required|numeric',
                    'perfil' => 'nullable|file|mimes:jpg,png,jpeg',
                ]);
            }else{
                $this->validate([
                    'tipo_documento' => 'required|numeric',
                    'documento' => "required|digits:9|numeric|unique:trabajador,trabajador_numero_documento,{$this->trabajador_id},id_trabajador",
                    'nombres' => 'required|string',
                    'apellidos' => 'required|string',
                    'direccion' => 'required|string',
                    'correo' => 'required|email',
                    'grado' => 'required|numeric',
                    'perfil' => 'nullable|file|mimes:jpg,png,jpeg',
                ]);
            }
            
            $trabajador = TrabajadorModel::find($this->trabajador_id);
            $id_trabajador = $this->trabajador_id;

            if($trabajador->trabajador_numero_documento == $this->documento && $trabajador->trabajador_apellido == $this->apellidos
                && $trabajador->trabajador_nombre == $this->nombres && $trabajador->trabajador_direccion == $this->direccion 
                && $trabajador->trabajador_correo == $this->correo && $trabajador->id_grado_academico == $this->grado){
                    if ($this->perfil) {
                        if (file_exists($trabajador->trabajador_perfil_url)) {
                            unlink($trabajador->trabajador_perfil_url);
                        }
                        $path = 'Posgrado/Usuarios/' . $id_trabajador . '/Perfil' . '/';
                        $filename = 'foto-perfil-' . date('HisdmY') . '.' . $this->perfil->getClientOriginalExtension();
                        $nombre_db = $path.$filename;
                        $this->perfil->storeAs($path, $filename, 'files_publico');
                        $trabajador->trabajador_perfil_url = $nombre_db;

                        $trabajador->save();

                        $this->dispatchBrowserEvent('alerta-trabajador', [
                            'title' => '¡Éxito!',
                            'text' => 'El perfil del trabajador '.$trabajador->trabajador_nombre_completo.' ha sido actualizado satisfactoriamente.',
                            'icon' => 'success',
                            'confirmButtonText' => 'Aceptar',
                            'color' => 'success'
                        ]);
                    }else{
                        $this->dispatchBrowserEvent('alerta-trabajador', [
                            'title' => '¡Información!',
                            'text' => 'No se realizaron cambios en los datos del trabajador.',
                            'icon' => 'info',
                            'confirmButtonText' => 'Aceptar',
                            'color' => 'info'
                        ]);
                    }
            }else{
                $trabajador->trabajador_numero_documento = $this->documento;
                $trabajador->trabajador_apellido = $this->apellidos;
                $trabajador->trabajador_nombre = $this->nombres;
                $trabajador->trabajador_nombre_completo = $this->nombres.' '.$this->apellidos;
                $trabajador->trabajador_direccion = $this->direccion;
                $trabajador->trabajador_correo = $this->correo;
                $trabajador->id_grado_academico = $this->grado;
                $trabajador->save();
    
                if ($this->perfil) {
                    if (file_exists($trabajador->trabajador_perfil_url)) {
                        unlink($trabajador->trabajador_perfil_url);
                    }
                    $path = 'Posgrado/Usuarios/' . $id_trabajador . '/Perfil' . '/';
                    $filename = 'foto-perfil-' . date('HisdmY') . '.' . $this->perfil->getClientOriginalExtension();
                    $nombre_db = $path.$filename;
                    $this->perfil->storeAs($path, $filename, 'files_publico');
                    $trabajador->trabajador_perfil_url = $nombre_db;
                }
                $trabajador->save();

                $this->dispatchBrowserEvent('alerta-trabajador', [
                    'title' => '¡Éxito!',
                    'text' => 'Trabajador '.$trabajador->trabajador_nombre_completo.' ha sido actualizado satisfactoriamente.',
                    'icon' => 'success',
                    'confirmButtonText' => 'Aceptar',
                    'color' => 'success'
                ]);
            }
        }

        // emitir evento para actualizar el perfil del usuario logueado en la barra de navegacion
        $this->emit('actualizar_perfil');

        // Resetear variables y renderizar
        $this->cancelar();

        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalTra'
        ]);

        // emitir evento para actualizar la imagen del usuario logueado
        $this->emit('update_avatar');

        $this->limpiar();
    }

    public function cargarTrabajadorId(TrabajadorModel $trabajador,$valor)
    { 
        //Limpiar asignaciones
        $this->limpiarAsignacion();

        $this->trabajador_id = $trabajador->id_trabajador;

        $this->trabajador_tipo_trabajador_docente_select = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',1)->where('trabajador_tipo_trabajador_estado',1)->first();
        $this->trabajador_tipo_trabajador_coordinador_select = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',2)->where('trabajador_tipo_trabajador_estado',1)->first();
        $this->trabajador_tipo_trabajador_administrativo_select = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',3)->where('trabajador_tipo_trabajador_estado',1)->first();

        if($valor == 1){//Asignar
            $this->modo = 3;
            $this->titulo_modal = 'Asignar Trabajador';

            //docentee
            $trabajador_tipo_trabajador_docente = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',1)->where('trabajador_tipo_trabajador_estado',1)->first();

            // dd($trabajador_tipo_trabajador_docente);
            if($trabajador_tipo_trabajador_docente){
                $this->docente_check = true;
                $this->tipo_docente = 1;

                $docente_model = Docente::where('id_trabajador',$this->trabajador_id)->where('docente_estado',1)->first();
                $this->tipo_docentes = $docente_model->id_tipo_docente;
                $this->categoria_docente = $docente_model->id_categoria_docente;
                
                $this->usuario_model = Usuario::all();
                $usuario_model = Usuario::where('id_trabajador_tipo_trabajador',$trabajador_tipo_trabajador_docente->id_trabajador_tipo_trabajador)->first();
                $this->usuario_docente = $usuario_model->usuario_correo;
                $this->usuario_antiguo_docente = $usuario_model->id_usuario; //para cambiar el estado cuando se actualiza los datos del trabajador

                $this->nullable = true;
                $this->disabled = 'disabled'; //desabilitar el check en la vista
            }else{
                $this->docente_check = false;
                $this->tipo_docente = 0;
            }
            
            //coordinador
            $trabajador_tipo_trabajador_coordinador = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',2)->where('trabajador_tipo_trabajador_estado',1)->first();

            // dd($trabajador_tipo_trabajador_coordinador);
            if($trabajador_tipo_trabajador_coordinador){
                $this->coordinador_check = true;
                $this->tipo_coordinador = 1;

                $coordinador_model = Coordinador::where('id_trabajador', $this->trabajador_id)->first();
                $this->facultad_model = Facultad::all();
                $this->facultad = $coordinador_model->id_facultad;
                $this->facultad_antiguo = $coordinador_model->id_facultad; //para cambiar el estado cuando se actualiza los datos del trabajador
                $this->categoria = $coordinador_model->id_categoria_docente;

                $this->usuario_model = Usuario::all();
                $usuario_model = Usuario::where('id_trabajador_tipo_trabajador',$trabajador_tipo_trabajador_coordinador->id_trabajador_tipo_trabajador)->first();
                $this->usuario = $usuario_model->usuario_correo;
                $this->usuario_antiguo = $usuario_model->id_usuario; //para cambiar el estado cuando se actualiza los datos del trabajador

                $this->disabled = 'disabled'; //desabilitar el check en la vista
            }else{
                $this->coordinador_check = false;
                $this->tipo_coordinador = 0;
            }

            //administrativo
            $trabajador_tipo_trabajador_administrativo = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',3)->where('trabajador_tipo_trabajador_estado',1)->first();
            // dd($trabajador_tipo_trabajador_administrativo);
            if($trabajador_tipo_trabajador_administrativo){
                $this->administrativo_check = true;
                $this->tipo_administrativo = 1;

                $administrativo_model = Administrativo::where('id_trabajador',$this->trabajador_id)->first();
                $this->area_model = AreaAdministrativo::all();
                $this->area = $administrativo_model->id_area_administrativo;

                $this->usuario_model = Usuario::all();
                $usuario_model = Usuario::where('id_trabajador_tipo_trabajador',$trabajador_tipo_trabajador_administrativo->id_trabajador_tipo_trabajador)->first();
                $this->usuario = $usuario_model->usuario_correo;
                $this->usuario_antiguo = $usuario_model->id_usuario;
                
                $this->disabled = 'disabled'; //desabilitar el check en la vista
            }else{
                $this->administrativo_check = false;
                $this->tipo_administrativo = 0;
            }
        }else if($valor ==  2){//Desasignar
            $this->limpiarDocente();
            $this->limpiarCoordinador();
            $this->limpiarAdministrativo();

            $this->modo = 5; 
            $this->titulo_modal = 'Desasignar Trabajador';

            //docentee
            $trabajador_tipo_trabajador_docente = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',1)->where('trabajador_tipo_trabajador_estado',1)->first();
            if($trabajador_tipo_trabajador_docente){
                $this->docente_check = true;
            }else{
                $this->docente_check = false;
            }
            
            //coordinador
            $trabajador_tipo_trabajador_coordinador = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',2)->where('trabajador_tipo_trabajador_estado',1)->first();
            if($trabajador_tipo_trabajador_coordinador){
                $this->coordinador_check = true;
            }else{
                $this->coordinador_check = false;
            }

            //administrativo
            $trabajador_tipo_trabajador_administrativo = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',3)->where('trabajador_tipo_trabajador_estado',1)->first();
            if($trabajador_tipo_trabajador_administrativo){
                $this->administrativo_check = true;
            }else{
                $this->administrativo_check = false;
            }
        }
    }

    public function limpiarDocente()
    {
        $this->resetErrorBag();
        $this->reset('tipo_docentes','cv','usuario_docente','nullable','usuario_antiguo_docente', 'categoria_docente');
        $this->docente_check = false;
        $this->tipo_docente = 0;
        $this->cv = null;
        $this->iteration++;
    }

    public function limpiarCoordinador()
    {
        $this->resetErrorBag();
        $this->reset('facultad','categoria','usuario', 'coordinador_check');
        $this->coordinador_check = false;
        $this->tipo_coordinador = 0;
    }

    public function limpiarAdministrativo()
    {
        $this->resetErrorBag();
        $this->reset('area','usuario', 'administrativo_check');
        $this->administrativo_check = false;
        $this->tipo_administrativo = 0;
    }

    public function limpiarAsignacion()
    {
        $this->limpiarDocente();
        $this->limpiarCoordinador();
        $this->limpiarAdministrativo();
    }

    //Crear un nuevo trabajador docente y asignarle un usuario
    public function crearDocente()
    {
        if($this->docente_check == true){
            $docente = new Docente();
            $docente->id_tipo_docente = $this->tipo_docentes;
            $docente->id_categoria_docente = $this->categoria_docente;
            $docente->docente_estado = 1;
            $docente->id_trabajador = $this->trabajador_id;
            $docente->save();
    
            $data = $this->cv;
            if($data != null){
                $path =  'Docente/';
                $filename = "cv-".$this->trabajador_id.".".$data->extension();
                $data = $this->cv;
                $data->storeAs($path, $filename, 'files_publico');
    
                $docente_nuevo = Docente::find($docente->id_docente);
                $docente_nuevo->docente_cv_url = $filename;
                $docente_nuevo->save();
            }
    
            $trabajador_tipo_trabajador_create = new TrabajadorTipoTrabajador();
            $trabajador_tipo_trabajador_create->id_tipo_trabajador = 1;
            $trabajador_tipo_trabajador_create->id_trabajador = $this->trabajador_id;
            $trabajador_tipo_trabajador_create->trabajador_tipo_trabajador_estado = 1;
            $trabajador_tipo_trabajador_create->save();
    
            $usuario_id = Usuario::where('usuario_correo',$this->usuario_docente)->first()->id_usuario;
            $usuario = Usuario::find($usuario_id);
            $usuario->id_trabajador_tipo_trabajador = $trabajador_tipo_trabajador_create->id_trabajador_tipo_trabajador;
            $usuario->usuario_estado = 2;
            $usuario->save();

            $this->alertaTrabajador('¡Éxito!', 'El trabajador ha sido asignado satisfactoriamente.', 'success', 'Aceptar', 'success');
        }
    }

    //Crear un nuevo trabajador coordinador y asignarle un usuario
    public function crearCoordinador()
    {
        if($this->coordinador_check == true){
            $coordinador = new Coordinador();
            $coordinador->id_categoria_docente = $this->categoria;
            $coordinador->coordinador_estado = 1;
            $coordinador->id_facultad = $this->facultad;
            $coordinador->id_trabajador = $this->trabajador_id;
            $coordinador->save();
    
            $trabajador_tipo_trabajador_create = new TrabajadorTipoTrabajador();
            $trabajador_tipo_trabajador_create->id_tipo_trabajador = 2;
            $trabajador_tipo_trabajador_create->id_trabajador = $this->trabajador_id;
            $trabajador_tipo_trabajador_create->trabajador_tipo_trabajador_estado = 1;
            $trabajador_tipo_trabajador_create->save();
    
            $usuario_id = Usuario::where('usuario_correo',$this->usuario)->first()->id_usuario;
            $usuario = Usuario::find($usuario_id);
            $usuario->id_trabajador_tipo_trabajador = $trabajador_tipo_trabajador_create->id_trabajador_tipo_trabajador;
            $usuario->usuario_estado = 2;
            $usuario->save();

            $this->asignarFacultad($this->facultad);

            $this->alertaTrabajador('¡Éxito!', 'El trabajador ha sido asignado satisfactoriamente.', 'success', 'Aceptar', 'success');
        }
    }

    //Crear un nuevo trabajador administrativo y asignarle un usuario
    public function crearAdministrativo()
    {
        if($this->administrativo_check == true){
            $administrativo = new Administrativo();
            $administrativo->id_area_administrativo = $this->area;
            $administrativo->administrativo_estado = 1;
            $administrativo->id_trabajador = $this->trabajador_id;
            $administrativo->save();
    
            $trabajador_tipo_trabajador_create = new TrabajadorTipoTrabajador();
            $trabajador_tipo_trabajador_create->id_tipo_trabajador = 3;
            $trabajador_tipo_trabajador_create->id_trabajador = $this->trabajador_id;
            $trabajador_tipo_trabajador_create->trabajador_tipo_trabajador_estado = 1;
            $trabajador_tipo_trabajador_create->save();
    
            $usuario_id = Usuario::where('usuario_correo',$this->usuario)->first()->id_usuario;
            $usuario = Usuario::find($usuario_id);
            $usuario->id_trabajador_tipo_trabajador = $trabajador_tipo_trabajador_create->id_trabajador_tipo_trabajador;
            $usuario->usuario_estado = 2;//Asignado
            $usuario->save();

            $this->alertaTrabajador('¡Éxito!', 'El trabajador ha sido asignado satisfactoriamente.', 'success', 'Aceptar', 'success');
        }
    }

    // Actualizar datos del docente trabajador
    public function actualizarDocente($docente)
    {
        if($this->tipo_docentes == 2){
            $data = $this->cv;
            if($data != null){
                $path =  'Docente/';
                $filename = "cv-".$this->trabajador_id.".".$data->extension();
                $data = $this->cv;
                $data->storeAs($path, $filename, 'files_publico');

                $docente->docente_cv_url = $filename;
            }
        }else{
            $docente->docente_cv_url = null;
        }
        $docente->docente_estado = 1;
        $docente->id_tipo_docente = $this->tipo_docentes;
        $docente->id_categoria_docente = $this->categoria_docente;
        $docente->save();
    }

    public function actualizarCoordinador($coordinador)
    {
        if($this->coordinador_check == true){
            $coordinador->id_facultad = $this->facultad;
            $coordinador->id_categoria_docente = $this->categoria;
            $coordinador->coordinador_estado = 1;
            $coordinador->save();
        }
    }

    // Actualizar datos del administrativo trabajador
    public function actualizarAdministrativo($administrativo)
    {
        if($this->coordinador_check == true){
            $administrativo->id_area_administrativo = $this->area;
            $administrativo->administrativo_estado = 1;
            $administrativo->save();
        }
    }

    // Actualizar datos del usuario trabajador
    public function actualizarUsuario($usuario, $trabajador_tipo_trabajador)
    {
        $usuario_id = Usuario::where('usuario_correo',$usuario)->first()->id_usuario;

        //cambiar el estado del nuevo usuario seleccionado
        $usuario = Usuario::find($usuario_id);
        $usuario->id_trabajador_tipo_trabajador = $trabajador_tipo_trabajador->id_trabajador_tipo_trabajador;
        $usuario->usuario_estado = 2;//Asignado
        $usuario->save();
    }

    //Cambiar el estado del usuario antiguo
    public function cambiarEstadoUsuarioAntiguo($usuario_antiguo)
    {
        $usuario = Usuario::find($usuario_antiguo);
        $usuario->id_trabajador_tipo_trabajador = null;
        $usuario->usuario_estado = 1;//Activo
        $usuario->save();
    }

    //Asignar un la facultad de un coordinador
    public function asignarFacultad($facultad)
    {
        $facultad = Facultad::find($facultad);
        $facultad->facultad_asignado = 1;
        $facultad->save();
    }

    //Desasignar un la facultad de un coordinador
    public function desasignarFacultad($facultad)
    {
        $facultad = Facultad::find($facultad);
        $facultad->facultad_asignado = 0;
        $facultad->save();
    }

    //Validacion docente, coordinador y administrativo
    public function validacionDocenteCoordinadorAdministrativo()
    {
        if($this->docente_check == true){
            if($this->tipo_docentes == 2){
                $this->validate([
                    'tipo_docentes' => 'required|numeric',
                    'cv' => 'required|file|mimes:pdf|max:10048',
                    'categoria_docente' => 'required|numeric',
                    'usuario_docente' => [
                        'required',
                        'string',
                        function ($attribute, $value, $fail) {
                            if ($value == $this->usuario) {
                                $fail('El usuario no puede ser usado en varios accesos');
                            }
                        },
                    ],
                    'usuario' => [
                        function ($attribute, $value, $fail) {
                            if ($this->coordinador_check || $this->administrativo_check) {
                                $val = '';
                                $this->coordinador_check ? $val='coordinador' : $val='administrativo';
                                if ($value ==  null) {
                                    $fail('El campo usuario '. $val . ' es requerido');
                                }
                            }
                        },
                        function ($attribute, $value, $fail) {
                            if ($value == $this->usuario_docente && $this->usuario_docente != null) {
                                $fail('El usuario no puede ser usado en varios accesos');
                            }
                        },
                    ],

                    'facultad' => $this->coordinador_check ? 'required|numeric' : '',
                    'categoria' => $this->coordinador_check ? 'required|numeric' : '',
                    
                    'area' => $this->administrativo_check ? 'required|numeric' : '',
                ]);
            }else{
                $this->cv = null;
                $this->iteration++;
                $this->validate([
                    'tipo_docentes' => 'required|numeric',
                    'categoria_docente' => 'required|numeric',
                    'usuario_docente' => [
                        'required',
                        'string',
                        function ($attribute, $value, $fail) {
                            if ($value == $this->usuario) {
                                $fail('El usuario no puede ser usado en varios accesos');
                            }
                        },
                    ],
                    'usuario' => [
                        function ($attribute, $value, $fail) {
                            if ($this->coordinador_check || $this->administrativo_check) {
                                $val = '';
                                $this->coordinador_check ? $val='coordinador' : $val='administrativo';
                                if ($value ==  null) {
                                    $fail('El campo usuario '. $val . ' es requerido');
                                }
                            }
                        },
                        function ($attribute, $value, $fail) {
                            if ($value == $this->usuario_docente && $this->usuario_docente != null) {
                                $fail('El usuario no puede ser usado en varios accesos');
                            }
                        },
                    ],

                    'facultad' => $this->coordinador_check ? 'required|numeric' : '',
                    'categoria' => $this->coordinador_check ? 'required|numeric' : '',
                    
                    'area' => $this->administrativo_check ? 'required|numeric' : '',
                ]);
            }
        }
    }

    public function asignarTrabajadorCoordinador()
    {
        //COORDINADOR
        $trabajador_tipo_trabajador_coordinador = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',2)->where('trabajador_tipo_trabajador_estado',1)->first(); 

        if($trabajador_tipo_trabajador_coordinador){//ACTUALIZAR COORDINADOR ACTIVO
            if($this->coordinador_check == true){
                $this->validacionDocenteCoordinadorAdministrativo();

                $coordinador = Coordinador::where('id_trabajador',$this->trabajador_id)->first();
                $this->actualizarCoordinador($coordinador);

                $this->cambiarEstadoUsuarioAntiguo($this->usuario_antiguo);
                $this->actualizarUsuario($this->usuario, $trabajador_tipo_trabajador_coordinador);

                $this->desasignarFacultad($this->facultad_antiguo);
                $this->asignarFacultad($this->facultad);

                $this->alertaTrabajador('¡Éxito!', 'El trabajador asignado ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');
            }
        }else{//ACTUALIZAR COORDINADOR INACTIVO O CREAR NUEVO COORDINADOR 
            if($this->coordinador_check == true){
                $this->validacionDocenteCoordinadorAdministrativo();

                $trabajador_tipo_trabajador_coordinador_desactivado = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',2)->where('trabajador_tipo_trabajador_estado',0)->first(); 

                if($trabajador_tipo_trabajador_coordinador_desactivado){//ACTUALIZAR COORDINADOR INACTIVO
                    $coordinador = Coordinador::where('id_trabajador',$this->trabajador_id)->first();

                    $this->actualizarCoordinador($coordinador);

                    $this->asignarFacultad($this->facultad);

                    $trabajador_tipo_trabajador_coordinador_desactivado->trabajador_tipo_trabajador_estado = 1;
                    $trabajador_tipo_trabajador_coordinador_desactivado->save();

                    $this->actualizarUsuario($this->usuario, $trabajador_tipo_trabajador_coordinador_desactivado);

                }else{//CREAR NUEVO COORDINADOR
                    $this->crearCoordinador();
                }
            }
        }
    }

    public function asignarTrabajadorAdministrativo()
    {
        //ADMINISTRATIVO
        $trabajador_tipo_trabajador_administrativo = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',3)->where('trabajador_tipo_trabajador_estado',1)->first(); 

        if($trabajador_tipo_trabajador_administrativo){//ACTUALIZAR ADMINISTRATIVO ACTIVO
            if($this->administrativo_check == true){
                $this->validacionDocenteCoordinadorAdministrativo();

                $administrativo = Administrativo::where('id_trabajador', $this->trabajador_id)->first();
                $this->actualizarAdministrativo($trabajador_tipo_trabajador_administrativo);

                $this->cambiarEstadoUsuarioAntiguo($this->usuario_antiguo);

                $this->actualizarUsuario($this->usuario, $trabajador_tipo_trabajador_administrativo);

                $this->alertaTrabajador('¡Éxito!', 'El trabajador asignado ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');

            }
        }else{//ACTUALIZAR ADMINISTRATIVO INACTIVO O CREAR NUEVO ADMINISTRATIVO
            if($this->administrativo_check == true){
                $this->validacionDocenteCoordinadorAdministrativo();

                $trabajador_tipo_trabajador_administrativo_desactivado = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',3)->where('trabajador_tipo_trabajador_estado',0)->first(); 

                if($trabajador_tipo_trabajador_administrativo_desactivado){//ACTUALIZAR ADMINISTRATIVO INACTIVO

                    $administrativo = Administrativo::where('id_trabajador', $this->trabajador_id)->where('administrativo_estado',2)->first();
                    $this->actualizarAdministrativo($administrativo);

                    $trabajador_tipo_trabajador_administrativo_desactivado->trabajador_tipo_trabajador_estado = 1;
                    $trabajador_tipo_trabajador_administrativo_desactivado->save();

                    $this->actualizarUsuario($this->usuario, $trabajador_tipo_trabajador_administrativo_desactivado);

                    $this->alertaTrabajador('¡Éxito!', 'El trabajador ha sido asignado satisfactoriamente.', 'success', 'Aceptar', 'success');

                }else{
                    $this->crearAdministrativo();
                }
            }
        }
    }

    public function asignarTrabajador()
    {
        //DOCENTE
        $trabajador_tipo_trabajador_docente = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',1)->where('trabajador_tipo_trabajador_estado',1)->first();
        if($trabajador_tipo_trabajador_docente){//Si es docente
            if($this->docente_check == true){//Si se selecciono el check de docente
                $this->validacionDocenteCoordinadorAdministrativo();
                $docente = Docente::where('id_trabajador',$this->trabajador_id)->first();
                $this->actualizarDocente($docente);
                $id_usuario = Usuario::where('id_trabajador_tipo_trabajador',$trabajador_tipo_trabajador_docente->id_trabajador_tipo_trabajador)->first()->id_usuario;
                $this->cambiarEstadoUsuarioAntiguo($id_usuario);
                $this->actualizarUsuario($this->usuario_docente, $trabajador_tipo_trabajador_docente);

                $this->asignarTrabajadorCoordinador();
                $this->asignarTrabajadorAdministrativo();

                $this->alertaTrabajador('¡Éxito!', 'El trabajador asignado ha sido actualizado satisfactoriamente.', 'success', 'Aceptar', 'success');
            }
        }else{//Si no es docente
            if($this->docente_check == true){
                $this->validacionDocenteCoordinadorAdministrativo();

                $trabajador_tipo_trabajador_docente_desactivado = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',1)->where('trabajador_tipo_trabajador_estado',0)->first();
                
                if($trabajador_tipo_trabajador_docente_desactivado){
                    $docente = Docente::where('id_trabajador',$this->trabajador_id)->where('docente_estado',2)->first();
                    $this->actualizarDocente($docente);

                    $trabajador_tipo_trabajador_docente_desactivado->trabajador_tipo_trabajador_estado = 1;
                    $trabajador_tipo_trabajador_docente_desactivado->save();

                    $this->actualizarUsuario($this->usuario_docente, $trabajador_tipo_trabajador_docente_desactivado);
                }else{
                    $this->crearDocente();
                }

                $this->asignarTrabajadorCoordinador();
                $this->asignarTrabajadorAdministrativo();

                $this->alertaTrabajador('¡Éxito!', 'El trabajador ha sido asignado satisfactoriamente.', 'success', 'Aceptar', 'success');
            }

            $this->asignarTrabajadorCoordinador();//Si no es docente, pero se selecciono el check de coordinador
            $this->asignarTrabajadorAdministrativo();//Si no es docente, pero se selecciono el check de administrativo
        }
        
        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modalAsignar'
        ]);
        $this->limpiarAsignacion();
    }

    public function updatedDocenteCheck($docente_check)
    {
        if($this->docente_check == false){
            $this->limpiarDocente();
        }
    }

    public function updatedCoordinadorCheck($coordinador_check)
    {
        if($this->coordinador_check == false){
            $this->limpiarCoordinador();
        }
    }

    public function updatedAdministrativoCheck($administrativo_check)
    {
        if($this->administrativo_check == false){
            $this->limpiarAdministrativo();
        }
    }

    public function cargarInfoTrabajador(TrabajadorModel $trabajador)
    {
        $this->modo = 4;
        $this->titulo_modal = 'Detalle del Trabajador';
        $this->trabajador_id = $trabajador->id_trabajador;
        $this->trabajador_model = $trabajador;

        $this->user_model_docente = null;
        $this->user_model_coordinador = null;
        $this->user_model_administrativo = null;

        //docente
        $this->trabajador_docente = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',1)->where('trabajador_tipo_trabajador_estado',1)->first();
        if($this->trabajador_docente){
            $this->docente_model = Docente::where('id_trabajador',$this->trabajador_id)->first();
            $this->user_model_docente = Usuario::where('id_trabajador_tipo_trabajador',$this->trabajador_docente->id_trabajador_tipo_trabajador)->where('usuario_estado',2)->first();
        }
        //coordinador
        $this->trabajador_coordinador = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',2)->where('trabajador_tipo_trabajador_estado',1)->first();
        if($this->trabajador_coordinador){
            $this->coordinador_model = Coordinador::where('id_trabajador',$this->trabajador_id)->first();
            $this->user_model_coordinador = Usuario::where('id_trabajador_tipo_trabajador',$this->trabajador_coordinador->id_trabajador_tipo_trabajador)->where('usuario_estado',2)->first();
        }
        //administrativo
        $this->trabajador_administrativo = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',3)->where('trabajador_tipo_trabajador_estado',1)->first();
        if($this->trabajador_administrativo){
            $this->administrativo_model = Administrativo::where('id_trabajador',$this->trabajador_id)->first();
            $this->user_model_administrativo = Usuario::where('id_trabajador_tipo_trabajador',$this->trabajador_administrativo->id_trabajador_tipo_trabajador)->where('usuario_estado',2)->first();
        }
    }

    public function desasignarTrabajadorAlerta()
    {
        $this->dispatchBrowserEvent('alertaConfirmacion', [
            'title' => '¿Estás seguro?',
                'text' => '¿Desea desasignar sus cargos al trabajador?',
                'icon' => 'question',
                'confirmButtonText' => 'Desasignar',
                'cancelButtonText' => 'Cancelar',
                'confimrColor' => 'primary',
                'cancelColor' => 'danger',
                'metodo' => 'desasignarTrabajador',
                'id' => '',
        ]);
    }

    public function desasignarTrabajador()
    {
        //docentee
        $trabajador_tipo_trabajador_docente = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',1)->where('trabajador_tipo_trabajador_estado',1)->first();
        $docente_desasig = '';
        $coodinador_desasig = '';
        $administrativo_desasig = '';

        if($trabajador_tipo_trabajador_docente){
            if($this->docente_check == false){
                $usuario = Usuario::where('id_trabajador_tipo_trabajador',$trabajador_tipo_trabajador_docente->id_trabajador_tipo_trabajador)->first();
                $usuario->id_trabajador_tipo_trabajador = null;
                $usuario->usuario_estado = 1;
                $usuario->save();

                //cambiar el estado del coordinador 
                $docente = Docente::where('id_trabajador',$this->trabajador_id)->where('docente_estado',1)->first();
                $docente->docente_estado = 0;
                $docente->save();
    
                //  desactivar trabajador tipo de trabajador y quitar al trabajador asignado
                $trabajador_tipo_trabajador_docente->trabajador_tipo_trabajador_estado = 0;
                $trabajador_tipo_trabajador_docente->save();

                $docente_desasig = $usuario->usuario_nombre;

            }
        }
        
        //coordinador
        $trabajador_tipo_trabajador_coordinador = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',2)->where('trabajador_tipo_trabajador_estado',1)->first();

        if($trabajador_tipo_trabajador_coordinador){
            if($this->coordinador_check == false){
                // desasiganr el trabajado asiganado al usuario y cambiar el estado del usuario a activo
                $usuario = Usuario::where('id_trabajador_tipo_trabajador',$trabajador_tipo_trabajador_coordinador->id_trabajador_tipo_trabajador)->first();
                $usuario->id_trabajador_tipo_trabajador = null;
                $usuario->usuario_estado = 1;
                $usuario->save();

                //cambiar el estado del coordinador
                $coordinador = Coordinador::where('id_trabajador',$this->trabajador_id)->where('coordinador_estado',1)->first();
                $facultad_id = $coordinador->id_facultad;
                $coordinador->coordinador_estado = 0;
                $coordinador->save();

                $this->desasignarFacultad($facultad_id);
    
                //  desactivar trabajador tipo de trabajador y quitar al trabajador asignado
                $trabajador_tipo_trabajador_coordinador->trabajador_tipo_trabajador_estado = 0;
                $trabajador_tipo_trabajador_coordinador->save();

                $coodinador_desasig = $usuario->usuario_nombre;

            }
        } 

        //administrativo
        $trabajador_tipo_trabajador_administrativo = TrabajadorTipoTrabajador::where('id_trabajador',$this->trabajador_id)->where('id_tipo_trabajador',3)->where('trabajador_tipo_trabajador_estado',1)->first();
        if($trabajador_tipo_trabajador_administrativo){
            if($this->administrativo_check == false){
                // desasiganr el trabajado asiganado al usuario y cambiar el estado del usuario a activo
                $usuario = Usuario::where('id_trabajador_tipo_trabajador',$trabajador_tipo_trabajador_administrativo->id_trabajador_tipo_trabajador)->first();
                $usuario->id_trabajador_tipo_trabajador = null;
                $usuario->usuario_estado = 1;
                $usuario->save();

                //cambiar el estado del coordinador 
                $administrativo = Administrativo::where('id_trabajador',$this->trabajador_id)->where('administrativo_estado',1)->first();
                $administrativo->administrativo_estado = 0;
                $administrativo->save();
    
                //  desactivar trabajador tipo de trabajador y quitar al trabajador asignado
                $trabajador_tipo_trabajador_administrativo->trabajador_tipo_trabajador_estado = 0;
                $trabajador_tipo_trabajador_administrativo->save();

                $administrativo_desasig = $usuario->usuario_nombre;

            }
        }

        if(!empty($docente_desasig) || !empty($coodinador_desasig) || !empty($administrativo_desasig)){
            $text = 'El trabajador ha sido desasignado satisfactoriamente del usuario';
            if (!empty($docente_desasig)) {
                if (!empty($coodinador_desasig)) {
                    $text .= ' ' . $coodinador_desasig . ' y ' . $docente_desasig;
                }else if(!empty($administrativo_desasig)) {
                    $text .= ' ' . $administrativo_desasig . ' y ' . $docente_desasig;
                }else{
                    $text .= ' ' . $docente_desasig;
                }
            }else if (!empty($coodinador_desasig)) {
                if (!empty($docente_desasig)) {
                    $text .= ' ' . $coodinador_desasig . ' y ' . $docente_desasig;
                }else{
                    $text .= ' ' . $coodinador_desasig;
                }
            }else if (!empty($administrativo_desasig)) {
                $text .= ' ' . $administrativo_desasig;
            }
            $this->alertaTrabajador('¡Éxito!', $text . '.', 'success', 'Aceptar', 'success');
        }else{
            $this->alertaTrabajador('¡Información!', 'No se han desasignado usuarios del trabajador.', 'info', 'Aceptar', 'info');
        }

        $this->dispatchBrowserEvent('modal', [
            'titleModal' => '#modaldDesAsignar'
        ]);
        $this->limpiarAsignacion();
    }

    public function updatedUsuario($usuario)
    {
        $this->validate([
            'usuario' => [
                function ($attribute, $value, $fail) {
                    if ($value == $this->usuario_docente) {
                        $fail('El usuario no puede ser usado en varios accesos');
                    }
                },
            ],
            'usuario_docente' => [
                function ($attribute, $value, $fail) {
                    if ($value == $this->usuario) {
                        $fail('El usuario no puede ser usado en varios accesos');
                    }
                },
            ]
        ]);
    }

    public function updatedUsuarioDocente($usuario_docente)
    {
        $this->validate([
            'usuario_docente' => [
                function ($attribute, $value, $fail) {
                    if ($value == $this->usuario && $this->usuario != null) {
                        $fail('El usuario no puede ser usado en varios accesos');
                    }
                },
            ],
            'usuario' => [
                function ($attribute, $value, $fail) {
                    if ($value == $this->usuario_docente && $this->usuario_docente != null) {
                        $fail('El usuario no puede ser usado en varios accesos');
                    }
                },
            ]
        ]);
    }

    //Limpiamos los filtros
    public function resetear_filtro()
    {
        $this->reset(['tipo', 'data_tipo']);
    }

    public function filtrar()
    {
        $this->data_tipo = $this->tipo;
    }

    public function mount()
    {
        $this->trabajador_tipo_trabajador_docente_select = collect();
        $this->trabajador_tipo_trabajador_coordinador_select = collect();
        $this->trabajador_tipo_trabajador_administrativo_select = collect();
    }

    public function render()
    {
        $tip = $this->data_tipo;
        $buscar = $this->search;

        $trabajadores = collect();

        if($this->data_tipo == 'all') {
            $trabjadores_model = TrabajadorModel::join('grado_academico','trabajador.id_grado_academico','=','grado_academico.id_grado_academico')
                    ->where(function ($query) {
                        $query->where('trabajador.trabajador_nombre','LIKE',"%{$this->search}%")
                            ->orWhere('trabajador.trabajador_apellido','LIKE',"%{$this->search}%");
                    })
                    ->orderBy('id_trabajador','DESC')
                    ->get();
        }else{
            $trabjadores_model = TrabajadorTipoTrabajador::join('trabajador','trabajador_tipo_trabajador.id_trabajador','=','trabajador.id_trabajador')
                ->join('grado_academico','trabajador.id_grado_academico','=','grado_academico.id_grado_academico')
                ->where(function($query) use ($tip){
                    $query->where('trabajador_tipo_trabajador.id_tipo_trabajador',$tip)
                        ->where('trabajador_tipo_trabajador.trabajador_tipo_trabajador_estado',1);
                })
                ->where(function($query) use ($buscar){
                    $query->where('trabajador.trabajador_nombre','LIKE',"%{$buscar}%")
                        ->orWhere('trabajador.trabajador_apellido','LIKE',"%{$buscar}%");
                    })
                ->orderBy('trabajador.id_trabajador','DESC')
                ->get();
        }

        foreach ($trabjadores_model as $trabajador) {
            $trabajador_tipo_trabajador = TrabajadorTipoTrabajador::where('id_trabajador',$trabajador->id_trabajador)->where('trabajador_tipo_trabajador_estado',1)->get();
            $coordinador = Coordinador::where('id_trabajador',$trabajador->id_trabajador)->where('coordinador_estado',1)->first();
            $administrativo = Administrativo::where('id_trabajador',$trabajador->id_trabajador)->where('administrativo_estado',1)->first();
            $docente = Docente::where('id_trabajador',$trabajador->id_trabajador)->where('docente_estado',1)->first();

            $trabajadores->push([
                'id_trabajador' => $trabajador->id_trabajador,
                'trabajador_nombre_completo' => $trabajador->trabajador_nombre_completo,
                'trabajador_numero_documento' => $trabajador->trabajador_numero_documento,
                'trabajador_correo' => $trabajador->trabajador_correo,
                'grado_academico' => $trabajador->grado_academico,
                'trabajador_perfil_url' => $trabajador->trabajador_perfil_url,
                'trabajador_estado' => $trabajador->trabajador_estado,
                'trabajador_tipo_trabajador' => $trabajador_tipo_trabajador,
                'coordinador' => $coordinador,
                'administrativo' => $administrativo,
                'docente' => $docente,
            ]);
        }

        $tipo_trabajadores = TipoTrabajador::where('id_tipo_trabajador','!=','4')->get();
        $tipo_docente_model = TipoDocente::all();
        $categoria_docente_model = CategoriaDocente::all();

        return view('livewire.modulo-administrador.gestion-usuarios.trabajador.index', [
            'tipo_doc' => TipoDocumento::all(),
            'grado_academico' => GradoAcademico::all(),
            'trabajadores' => $trabajadores,
            'tipo_trabajadores' => $tipo_trabajadores,
            'tipo_docente_model' => $tipo_docente_model,
            'categoria_docente_model' => $categoria_docente_model,
        ]);
    }
}