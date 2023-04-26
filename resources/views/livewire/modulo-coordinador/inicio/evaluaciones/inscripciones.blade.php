<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    @if ($programa->mencion)
                        Mencion en {{ ucwords(strtolower($programa->mencion)) }} en Modalidad {{ ucwords(strtolower($programa->modalidad->modalidad)) }} del Proceso de {{ ucwords(strtolower($admision->admision)) }}
                    @else
                        {{ ucwords(strtolower($programa->programa)) }} en {{ ucwords(strtolower($programa->subprograma)) }} en Modalidad {{ ucwords(strtolower($programa->modalidad->modalidad)) }} del Proceso de {{ ucwords(strtolower($admision->admision)) }}
                    @endif
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('coordinador.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('coordinador.inicio') }}" class="text-muted text-hover-primary">Evaluaciones</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('coordinador.programas', $programa->id_modalidad) }}" class="text-muted text-hover-primary">Modalidad {{ ucwords(strtolower($programa->modalidad->modalidad)) }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        @if ($programa->mencion)
                            Mencion en {{ ucwords(strtolower($programa->mencion)) }}
                        @else
                            {{ ucwords(strtolower($programa->programa)) }} en {{ ucwords(strtolower($programa->subprograma)) }}
                        @endif
                    </li>
                </ul>
            </div>
            <div class="d-flex flex-stack">
                <div class="d-flex align-items-center text-center gap-2 gap-lg-3 ms-5">
                    @if ($inscripciones->count() == $evaluaciones->count())
                        @if ($programa->programa_tipo == 1)
                            <a href="{{ route('coordinador.reporte-maestria', ['id_programa' => $id_programa, 'id_admision' => $id_admision]) }}" target="_blank" class="btn btn-info btn-sm">
                                Generar Acta de Evaluación
                            </a>
                        @else
                            <a href="{{ route('coordinador.reporte-doctorado', ['id_programa' => $id_programa, 'id_admision' => $id_admision]) }}" target="_blank" class="btn btn-info btn-sm">
                                Generar Acta de Evaluación
                            </a>
                        @endif
                    @else
                        <button type="button" class="btn btn-info btn-sm" disabled>
                            Generar Acta de Evaluación
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
                    <div class="alert bg-light-primary border-primary d-flex align-items-center p-5 mb-5">
                        <span class="svg-icon svg-icon-2hx svg-icon-primary me-4 d-flex align-items-center">
                            <i class="las la-exclamation-circle fs-2 text-primary"></i>
                        </span>
                        <div class="d-flex flex-column gap-2">
                            @if ($programa->programa_tipo == 1)
                                <span class="fw-bold">
                                    La nota aprobatoria para ser admitido es de {{ number_format($puntaje_model->puntaje_maestria) }} puntos totales (EVA. EXPEDIENTE + ENTREVISTA + TEMA DE TESIS). Una vez realizado la evaluación, no podrá realizar modificación de las notas ingresadas.
                                </span>
                            @else
                                <span class="fw-bold">
                                    La nota aprobatoria para ser admitido es de {{ number_format($puntaje_model->puntaje_doctorado) }} puntos totales (EVA. EXPEDIENTE + ENTREVISTA + TEMA DE TESIS). Una vez realizado la evaluación, no podrá realizar modificación de las notas ingresadas.
                                </span>
                            @endif
                        </div>
                    </div>
                    {{-- card monto de pagos --}}
                    <div class="card shadow-sm">
                        <div class="card-body mb-0">
                            <div class="table-responsive">
                                <div class="d-flex flex-column flex-md-row align-items-center mb-5 w-100">
                                    <div class="col-md-4 pe-md-3"></div>
                                    <div class="col-md-4 px-md-3"></div>
                                    <div class="col-md-4 ps-md-3">
                                        <input type="search" wire:model="search" class="form-control w-100" placeholder="Buscar..."/>
                                    </div>
                                </div>
                                <table class="table table-hover table-rounded table-bordered align-middle table-row-bordered border mb-0 gy-4 gs-4">
                                    <thead class="bg-light-warning">
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                            <th>#</th>
                                            <th>Apellidos y Nombres</th>
                                            <th>Numero Documento</th>
                                            <th class="text-center">Eva. Expediente</th>
                                            @if ($programa->programa_tipo == 2)
                                            <th class="text-center">Eva. Tesis</th>
                                            @endif
                                            <th class="text-center">Eva. Entrevista</th>
                                            <th class="text-center">Puntaje Final</th>
                                            <th class="text-center">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($inscripciones as $item)
                                            @php $evaluacion = App\Models\Evaluacion::where('id_inscripcion', $item->id_inscripcion)->first(); @endphp
                                            <tr>
                                                <td class="fw-bold">
                                                    {{ $item->id_inscripcion }}
                                                </td>
                                                <td>
                                                    {{ $item->nombre_completo }}
                                                </td>
                                                <td>
                                                    {{ $item->numero_documento }}
                                                </td>
                                                <td align="center">
                                                    @if ($evaluacion)
                                                        @if ($evaluacion->puntaje_expediente)
                                                            <span class="fw-bold fs-6">
                                                                {{ number_format($evaluacion->puntaje_expediente) }} pts.
                                                            </span>
                                                        @else
                                                            <button class="btn btn-info btn-sm hover-scale" wire:click="evaluacion_expediente({{ $item->id_inscripcion }})">
                                                                Evaluar
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button class="btn btn-info btn-sm hover-scale" wire:click="evaluacion_expediente({{ $item->id_inscripcion }})">
                                                            Evaluar
                                                        </button>
                                                    @endif
                                                </td>
                                                @if ($programa->programa_tipo == 2)
                                                <td align="center">
                                                    @if ($evaluacion)
                                                        @if ($evaluacion->puntaje_investigacion)
                                                            <span class="fw-bold fs-6">
                                                                {{ number_format($evaluacion->puntaje_investigacion) }} pts.
                                                            </span>
                                                        @else
                                                            <button class="btn btn-info btn-sm hover-scale" wire:click="evaluacion_investigacion({{ $item->id_inscripcion }})">
                                                                Evaluar
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button class="btn btn-info btn-sm hover-scale" wire:click="evaluacion_investigacion({{ $item->id_inscripcion }})">
                                                            Evaluar
                                                        </button>
                                                    @endif
                                                </td>
                                                @endif
                                                <td align="center">
                                                    @if ($evaluacion)
                                                        @if ($evaluacion->puntaje_entrevista)
                                                            <span class="fw-bold fs-6">
                                                                {{ number_format($evaluacion->puntaje_entrevista) }} pts.
                                                            </span>
                                                        @else
                                                            <button class="btn btn-info btn-sm hover-scale" wire:click="evaluacion_entrevista({{ $item->id_inscripcion }})">
                                                                Evaluar
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button class="btn btn-info btn-sm hover-scale" wire:click="evaluacion_entrevista({{ $item->id_inscripcion }})">
                                                            Evaluar
                                                        </button>
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if ($evaluacion)
                                                        @if ($evaluacion->puntaje_final)
                                                            <span class="fw-bold fs-6">
                                                                {{ number_format($evaluacion->puntaje_final) }} pts.
                                                            </span>
                                                        @else
                                                            <span class="fw-bold fs-6">
                                                                -
                                                            </span>
                                                        @endif

                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if ($evaluacion)
                                                        @if ($evaluacion->evaluacion_estado == 1)
                                                            <span class="badge badge-warning fs-6">
                                                                Pendiente
                                                            </span>
                                                        @elseif ($evaluacion->evaluacion_estado == 2)
                                                            <span class="badge badge-success fs-6">
                                                                Admitido
                                                            </span>
                                                        @elseif ($evaluacion->evaluacion_estado == 3)
                                                            <span class="badge badge-danger fs-6">
                                                                No Admitido
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-warning fs-6">
                                                            Pendiente
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            @if ($search != '')
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">
                                                        No se encontraron resultados para la busqueda "{{ $search }}"
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">
                                                        No hay registros
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // filtro_proceso select2
        $(document).ready(function () {
            $('#filtro_proceso').select2({
                placeholder: 'Seleccione',
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
            $('#filtro_proceso').on('change', function(){
                @this.set('filtro_proceso', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_proceso').select2({
                    placeholder: 'Seleccione',
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
                $('#filtro_proceso').on('change', function(){
                    @this.set('filtro_proceso', this.value);
                });
            });
        });
    </script>
@endpush
