<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    @if ($programa->mencion)
                        Mencion en {{ ucwords(strtolower($programa->mencion)) }}
                    @else
                        {{ ucwords(strtolower($programa->programa)) }} en
                        {{ ucwords(strtolower($programa->subprograma)) }}
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
                        <a href="{{ route('coordinador.inicio') }}"
                            class="text-muted text-hover-primary">Evaluaciones</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    <div
                        class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                        <i class="ki-outline ki-information-5 fs-2qx me-4 text-primary"></i>
                        <div class="d-flex flex-column gap-2">
                            @if ($programa->programa_tipo == 1)
                                <span class="fw-bold fs-5">
                                    La nota aprobatoria para ser admitido es de
                                    {{ number_format($puntaje->puntaje_maestria) }} puntos totales (EVA.
                                    EXPEDIENTE + ENTREVISTA + TEMA DE TESIS). Una vez realizado la evaluación, no podrá
                                    realizar modificación de las notas ingresadas.
                                </span>
                            @else
                                <span class="fw-bold fs-5">
                                    La nota aprobatoria para ser admitido es de
                                    {{ number_format($puntaje->puntaje_doctorado) }} puntos totales (EVA.
                                    EXPEDIENTE + ENTREVISTA + TEMA DE TESIS). Una vez realizado la evaluación, no podrá
                                    realizar modificación de las notas ingresadas.
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card shadow-sm mb-5">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <input type="search" wire:model="search" class="form-control w-full"
                                        placeholder="Buscar..." />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table
                                    class="table table-hover table-rounded table-bordered align-middle table-row-bordered border mb-0 gy-4 gs-4">
                                    <thead class="bg-light-warning">
                                        <tr class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                            <th class="text-center">#</th>
                                            <th>Apellidos y Nombres</th>
                                            <th class="text-center">Documento</th>
                                            <th class="text-center">Eva. Expediente</th>
                                            @if ($programa->programa_tipo == 2)
                                                <th class="text-center">Eva. Tesis</th>
                                            @endif
                                            <th class="text-center">Eva. Entrevista</th>
                                            <th class="text-center">Puntaje Final</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($inscripciones as $item)
                                            @php $evaluacion = App\Models\Evaluacion::where('id_inscripcion', $item->id_inscripcion)->first(); @endphp
                                            <tr>
                                                <td class="fw-bold fs-6" align="center">
                                                    {{ $item->numero }}
                                                </td>
                                                <td class="fs-6">
                                                    {{ $item->nombre_completo }}
                                                </td>
                                                <td class="fs-6" align="center">
                                                    {{ $item->numero_documento }}
                                                </td>
                                                <td align="center">
                                                    @if ($evaluacion)
                                                        @if ($evaluacion->puntaje_expediente)
                                                            <span class="fw-bold fs-6">
                                                                {{ number_format($evaluacion->puntaje_expediente) }}
                                                                pts.
                                                            </span>
                                                        @else
                                                            <button type="button" class="btn btn-info hover-scale"
                                                                wire:click="cargar_evaluacion({{ $item->id_inscripcion }}, 'expediente')">
                                                                Evaluar
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button type="button" class="btn btn-info hover-scale"
                                                            wire:click="cargar_evaluacion({{ $item->id_inscripcion }}, 'expediente')">
                                                            Evaluar
                                                        </button>
                                                    @endif
                                                </td>
                                                @if ($programa->programa_tipo == 2)
                                                    <td align="center">
                                                        @if ($evaluacion)
                                                            @if ($evaluacion->puntaje_investigacion)
                                                                <span class="fw-bold fs-6">
                                                                    {{ number_format($evaluacion->puntaje_investigacion) }}
                                                                    pts.
                                                                </span>
                                                            @else
                                                                <button type="button" class="btn btn-info hover-scale"
                                                                    wire:click="cargar_evaluacion({{ $item->id_inscripcion }}, 'investigacion')">
                                                                    Evaluar
                                                                </button>
                                                            @endif
                                                        @else
                                                            <button type="button" class="btn btn-info hover-scale"
                                                                wire:click="cargar_evaluacion({{ $item->id_inscripcion }}, 'investigacion')">
                                                                Evaluar
                                                            </button>
                                                        @endif
                                                    </td>
                                                @endif
                                                <td align="center">
                                                    @if ($evaluacion)
                                                        @if ($evaluacion->puntaje_entrevista)
                                                            <span class="fw-bold fs-6">
                                                                {{ number_format($evaluacion->puntaje_entrevista) }}
                                                                pts.
                                                            </span>
                                                        @else
                                                            <button type="button" class="btn btn-info hover-scale"
                                                                wire:click="cargar_evaluacion({{ $item->id_inscripcion }}, 'entrevista')">
                                                                Evaluar
                                                            </button>
                                                        @endif
                                                    @else
                                                        <button type="button" class="btn btn-info hover-scale"
                                                            wire:click="cargar_evaluacion({{ $item->id_inscripcion }}, 'entrevista')">
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
                                                    @else
                                                        <span class="fw-bold fs-6">
                                                            -
                                                        </span>
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    @if ($evaluacion)
                                                        @if ($evaluacion->evaluacion_estado == 1)
                                                            <span class="badge badge-warning fs-6 px-3 py-2">
                                                                Pendiente
                                                            </span>
                                                        @elseif ($evaluacion->evaluacion_estado == 2)
                                                            <span class="badge badge-success fs-6 px-3 py-2">
                                                                Admitido
                                                            </span>
                                                        @elseif ($evaluacion->evaluacion_estado == 3)
                                                            <span class="badge badge-danger fs-6 px-3 py-2">
                                                                No Admitido
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-warning fs-6">
                                                            Pendiente
                                                        </span>
                                                    @endif
                                                </td>
                                                <td align="center">
                                                    <button class="btn btn-primary fw-bold hover-scale"
                                                        wire:click="detalle_evaluacion({{ $item->id_inscripcion }})"
                                                        data-bs-toggle="modal" data-bs-target="#detalle-evaluacion">
                                                        Detalle
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            @if ($search != '')
                                                <tr>
                                                    <td colspan="{{ $programa->programa_tipo == 2 ? '9' : '8' }}"
                                                        class="text-center text-muted">
                                                        No se encontraron resultados para la busqueda
                                                        "{{ $search }}"
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="{{ $programa->programa_tipo == 2 ? '9' : '8' }}"
                                                        class="text-center text-muted">
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
    <div wire:ignore.self class="modal fade" tabindex="-1" id="detalle-evaluacion">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">{{ $title_modal }}</h2>

                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-2hx">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5"
                                    fill="currentColor" />
                                <rect x="7" y="15.3137" width="12" height="2" rx="1"
                                    transform="rotate(-45 7 15.3137)" fill="currentColor" />
                                <rect x="8.41422" y="7" width="12" height="2" rx="1"
                                    transform="rotate(45 8.41422 7)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row mb-3">
                                <span class="col-md-4 fw-semibold text-gray-600 fs-5">
                                    Apellidos y Nombres
                                </span>
                                <span class="col-md-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-md-7 fw-bold text-gray-900 fs-5">
                                    {{ $nombre_completo }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-md-4 fw-semibold text-gray-600 fs-5">
                                    Documento de Identidad
                                </span>
                                <span class="col-md-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-md-7 fw-bold text-gray-900 fs-5">
                                    {{ $documento }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-md-4 fw-semibold text-gray-600 fs-5">
                                    Correo Electrónico
                                </span>
                                <span class="col-md-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-md-7 fw-bold text-gray-900 fs-5">
                                    {{ $correo }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-md-4 fw-semibold text-gray-600 fs-5">
                                    Celular
                                </span>
                                <span class="col-md-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-md-7 fw-bold text-gray-900 fs-5">
                                    (+51) {{ $celular }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-md-4 fw-semibold text-gray-600 fs-5">
                                    Especialidad
                                </span>
                                <span class="col-md-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-md-7 fw-bold text-gray-900 fs-5">
                                    {{ $especialidad }}
                                </span>
                            </div>
                            <div class="row mb-3">
                                <span class="col-md-4 fw-semibold text-gray-600 fs-5">
                                    Grado Académico
                                </span>
                                <span class="col-md-1 fw-semibold text-gray-600 fs-5">
                                    :
                                </span>
                                <span class="col-md-7 fw-bold text-gray-900 fs-5">
                                    {{ $grado_academico }}
                                </span>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div
                                class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5">
                                <span class="svg-icon svg-icon-2hx svg-icon-primary me-4 d-flex align-items-center">
                                    <i class="las la-exclamation-circle fs-1 text-primary"></i>
                                </span>
                                <div class="d-flex flex-column gap-2">
                                    <span class="fw-bold fs-6">
                                        - Para abrir el expediente, dar click en el nombre del mismo. <br>
                                        - En caso de que el postulante no haya enviado su expediente, aparecerá en color
                                        rojo.
                                    </span>
                                </div>
                            </div>
                        </div>
                        <span class="col-12 fs-3 fw-bold mb-3">
                            Información de Expedientes
                        </span>
                        <div class="col-md-12">
                            <div class="row g-2">
                                @if ($expedientes)
                                    @foreach ($expedientes as $item)
                                        <div class="col-md-12">
                                            @if ($item->estado == 1)
                                                <a target="_blank"
                                                    href="{{ asset($item->expediente_inscripcion_url) }}">
                                                    <div
                                                        class="card hover-elevate-up bg-light-success border border-3 border-success px-5 py-2 text-gray-900">
                                                        <span class="fs-5 fw-semibold">
                                                            {{ $item->expediente }}
                                                        </span>
                                                    </div>
                                                </a>
                                            @else
                                                <div
                                                    class="card bg-light-danger border border-3 border-danger px-5 py-2 text-gray-900">
                                                    <span class="fs-5 fw-semibold">
                                                        {{ $item->expediente }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modal-evaluacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showModalLabel">
                        Editar Evaluacion
                    </h5>
                    <button type="button" wire:click="limpiar()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-5">
                        @if ($variable === 'expediente')
                            <div class="col-12">
                                <div class="p-4 rounded-2 text-center fw-bold cursor-pointer @if ($variable === 'expediente') bg-info text-white @else bg-light-info @endif"
                                    wire:click="change_title('expediente')">
                                    Evaluacion de Expediente
                                </div>
                            </div>
                        @elseif ($variable === 'investigacion')
                            <div class="col-12">
                                <div class="p-4 rounded-2 text-center fw-bold cursor-pointer @if ($variable === 'investigacion') bg-info text-white @else bg-light-info @endif"
                                    wire:click="change_title('investigacion')">
                                    Evaluacion de Investigacion
                                </div>
                            </div>
                        @elseif ($variable === 'entrevista')
                            <div class="col-12">
                                <div class="p-4 rounded-2 text-center fw-bold cursor-pointer @if ($variable === 'entrevista') bg-info text-white @else bg-light-info @endif"
                                    wire:click="change_title('entrevista')">
                                    Evaluacion de Entrevista
                                </div>
                            </div>
                        @endif
                    </div>
                    @if ($expedientes_inscripcion->count() > 0)
                        {{-- card de exediente --}}
                        <div class="row g-5 mb-5">
                            @foreach ($expedientes_inscripcion as $item)
                                @php $expediente_tipo_evaluacion = App\Models\ExpedienteTipoEvaluacion::where('expediente_tipo_evaluacion', $expediente_tipo_evaluacion)->where('id_expediente', $item->id_expediente)->first(); @endphp
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
                    @if ($variable === 'expediente')
                        <div class="row">
                            <div class="col-md-12">
                                {{-- @if ($expedientes)
                                    <div class="row g-5 mb-5">
                                        @foreach ($expedientes as $item)
                                            @php $expediente_tipo_evaluacion = App\Models\ExpedienteTipoEvaluacion::where('expediente_tipo_evaluacion', 1)->where('id_expediente', $item->id_expediente)->first(); @endphp
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
                                @endif --}}
                                {{-- alerta --}}
                                {{-- <div class="alert bg-light-warning border-warning border-3 d-flex align-items-center p-5 mb-5">
                                    <i class="ki-outline ki-information-2 fs-2qx me-4 text-warning"></i>
                                    <div class="d-flex flex-column gap-2">
                                        <span class="fw-bold fs-6">
                                            Nota: Si el postulante no cuenta con su Grado Académico "Bachiller" en caso de Maestria o "Magister" en caso de Doctorado, se debe evaluar con puntaje "0" (cero) o dar click en el boton "Evaluar Cero".
                                        </span>
                                    </div>
                                </div> --}}
                                <div class="table-responsive">
                                    <table
                                        class="table table-hover table-rounded table-bordered align-middle table-row-bordered border mb-0 gy-4 gs-4">
                                        <thead class="bg-light-warning">
                                            <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                <th class="col-md-6">Concepto</th>
                                                <th class="text-center">Puntaje Especifico</th>
                                                <th class="text-center">Puntaje</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($evaluacion_expediente as $item)
                                                @php $evaluacion_expediente_items = App\Models\EvaluacionExpedienteItem::where('id_evaluacion_expediente_titulo', $item->id_evaluacion_expediente_titulo)->get(); @endphp
                                                <tr wire:key="{{ $item->id_evaluacion_expediente_titulo }}">
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <strong>
                                                                <span class="me-3">
                                                                    {{ $loop->iteration }}.
                                                                </span>
                                                                {{ $item->evaluacion_expediente_titulo }}
                                                            </strong>
                                                            <div class="ms-3">
                                                                @foreach ($evaluacion_expediente_items as $item2)
                                                                    @php $evaluacion_expediente_subitems = App\Models\EvaluacionExpedienteSubitem::where('id_evaluacion_expediente_item', $item2->id_evaluacion_expediente_item)->get(); @endphp
                                                                    <div>
                                                                        <span class="me-3">
                                                                            <i class="las la-long-arrow-alt-right"></i>
                                                                        </span>
                                                                        {{ $item2->evaluacion_expediente_item }}
                                                                        @foreach ($evaluacion_expediente_subitems as $item3)
                                                                            <div>
                                                                                <span class="me-3 ms-3">
                                                                                    <i
                                                                                        class="las la-long-arrow-alt-right"></i>
                                                                                </span>
                                                                                {{ $item3->evaluacion_expediente_subitem }}
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td align="center">
                                                        <strong>
                                                            PUNTAJE MAXIMO
                                                            ({{ number_format($item->evaluacion_expediente_titulo_puntaje, 0) }})
                                                        </strong>
                                                        <div class="ms-3">
                                                            @foreach ($evaluacion_expediente_items as $item2)
                                                                @if ($item2->evaluacion_expediente_item_puntaje != null)
                                                                    <div>
                                                                        @if ($evaluacion_expediente_subitems->count() > 0)
                                                                            <strong>
                                                                                Maximo
                                                                                {{ number_format($item2->evaluacion_expediente_item_puntaje) }}
                                                                            </strong>
                                                                        @else
                                                                            <span class="me-2">
                                                                                <i
                                                                                    class="las la-long-arrow-alt-right"></i>
                                                                            </span>
                                                                            {{ number_format($item2->evaluacion_expediente_item_puntaje) }}
                                                                            {{ number_format($item2->evaluacion_expediente_item_puntaje) > 1 ? 'pts.' : 'pto.' }}
                                                                        @endif
                                                                        @foreach ($evaluacion_expediente_subitems as $item3)
                                                                            <div>
                                                                                <span class="me-2">
                                                                                    <i
                                                                                        class="las la-long-arrow-alt-right"></i>
                                                                                </span>
                                                                                {{ number_format($item3->evaluacion_expediente_subitem_puntaje, 2) }}
                                                                                pts.
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <td align="center" class="fs-5">
                                                        <input type="number" class="form-control"
                                                            wire:model="puntajes_expediente.{{ $item->id_evaluacion_expediente_titulo }}"
                                                            id="{{ $item->id_evaluacion_expediente_titulo }}"
                                                            style="width: 100px" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-light-secondary">
                                            <tr>
                                                <td colspan="2" class="fw-bold text-center">TOTAL</td>
                                                <td align="center" class="fw-bold fs-5">{{ $puntaje_total }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                {{-- <div class="my-5">
                                    <form novalidate autocomplete="off">
                                        <!-- Example Textarea -->
                                        <div>
                                            <label class="form-label">Ingrese observación</label>
                                            <textarea class="form-control" rows="3" wire:model="observacion" ></textarea>
                                        </div>
                                    </form>
                                </div> --}}
                                <div class="text-end mt-5">
                                    <button type="button" wire:click="evaluar_expediente" class="btn btn-success">
                                        Evaluar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @elseif ($variable === 'investigacion')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table
                                        class="table table-hover table-rounded table-bordered align-middle table-row-bordered border mb-0 gy-4 gs-4">
                                        <thead class="bg-light-warning">
                                            <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                <th class="col-md-6">Concepto</th>
                                                <th class="text-center">Puntaje Especifico</th>
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
                                                        PUNTAJE MAXIMO
                                                        ({{ number_format($item->evaluacion_investigacion_item_puntaje, 0) }})
                                                    </td>
                                                    <td align="center" class="fs-5">
                                                        <input type="number" class="form-control"
                                                            wire:model="puntajes_investigacion.{{ $item->id_evaluacion_investigacion_item }}"
                                                            id="{{ $item->id_evaluacion_investigacion_item }}"
                                                            style="width: 100px" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-light-secondary">
                                            <tr>
                                                <td colspan="2" class="fw-bold text-center">TOTAL</td>
                                                <td align="center" class="fw-bold fs-5">{{ $puntaje_total }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                {{-- <div class="my-5">
                                    <form novalidate autocomplete="off">
                                        <!-- Example Textarea -->
                                        <div>
                                            <label class="form-label">Ingrese observación</label>
                                            <textarea class="form-control" rows="3" wire:model="observacion" ></textarea>
                                        </div>
                                    </form>
                                </div> --}}
                                <div class="text-end mt-5">
                                    <button type="button" wire:click="evaluar_investigacion"
                                        class="btn btn-success">
                                        Evaluar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @elseif ($variable === 'entrevista')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table
                                        class="table table-hover table-rounded table-bordered align-middle table-row-bordered border mb-0 gy-4 gs-4">
                                        <thead class="bg-light-warning">
                                            <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                <th class="col-md-6">Concepto</th>
                                                <th class="text-center">Puntaje Especifico</th>
                                                <th class="text-center">Puntaje</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($evaluacion_entrevista as $item)
                                                <tr>
                                                    <td class="fw-bold">
                                                        <span class="me-3">
                                                            {{ $loop->iteration }}.
                                                        </span>
                                                        {{ $item->evaluacion_entrevista_item }}
                                                    </td>
                                                    <td align="center" class="fw-bold">
                                                        PUNTAJE MAXIMO
                                                        ({{ number_format($item->evaluacion_entrevista_item_puntaje, 0) }})
                                                    </td>
                                                    <td align="center" class="fs-5">
                                                        <input type="number" class="form-control"
                                                            wire:model="puntajes_entrevista.{{ $item->id_evaluacion_entrevista_item }}"
                                                            id="{{ $item->id_evaluacion_entrevista_item }}"
                                                            style="width: 100px" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-light-secondary">
                                            <tr>
                                                <td colspan="2" class="fw-bold text-center">TOTAL</td>
                                                <td align="center" class="fw-bold fs-5">{{ $puntaje_total }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                {{-- <div class="my-5">
                                    <form novalidate autocomplete="off">
                                        <!-- Example Textarea -->
                                        <div>
                                            <label class="form-label">Ingrese observación</label>
                                            <textarea class="form-control" rows="3" wire:model="observacion" ></textarea>
                                        </div>
                                    </form>
                                </div> --}}
                                <div class="text-end mt-5">
                                    <button type="button" wire:click="evaluar_entrevista" class="btn btn-success">
                                        Evaluar
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
