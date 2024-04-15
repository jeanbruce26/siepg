<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Evaluación de Expediente de {{ ucwords(strtolower($persona->nombre_completo)) }}
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
                            <a href="{{ route('coordinador.evaluaciones', ['id' => $id_programa, 'id_admision' => $id_admision]) }}" class="text-muted text-hover-primary">
                                Mencion en {{ ucwords(strtolower($programa->mencion)) }}
                            </a>
                        @else
                            <a href="{{ route('coordinador.evaluaciones', ['id' => $id_programa, 'id_admision' => $id_admision]) }}" class="text-muted text-hover-primary">
                                {{ ucwords(strtolower($programa->programa)) }} en {{ ucwords(strtolower($programa->subprograma)) }}
                            </a>
                        @endif
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        Evalaución de Expediente
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- card de datos del postulante y fecha de evaluacion --}}
                    <div class="card shadow-sm mb-5">
                        <div class="px-8 py-5 mb-0 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <i class="ki-outline ki-user-tick fs-2x"></i>
                                <span class="fs-5">Postulante:</span>
                                <span class="fw-bold fs-5">{{ ucwords(strtolower($persona->nombre_completo)) }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <i class="ki-outline ki-calendar-tick fs-2x"></i>
                                <span class="fs-5">Fecha de Evaluación:</span>
                                <span class="fw-bold fs-5">{{ $evaluacion->fecha_expediente ? date('d/m/Y', strtotime($evaluacion->fecha_expediente)) : date('d/m/Y', strtotime(today())) }}</span>
                            </div>
                        </div>
                    </div>
                    @if ($expedientes)
                        {{-- card de exediente --}}
                        <div class="row g-5 mb-5">
                            @foreach ($expedientes as $item)
                                @php $expediente_tipo_evaluacion = App\Models\ExpedienteTipoEvaluacion::where('expediente_tipo_evaluacion', 2)->where('id_expediente', $item->id_expediente)->first(); @endphp
                                @if ($expediente_tipo_evaluacion)
                                    <div class="col-xl-4 col-lg-6 col-md-6">
                                        <div class="card shadow-sm bg-info bg-opacity-20 h-100">
                                            <div class="card-body mb-0 d-flex flex-column justify-content-center">
                                                <div class="text-center mb-5">
                                                    <span class="fs-4 fs-md-3 fw-bold text-gray-800">
                                                        {{ $item->expediente }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <a href="{{ asset($item->expediente_inscripcion_url) }}" target="_blank" class="btn btn-info w-100 hover-scale">
                                                        Abrir Expediente
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    {{-- card --}}
                    <div class="card shadow-sm">
                        <div class="card-body mb-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-rounded table-bordered align-middle table-row-bordered border mb-0 gy-4 gs-4">
                                    <thead class="bg-light-warning">
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                            <th class="col-md-6">Concepto</th>
                                            <th class="text-center">Puntaje Especifico</th>
                                            <th class="text-center">Calificación</th>
                                            <th class="text-center">Puntaje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($evaluacion_investigacion as $item)
                                            <tr>
                                                <td class="fw-bold">
                                                    <span class="me-3">
                                                        {{ $loop->iteration }}.
                                                    </span>
                                                    {{ $item->evaluacion_investigacion_item }}
                                                </td>
                                                <td align="center" class="fw-bold">
                                                    PUNTAJE MAXIMO ({{ number_format($item->evaluacion_investigacion_item_puntaje,0) }})
                                                </td>
                                                <td align="center">
                                                    <button type="button" wire:click="cargar_evaluacion_investigacion_item({{ $item->id_evaluacion_investigacion_item }})" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal_puntaje">
                                                        Ingresar Puntaje
                                                    </button>
                                                </td>
                                                @php $evaluacion_investigacion_notas = App\Models\EvaluacionInvestigacion::where('id_evaluacion_investigacion_item', $item->id_evaluacion_investigacion_item)->where('id_evaluacion',$id_evaluacion)->first(); @endphp
                                                <td align="center" class="fs-5">
                                                    @if ($evaluacion_investigacion_notas)
                                                        <strong>{{ number_format($evaluacion_investigacion_notas->evaluacion_investigacion_puntaje, 0) }}</strong>
                                                    @else
                                                        <strong>-</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-light-secondary">
                                        <tr>
                                            <td colspan="3" class="fw-bold text-center">TOTAL</td>
                                            <td align="center" class="fw-bold fs-5">{{ $puntaje_total }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="my-5">
                                <form novalidate autocomplete="off">
                                    <!-- Example Textarea -->
                                    <div>
                                        <label class="form-label">Ingrese observación</label>
                                        <textarea class="form-control" rows="3" wire:model="observacion" ></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="text-end">
                                <button type="button" wire:click="evaluar_investigacion_paso_1()" class="btn btn-info">
                                    Evaluar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Puntaje --}}
    <div wire:ignore.self class="modal fade" id="modal_puntaje" tabindex="-1" role="dialog" aria-labelledby="modalNotaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase" id="modalNotaLabel">Ingresar su puntaje</h5>
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
                            <input type="number" class="form-control @error('puntaje') is-invalid @enderror w-100" wire:model="puntaje" placeholder="Ingrese el puntaje...">
                            @error('puntaje')
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
                        <button type="button" wire:click="agregar_puntaje()" class="btn btn-success">Guardar</button>
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
