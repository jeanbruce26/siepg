<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    @if ($programa->mencion)
                        Inscripciones de la Mencion en {{ ucwords(strtolower($programa->mencion)) }} en Modalidad {{ ucwords(strtolower($programa->modalidad->modalidad)) }} del Proceso de {{ ucwords(strtolower($admision->admision)) }}
                    @else
                    Inscripciones de la {{ ucwords(strtolower($programa->programa)) }} en {{ ucwords(strtolower($programa->subprograma)) }} en Modalidad {{ ucwords(strtolower($programa->modalidad->modalidad)) }} del Proceso de {{ ucwords(strtolower($admision->admision)) }}
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
                            Inscripciones de la Mencion en {{ ucwords(strtolower($programa->mencion)) }}
                        @else
                            Inscripciones de la {{ ucwords(strtolower($programa->programa)) }} en {{ ucwords(strtolower($programa->subprograma)) }}
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- alerta --}}
                    <div class="alert bg-light-primary border border-primary d-flex alig-items-center p-5 mb-5">
                        <span class="svg-icon svg-icon-2hx svg-icon-primary me-4 d-flex align-items-center">
                            <i class="las la-exclamation-circle fs-2 text-primary"></i>
                        </span>
                        <div class="d-flex flex-column gap-2">
                            <span class="fw-bold">
                                Los datos se pueden ordenar de forma "ascendente" o "descendente" haciendo click en el encabezado de la columna.
                            </span>
                        </div>
                    </div>
                    {{-- card monto de pagos --}}
                    <div class="card shadow-sm">
                        <div class="card-body mb-0">
                            <div class="table-responsive">
                                <div class="d-flex flex-column flex-md-row align-items-center mb-5 w-100">
                                    <div class="col-md-4 pe-md-3"></div>
                                    <div class="col-md-4 px-md-3">
                                        {{-- <select class="form-select" wire:model="filtro_canal_pago" data-control="select2" id="filtro_canal_pago" data-placeholder="Seleccione el canal de pago">
                                            <option></option>
                                            <option value="all">Mostrar todos los pagos</option>
                                            @foreach ($canal_pagos as $item)
                                                <option value="{{ $item->id_canal_pago }}">Pago en {{ $item->canal_pago }}</option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                    <div class="col-md-4 ps-md-3">
                                        <input type="search" wire:model="search" class="form-control w-100" placeholder="Buscar..."/>
                                    </div>
                                </div>
                                <table class="table table-hover table-rounded align-middle table-row-bordered border mb-0 gy-4 gs-4">
                                    <thead class="bg-light-warning">
                                        <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                            <th @if ($sort_nombre == 'id_inscripcion')
                                                    @if ($sort_direccion == 'asc')
                                                        class="table-sort-asc"
                                                    @else
                                                        class="table-sort-desc"
                                                    @endif
                                                @endif  style="cursor: pointer;" wire:click="ordenar_tabla('id_inscripcion')">
                                                #
                                            </th>
                                            <th @if ($sort_nombre == 'nombre_completo')
                                                    @if ($sort_direccion == 'asc')
                                                        class="table-sort-asc"
                                                    @else
                                                        class="table-sort-desc"
                                                    @endif
                                                @endif  style="cursor: pointer;" wire:click="ordenar_tabla('nombre_completo')">
                                                Apellidos y Nombres
                                            </th>
                                            <th>Numero Documento</th>
                                            <th @if ($sort_nombre == 'inscripcion_fecha')
                                                    @if ($sort_direccion == 'asc')
                                                        class="table-sort-asc"
                                                    @else
                                                        class="table-sort-desc"
                                                    @endif
                                                @endif  style="cursor: pointer;" wire:click="ordenar_tabla('inscripcion_fecha')">
                                                Fecha de Inscripcion
                                            </th>
                                            <th>Celular</th>
                                            <th>Correo Electronico</th>
                                            <th>Especialidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($inscripciones as $item)
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
                                                <td>
                                                    {{ date('d/m/Y', strtotime($item->inscripcion_fecha)) }}
                                                </td>
                                                <td>
                                                    (+51) {{ $item->celular }}
                                                </td>
                                                <td>
                                                    {{ $item->correo }}
                                                </td>
                                                <td>
                                                    {{ $item->especialidad_carrera }}
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
