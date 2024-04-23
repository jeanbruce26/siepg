<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Evaluación de Postulantes
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('administrador.dashboard') }}" class="text-muted text-hover-primary">
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        Evaluación de Postulantes
                    </li>
                </ul>
            </div>
            <div class="d-flex align-items-center text-center gap-2 gap-lg-3 ms-5">
                <button type="button" class="btn btn-primary btn-sm hover-elevate-up" data-kt-menu-trigger="click"
                    data-kt-menu-placement="bottom-end">
                    <span class="svg-icon svg-icon-muted svg-icon-3"><svg width="24" height="24"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path opacity="0.3" d="M10 4H21C21.6 4 22 4.4 22 5V7H10V4Z" fill="currentColor" />
                            <path opacity="0.3" d="M13 14.4V9C13 8.4 12.6 8 12 8C11.4 8 11 8.4 11 9V14.4H13Z"
                                fill="currentColor" />
                            <path
                                d="M10.4 3.60001L12 6H21C21.6 6 22 6.4 22 7V19C22 19.6 21.6 20 21 20H3C2.4 20 2 19.6 2 19V4C2 3.4 2.4 3 3 3H9.20001C9.70001 3 10.2 3.20001 10.4 3.60001ZM13 14.4V9C13 8.4 12.6 8 12 8C11.4 8 11 8.4 11 9V14.4H8L11.3 17.7C11.7 18.1 12.3 18.1 12.7 17.7L16 14.4H13Z"
                                fill="currentColor" />
                        </svg>
                    </span>
                    Exportar
                </button>
                <div id="kt_datatable_example_export_menu"
                    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
                    data-kt-menu="true">
                    <div class="menu-item px-3">
                        <a href="#exportarExcel" wire:click="exportar_excel" class="menu-link px-3">
                            Exportar a Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid pt-5">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select class="form-select" wire:model="programa_filtro"
                                id="programa_filtro" data-control="select2"
                                data-placeholder="Seleccione el Programa">
                                <option></option>
                                <option value="0">
                                    TODOS LOS PROGRAMAS
                                </option>
                                @foreach ($programas as $item)
                                    <option value="{{ $item->id_programa }}">
                                        {{ $item->programa }} EN {{ $item->subprograma }}
                                        @if ($item->mencion != '')
                                            CON MENCION EN {{ $item->mencion }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm">
                <!-- filro de busqueda -->
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <div class="d-flex align-items-center gap-2">
                                mostrar
                                <select class="form-select form-select-sm" wire:model="cant_paginas">
                                    <option value="10">
                                        10
                                    </option>
                                    <option value="25">
                                        25
                                    </option>
                                    <option value="50">
                                        50
                                    </option>
                                    <option value="100">
                                        100
                                    </option>
                                    <option value="150">
                                        150
                                    </option>
                                    <option value="200">
                                        200
                                    </option>
                                </select>
                                registros
                            </div>
                        </div>
                        <div class="col-md-5"></div>
                        <div class="col-md-5">
                            <input class="form-control form-control-sm text-muted" type="search"
                                wire:model="search" placeholder="Buscar...">
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-rounded border gy-4 gs-4 mb-0 align-middle">
                            <thead class="bg-light-primary">
                                <tr align="center"
                                    class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                    <th scope="col">ID</th>
                                    <th scope="col"
                                        @if ($sort_nombre == 'nombre_completo')
                                            @if ($sort_direccion == 'asc')
                                                class="table-sort-asc"
                                            @else
                                                class="table-sort-desc"
                                            @endif
                                        @endif  style="cursor: pointer;" wire:click="ordenar_tabla('nombre_completo')">
                                        Postulante
                                    </th>
                                    <th scope="col">Programa</th>
                                    <th scope="col"
                                        @if ($sort_nombre == 'puntaje_expediente')
                                            @if ($sort_direccion == 'asc')
                                                class="table-sort-asc"
                                            @else
                                                class="table-sort-desc"
                                            @endif
                                        @endif  style="cursor: pointer;" wire:click="ordenar_tabla('puntaje_expediente')">
                                        Expediente
                                    </th>
                                    <th scope="col"
                                        @if ($sort_nombre == 'puntaje_investigacion')
                                            @if ($sort_direccion == 'asc')
                                                class="table-sort-asc"
                                            @else
                                                class="table-sort-desc"
                                            @endif
                                        @endif  style="cursor: pointer;" wire:click="ordenar_tabla('puntaje_investigacion')">
                                        Investigacion
                                    </th>
                                    <th scope="col"
                                        @if ($sort_nombre == 'puntaje_entrevista')
                                            @if ($sort_direccion == 'asc')
                                                class="table-sort-asc"
                                            @else
                                                class="table-sort-desc"
                                            @endif
                                        @endif  style="cursor: pointer;" wire:click="ordenar_tabla('puntaje_entrevista')">
                                        Entrevista
                                    </th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($inscripciones as $item)
                                @php
                                    $evaluacion_data = App\Models\Evaluacion::where('id_inscripcion', $item->id_inscripcion)->first();
                                @endphp
                                    <tr wire:key="{{ $item->id_inscripcion }}">
                                        <td align="center" class="fw-bold">
                                            {{ $item->id_inscripcion }}
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-900 text-hover-primary mb-1">
                                                    {{ $item->apellido_paterno }} {{ $item->apellido_materno }},
                                                    {{ $item->nombre }}
                                                </span>
                                                <span class="text-gray-600">{{ $item->numero_documento }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $item->programa }}
                                            EN {{ $item->subprograma }}
                                            @if ($item->mencion != '')
                                                CON MENCION EN {{ $item->mencion }}
                                            @endif
                                        </td>
                                        <td align="center" class="fw-bold">
                                            {{ $evaluacion_data->puntaje_expediente ?? '-' }}
                                        </td>
                                        <td align="center" class="fw-bold">
                                            {{ $evaluacion_data->puntaje_investigacion ?? '-' }}
                                        </td>
                                        <td align="center" class="fw-bold">
                                            {{ $evaluacion_data->puntaje_entrevista ?? '-' }}
                                        </td>
                                        <td align="center">
                                            <a class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary btn-sm"
                                                data-bs-toggle="dropdown">
                                                Acciones
                                                <span class="svg-icon fs-5 m-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
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
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4 w-175px"
                                                data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a wire:click="cargar_evaluacion({{ $item->id_inscripcion }})"
                                                        class="menu-link px-3 cursor-pointer" data-bs-toggle="modal"
                                                        data-bs-target="#modal-evaluacion">
                                                        Evaluar
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    @if ($search != '')
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">
                                                No se encontraron resultados para la busqueda
                                                "{{ $search }}"
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">
                                                No hay registros
                                            </td>
                                        </tr>
                                    @endif
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    @if ($inscripciones->hasPages())
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center text-gray-700">
                                Mostrando {{ $inscripciones->firstItem() }} -
                                {{ $inscripciones->lastItem() }} de
                                {{ $inscripciones->total() }} registros
                            </div>
                            <div>
                                {{ $inscripciones->links() }}
                            </div>
                        </div>
                    @else
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center text-gray-700">
                                Mostrando {{ $inscripciones->firstItem() }} -
                                {{ $inscripciones->lastItem() }} de
                                {{ $inscripciones->total() }} registros
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modal-evaluacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showModalLabel">
                        Editar Evaluacion <span class="badge badge-warning">version 1</span>
                    </h5>
                    <button type="button" wire:click="limpiar()" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-5">
                        <div class="@if($es_doctorado == true ) col-4 @else col-6 @endif">
                            <div class="p-4 rounded-2 text-center fw-bold cursor-pointer @if($variable === 'expediente') bg-info text-white @else bg-light-info @endif"
                                wire:click="change_title('expediente')">
                                Evaluacion de Expediente
                            </div>
                        </div>
                        @if ($es_doctorado == true)
                        <div class="col-4">
                            <div class="p-4 rounded-2 text-center fw-bold cursor-pointer @if($variable === 'investigacion') bg-info text-white @else bg-light-info @endif"
                                wire:click="change_title('investigacion')">
                                Evaluacion de Investigacion
                            </div>
                        </div>
                        @endif
                        <div class="@if($es_doctorado == true ) col-4 @else col-6 @endif">
                            <div class="p-4 rounded-2 text-center fw-bold cursor-pointer @if($variable === 'entrevista') bg-info text-white @else bg-light-info @endif"
                                wire:click="change_title('entrevista')">
                                Evaluacion de Entrevista
                            </div>
                        </div>
                    </div>
                    <div>
                        @if ($variable === 'expediente')
                            <div class="row mb-5 mb-xl-10">
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
                                    <div class="alert bg-light-warning border-warning border-3 d-flex align-items-center p-5 mb-5">
                                        <i class="ki-outline ki-information-2 fs-2qx me-4 text-warning"></i>
                                        <div class="d-flex flex-column gap-2">
                                            <span class="fw-bold fs-6">
                                                Nota: Si el postulante no cuenta con su Grado Académico "Bachiller" en caso de Maestria o "Magister" en caso de Doctorado, se debe evaluar con puntaje "0" (cero) o dar click en el boton "Evaluar Cero".
                                            </span>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover table-rounded table-bordered align-middle table-row-bordered border mb-0 gy-4 gs-4">
                                            <thead class="bg-light-warning">
                                                <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                    <th class="col-md-6">Concepto</th>
                                                    <th class="text-center">Punatje Especifico</th>
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
                                                                                        <i class="las la-long-arrow-alt-right"></i>
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
                                                                PUNTAJE MAXIMO ({{ number_format($item->evaluacion_expediente_titulo_puntaje,0) }})
                                                            </strong>
                                                            <div class="ms-3">
                                                                @foreach ($evaluacion_expediente_items as $item2)
                                                                    @if ($item2->evaluacion_expediente_item_puntaje != null)
                                                                        <div>
                                                                            @if ($evaluacion_expediente_subitems->count() > 0)
                                                                                <strong>
                                                                                    Maximo {{ number_format($item2->evaluacion_expediente_item_puntaje) }}
                                                                                </strong>
                                                                            @else
                                                                                <span class="me-2">
                                                                                    <i class="las la-long-arrow-alt-right"></i>
                                                                                </span>
                                                                                {{ number_format($item2->evaluacion_expediente_item_puntaje) }} {{ number_format($item2->evaluacion_expediente_item_puntaje) > 1 ? 'pts.' : 'pto.' }}
                                                                            @endif
                                                                            @foreach ($evaluacion_expediente_subitems as $item3)
                                                                                <div>
                                                                                    <span class="me-2">
                                                                                        <i class="las la-long-arrow-alt-right"></i>
                                                                                    </span>
                                                                                    {{ number_format($item3->evaluacion_expediente_subitem_puntaje,2) }} pts.
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                        <td align="center" class="fs-5">
                                                            <input type="number" class="form-control" wire:model="puntajes_expediente.{{ $item->id_evaluacion_expediente_titulo }}"
                                                                id="{{ $item->id_evaluacion_expediente_titulo }}" style="width: 100px"/>
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
                                        <button type="button" wire:click="evaluar_expediente" class="btn btn-success">
                                            Evaluar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @elseif ($variable === 'investigacion')
                            <div class="row mb-5 mb-xl-10">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-rounded table-bordered align-middle table-row-bordered border mb-0 gy-4 gs-4">
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
                                                            PUNTAJE MAXIMO ({{ number_format($item->evaluacion_investigacion_item_puntaje,0) }})
                                                        </td>
                                                        <td align="center" class="fs-5">
                                                            <input type="number" class="form-control" wire:model="puntajes_investigacion.{{ $item->id_evaluacion_investigacion_item }}"
                                                                    id="{{ $item->id_evaluacion_investigacion_item }}" style="width: 100px"/>
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
                                        <button type="button" wire:click="evaluar_investigacion" class="btn btn-success">
                                            Evaluar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @elseif ($variable === 'entrevista')
                            <div class="row mb-5 mb-xl-10">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-rounded table-bordered align-middle table-row-bordered border mb-0 gy-4 gs-4">
                                            <thead class="bg-light-warning">
                                                <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                                    <th class="col-md-6">Concepto</th>
                                                    <th class="text-center">Punatje Especifico</th>
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
                                                            PUNTAJE MAXIMO ({{ number_format($item->evaluacion_entrevista_item_puntaje,0) }})
                                                        </td>
                                                        <td align="center" class="fs-5">
                                                            <input type="number" class="form-control" wire:model="puntajes_entrevista.{{ $item->id_evaluacion_entrevista_item }}"
                                                                    id="{{ $item->id_evaluacion_entrevista_item }}" style="width: 100px"/>
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
    @push('scripts')
        <script>
            //Filtro de programa_filtro de select2
            $(document).ready(function() {
                $('#programa_filtro').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#programa_filtro').on('change', function() {
                    @this.set('programa_filtro', this.value);
                });
                Livewire.hook('message.processed', (message, component) => {
                    $('#programa_filtro').select2({
                        placeholder: 'Seleccione',
                        allowClear: true,
                        width: '100%',
                        selectOnClose: true,
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            },
                            searching: function() {
                                return "Buscando...";
                            }
                        }
                    });
                    $('#programa_filtro').on('change', function() {
                        @this.set('programa_filtro', this.value);
                    });
                });
            });
        </script>
    @endpush
</div>
