<?php

namespace App\Http\Livewire\ModuloPlataforma\Matriculas;

use App\Models\AdmitidoCiclo;
use App\Models\CursoProgramaProceso;
use App\Models\Inscripcion;
use App\Models\Matricula;
use App\Models\MatriculaCurso;
use App\Models\Pago;
use App\Models\Persona;
use App\Models\ProgramaProcesoGrupo;
use Livewire\Component;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class Index extends Component
{
    public $search = ''; // variable de busqueda
    public $grupo; // variable para almacenar el grupo seleccionado
    public $check_pago = []; // variable para almacenar los checkbox de los pagos
    public $admitido; // variable para almacenar el admitido del usuario logueado
    public $id_matricula; // variable para almacenar el id de la matricula

    protected $listeners = [
        'generar_matricula' => 'generar_matricula',
        'ficha_matricula' => 'ficha_matricula',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'grupo' => 'required|numeric',
            'check_pago' => 'required|array|min:1|max:1',
        ]);
    }

    public function limpiar_modal()
    {
        $this->reset();
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function alerta_generar_matricula()
    {
        // validamos el formulario
        $this->validate([
            'grupo' => 'required|numeric',
            'check_pago' => 'required|array|min:1|max:1',
        ]);

        // validar que el checkbox tenga al menos un pago seleccionado y como maximo sea un pago el seleccionado
        if ( count($this->check_pago) == 0 )
        {
            $this->dispatchBrowserEvent('alerta_generar_matricula', [
                'title' => '¡Error!',
                'text' => 'Debe seleccionar un pago para generar la matrícula',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }
        else if ( count($this->check_pago) > 1 )
        {
            $this->dispatchBrowserEvent('alerta_generar_matricula', [
                'title' => '¡Error!',
                'text' => 'Solo puede seleccionar un pago para generar la matrícula',
                'icon' => 'error',
                'confirmButtonText' => 'Aceptar',
                'color' => 'danger'
            ]);
            return;
        }

        $this->dispatchBrowserEvent('alerta_generar_matricula_2', [
            'title' => 'Confirmar matrícula',
            'text' => '¿Está seguro de generar la matrícula?',
            'icon' => 'question',
            'confirmButtonText' => 'Generar',
            'cancelButtonText' => 'Cancelar',
            'confirmButtonColor' => 'primary',
            'cancelButtonColor' => 'danger'
        ]);
    }

    public function generar_matricula()
    {
        // buscamos el pago
        $pago = Pago::find($this->check_pago[0]);
        $admitido = $this->admitido;

        // buscamos el grupo
        $grupo = $this->grupo;

        // obtenemos el ciclo del admitido
        $ciclo = AdmitidoCiclo::where('id_admitido', $admitido->id_admitido)->where('admitido_ciclo_estado', 1)->first();

        // generar codigo de matricula
        $codigo = 'M000000001';

        // obtener el ultimo registro de matricula
        $matricula = Matricula::orderBy('id_matricula', 'desc')->first();
        if ( $matricula )
        {
            $codigo = 'M' . str_pad($matricula->id_matricula + 1, 9, "0", STR_PAD_LEFT);
        }
        else
        {
            $codigo = 'M000000001';
        }
        // registrar matricula
        $matricula = new Matricula();
        $matricula->matricula_codigo = $codigo;
        $matricula->matricula_proceso = $admitido->programa_proceso->admision->admision_año . ' - ' . $admitido->programa_proceso->admision->admision_convocatoria;
        $matricula->matricula_year = date('Y-m-d');
        $matricula->matricula_fecha_creacion = date('Y-m-d H:i:s');
        $matricula->matricula_estado = 1;
        $matricula->id_admitido = $admitido->id_admitido;
        $matricula->id_programa_proceso_grupo = $grupo;
        $matricula->id_ciclo = $ciclo->id_ciclo;
        $matricula->id_pago = $pago->id_pago;
        $matricula->save();

        // cambiar de estado
        $pago->pago_estado = 2;
        $pago->save();

        // registramos los cursos de la matricula
        // obetenmos los cursos del ciclo del admitido
        $curso_programa_proceso = CursoProgramaProceso::join('curso', 'curso.id_curso', 'curso_programa_proceso.id_curso')
                                                        ->where('curso_programa_proceso.id_programa_proceso', $admitido->id_programa_proceso)
                                                        ->where('curso.id_ciclo', $ciclo->id_ciclo)
                                                        ->get();
        foreach ( $curso_programa_proceso as $item )
        {
            $matricula_curso = new MatriculaCurso();
            $matricula_curso->id_matricula = $matricula->id_matricula;
            $matricula_curso->id_curso_programa_proceso = $item->id_curso_programa_proceso;
            $matricula_curso->matricula_curso_fecha_creacion = date('Y-m-d');
            $matricula_curso->matricula_curso_estado = 1;
            $matricula_curso->save();
        }

        // emitimos una alerta de que se esta generando la matricula
        $this->dispatchBrowserEvent('alerta_generar_matricula', [
            'title' => '¡Exito!',
            'text' => 'Se ha generado su matrícula correctamente',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);

        // cerramos el modal
        $this->dispatchBrowserEvent('modal_matricula', ['action' => 'hide']);
    }

    public function alerta_ficha_matricula($id_matricula)
    {
        $this->id_matricula = $id_matricula;
        $this->dispatchBrowserEvent('alerta_generar_matricula_temporizador', [
            'title' => '¡Exito!',
            'text' => 'Se ha generado su ficha de matrícula correctamente',
            'icon' => 'success',
            'confirmButtonText' => 'Aceptar',
            'color' => 'success'
        ]);
    }

    public function ficha_matricula()
    {
        // buscamos el admitido
        $admitido = $this->admitido;

        // buscamos la matricula
        $matricula = Matricula::find($this->id_matricula);

        // buscamos el pago
        $pago = Pago::where('id_pago', $matricula->id_pago)->first();

        // buscamos los cursos de la matricula
        $cursos = MatriculaCurso::join('curso_programa_proceso', 'curso_programa_proceso.id_curso_programa_proceso', 'matricula_curso.id_curso_programa_proceso')
                                ->join('curso', 'curso.id_curso', 'curso_programa_proceso.id_curso')
                                ->where('matricula_curso.id_matricula', $matricula->id_matricula)
                                ->get();

        $programa = null;
        $subprograma = null;
        $mencion = null;
        if($admitido->programa_proceso->programa_plan->programa->mencion == null){
            $programa = $admitido->programa_proceso->programa_plan->programa->programa;
            $subprograma = $admitido->programa_proceso->programa_plan->programa->subprograma;
            $mencion = null;
        }else{
            $programa = $admitido->programa_proceso->programa_plan->programa->programa;
            $subprograma = $admitido->programa_proceso->programa_plan->programa->subprograma;
            $mencion = $admitido->programa_proceso->programa_plan->programa->mencion;
        }
        $fecha = date('d/m/Y', strtotime($pago->pago_fecha));
        $numero_operacion = $pago->pago_operacion;
        $plan = $admitido->programa_proceso->programa_plan->plan->plan;
        $ciclo = $matricula->ciclo->ciclo;
        $codigo = $admitido->admitido_codigo;
        $nombre = $admitido->persona->nombre_completo;
        $domicilio = $admitido->persona->direccion;
        $celular = $admitido->persona->celular;
        $grupo = $matricula->programa_proceso_grupo->grupo_detalle;
        $admision = $admitido->programa_proceso->admision->admision;
        $modalidad = $admitido->programa_proceso->programa_plan->programa->id_modalidad == 1 ? 'PRESENCIAL' : 'DISTANCIA';
        // dd($programa, $subprograma, $mencion, $fecha, $numero_operacion, $plan, $ciclo, $codigo, $nombre, $domicilio, $celular, $cursos, $grupo, $admision, $modalidad);
        $data = [
            'programa' => $programa,
            'subprograma' => $subprograma,
            'mencion' => $mencion,
            'fecha' => $fecha,
            'numero_operacion' => $numero_operacion,
            'plan' => $plan,
            'ciclo' => $ciclo,
            'codigo' => $codigo,
            'nombre' => $nombre,
            'domicilio' => $domicilio,
            'celular' => $celular,
            'cursos' => $cursos,
            'grupo' => $grupo,
            'admision' => $admision,
            'modalidad' => $modalidad
        ];

        $nombre_pdf = Str::slug($nombre) . '-ficha-matricula-' . $ciclo . '.pdf';
        $path = 'Posgrado/' . $admision . '/' . $admitido->persona->numero_documento . '/' . 'Expedientes' . '/';
        $pdf = Pdf::loadView('modulo-plataforma.matriculas.ficha-matricula', $data)->save(public_path($path . $nombre_pdf));

        $matricula->matricula_ficha_url = $path . $nombre_pdf;
        $matricula->save();
    }

    public function render()
    {
        $usuario = auth('plataforma')->user(); // obtenemos el usuario autenticado en la plataforma
        $persona = Persona::where('numero_documento', $usuario->usuario_estudiante)->first(); // obtenemos la persona del usuario autenticado en la plataforma
        $inscripcion_ultima = Inscripcion::where('id_persona', $persona->id_persona)->orderBy('id_inscripcion', 'desc')->first(); // inscripcion del usuario logueado
        $evaluacion = $inscripcion_ultima->evaluacion; // evaluacion de la inscripcion del usuario logueado
        $ciclo_admitido = null;
        $matriculas = collect();
        if ( $evaluacion )
        {
            $this->admitido = $persona->admitido->where('id_evaluacion', $evaluacion->id_evaluacion)->first(); // admitido de la inscripcion del usuario logueado
            if ( $this->admitido )
            {
                $grupos = ProgramaProcesoGrupo::where('id_programa_proceso', $this->admitido->id_programa_proceso)->get(); // grupos de la admision del usuario logueado
                $ciclo_admitido = AdmitidoCiclo::where('id_admitido', $this->admitido->id_admitido)->where('admitido_ciclo_estado', 1)->first(); // ciclo de la admision del usuario logueado
                $matriculas = Matricula::where('id_admitido', $this->admitido->id_admitido)->get(); // matriculas del usuario logueado
            }
        }
        else
        {
            $this->admitido = null;
        }

        $pagos = Pago::where(function ($query) {
                        $query->where('pago_operacion', 'like', '%' . $this->search . '%')
                            ->orWhere('id_pago', 'like', '%' . $this->search . '%');
                    })
                    ->where('pago_documento', auth('plataforma')->user()->usuario_estudiante)
                    ->where('pago_estado', 1)
                    ->where('pago_verificacion', 2)
                    ->orderBy('id_pago', 'desc')
                    ->get(); // pagos del usuario logueado

        return view('livewire.modulo-plataforma.matriculas.index', [
            'pagos' => $pagos,
            'grupos' => $grupos,
            'ciclo_admitido' => $ciclo_admitido,
            'matriculas' => $matriculas
        ]);
    }
}
