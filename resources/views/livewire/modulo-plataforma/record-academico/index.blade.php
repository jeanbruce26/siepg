<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Record Académico
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('plataforma.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        Record Académico
                    </li>
                </ul>
            </div>
            {{-- <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('plataforma.record-academico-ficha', ['id_admitido' => $admitido->id_admitido]) }}" target="_blank" class="btn fw-bold btn-primary">
                    Descargar Record Académico
                </a>
            </div> --}}
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    <div class="card px-5 py-4 mb-5">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-sm-items-between gap-2">
                            <table>
                                <tr class="fs-6 fs-sm-5">
                                    <td class="w-95px w-sm-125px">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-teacher me-2 fs-3"></i>
                                            {{ $admitido->programa_proceso->programa_plan->programa->programa }}
                                        </div>
                                    </td>
                                    <td class="w-15px">
                                        :
                                    </td>
                                    <td class="fw-semibold">
                                        {{ $admitido->programa_proceso->programa_plan->programa->subprograma }} {{ $admitido->programa_proceso->programa_plan->programa->mencion ? 'CON MENCION EN '.$admitido->programa_proceso->programa_plan->programa->mencion : '' }}
                                    </td>
                                </tr>
                            </table>
                            <table>
                                <tr class="fs-6 fs-sm-5">
                                    <td class="w-95px w-sm-95px">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-award me-2 fs-3"></i>
                                            GRUPO
                                        </div>
                                    </td>
                                    <td class="w-15px">
                                        :
                                    </td>
                                    <td class="fw-semibold">
                                        {{ $ultima_matricula->programa_proceso_grupo->grupo_detalle }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="card px-5 py-4 mb-5">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-sm-items-between">
                            <div class="d-flex flex-column justify-content-center align-items-between">
                                <table>
                                    <tr class="fs-6 fs-sm-5">
                                        <td class="w-95px w-sm-125px">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-outline ki-user me-2 fs-3"></i>
                                                ALUMNO
                                            </div>
                                        </td>
                                        <td class="w-15px">
                                            :
                                        </td>
                                        <td class="fw-semibold">
                                            {{ $admitido->persona->nombre_completo }}
                                        </td>
                                    </tr>
                                </table>
                                <table>
                                    <tr class="fs-6 fs-sm-5">
                                        <td class="w-95px w-sm-125px">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-outline ki-barcode me-2 fs-3"></i>
                                                CODIGO
                                            </div>
                                        </td>
                                        <td class="w-15px">
                                            :
                                        </td>
                                        <td class="fw-semibold">
                                            {{ $admitido->admitido_codigo }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <table>
                                <tr class="fs-6 fs-sm-5">
                                    <td class="w-95px w-sm-95px">
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-calendar me-2 fs-3"></i>
                                            FECHA
                                        </div>
                                    </td>
                                    <td class="w-15px">
                                        :
                                    </td>
                                    <td class="fw-semibold">
                                        {{ date('d/m/Y') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    {{-- tabla --}}
                    @foreach ($ciclos as $item)
                    @php
                        $cursos = App\Models\CursoProgramaPlan::join('curso', 'curso.id_curso', '=', 'curso_programa_plan.id_curso')
                            ->where('curso_programa_plan.id_programa_plan', $programa->id_programa_plan)
                            ->where('curso.id_ciclo', $item->id_ciclo)
                            ->get();
                    @endphp
                        <div class="card shadow-sm mb-5">
                            <div class="px-10 pt-5 pb-2 border-bottom mb-0">
                                <h3 class="card-title fs-2" style="font-weight: 700">
                                    CICLO - {{ $item->ciclo }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle table-rounded border mb-0 gy-5 gs-5">
                                        <thead class="bg-light-warning">
                                            <tr class="fw-bold fs-5 text-gray-900 border-bottom-2 border-gray-200 text-center">
                                                <th>CODIGO</th>
                                                <th class="col-4">CURSO</th>
                                                <th>FECHA</th>
                                                <th>SECCION</th>
                                                <th>CRED.</th>
                                                <th>PERIODO</th>
                                                <th>NOTA</th>
                                                <th>ESTADO</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-semibold text-gray-700">
                                            @foreach ($cursos as $curso)
                                            @php
                                                $data = App\Models\MatriculaCurso::join('matricula', 'matricula.id_matricula', '=', 'matricula_curso.id_matricula')
                                                    ->join('programa_proceso_grupo', 'programa_proceso_grupo.id_programa_proceso_grupo', '=', 'matricula.id_programa_proceso_grupo')
                                                    ->where('matricula_curso.id_curso_programa_plan', $curso->id_curso_programa_plan)
                                                    ->where('matricula.id_admitido', $admitido->id_admitido)
                                                    ->orderBy('matricula_curso.id_matricula_curso', 'desc')
                                                    ->first();
                                                $docente = null;
                                                if ($data) {
                                                    $nota_matricula_curso = App\Models\NotaMatriculaCurso::where('id_matricula_curso', $data->id_matricula_curso)->first();
                                                    if ($nota_matricula_curso) {
                                                        $docente = App\Models\Docente::where('id_docente', $nota_matricula_curso->id_docente)->first();
                                                        // if ($docente == null) {
                                                        //     $docente = App\Models\DocenteCurso::where('id_curso_programa_plan', $data->id_curso_programa_plan)
                                                        //         ->where('id_programa_proceso_grupo', $data->id_programa_proceso_grupo)
                                                        //         ->first();
                                                        //     $docente = $docente ? App\Models\Docente::where('id_docente', $docente->id_docente)->first() : null;
                                                        // }
                                                    }
                                                }
                                            @endphp
                                                <tr class="border-bottom fs-6">
                                                    <td class="text-center">
                                                        {{ $curso->curso_codigo }}
                                                    </td>
                                                    <td>
                                                        {{ $curso->curso_nombre }}
                                                        <div class="fs-6">
                                                            <span class="fw-bold">Docente:</span>
                                                            @if ($docente)
                                                                {{ $docente->trabajador->grado_academico->grado_academico_prefijo }} {{ $docente->trabajador->trabajador_nombre_completo }}
                                                            @else
                                                                Sin asignar
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $data ? date('d/m/Y', strtotime($data->created_at)) : '---' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $data ? $data->grupo_detalle : '---' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $curso->curso_credito }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $data ? $data->matricula_proceso : '---' }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $nota_matricula_curso->nota_promedio_final ?? '---' }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($data)
                                                            @if ($data->matricula_curso_estado == 1)
                                                                <span class="badge badge-secondary fs-6 px-3 py-2">
                                                                    PENDIENTE
                                                                </span>
                                                            @elseif ($data->matricula_curso_estado == 2 && $nota_matricula_curso->id_estado_cursos == 4)
                                                                <span class="badge badge-secondary fs-6 px-3 py-2">
                                                                    NSP
                                                                </span>
                                                            @elseif ($nota_matricula_curso->nota_promedio_final >= 14)
                                                                <span class="badge badge-success fs-6 px-3 py-2">
                                                                    APROBADO
                                                                    
                                                                </span>
                                                            @else
                                                                <span class="badge badge-danger fs-6 px-3 py-2">
                                                                    DESAPROBADO
                                                                </span>
                                                            @endif
                                                        @else
                                                            <span class="badge badge-secondary fs-6 px-3 py-2">
                                                                PENDIENTE
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    {{-- <script>
        // canal_pago select2
        $(document).ready(function () {
            $('#canal_pago').select2({
                placeholder: 'Seleccione su canal de pago',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando..";
                    }
                }
            });
            $('#canal_pago').on('change', function(){
                @this.set('canal_pago', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#canal_pago').select2({
                    placeholder: 'Seleccione su canal de pago',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando..";
                        }
                    }
                });
                $('#canal_pago').on('change', function(){
                    @this.set('canal_pago', this.value);
                });
            });
        });
        // concepto_pago select2
        $(document).ready(function () {
            $('#concepto_pago').select2({
                placeholder: 'Seleccione su concepto de pago',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando..";
                    }
                }
            });
            $('#concepto_pago').on('change', function(){
                @this.set('concepto_pago', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#concepto_pago').select2({
                    placeholder: 'Seleccione su concepto de pago',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando..";
                        }
                    }
                });
                $('#concepto_pago').on('change', function(){
                    @this.set('concepto_pago', this.value);
                });
            });
        });
        // grupo select2
        $(document).ready(function () {
            $('#grupo').select2({
                placeholder: 'Seleccione su grupo',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando..";
                    }
                }
            });
            $('#grupo').on('change', function(){
                @this.set('grupo', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#grupo').select2({
                    placeholder: 'Seleccione su grupo',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando..";
                        }
                    }
                });
                $('#grupo').on('change', function(){
                    @this.set('grupo', this.value);
                });
            });
        });
        // filtro_concepto_pago select2
        $(document).ready(function () {
            $('#filtro_concepto_pago').select2({
                placeholder: 'Seleccione su concepto de pago',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando..";
                    }
                }
            });
            $('#filtro_concepto_pago').on('change', function(){
                @this.set('filtro_concepto_pago', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_concepto_pago').select2({
                    placeholder: 'Seleccione su concepto de pago',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando..";
                        }
                    }
                });
                $('#filtro_concepto_pago').on('change', function(){
                    @this.set('filtro_concepto_pago', this.value);
                });
            });
        });
    </script> --}}
@endpush
