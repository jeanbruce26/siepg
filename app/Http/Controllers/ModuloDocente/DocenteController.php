<?php

namespace App\Http\Controllers\ModuloDocente;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\CursoProgramaPlan;
use App\Models\Docente;
use App\Models\DocenteCurso;
use App\Models\MatriculaCurso;
use App\Models\Programa;
use App\Models\ProgramaProcesoGrupo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function inicio()
    {
        return view('modulo-docente.inicio.index');
    }

    public function perfil()
    {
        $id_tipo_trabajador = 1;
        return view('modulo-docente.perfil.index', [
            'id_tipo_trabajador' => $id_tipo_trabajador
        ]);
    }

    public function matriculados($id_docente_curso)
    {
        return view('modulo-docente.matriculados.index', [
            'id_docente_curso' => $id_docente_curso
        ]);
    }

    public function acta_evaluacion($id_docente_curso)
    {
        $docente_curso = DocenteCurso::find($id_docente_curso);
        $matriculados = MatriculaCurso::join('matricula', 'matricula_curso.id_matricula', 'matricula.id_matricula')
                        ->join('admitido', 'matricula.id_admitido', 'admitido.id_admitido')
                        ->join('persona', 'admitido.id_persona', 'persona.id_persona')
                        ->where('matricula_curso.id_curso_programa_plan', $docente_curso->id_curso_programa_plan)
                        ->where('matricula.id_programa_proceso_grupo', $docente_curso->id_programa_proceso_grupo)
                        ->orderBy('persona.nombre_completo', 'asc')
                        ->get();

        $fecha2 = date('YmdHis');

        $curso_programa_plan = CursoProgramaPlan::find($docente_curso->id_curso_programa_plan);
        $curso_model = Curso::find($curso_programa_plan->id_curso);
        $programa_proceso_grupo = ProgramaProcesoGrupo::find($docente_curso->id_programa_proceso_grupo);
        $programa_model = Programa::find($curso_programa_plan->programa_plan->id_programa);
        $docente_model = Docente::find($docente_curso->id_docente);
        $trabajador = $docente_model->trabajador;

        $admision_año = $docente_curso->admision->admision_año;

        $programa = $programa_model->programa_tipo == 1 ? 'Maestría' : 'Doctorado';
        $subprograma = $programa_model->subprograma;
        $mencion = $programa_model->mencion ? $programa_model->mencion : '';
        $curso = strtoupper($curso_model->curso_nombre);
        $codigo_curso = $curso_model->curso_codigo;
        $docente = ($trabajador->id_grado_academico == 4 ? 'Dr. ' : ($trabajador->id_grado_academico == 3 ? 'Mg. ' : 'Bach. ')) . strtoupper($trabajador->trabajador_nombre_completo);
        $codigo_docente = $trabajador->trabajador_numero_documento;
        $creditos = $curso_model->curso_credito;
        $ciclo = $curso_model->ciclo->ciclo;
        $grupo = $programa_proceso_grupo->grupo_detalle;

        $data = [
            'matriculados' => $matriculados,
            'programa' => $programa,
            'subprograma' => $subprograma,
            'mencion' => $mencion,
            'curso' => $curso,
            'codigo_curso' => $codigo_curso,
            'docente' => $docente,
            'codigo_docente' => $codigo_docente,
            'creditos' => $creditos,
            'ciclo' => $ciclo,
            'grupo' => $grupo,
            'admision_año' => $admision_año
        ];

        $pdf = Pdf::loadView('modulo-docente.acta-evaluacion.ficha-acta-evaluacion', $data);

        return $pdf->stream('acta-evaluacion-docente-'.$fecha2.'.pdf');
    }
}
