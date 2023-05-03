<?php

namespace App\Http\Livewire\ModuloAdministrador\Inscripcion\Inscripcion;

use App\Models\Admision;
use App\Models\Expediente;
use App\Models\ExpedienteInscripcion;
use App\Models\ExpedienteInscripcionSeguimiento;
use App\Models\Inscripcion;
use App\Models\Persona;
use App\Models\Programa;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{

    use WithPagination;
    
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => '']
    ];

    public $search = '';
    
    //variables
    public $inscripcion_id;
    public $programa;
    public $programa_nombre;
    public $programa_model = null;
    public $subprograma;
    public $subprograma_model = null;
    public $mencion;
    public $mencion_model = null;
    
    public $tipo_expediente;

    // variables de filtros
    public $filtro_programa;

    protected $listeners = [
        'render', 'cambiarPrograma', 'cambiarSeguimiento', 'reservarPago'
    ];
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'programa' => 'required',
            'subprograma' => 'required|numeric',
            'mencion' => 'required|numeric',
        ]);
    }

    public function limpiar()
    {
        $this->reset(
            'programa',
            'subprograma',
            'mencion',
        );
    }

    public function cargarInscripcion(Inscripcion $inscripcion)
    {   
        $this->inscripcion_id = $inscripcion->id_inscripcion;
        $this->programa_model = Programa::where('id_sede',$inscripcion->mencion->subprograma->programa->sede->cod_sede)->get();
        $this->programa = $inscripcion->mencion->subprograma->programa->id_programa;
        $this->programa_nombre = ucfirst(strtolower($inscripcion->mencion->subprograma->programa->descripcion_programa));
        $this->subprograma_model = SubPrograma::where('id_programa',$inscripcion->mencion->subprograma->programa->id_programa)->get();
        $this->subprograma = $inscripcion->mencion->subprograma->id_subprograma;
        $this->updatedSubPrograma($this->subprograma);
    }

    public function updatedPrograma($programa)
    {
        $pro = Programa::find($programa);
        $this->programa_nombre = ucfirst(strtolower($pro->descripcion_programa));
        $this->subprograma_model = SubPrograma::where('id_programa',$programa)->get();
        $this->subprograma = null;
        $this->mencion_model = collect();
        $this->mencion = null;
    }

    public function updatedSubPrograma($subprograma){
        $this->mencion_model = Mencion::where('id_subprograma',$subprograma)->where('mencion_estado',1)->get();
        $this->mencion = null;
        $valor = null;
        foreach($this->mencion_model as $item){
            $valor = $item->mencion;
        }
        if($valor == null){
            $this->mencion = Mencion::where('id_subprograma',$subprograma)->first();
            if($this->mencion){
                $this->mencion = $this->mencion->id_mencion;
            }
        }
    }

    public function guardarCambioPrograma()
    {
        $this->validate([
            'programa' => 'required',
            'subprograma' => 'required|numeric',
            'mencion' => 'required|numeric',
        ]);

        $this->dispatchBrowserEvent('alertaCambioPrograma');
    }

    public function cambiarPrograma()
    {
        $programa = Programa::find($this->programa);
        $inscripcion = Inscripcion::find($this->inscripcion_id);
        if($programa->descripcion_programa === 'DOCTORADO'){
            $inscripcion->tipo_programa = 2;
            $this->tipo_expediente = 2;
        }else{
            $inscripcion->tipo_programa = 1;
            $this->tipo_expediente = 1;
        }
        $inscripcion->id_mencion = $this->mencion;
        $inscripcion->save();

        // Eliminar expedientes de la inscripcion que no son del programa elegido
        $expediente_inscripcion = ExpedienteInscripcion::where('id_inscripcion',$this->inscripcion_id)->get();
        // delete storage file and database
        foreach($expediente_inscripcion as $exp){
            $expediente = Expediente::where('cod_exp', $exp->expediente_cod_exp)
                                        ->where(function($query){
                                            $query->where('expediente_tipo', 0)
                                                ->orWhere('expediente_tipo', $this->tipo_expediente);
                                        })
                                        ->first();
            if($expediente === null){
                $exp->delete();
                File::delete($exp->nom_exped);
            }
        }
        
        $this->limpiar();
        $this->dispatchBrowserEvent('modalCambiarPrograma');
        $this->dispatchBrowserEvent('alertaSuccess', [
            'titulo' => '¡Éxito!',
            'mensaje' => 'El programa se ha cambiado correctamente.'
        ]);
        $this->generarPdf($this->inscripcion_id);
    }

    public function generarPdf($id)
    {
        $inscripcion = Inscripcion::where('id_inscripcion',$id)->first();

        $montoTotal=0;

        $inscripcion_pago = InscripcionPago::where('inscripcion_id',$id)->get();
        foreach($inscripcion_pago as $item){
            $montoTotal = $montoTotal + $item->pago->monto;
        }

        $admision3 = Admision::where('estado',1)->first();
        $admi = $admision3->admision;

        $fecha_actual = $inscripcion->fecha_inscripcion->format('h:i:s a d/m/Y');
        $fecha_actual2 = $inscripcion->fecha_inscripcion->format('d-m-Y');
        $mencion = Mencion::where('id_mencion',$inscripcion->id_mencion)->get();
        $admisionn = Admision::where('estado',1)->get();
        $inscrip = Inscripcion::where('id_inscripcion',$id)->get();
        $inscripcion_codigo = Inscripcion::where('id_inscripcion',$id)->first()->inscripcion_codigo;
        $tiempo = 6;
        $valor = '+ '.intval($tiempo).' month';
        setlocale( LC_ALL,"es_ES@euro","es_ES","esp" );
        $final = strftime('%d de %B del %Y', strtotime($fecha_actual2.$valor));
        $per = Persona::where('idpersona', $inscripcion->persona_idpersona)->get();
        $expedienteInscripcion = ExpedienteInscripcion::where('id_inscripcion',$id)->get();
        $expedi = $expedi = Expediente::where('estado', 1)
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
            'persona' => $per,
            'fecha_actual' => $fecha_actual,
            'mencion' => $mencion,
            'admisionn' => $admisionn,
            'inscripcion_pago' => $inscripcion_pago,
            'inscrip' => $inscrip,
            'inscripcion_codigo' => $inscripcion_codigo,
            'montoTotal' => $montoTotal,
            'final' => $final,
            'expedienteInscripcion' => $expedienteInscripcion,
            'expedi' => $expedi,
            'seguimiento_count' => $seguimiento_count
        ];

        $nombre_pdf = 'FICHA_INSCRIPCION.pdf';
        $path_pdf = $admi.'/'.$id.'/'.$nombre_pdf;
        $pdf = Pdf::loadView('modulo_inscripcion.inscripcion.reporte-pdf', $data)->save(public_path($admi.'/'.$id.'/'). $nombre_pdf);

        $ins = Inscripcion::find($id);
        $ins->inscripcion = $path_pdf;
        $ins->save();
    }

    public function cargarAlertaSeguimiento($id)
    {
        $this->inscripcion_id = $id;
        $this->dispatchBrowserEvent('alertaSeguimiento');
    }

    public function cambiarSeguimiento()
    {
        $expediente_seguimiento = ExpedienteInscripcionSeguimiento::join('ex_insc', 'ex_insc.cod_ex_insc', '=', 'expediente_inscripcion_seguimiento.cod_ex_insc')
                                                        ->where('ex_insc.id_inscripcion', $this->inscripcion_id)
                                                        ->where('expediente_inscripcion_seguimiento.tipo_seguimiento', 1)
                                                        ->where('expediente_inscripcion_seguimiento.expediente_inscripcion_seguimiento_estado', 1)
                                                        ->get();
        if($expediente_seguimiento->count() > 0){
            foreach($expediente_seguimiento as $item){
                $item->expediente_inscripcion_seguimiento_estado = 0;
                $item->save();
            }
        }else{
            $inscripcion = Inscripcion::find($this->inscripcion_id);
            $exp_ins = ExpedienteInscripcion::join('expediente','ex_insc.expediente_cod_exp','=','expediente.cod_exp')
                                                ->where('ex_insc.id_inscripcion',$this->inscripcion_id)
                                                ->where(function($query) use ($inscripcion){
                                                    $query->where('expediente.expediente_tipo', 0)
                                                        ->orWhere('expediente.expediente_tipo', $inscripcion->tipo_programa);
                                                })
                                                ->get();
            $exp_seg = ExpedienteTipoSeguimiento::join('expediente','expediente_tipo_seguimiento.cod_exp','=','expediente.cod_exp')
                                                ->where('expediente_tipo_seguimiento_estado', 1)
                                                ->where('tipo_seguimiento', 1)
                                                ->where(function($query) use ($inscripcion){
                                                    $query->where('expediente.expediente_tipo', 0)
                                                        ->orWhere('expediente.expediente_tipo', $inscripcion->tipo_programa);
                                                })
                                                ->get();

            $array_seguimiento = [];
            foreach ($exp_ins as $exp) {
                foreach ($exp_seg as $seg) {
                    if($exp->expediente_cod_exp == $seg->cod_exp){
                        array_push($array_seguimiento, $exp->cod_ex_insc);
                    }
                }
            }
            // Registrar datos del seguimiento del expediente de inscripcion
            foreach ($array_seguimiento as $item) {
                $seguimiento_exp_ins = new ExpedienteInscripcionSeguimiento();
                $seguimiento_exp_ins->cod_ex_insc = $item;
                $seguimiento_exp_ins->tipo_seguimiento = 1;
                $seguimiento_exp_ins->expediente_inscripcion_seguimiento_estado = 1;
                $seguimiento_exp_ins->save();
            }
        }
        $this->dispatchBrowserEvent('alertaSuccess', [
            'titulo' => '¡Éxito!',
            'mensaje' => 'Se ha cambiado el seguimiento correctamente.'
        ]);
    }

    public function export() 
    {
        $fecha_actual = date("Ymd", strtotime(today()));
        $hora_actual = date("His", strtotime(now()));

        $this->dispatchBrowserEvent('notificacionInscripcion', ['message' =>'Excel exportado satisfactoriamente.', 'color' => '#2eb867']);

        return Excel::download(new DataInscripcionesExport, 'inscritos-'.$fecha_actual.'-'.$hora_actual.'.xlsx');
    }

    public function reservar_inscripcion($id_inscripcion){
        $this->dispatchBrowserEvent('alertaReserva', [
            'titulo' => '¡Reservar inscripción!',
            'mensaje' => '¿Está seguro de reservar la inscripción?',
            'id_inscripcion' => $id_inscripcion
        ]);
    }

    public function reservarPago($id_inscripcion){
        $mostrar_tipo_expediente = Inscripcion::find($id_inscripcion)->tipo_programa;
        // verificamos si tiene seguimiento
        $expediente_seguimiento = ExpedienteInscripcionSeguimiento::join('ex_insc', 'ex_insc.cod_ex_insc', '=', 'expediente_inscripcion_seguimiento.cod_ex_insc')
                                                        ->where('ex_insc.id_inscripcion', $this->inscripcion_id)
                                                        ->where('expediente_inscripcion_seguimiento.tipo_seguimiento', 1)
                                                        ->where('expediente_inscripcion_seguimiento.expediente_inscripcion_seguimiento_estado', 1)
                                                        ->get();
        if($expediente_seguimiento->count() > 0){ // si tiene seguimiento eliminamos su seguimiento
            foreach($expediente_seguimiento as $item){
                $item->delete();
            }
        }
        // obtenemos los expedientes de la inscripcion
        $expediente_inscripcion = ExpedienteInscripcion::where('id_inscripcion', $id_inscripcion)->get(); 
        // Eliminar expedientes de la inscripcion que no son del programa elegido
        // delete storage file and database
        foreach($expediente_inscripcion as $exp){
            $expediente = Expediente::where('cod_exp', $exp->expediente_cod_exp)
                                        ->where(function($query) use ($mostrar_tipo_expediente){
                                            $query->where('expediente_tipo', 0)
                                                ->orWhere('expediente_tipo', $mostrar_tipo_expediente);
                                        })
                                        ->first();
            if($expediente === null){
                $exp->delete();
                File::delete($exp->nom_exped);
            }
        }
        // ahora eliminamos los expedientes de la inscripcion
        foreach($expediente_inscripcion as $item){
            $item->delete(); // eliminamos los expedientes de la inscripcion
        }
        // verificamos los pagos de la inscripcion
        $inscripcion_pago = InscripcionPago::where('inscripcion_id', $id_inscripcion)->get();
        // eliminamos los pagos de la inscripcion y al pago le cambiamos el estado a 0
        foreach($inscripcion_pago as $item){
            $pago = Pago::find($item->pago_id);
            $pago->estado = 0;
            $pago->save();
            $item->delete();
        }
        // obtenemos la inscripcion
        $inscripcion = Inscripcion::find($id_inscripcion);
        // eliminamos la inscripcion
        $inscripcion->delete();
        // emitimos la alerta de exito
        $this->dispatchBrowserEvent('alertaSuccess', [
            'titulo' => '¡Éxito!',
            'mensaje' => 'Se ha eliminado la inscripción correctamente y se ha reservado el pago.'
        ]);
    }

    public function limpiar_filtro()
    {
        $this->reset('filtro_programa');
    }

    public function render()
    {
        if($this->filtro_programa)
        {
            $inscripcion = Inscripcion::join('persona','inscripcion.persona_idpersona','=','persona.idpersona')
                ->join('mencion','inscripcion.id_mencion','=','mencion.id_mencion')
                ->join('subprograma','mencion.id_subprograma','=','subprograma.id_subprograma')
                ->join('programa','subprograma.id_programa','=','programa.id_programa')
                ->where('mencion.id_mencion',$this->filtro_programa)
                ->where(function($query){
                    $query->where('persona.nombres','LIKE',"%{$this->search}%")
                        ->orWhere('persona.apell_pater','LIKE',"%{$this->search}%")
                        ->orWhere('persona.apell_mater','LIKE',"%{$this->search}%")
                        ->orWhere('persona.nombre_completo','LIKE',"%{$this->search}%")
                        ->orWhere('persona.num_doc','LIKE',"%{$this->search}%")
                        ->orWhere('inscripcion.id_inscripcion','LIKE',"%{$this->search}%");
                })
                ->orderBy('inscripcion.id_inscripcion','desc')
                ->paginate(100);
        }else{
            $inscripcion = Inscripcion::join('persona','inscripcion.persona_idpersona','=','persona.idpersona')
                ->join('mencion','inscripcion.id_mencion','=','mencion.id_mencion')
                ->join('subprograma','mencion.id_subprograma','=','subprograma.id_subprograma')
                ->join('programa','subprograma.id_programa','=','programa.id_programa')
                ->where(function($query){
                    $query->where('persona.nombres','LIKE',"%{$this->search}%")
                        ->orWhere('persona.apell_pater','LIKE',"%{$this->search}%")
                        ->orWhere('persona.apell_mater','LIKE',"%{$this->search}%")
                        ->orWhere('persona.nombre_completo','LIKE',"%{$this->search}%")
                        ->orWhere('persona.num_doc','LIKE',"%{$this->search}%")
                        ->orWhere('inscripcion.id_inscripcion','LIKE',"%{$this->search}%");
                })
                ->orderBy('inscripcion.id_inscripcion','desc')
                ->paginate(100);
        }
        // $inscripcion = Inscripcion::join('persona','inscripcion.persona_idpersona','=','persona.idpersona')
        //         ->join('mencion','inscripcion.id_mencion','=','mencion.id_mencion')
        //         ->join('subprograma','mencion.id_subprograma','=','subprograma.id_subprograma')
        //         ->join('programa','subprograma.id_programa','=','programa.id_programa')
        //         ->where('persona.nombres','LIKE',"%{$this->search}%")
        //         ->orWhere('persona.apell_pater','LIKE',"%{$this->search}%")
        //         ->orWhere('persona.apell_mater','LIKE',"%{$this->search}%")
        //         ->orWhere('persona.nombre_completo','LIKE',"%{$this->search}%")
        //         ->orWhere('inscripcion.id_inscripcion','LIKE',"%{$this->search}%")
        //         ->orWhere('persona.num_doc','LIKE',"%{$this->search}%")
        //         ->orderBy('inscripcion.id_inscripcion','DESC')->paginate(100);

        $programas = Mencion::join('subprograma','mencion.id_subprograma','=','subprograma.id_subprograma')
                ->join('programa','subprograma.id_programa','=','programa.id_programa')
                ->where('mencion.mencion_estado', 1)
                ->orderBy('programa.descripcion_programa','ASC')
                ->orderBy('subprograma.subprograma','ASC')
                ->get();

        // dd($programas);

        return view('livewire.modulo-administrador.inscripcion.inscripcion.index', [
            'inscripcion' => $inscripcion,
            'programas' => $programas
        ]);
    }
}
