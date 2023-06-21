<div>
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Inscripción
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
                        <li class="breadcrumb-item text-muted">Inscripción</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="#ModalInscripcion" wire:click="modo()" class="btn btn-primary btn-sm hover-elevate-up" data-bs-toggle="modal" data-bs-target="#ModalInscripcion">Nuevo</a>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid pt-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="me-1">
                                <a class="btn btn-sm btn-light-primary me-3 fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start">
                                    <span class="svg-icon svg-icon-6 svg-icon-muted me-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    Filtro
                                </a>
                                <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="menu_inscripcion" wire:ignore.self>
                                    <div class="px-7 py-5">
                                        <div class="fs-5 text-dark fw-bold">
                                            Opciones de filtrado
                                        </div> 
                                    </div>
                                    <div class="separator border-gray-200"></div>
                                    <div class="px-7 py-5">
                                        <div class="mb-5">
                                            <label class="form-label fw-semibold">Proceso de Admisión:</label>
                                            <div>
                                                <select class="form-select" wire:model="proceso_filtro" id="proceso_filtro"  data-control="select2" data-placeholder="Seleccione el Proceso">
                                                    <option></option>
                                                    @foreach ($procesos as $item)
                                                        <option value="{{ $item->id_admision }}">{{ $item->admision }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5">
                                            <label class="form-label fw-semibold">Modalidad del Programa:</label>
                                            <div>
                                                <select class="form-select" wire:model="modalidad_filtro" id="modalidad_filtro" data-control="select2" data-placeholder="Seleccione la Modalidad">
                                                    <option></option>
                                                    @foreach ($modalidades as $item)
                                                        <option value="{{ $item->id_modalidad }}">{{ $item->modalidad }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5">
                                            <label class="form-label fw-semibold">Programa:</label>
                                            <div>
                                                <select class="form-select" wire:model="programa_filtro" id="programa_filtro" data-control="select2" data-placeholder="Seleccione el Programa">
                                                    <option></option>
                                                    @if($modalidad_filtro)
                                                        @php
                                                            $programas = App\Models\Programa::where('id_modalidad', $modalidad_filtro)->get();
                                                        @endphp
                                                        @foreach ($programas as $item)
                                                            <option value="{{ $item->id_programa }}">{{ $item->programa }} EN {{ $item->subprograma }} @if($item->mencion != '') CON MENCION EN {{ $item->mencion }}@endif</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5">
                                            <label class="form-label fw-semibold">Tipo de Seguimiento:</label>
                                            <div>
                                                <select class="form-select" wire:model="seguimiento_filtro" id="seguimiento_filtro" data-control="select2" data-placeholder="Seleccione el Seguimiento">
                                                    <option></option>
                                                    @foreach ($seguimientos as $item)
                                                        <option value="{{ $item->id_tipo_seguimiento }}">{{ $item->tipo_seguimiento }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="button" wire:click="resetear_filtro" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Resetear</button>
                                            <button type="button" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true" wire:click="filtrar">Aplicar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="ms-2">
                                <input class="form-control form-control-sm text-muted" type="search" wire:model="search"
                                    placeholder="Buscar...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-rounded border gy-4 gs-4 mb-0 align-middle">
                                <thead class="bg-light-primary">
                                    <tr align="center" class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                        <th scope="col" class="col-md-1">ID</th>
                                        <th scope="col">Postulante</th>
                                        <th scope="col">Programa</th>
                                        <th scope="col" class="col-md-1">Modalidad</th>
                                        <th scope="col" class="col-md-1">Estado</th>
                                        <th scope="col" class="col-md-2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($inscripcionModel as $item)
                                    <tr>
                                        <td align="center" class="fw-bold fs-5">{{ $item->id_inscripcion }}</td>
                                        <td>{{ $item->numero_documento }} - {{ $item->apellido_paterno }} {{ $item->apellido_materno }} {{ $item->nombre }}</td>
                                        <td> {{ $item->programa }} EN {{ $item->subprograma }} @if($item->mencion != '') CON MENCION EN {{ $item->mencion }}@endif</td>
                                        <td align="center">{{ $item->modalidad }}</td>
                                        <td align="center">
                                            @if ($item->inscripcion_estado == 1)
                                                <span style="cursor: pointer;" wire:click="cargarAlertaEstado({{ $item->id_inscripcion }})" class="badge text-bg-success text-light hover-elevate-down fs-6 px-3 py-2">Activo</span></span>
                                            @else
                                                <span style="cursor: pointer;" wire:click="cargarAlertaEstado({{ $item->id_inscripcion }})" class="badge text-bg-danger text-light hover-elevate-down fs-6 px-3 py-2">Inactivo</span></span>
                                            @endif
                                        </td>
                                        <td align="center">
                                            <a class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary btn-sm" data-bs-toggle="dropdown">
                                                Acciones
                                                <span class="svg-icon fs-5 m-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                            <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4 w-175px" data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="#ModalInscripcion" wire:click="cargarInscripcion({{ $item->id_inscripcion }}, 3)" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#ModalInscripcion">
                                                        Detalle
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="#ModalInscripcion" wire:click="cargarInscripcion({{ $item->id_inscripcion }}, 2)" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#ModalInscripcion">
                                                        Editar
                                                    </a>
                                                </div>
                                                {{-- <div class="menu-item px-3">
                                                    <a href="{{ route('administrador.programa.gestion-plan-proceso', $item->id_inscripcion) }}" class="menu-link px-3 text-start">
                                                        Gestión de Plan y Proceso
                                                    </a>
                                                </div> --}}
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        @if ($search != '')
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">
                                                    No se encontraron resultados para la busqueda
                                                    "{{ $search }}"
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
                        {{-- paginacion de la tabla --}}
                        @if ($inscripcionModel->hasPages())
                            <div class="d-flex justify-content-between mt-5">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $inscripcionModel->firstItem() }} - {{ $inscripcionModel->lastItem() }} de
                                    {{ $inscripcionModel->total() }} registros
                                </div>
                                <div>
                                    {{ $inscripcionModel->links() }}
                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-between mt-5">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $inscripcionModel->firstItem() }} - {{ $inscripcionModel->lastItem() }} de
                                    {{ $inscripcionModel->total() }} registros
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div> 
    </div>

    {{-- Modal Programa --}}
    {{-- <div wire:ignore.self class="modal fade" tabindex="-1" id="ModalInscripcion">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        {{ $titulo }}
                    </h3>
                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="svg-icon svg-icon-2hx">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.3" x="2" y="2" width="20" height="20"
                                    rx="5" fill="currentColor" />
                                <rect x="7" y="15.3137" width="12" height="2" rx="1"
                                    transform="rotate(-45 7 15.3137)" fill="currentColor" />
                                <rect x="8.41422" y="7" width="12" height="2" rx="1"
                                    transform="rotate(45 8.41422 7)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body">
                    <form autocomplete="off" class="row g-5 {{ $modo == 3 ? 'mb-3' : '' }}">
                        <div class="col-md-6">
                            <label for="programa_codigo" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                Programa
                            </label>
                            @if($modo == 3)
                                <input type="text" wire:model="programa"
                                    class="form-control" id="programa" readonly />
                            @else
                                <select class="form-select @error('programa_tipo') is-invalid @enderror"
                                    wire:model="programa_tipo" id="programa_tipo" data-control="select2"
                                    data-placeholder="Seleccione el Plan" data-allow-clear="true"
                                    data-dropdown-parent="#ModalInscripcion">
                                    <option></option>
                                    <option value="1">MAESTRIA</option>
                                    <option value="2">DOCTORADO</option>
                                </select>
                                @error('programa_tipo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="programa_iniciales" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                Iniciales 
                                @switch($programa_tipo)
                                    @case(1)
                                        Maestría
                                        @break
                                    @case(2)
                                        Doctorado
                                        @break
                                    @default
                                        ***
                                        @break
                                @endswitch
                            </label>
                            <input type="text" wire:model="programa_iniciales"
                                class="form-control @error('programa_iniciales') is-invalid @enderror"
                                placeholder="Ingrese las iniciales del programa" id="programa_iniciales" @if($modo == 3) readonly @endif />
                        </div>
                        <div class="col-md-12">
                            <label for="subprograma" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                @switch($programa_tipo)
                                    @case(1)
                                        Maestría
                                        @break
                                    @case(2)
                                        Doctorado
                                        @break
                                    @default
                                        ***
                                        @break
                                @endswitch
                            </label>
                            <input type="text" wire:model="subprograma"
                                class="form-control @error('subprograma') is-invalid @enderror"
                                placeholder="Ingrese {{ $programa_tipo == 1 ? 'la maestría' : '' }}{{ $programa_tipo == 2 ? 'el doctorado' : '' }}" id="subprograma" @if($modo == 3) readonly @endif />
                        </div>
                        <div class="col-md-6">
                            <label for="mencion" class="form-label">
                                Mención
                            </label>
                            <input type="text" wire:model="mencion"
                                class="form-control @error('mencion') is-invalid @enderror"
                                placeholder="{{ $modo == 3 ? 'Sin mención' : 'Ingrese la mención' }}" id="mencion" @if($modo == 3) readonly @endif />
                        </div>
                        <div class="col-md-6">
                            <label for="facultad" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                Facultad
                            </label>
                            @if($modo == 3)
                                <input type="text" wire:model="facultadDetalle"
                                    class="form-control" id="facultadDetalle" readonly />
                            @else
                                <select class="form-select @error('facultad') is-invalid @enderror"
                                    wire:model="facultad" id="facultad" data-control="select2"
                                    data-placeholder="Seleccione la Facultad" data-allow-clear="true"
                                    data-dropdown-parent="#ModalInscripcion">
                                    <option></option>
                                    @foreach ($facultad_model as $item)
                                        @if($item->facultad_estado == 1)
                                            <option value="{{ $item->id_facultad }}">{{ $item->facultad }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('facultad')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="id_sunedu" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                ID SUNEDU
                            </label>
                            <input type="text" wire:model="id_sunedu"
                                class="form-control @error('id_sunedu') is-invalid @enderror"
                                placeholder="Ingrese el ID de SUNEDU" id="mencion" @if($modo == 3) readonly @endif />
                                @error('id_sunedu')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="codigo_sunedu" class="form-label">
                                Código SUNEDU
                            </label>
                            <input type="text" wire:model="codigo_sunedu"
                                class="form-control @error('codigo_sunedu') is-invalid @enderror"
                                placeholder="Ingrese el código de SUNEDU" id="mencion" @if($modo == 3) readonly @endif />
                                @error('codigo_sunedu')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="modalidad" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                Modalidad
                            </label>
                            @if($modo == 3)
                                <input type="text" wire:model="modalidadDetalle"
                                    class="form-control" id="modalidadDetalle" readonly />
                            @else
                                <select class="form-select @error('modalidad') is-invalid @enderror"
                                    wire:model="modalidad" id="modalidad" data-control="select2"
                                    data-placeholder="Seleccione la Modalidad" data-allow-clear="true"
                                    data-dropdown-parent="#ModalInscripcion">
                                    <option></option>
                                    @foreach ($modalidad_model as $item)
                                        @if($item->modalidad_estado == 1)
                                            <option value="{{ $item->id_modalidad }}">{{ $item->modalidad }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('modalidad')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="sede" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                Sede
                            </label>
                            @if($modo == 3)
                                <input type="text" wire:model="sedeDetalle"
                                    class="form-control" id="sedeDetalle" readonly />
                            @else
                                <select class="form-select @error('sede') is-invalid @enderror"
                                    wire:model="sede" id="sede" data-control="select2"
                                    data-placeholder="Seleccione la Sede" data-allow-clear="true"
                                    data-dropdown-parent="#ModalInscripcion">
                                    <option></option>
                                    @foreach ($sede_model as $item)
                                        @if($item->sede_estado == 1)
                                            <option value="{{ $item->id_sede }}">{{ $item->sede }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('sede')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>
                        
                    </form>
                </div>
                @if($modo != 3)
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="limpiar()">
                            Cerrar
                        </button>
                        <button type="button" wire:click="guardarPrograma" class="btn btn-primary" style="width: 150px" wire:loading.attr="disabled" wire:target="guardarPrograma">
                            <div wire:loading.remove wire:target="guardarPrograma, voucher">
                                Guardar
                            </div>
                            <div wire:loading wire:target="guardarPrograma">
                                Procesando <span class="spinner-border spinner-border-sm align-middle ms-2">
                            </div>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div> --}}

</div>
@push('scripts')
    <script>
        //Select2 de Filtro
        //Filtro de proceso_filtro select2
        $(document).ready(function () {
            $('#proceso_filtro').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando...";
                    }
                }
            });
            $('#proceso_filtro').on('change', function(){
                @this.set('proceso_filtro', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#proceso_filtro').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando...";
                        }
                    }
                });
                $('#proceso_filtro').on('change', function(){
                    @this.set('proceso_filtro', this.value);
                });
            });
        });

        //Filtro de modalidad_filtro select2
        $(document).ready(function () {
            $('#modalidad_filtro').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando...";
                    }
                }
            });
            $('#modalidad_filtro').on('change', function(){
                @this.set('modalidad_filtro', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#modalidad_filtro').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando...";
                        }
                    }
                });
                $('#modalidad_filtro').on('change', function(){
                    @this.set('modalidad_filtro', this.value);
                });
            });
        });

        //Filtro de programa_filtro select2
        $(document).ready(function () {
            $('#programa_filtro').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando...";
                    }
                }
            });
            $('#programa_filtro').on('change', function(){
                @this.set('programa_filtro', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#programa_filtro').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando...";
                        }
                    }
                });
                $('#programa_filtro').on('change', function(){
                    @this.set('programa_filtro', this.value);
                });
            });
        });

        //Filtro de seguimiento_filtro de select2
        $(document).ready(function () {
            $('#seguimiento_filtro').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando...";
                    }
                }
            });
            $('#seguimiento_filtro').on('change', function(){
                @this.set('seguimiento_filtro', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#seguimiento_filtro').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando...";
                        }
                    }
                });
                $('#seguimiento_filtro').on('change', function(){
                    @this.set('seguimiento_filtro', this.value);
                });
            });
        });
    </script>
@endpush