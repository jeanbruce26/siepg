<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Encuesta de Evaluación Docente
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('plataforma.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        Evaluación Docente
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- alerta  --}}
                    <div
                        class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                        <i class="ki-outline ki-information-5 fs-2qx me-4 text-primary"></i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-5">
                                Acontinuación se muestra la lista de evaluaciones docentes que tiene pendiente por
                                realizar.
                            </span>
                        </div>
                    </div>
                    {{-- header de la tabla --}}
                    {{-- <div class="card p-4 mb-5">
                        <div class="d-flex flex-column flex-md-row align-items-center w-100">
                            <div class="col-md-4 pe-md-3 mb-2 mb-md-0"></div>
                            <div class="col-md-4 px-md-3 mb-2 mb-md-0"></div>
                            <div class="col-md-4 ps-md-3">
                                <input type="search" wire:model="search" class="form-control w-100"
                                    placeholder="Buscar..." />
                            </div>
                        </div>
                    </div> --}}
                    {{-- tabla de pagos --}}
                    <div class="card shadow-sm mb-5">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-rounded border mb-0 gy-5 gs-5">
                                <thead class="bg-light-warning">
                                    <tr class="fw-bold fs-5 text-gray-900 border-bottom-2 border-gray-200">
                                        <th>#</th>
                                        <th class="col-md-2">Codigo Curso</th>
                                        <th class="col-md-3">Curso</th>
                                        <th class="col-md-3">Docente</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-700">
                                    @forelse ($cursos_activos_matricula as $item)
                                        @php
                                            $curso = App\Models\CursoProgramaPlan::query()
                                                ->join('curso', 'curso_programa_plan.id_curso', 'curso.id_curso')
                                                ->where(
                                                    'curso_programa_plan.id_curso_programa_plan',
                                                    $item->id_curso_programa_plan,
                                                )
                                                ->first();
                                            $docente = App\Models\Docente::find($item->id_docente);
                                            $evaluacion_docente = App\Models\EvaluacionDocente::query()
                                                ->where('id_nota_matricula_curso', $item->id_nota_matricula_curso)
                                                ->where('id_docente', $item->id_docente)
                                                ->where('id_admitido', $item->id_admitido)
                                                ->first();
                                        @endphp
                                        <tr class="fs-6">
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $curso->curso_codigo ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $curso->curso_nombre ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $docente->trabajador->grado_academico->grado_academico_prefijo ?? '-' }}
                                                {{ $docente->trabajador->trabajador_nombre_completo ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $evaluacion_docente ? convertirFechaHora($evaluacion_docente->created_at) : '-' }}
                                            </td>
                                            <td>
                                                @if ($evaluacion_docente)
                                                    <span class="badge badge-success fs-6 px-3 py-2">Respondido</span>
                                                @else
                                                    <span class="badge badge-warning fs-6 px-3 py-2">Pendiente</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($evaluacion_docente)
                                                    -
                                                @else
                                                    <a href="#modal-encuesta" wire:click="cargar({{ $item }})"
                                                        class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale"
                                                        data-bs-toggle="modal" data-bs-target="#modal-encuesta">
                                                        Responder
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="fs-6">
                                            <td colspan="7" class="text-center">
                                                <div class="text-muted py-5">
                                                    @if ($search == '')
                                                        No se encontraron resultados
                                                    @elseif($search)
                                                        No hay resultados de la busqueda "{{ $search }}"
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- paginacion de la tabla de pagos --}}
                    @if ($cursos_activos_matricula->hasPages())
                        <div class="d-flex justify-content-between mt-5">
                            <div class="d-flex align-items-center text-gray-700">
                                Mostrando {{ $cursos_activos_matricula->firstItem() }} -
                                {{ $cursos_activos_matricula->lastItem() }} de
                                {{ $cursos_activos_matricula->total() }} registros
                            </div>
                            <div>
                                {{ $cursos_activos_matricula->links() }}
                            </div>
                        </div>
                    @else
                        <div class="d-flex justify-content-between mt-5">
                            <div class="d-flex align-items-center text-gray-700">
                                Mostrando {{ $cursos_activos_matricula->firstItem() }} -
                                {{ $cursos_activos_matricula->lastItem() }} de
                                {{ $cursos_activos_matricula->total() }} registros
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modal-encuesta" data-bs-backdrop="static" tabindex="-1"
        aria-labelledby="modal_encuesta" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body p-5">
                    <div class="text-center p-5 rounded-4 bg-light-info mx-5">
                        <span class="svg-icon svg-icon-info svg-icon-5hx">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM11.7 17.7L16 14C16.4 13.6 16.4 12.9 16 12.5C15.6 12.1 15.4 12.6 15 13L11 16L9 15C8.6 14.6 8.4 14.1 8 14.5C7.6 14.9 8.1 15.6 8.5 16L10.3 17.7C10.5 17.9 10.8 18 11 18C11.2 18 11.5 17.9 11.7 17.7Z"
                                    fill="currentColor" />
                                <path
                                    d="M10.4343 15.4343L9.25 14.25C8.83579 13.8358 8.16421 13.8358 7.75 14.25C7.33579 14.6642 7.33579 15.3358 7.75 15.75L10.2929 18.2929C10.6834 18.6834 11.3166 18.6834 11.7071 18.2929L16.25 13.75C16.6642 13.3358 16.6642 12.6642 16.25 12.25C15.8358 11.8358 15.1642 11.8358 14.75 12.25L11.5657 15.4343C11.2533 15.7467 10.7467 15.7467 10.4343 15.4343Z"
                                    fill="currentColor" />
                                <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
                            </svg>
                        </span>
                    </div>

                    <form autocomplete="off" class="mt-5 mx-5">
                        <h3 class="mb-3 text-center fw-bold">
                            Encuesta Evaluación Docente
                            </h4>
                            <div class="mt-5">
                                <span class="fs-5">
                                    Docente: <b>{{ $nombre_docente ?? '-' }}</b>
                                </span>
                                <br>
                                <span class="fs-5">
                                    Curso: <b>{{ $nombre_curso ?? '-' }}</b>
                                </span>
                            </div>
                            <div class="mt-5 mb-5">
                                @foreach ($evaluacion_docente_preguntas as $item)
                                    <div wire:key="{{ $item->id_evaluacion_docente_pregunta }}">
                                        <span class="fs-5">
                                            {{ $loop->iteration }}.- {{ $item->evaluacion_docente_pregunta }}
                                        </span>
                                        <br>
                                        <div class="row g-3 mt-2 mb-4">
                                            <div class="form-check form-check-custom col-md-4">
                                                <input
                                                    class="form-check-input @if ($errors->has('respuestas.' . $item->id_evaluacion_docente_pregunta)) is-invalid @endif"
                                                    type="radio" value="1"
                                                    wire:model="respuestas.{{ $item->id_evaluacion_docente_pregunta }}"
                                                    id="si-{{ $item->id_evaluacion_docente_pregunta }}" />
                                                <label class="form-check-label"
                                                    for="si-{{ $item->id_evaluacion_docente_pregunta }}">
                                                    Si
                                                </label>
                                            </div>
                                            <div class="form-check form-check-custom col-md-4">
                                                <input
                                                    class="form-check-input @if ($errors->has('respuestas.' . $item->id_evaluacion_docente_pregunta)) is-invalid @endif"
                                                    type="radio" value="0"
                                                    wire:model="respuestas.{{ $item->id_evaluacion_docente_pregunta }}"
                                                    id="no-{{ $item->id_evaluacion_docente_pregunta }}" />
                                                <label class="form-check-label"
                                                    for="no-{{ $item->id_evaluacion_docente_pregunta }}">
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="hstack gap-2 justify-content-center mt-2">
                                <button type="button" wire:click="guardar_encuesta" class="btn btn-info">
                                    Guardar
                                </button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
