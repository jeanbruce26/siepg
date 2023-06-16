<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Matriculados - {{ $curso->curso_nombre }} - Ciclo {{ $curso->ciclo->ciclo }} - Grupo {{ $grupo->grupo_detalle }}
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('docente.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        Matriculados
                    </li>
                </ul>
            </div>
            <div class="d-flex flex-stack">
                <div class="d-flex align-items-center text-center gap-2 gap-lg-3 ms-5">
                    @if ($matriculados_count == $matriculados_finalizados_count)
                        <a href="{{ route('docente.acta_evaluacion', ['id_docente_curso' => $id_docente_curso]) }}" target="_blank" class="btn btn-info fw-bold">
                            Acta de Evaluación
                        </a>
                    @else
                        <button type="button" class="btn btn-info fw-bold" disabled>
                            Acta de Evaluación
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- alerta --}}
                    <div
                        class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                        <i class="ki-duotone ki-information-5 fs-2qx me-4 text-primary">
                            <i class="path1"></i>
                            <i class="path2"></i>
                            <i class="path3"></i>
                        </i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-5">
                                A continuación se muestran los alumnos matriculados en el curso.
                            </span>
                        </div>
                    </div>
                    {{-- card  --}}
                    <div class="card shadow-sm">
                        <div class="card-body mb-0">
                            <div class="d-flex align-items-center mb-5">
                                <div class="col-md-4 pe-3">
                                </div>
                                <div class="col-md-4 px-3">
                                </div>
                                <div class="col-md-4 ps-3">
                                    <input type="search" wire:model="search" class="form-control w-100" placeholder="Buscar..."/>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover table-rounded align-middle table-row-bordered border mb-0 gy-4 gs-4">
                                    <thead class="bg-light-warning">
                                        <tr class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                            <th>ID</th>
                                            <th>Código</th>
                                            <th>Alumno</th>
                                            <th class="text-center">Evaluación Permanente</th>
                                            <th class="text-center">Evaluación Medio Curso</th>
                                            <th class="text-center">Evaluación Final</th>
                                            <th class="text-center">Promedio Final</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($matriculados as $item)
                                            @php
                                                $nota_curso = App\Models\NotaMatriculaCurso::where('id_matricula_curso', $item->id_matricula_curso)->first();
                                            @endphp
                                            <tr class="fs-6 text-gray-800">
                                                <td class="fw-bold">
                                                    {{ $item->id_matricula_curso }}
                                                </td>
                                                <td>
                                                    {{ $item->admitido_codigo }}
                                                </td>
                                                <td>
                                                    {{ $item->nombre_completo }}
                                                </td>
                                                <td align="center">
                                                    @if ($nota_curso)
                                                        @if ($nota_curso->nota_evaluacion_permanente !== null)
                                                            <span class="fw-bold fs-5">
                                                                {{ number_format($nota_curso->nota_evaluacion_permanente) }} pts.
                                                            </span>
                                                        @else
                                                            <button type="button" wire:click="cargar_curso({{$item->id_matricula_curso}}, 1)" class="btn btn-info">
                                                                Nota
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button type="button" wire:click="cargar_curso({{$item->id_matricula_curso}}, 1)" class="btn btn-info">
                                                            Nota
                                                        </button>
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if ($nota_curso)
                                                        @if ($nota_curso->nota_evaluacion_medio_curso !== null)
                                                            <span class="fw-bold fs-5">
                                                                {{ number_format($nota_curso->nota_evaluacion_medio_curso) }} pts.
                                                            </span>
                                                        @else
                                                            <button type="button" wire:click="cargar_curso({{$item->id_matricula_curso}}, 2)" class="btn btn-info">
                                                                Nota
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button type="button" wire:click="cargar_curso({{$item->id_matricula_curso}}, 2)" class="btn btn-info">
                                                            Nota
                                                        </button>
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if ($nota_curso)
                                                        @if ($nota_curso->nota_evaluacion_final !== null)
                                                            <span class="fw-bold fs-5">
                                                                {{ number_format($nota_curso->nota_evaluacion_final) }} pts.
                                                            </span>
                                                        @else
                                                            <button type="button" wire:click="cargar_curso({{$item->id_matricula_curso}}, 3)" class="btn btn-info">
                                                                Nota
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button type="button" wire:click="cargar_curso({{$item->id_matricula_curso}}, 3)" class="btn btn-info">
                                                            Nota
                                                        </button>
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if ($nota_curso)
                                                        @if ($nota_curso->nota_promedio_final !== null)
                                                            <span class="fw-bold fs-5">
                                                                {{ number_format($nota_curso->nota_promedio_final) }} pts.
                                                            </span>
                                                        @else
                                                            -
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($nota_curso)
                                                        @if ($nota_curso->id_estado_cursos == 1)
                                                            <span class="badge badge-success fs-6 px-3 py-2">
                                                                {{ $nota_curso->estado_cursos->estado_cursos }}
                                                            </span>
                                                        @elseif ($nota_curso->id_estado_cursos == 2)
                                                            <span class="badge badge-danger fs-6 px-3 py-2">
                                                                {{ $nota_curso->estado_cursos->estado_cursos }}
                                                            </span>
                                                        @elseif ($nota_curso->id_estado_cursos == 3)
                                                            <span class="badge badge-danger fs-6 px-3 py-2">
                                                                {{ $nota_curso->estado_cursos->estado_cursos }}
                                                            </span>
                                                        @elseif ($nota_curso->id_estado_cursos == 4)
                                                            <span class="badge badge-danger fs-6 px-3 py-2">
                                                                {{ $nota_curso->estado_cursos->estado_cursos }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-warning fs-6 px-3 py-2">
                                                                Por evaluar
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-warning fs-6 px-3 py-2">
                                                            Por evaluar
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button"
                                                        class="btn btn-flex btn-center fw-bold btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale"
                                                        {{-- data-kt-menu-trigger="click" --}}
                                                        {{-- data-kt-menu-placement="bottom-end" --}}
                                                        data-bs-toggle="dropdown">
                                                        Acciones
                                                        <span class="svg-icon fs-5 rotate-180 ms-2 me-0 m-0">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                width="24px" height="24px" viewBox="0 0 24 24"
                                                                version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none"
                                                                    fill-rule="evenodd">
                                                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                                    <path
                                                                        d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z"
                                                                        fill="currentColor" fill-rule="nonzero"
                                                                        transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)">
                                                                    </path>
                                                                </g>
                                                            </svg>
                                                        </span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-6 w-150px py-4"
                                                        data-kt-menu="true">
                                                        <div class="menu-item px-3">
                                                            <a wire:click="alerta_asignar_nsp({{ $item->id_matricula_curso }})"
                                                                class="menu-link px-3 fs-6">
                                                                Asignar NSP
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            @if ($search != '')
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        No se encontraron resultados para la busqueda "{{ $search }}"
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        No hay registros
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if ($matriculados->hasPages())
                                <div class="d-flex justify-content-between mt-5">
                                    <div class="d-flex align-items-center text-gray-700">
                                        Mostrando {{ $matriculados->firstItem() }} - {{ $matriculados->lastItem() }} de {{ $matriculados->total()}} registros
                                    </div>
                                    <div>
                                        {{ $matriculados->links() }}
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between mt-5">
                                    <div class="d-flex align-items-center text-gray-700">
                                        Mostrando {{ $matriculados->firstItem() }} - {{ $matriculados->lastItem() }} de {{ $matriculados->total()}} registros
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Puntaje --}}
    <div wire:ignore.self class="modal fade" id="modal_nota" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase">Ingresar Nota</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" wire:click="limpiar_modal" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-2hx">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor"/>
                                <rect x="7" y="15.3137" width="12" height="2" rx="1" transform="rotate(-45 7 15.3137)" fill="currentColor"/>
                                <rect x="8.41422" y="7" width="12" height="2" rx="1" transform="rotate(45 8.41422 7)" fill="currentColor"/>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body">
                    <form class="row g-5 alig-items-center">
                        <div class="col-12">
                            <input type="number" class="form-control @error('nota') is-invalid @enderror w-100" wire:model="nota" placeholder="Ingrese la nota...">
                            @error('nota')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-between w-100">
                        <button type="button" wire:click="limpiar_modal()" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" wire:click="agregar_nota()" class="btn btn-success">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @push('scripts')
    <script>
        // filtro_proceso select2
        $(document).ready(function() {
            $('#filtro_proceso').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando..";
                    }
                }
            });
            $('#filtro_proceso').on('change', function() {
                @this.set('filtro_proceso', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_proceso').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    }
                });
                $('#filtro_proceso').on('change', function() {
                    @this.set('filtro_proceso', this.value);
                });
            });
        });
    </script>
@endpush --}}
