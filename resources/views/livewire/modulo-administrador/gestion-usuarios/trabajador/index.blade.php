<div>
    
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Trabajador
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
                        <li class="breadcrumb-item text-muted">Trabajador</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="#modalTra" data-bs-toggle="modal" 
                    data-bs-target="#modalTra" wire:click="modo()" class="btn btn-primary btn-sm hover-elevate-up">Nuevo</a>
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
                                <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="menu_expediente" wire:ignore.self>
                                    <div class="px-7 py-5">
                                        <div class="fs-5 text-dark fw-bold">
                                            Opciones de filtrado
                                        </div> 
                                    </div>
                                    <div class="separator border-gray-200"></div>
                                    <div class="px-7 py-5">
                                        <div class="mb-5 col-md-12">
                                            <label class="form-label fw-semibold">Mostrar:</label>
                                            <div>
                                                <select class="form-select" wire:model="mostrar" id="mostrar"  data-control="select2" data-placeholder="Seleccione">
                                                    <option value="10" selected>10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-5 col-md-12">
                                            <label class="form-label fw-semibold">Tipo:</label>
                                            <div>
                                                <select class="form-select" wire:model="tipo" id="tipo"  data-control="select2" data-placeholder="Seleccione">
                                                    <option value="all" selected>Mostrar todos</option>
                                                    @foreach ($tipo_trabajadores as $item)
                                                        <option value="{{ $item->id_tipo_trabajador }}">{{ $item->tipo_trabajador }}
                                                        </option>
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
                                        <th scope="col">Documento</th>
                                        <th scope="col">Nombres</th>
                                        <th scope="col">Grado</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col" class="col-md-1">Estado</th>
                                        <th scope="col" class="col-md-2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($trabajadores as $item)
                                        <tr>
                                            <td align="center" class="fs-5">
                                                <strong>{{ $item['id_trabajador'] }}</strong>
                                            </td>
                                            <td align="center">{{ $item['trabajador_numero_documento'] }}</td>
                                            <td>
                                                <div class="d-flex justify-conten-star align-items-center">
                                                    <div class="flex-shirnk-0">
                                                        @if ($item['trabajador_perfil_url'] && file_exists(public_path($item['trabajador_perfil_url'])))
                                                            <img class="rounded-circle avatar-xs"
                                                                src="{{ asset($item['trabajador_perfil_url']) }}"
                                                                alt="perfil Avatar">
                                                        @else
                                                            <img class="rounded-circle avatar-xs"
                                                                src="{{ asset('assets/media/avatars/blank.png') }}"
                                                                alt="perfil Avatar">
                                                        @endif
                                                    </div>
                                                    <div class="ms-2 d-flex flex-column">
                                                        <a href="#modalInfo"
                                                        wire:click="cargarInfoTrabajador({{ $item['id_trabajador'] }})"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalInfo" class="text-gray-900 text-hover-primary mb-1">
                                                            {{ $item['trabajador_nombre_completo'] }}
                                                        </a>
                                                        <span class="text-gray-600">{{ $item['trabajador_correo'] }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td align="center">{{ $item['grado_academico'] }}</td>
                                            <td align="center" class="">
                                                <ul style="list-style: none; padding: 0; margin: 0;">
                                                    @forelse ($item['trabajador_tipo_trabajador'] as $item2)
                                                        @if ($item2['id_tipo_trabajador'] == 1)
                                                            @if ($item['docente'])
                                                                Docente
                                                                @if ($item['docente']['id_tipo_docente'] == 1)
                                                                    <li class="text-gray-500">
                                                                        (INTERNO)
                                                                    </li>
                                                                @else 
                                                                    <li class="text-gray-500">
                                                                        (EXTERNO)
                                                                    </li>
                                                                @endif
                                                            @endif
                                                        @endif
                                                        @if ($item2['id_tipo_trabajador'] == 2)
                                                            Coordinador de Unidad
                                                            @if ($item['coordinador'])
                                                                <li class="text-gray-500">
                                                                    ({{ $item['coordinador']['facultad']['facultad'] }})
                                                                </li>
                                                            @endif
                                                        @endif
                                                        @if ($item2['id_tipo_trabajador'] == 3)
                                                            Administrativo
                                                            @if ($item['administrativo'])
                                                                <li class="text-gray-500">
                                                                    ({{ $item['administrativo']['area_administrativo']['area_administrativo'] }})
                                                                </li>
                                                            @endif
                                                        @endif
                                                    @empty
                                                        <li class="text-gray-500">
                                                            NO ASIGNADO
                                                        </li>
                                                    @endforelse
                                                </ul>
                                            </td>
                                            <td align="center">
                                                @if ($item['trabajador_estado'] == 1)
                                                    <span style="cursor: pointer;"
                                                        wire:click="cargarAlerta({{ $item['id_trabajador'] }})"
                                                        class="badge text-bg-success text-light fs-6 px-3 py-2">Activo</span>
                                                @else
                                                    <span style="cursor: pointer;"
                                                        wire:click="cargarAlerta({{ $item['id_trabajador'] }})"
                                                        class="badge text-bg-danger text-light fs-6 px-3 py-2">Inactivo</span>
                                                @endif
                                            </td>
                                            <td align="center">
                                                <a class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary btn-sm" data-bs-toggle="dropdown">
                                                    Actions
                                                    <span class="svg-icon fs-5 m-0">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                                <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <a href="#modalTra"
                                                        wire:click="cargarTrabajador({{ $item['id_trabajador'] }})" 
                                                        class="menu-link px-3" data-bs-toggle="modal" 
                                                        data-bs-target="#modalTra">
                                                            Editar
                                                        </a>
                                                    </div>
                                                    @if ($item['trabajador_estado'] == 1)
                                                        <div class="menu-item px-3">
                                                            <a href="#modalAsignar"
                                                            wire:click="cargarTrabajadorId({{ $item['id_trabajador'] }},1)" class="menu-link px-3" data-bs-toggle="modal" 
                                                            data-bs-target="#modalAsignar">
                                                                Asignar
                                                            </a>
                                                        </div>
                                                        @if ($item['trabajador_tipo_trabajador']->count() != 0)
                                                            <div class="menu-item px-3">
                                                                <a href="#modaldDesAsignar"
                                                                wire:click="cargarTrabajadorId({{ $item['id_trabajador'] }},2)" 
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modaldDesAsignar"
                                                                class="menu-link px-3">
                                                                    Desasignar
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endif
                                                    <div class="menu-item px-3">
                                                        <a  href="#modalInfo"
                                                        wire:click="cargarInfoTrabajador({{ $item['id_trabajador'] }})"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalInfo" class="menu-link px-3">
                                                            Datalle
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        @if ($search != '')
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">
                                                    No se encontraron resultados para la busqueda
                                                    "{{ $search }}"
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
                    </div>
                </div>

            </div>
        </div>

        {{-- Modal Trabajador --}}
        <div wire:ignore.self class="modal fade" tabindex="-1" id="modalTra">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">
                            {{ $titulo_modal }}
                        </h2>
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
                        <form autocomplete="off">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="tipo_documento" class="required form-label">
                                        Tipo de Documento
                                    </label>
                                    <select class="form-select @error('tipo_documento') is-invalid @enderror"
                                        wire:model="tipo_documento" id="tipo_documento" data-control="select2"
                                        data-placeholder="Seleccione su tipo de documento" data-allow-clear="true"
                                        data-dropdown-parent="#modalTra">
                                        <option></option>
                                        @foreach ($tipo_doc as $item)
                                            <option value="{{ $item->id_tipo_documento }}">{{ $item->tipo_documento }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tipo_documento')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Documento <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('documento') is-invalid  @enderror"
                                        wire:model="documento" placeholder="Ingrese su número de documento">
                                    @error('documento')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nombres') is-invalid  @enderror"
                                        wire:model="nombres" placeholder="Ingrese su nombre">
                                    @error('nombres')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('apellidos') is-invalid  @enderror"
                                        wire:model="apellidos" placeholder="Ingrese sus apellidos">
                                    @error('apellidos')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Correo <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('correo') is-invalid  @enderror"
                                        wire:model="correo" placeholder="Ingrese su correo electrónico">
                                    @error('correo')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Dirección <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('direccion') is-invalid  @enderror"
                                        wire:model="direccion" placeholder="Ingrese su direccion de domicilio">
                                    @error('direccion')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="grado" class="required form-label">
                                        Grado
                                    </label>
                                    <select class="form-select @error('grado') is-invalid @enderror"
                                        wire:model="grado" id="grado" data-control="select2"
                                        data-placeholder="Seleccione su grado académico" data-allow-clear="true"
                                        data-dropdown-parent="#modalTra">
                                        <option></option>
                                        @foreach ($grado_academico as $item)
                                            <option value="{{ $item->id_grado_academico }}">{{ $item->grado_academico }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('grado')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Foto de perfil</label>
                                    <input type="file" class="form-control @error('perfil') is-invalid  @enderror"
                                        wire:model="perfil" id="upload{{ $iteration }}">
                                    @error('perfil')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer col-12 d-flex justify-content-between">
                        <button type="button" wire:click="limpiar()" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" wire:click="guardarTrabajador()" class="btn btn-primary" wire:loading.attr="disabled">
                            <div wire:loading.remove wire:target="guardarTrabajador">
                                Guardar
                            </div>
                            <div wire:loading wire:target="guardarTrabajador">
                                <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                                Guardando...
                            </div>
                        </button>
                        
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Asiganar Tipo Trabajador --}}
        <div wire:ignore.self class="modal fade" tabindex="-1" id="modalAsignar">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">
                            {{ $titulo_modal }}
                        </h2>
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
                        <form autocomplete="off">
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label class="form-label mb-5">Tipo de trabajador <span class="text-danger">*</span></label>
                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input" type="checkbox" wire:model="docente_check" id="docente_check" style="cursor: pointer" 
                                        @if($trabajador_tipo_trabajador_docente_select) disabled @endif/>
                                        <label class="fw-semibold {{ $trabajador_tipo_trabajador_docente_select ? 'text-gray-400' : 'text-gray-800' }} ms-5" for="docente_check"
                                        @if(!$trabajador_tipo_trabajador_docente_select) style="cursor: pointer" @endif>
                                            Docente
                                        </label>
                                    </div>
                                    
                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input" type="checkbox" wire:model="coordinador_check" id="coordinador_check" style="cursor: pointer" 
                                        @if($administrativo_check == true || $trabajador_tipo_trabajador_coordinador_select) disabled @endif/>
                                        <label class="fw-semibold {{ $administrativo_check == true ||  $trabajador_tipo_trabajador_coordinador_select ? 'text-gray-400' : 'text-gray-800' }} ms-5" for="coordinador_check"
                                        @if(!($administrativo_check == true || $trabajador_tipo_trabajador_coordinador_select)) style="cursor: pointer" @endif>
                                        Coordinador de Unidad
                                        </label>
                                    </div>
                                    
                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input" type="checkbox" wire:model="administrativo_check" id="administrativo_check" style="cursor: pointer" 
                                        @if($coordinador_check == true || $trabajador_tipo_trabajador_administrativo_select) disabled @endif/>
                                        <label class="fw-semibold {{ $coordinador_check == true ||  $trabajador_tipo_trabajador_administrativo_select ? 'text-gray-400' : 'text-gray-800' }} ms-5" for="administrativo_check"
                                        @if(!($coordinador_check == true || $trabajador_tipo_trabajador_administrativo_select)) style="cursor: pointer" @endif>
                                        Administrativo
                                        </label>
                                    </div>

                                    <div class="border-gray-200 border mt-5 mb-2"></div>
                                </div>
                                @if ($tipo_docente == 1)
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label for="tipo_docentes" class="required form-label">
                                                    Tipo de Docente
                                                </label>
                                                <select class="form-select @error('tipo_docentes') is-invalid @enderror"
                                                    wire:model="tipo_docentes" id="tipo_docentes" data-control="select2"
                                                    data-placeholder="Seleccione el tipo de docente" data-allow-clear="true"
                                                    data-dropdown-parent="#modalAsignar">
                                                    <option></option>
                                                    @foreach ($tipo_docente_model as $item)
                                                        <option value="{{ $item->id_tipo_docente }}">
                                                            {{ $item->tipo_docente }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('tipo_docentes')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="categoria_docente" class="required form-label">
                                                    Categoria Docente
                                                </label>
                                                <select class="form-select @error('categoria_docente') is-invalid @enderror"
                                                    wire:model="categoria_docente" id="categoria_docente" data-control="select2"
                                                    data-placeholder="Seleccione la categoria" data-allow-clear="true"
                                                    data-dropdown-parent="#modalAsignar">
                                                    <option></option>
                                                    @foreach ($categoria_docente_model as $item)
                                                        @if($tipo_docentes)
                                                            @if($item->categoria_docente != 'DOCENTE CONTRATADO' && $tipo_docentes == 1)
                                                                <option value="{{ $item->id_categoria_docente }}">{{ $item->categoria_docente }}</option>
                                                            @else
                                                                @if($item->categoria_docente == 'DOCENTE CONTRATADO' && $tipo_docentes == 2)
                                                                    <option value="{{ $item->id_categoria_docente }}">{{ $item->categoria_docente }}</option>
                                                                @endif
                                                                @if($item->categoria_docente == 'SIN CATEGORIA')
                                                                    <option value="{{ $item->id_categoria_docente }}">{{ $item->categoria_docente }}</option>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('categoria_docente')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            @if ($tipo_docentes == 2)
                                                <div class="mb-3 col-md-12">
                                                    <label for="cv" class="required form-label">
                                                        Curriculum Vitae
                                                    </label>
                                                    <input type="file" class="form-control @error('cv') is-invalid  @enderror" wire:model="cv" accept=".pdf">
                                                    @error('cv')
                                                        <span class="error text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endif
                                            <div class="mb-3 col-md-12">
                                                <label for="usuario_docente" class="required form-label">
                                                    Usuario Docente
                                                </label>
                                                <select class="form-select @error('usuario_docente') is-invalid @enderror"
                                                    wire:model="usuario_docente" id="usuario_docente" data-control="select2"
                                                    data-placeholder="Seleccione el usuario de docente" data-allow-clear="true"
                                                    data-dropdown-parent="#modalAsignar">
                                                    <option></option>
                                                    @foreach ($usuario_model as $item)
                                                        <option value="{{ $item->usuario_correo }}" @if($item->usuario_estado == 0 || $item->usuario_estado == 2) disabled @endif>
                                                            {{ $item->usuario_nombre }} ({{ $item->usuario_correo }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('usuario_docente')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-bottom mb-3 mt-3"></div>
                                @endif
                                @if ($tipo_coordinador == 1)
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label for="categoria" class="required form-label">
                                                    Categoria
                                                </label>
                                                <select class="form-select @error('categoria') is-invalid @enderror"
                                                    wire:model="categoria" id="categoria" data-control="select2"
                                                    data-placeholder="Seleccione la categoria" data-allow-clear="true"
                                                    data-dropdown-parent="#modalAsignar">
                                                    <option></option>
                                                    @foreach ($categoria_docente_model as $item)
                                                        @if($item->categoria_docente != 'DOCENTE CONTRATADO')
                                                            <option value="{{ $item->id_categoria_docente }}">{{ $item->categoria_docente }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('categoria')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-md-6">
                                                <label for="facultad" class="required form-label">
                                                    Facultad
                                                </label>
                                                <select class="form-select @error('facultad') is-invalid @enderror"
                                                    wire:model="facultad" id="facultad" data-control="select2"
                                                    data-placeholder="Seleccione la facultad" data-allow-clear="true"
                                                    data-dropdown-parent="#modalAsignar">
                                                    <option></option>
                                                    @foreach ($facultad_model as $item)
                                                        <option value="{{ $item->id_facultad }}" @if($item->facultad_asignado == 1) disabled @endif>{{ $item->facultad }}</option>
                                                    @endforeach
                                                </select>
                                                @error('facultad')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($tipo_administrativo == 1)
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <label for="area" class="required form-label">
                                                    Área
                                                </label>
                                                <select class="form-select @error('area') is-invalid @enderror"
                                                    wire:model="area" id="area" data-control="select2"
                                                    data-placeholder="Seleccione el area" data-allow-clear="true"
                                                    data-dropdown-parent="#modalAsignar">
                                                    <option></option>
                                                    @foreach ($area_model as $item)
                                                        <option value="{{ $item->id_area_administrativo }}">
                                                            {{ $item->area_administrativo }}</option>
                                                    @endforeach
                                                </select>
                                                @error('area')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($tipo_administrativo == 1 || $tipo_coordinador == 1)
                                    <div class="col-md-12">
                                        <div class="mb-3 col-md-12">
                                            <label for="usuario" class="required form-label">
                                                Usuario @if($tipo_administrativo == 1) Administrativo @else Coordinador @endif
                                            </label>
                                            <select class="form-select @error('usuario') is-invalid @enderror"
                                                wire:model="usuario" id="usuario" data-control="select2"
                                                data-placeholder="Seleccione el usuario" data-allow-clear="true"
                                                data-dropdown-parent="#modalAsignar">
                                                <option></option>
                                                @foreach ($usuario_model as $item)
                                                    <option value="{{ $item->usuario_correo }}" @if($item->usuario_estado == 0 || $item->usuario_estado == 2) disabled @endif>
                                                        {{ $item->usuario_nombre }} ({{ $item->usuario_correo }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('usuario')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="border-bottom mb-3 mt-3"></div>
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer col-12 d-flex justify-content-between">
                        <button type="button" wire:click="limpiarAsignacion()" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" wire:click="asignarTrabajador()" class="btn btn-primary" wire:loading.attr="disabled">
                            <div wire:loading.remove wire:target="asignarTrabajador">
                                Guardar
                            </div>
                            <div wire:loading wire:target="asignarTrabajador">
                                <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                                Guardando...
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Desasiganar Tipo Trabajador --}}
        <div wire:ignore.self class="modal fade" tabindex="-1" id="modaldDesAsignar">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">
                            {{ $titulo_modal }}
                        </h2>
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
                        <form autocomplete="off">
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label class="form-label">Trabajador asignado a:</label>

                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input" type="checkbox" wire:model="docente_check" id="docente_check_desasignar" style="cursor: pointer" 
                                        @if(!$trabajador_tipo_trabajador_docente_select) disabled @endif/>
                                        <label class="fw-semibold {{ (!$trabajador_tipo_trabajador_docente_select) ? 'text-gray-400' : 'text-gray-800' }} ms-5" for="docente_check_desasignar"
                                        @if($trabajador_tipo_trabajador_docente_select) style="cursor: pointer" @endif>
                                            Docente
                                        </label>
                                    </div>
                                    
                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input" type="checkbox" wire:model="coordinador_check" id="coordinador_check_desasignar" style="cursor: pointer" 
                                        @if(!$trabajador_tipo_trabajador_coordinador_select) disabled @endif/>
                                        <label class="fw-semibold {{ !$trabajador_tipo_trabajador_coordinador_select ? 'text-gray-400' : 'text-gray-800' }} ms-5" for="coordinador_check_desasignar"
                                        @if($trabajador_tipo_trabajador_coordinador_select) style="cursor: pointer" @endif>
                                        Coordinador de Unidad
                                        </label>
                                    </div>
                                    
                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input" type="checkbox" wire:model="administrativo_check" id="administrativo_check_desasignar" style="cursor: pointer" 
                                        @if(!$trabajador_tipo_trabajador_administrativo_select) disabled @endif/>
                                        <label class="fw-semibold {{ (!$trabajador_tipo_trabajador_administrativo_select) ? 'text-gray-400' : 'text-gray-800' }} ms-5" for="administrativo_check_desasignar"
                                        @if($trabajador_tipo_trabajador_administrativo_select) style="cursor: pointer" @endif>
                                        Administrativo
                                        </label>
                                    </div>

                                    <div class="border-bottom mt-3"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer col-12 d-flex justify-content-between">
                        <button type="button" wire:click="limpiarAsignacion()" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" wire:click="desasignarTrabajadorAlerta()" class="btn btn-primary" wire:loading.attr="disabled">
                            <div wire:loading.remove wire:target="desasignarTrabajadorAlerta">
                                Guardar
                            </div>
                            <div wire:loading wire:target="desasignarTrabajadorAlerta">
                                <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                                Guardando...
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Informacion del Trabajador --}}
        <div wire:ignore.self class="modal fade" tabindex="-1" id="modalInfo">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">
                            {{ $titulo_modal }}
                        </h2>
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
                        <div class="row">
                            <div class="col-md-12">
                                @if ($trabajador_model)
                                    <div class="row mb-1">
                                        <h6 class="fw-bold">Datos Personales</h6>
                                    </div>
                                    <div class="col-md-12">
                                        <table style="width: 100%">
                                            <tbody>
                                                <tr>
                                                    <td width="180">Nombres</td>
                                                    <td width="20">:</td>
                                                    <td>{{ $trabajador_model->trabajador_nombre }} {{ $trabajador_model->trabajador_apellido }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Documento</td>
                                                    <td>:</td>
                                                    <td>{{ $trabajador_model->trabajador_numero_documento }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Correo</td>
                                                    <td>:</td>
                                                    <td>{{ $trabajador_model->trabajador_correo }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Direccion</td>
                                                    <td>:</td>
                                                    <td>{{ $trabajador_model->trabajador_direccion }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Grado</td>
                                                    <td>:</td>
                                                    <td>{{ ucwords(strtolower($trabajador_model->grado_academico->grado_academico )) }}</td>
                                                </tr>
                                                @if ($trabajador_model->trabajador_estado == 2)
                                                <tr>
                                                    <td>Estado</td>
                                                    <td>:</td>
                                                    <td class="text-danger">Inactivo</td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                @if ($trabajador_docente)
                                    @if ($docente_model)
                                        <div class="row mb-1 mt-4">
                                            <h6 class="fw-bold">Datos de Docente</h6>
                                        </div>
                                        <div class="col-md-12">
                                            <table style="width: 100%">
                                                <tbody>
                                                    <tr>
                                                        <td width="180">Tipo de docente</td>
                                                        <td width="20">:</td>
                                                        <td>{{ $docente_model->tipo_docente->tipo_docente }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Curriculum Vitae</td>
                                                        <td>:</td>
                                                        <td>
                                                            @if ($docente_model->docente_cv_url)
                                                                <a href="{{ asset('Docente/' . $docente_model->docente_cv_url) }}" target="_blank" class="text-primary">
                                                                    Ver
                                                                </a>
                                                            @else
                                                                <span class="text-dark">No tiene CV</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                    @if ($user_model_docente)
                                        <div class="row mb-1 mt-4">
                                            <h6 class="fw-bold">Datos de Usuario Docente</h6>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <table style="width: 100%">
                                                <tbody>
                                                    <tr>
                                                        <td width="180">Usuario</td>
                                                        <td width="20">:</td>
                                                        <td>{{ $user_model_docente->usuario_nombre }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Correo</td>
                                                        <td>:</td>
                                                        <td>{{ $user_model_docente->usuario_correo }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Contraseña</td>
                                                        <td>:</td>
                                                        <td>***</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                @endif
                                @if ($trabajador_coordinador)
                                    @if ($coordinador_model)
                                        <div class="row mb-1 mt-4">
                                            <h6 class="fw-bold">Datos de Coordinador</h6>
                                        </div>
                                        <div class="col-md-12">
                                            <table style="width: 100%">
                                                <tbody>
                                                    <tr>
                                                        <td width="180">Categoria de docente</td>
                                                        <td width="20">:</td>
                                                        <td>{{ ucwords(strtolower($coordinador_model->categoria_docente->categoria_docente)) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Facultad</td>
                                                        <td>:</td>
                                                        <td>{{ ucwords(strtolower($coordinador_model->facultad->facultad)) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                    @if ($user_model_coordinador)
                                        <div class="row mb-1 mt-4">
                                            <h6 class="fw-bold">Datos de Usuario Coordinador</h6>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <table style="width: 100%">
                                                <tbody>
                                                    <tr>
                                                        <td width="180">Usuario</td>
                                                        <td width="20">:</td>
                                                        <td>{{ $user_model_coordinador->usuario_nombre }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Correo</td>
                                                        <td>:</td>
                                                        <td>{{ $user_model_coordinador->usuario_correo }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Contraseña</td>
                                                        <td>:</td>
                                                        <td>***</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                @endif
                                @if ($trabajador_administrativo)
                                    @if ($administrativo_model)
                                        <div class="row mb-1 mt-4">
                                            <h6 class="fw-bold">Datos de Administrativo</h6>
                                        </div>
                                        <div class="col-md-12">
                                            <table style="width: 100%">
                                                <tbody>
                                                    @if ($administrativo_model->area_administrativo->area_administrativo)
                                                    <tr>
                                                        <td width="180">Area</td>
                                                        <td width="20">:</td>
                                                        <td>{{ $administrativo_model->area_administrativo->area_administrativo }}</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                    @if ($user_model_administrativo)
                                        <div class="row mb-1 mt-4">
                                            <h6 class="fw-bold">Datos de Usuario Administrativo</h6>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <table style="width: 100%">
                                                <tbody>
                                                    <tr>
                                                        <td width="180">Usuario</td>
                                                        <td width="20">:</td>
                                                        <td>{{ $user_model_administrativo->usuario_nombre }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Correo</td>
                                                        <td>:</td>
                                                        <td>{{ $user_model_administrativo->usuario_correo }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Contraseña</td>
                                                        <td>:</td>
                                                        <td>***</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                @endif
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
        //Select2 de Filtro
        // mostrar select2
        $(document).ready(function () {
            $('#mostrar').select2({
                placeholder: 'Seleccione',
                allowClear: false,
                width: '100%',
                selectOnClose: false,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando...";
                    }
                }
            });
            $('#mostrar').on('change', function(){
                @this.set('mostrar', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#mostrar').select2({
                    placeholder: 'Seleccione',
                    allowClear: false,
                    width: '100%',
                    selectOnClose: false,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando...";
                        }
                    }
                });
                $('#mostrar').on('change', function(){
                    @this.set('mostrar', this.value);
                });
            });
        });

        // tipo select2
        $(document).ready(function () {
            $('#tipo').select2({
                placeholder: 'Seleccione',
                allowClear: false,
                width: '100%',
                selectOnClose: false,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando...";
                    }
                }
            });
            $('#tipo').on('change', function(){
                @this.set('tipo', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#tipo').select2({
                    placeholder: 'Seleccione',
                    allowClear: false,
                    width: '100%',
                    selectOnClose: false,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando...";
                        }
                    }
                });
                $('#tipo').on('change', function(){
                    @this.set('tipo', this.value);
                });
            });
        });

        // Select2 de modal de nuevo y editar
        // tipo_documento select2
        $(document).ready(function () {
            $('#tipo_documento').select2({
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
            $('#tipo_documento').on('change', function(){
                @this.set('tipo_documento', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#tipo_documento').select2({
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
                $('#tipo_documento').on('change', function(){
                    @this.set('tipo_documento', this.value);
                });
            });
        });
        
        // grado select2
        $(document).ready(function () {
            $('#grado').select2({
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
            $('#grado').on('change', function(){
                @this.set('grado', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#grado').select2({
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
                $('#grado').on('change', function(){
                    @this.set('grado', this.value);
                });
            });
        });
        
        //Select2 de modal de asignar
        // tipo_docentes select2
        $(document).ready(function () {
            $('#tipo_docentes').select2({
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
            $('#tipo_docentes').on('change', function(){
                @this.set('tipo_docentes', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#tipo_docentes').select2({
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
                $('#tipo_docentes').on('change', function(){
                    @this.set('tipo_docentes', this.value);
                });
            });
        });

        // categoria_docente select2
        $(document).ready(function () {
            $('#categoria_docente').select2({
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
            $('#categoria_docente').on('change', function(){
                @this.set('categoria_docente', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#categoria_docente').select2({
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
                $('#categoria_docente').on('change', function(){
                    @this.set('categoria_docente', this.value);
                });
            });
        });
        
        // usuario_docente select2
        $(document).ready(function () {
            $('#usuario_docente').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: false,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando...";
                    }
                }
            });
            $('#usuario_docente').on('change', function(){
                @this.set('usuario_docente', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#usuario_docente').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: false,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando...";
                        }
                    }
                });
                $('#usuario_docente').on('change', function(){
                    @this.set('usuario_docente', this.value);
                });
            });
        });

        // categoria select2
        $(document).ready(function () {
            $('#categoria').select2({
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
            $('#categoria').on('change', function(){
                @this.set('categoria', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#categoria').select2({
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
                $('#categoria').on('change', function(){
                    @this.set('categoria', this.value);
                });
            });
        });

        // facultad select2
        $(document).ready(function () {
            $('#facultad').select2({
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
            $('#facultad').on('change', function(){
                @this.set('facultad', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#facultad').select2({
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
                $('#facultad').on('change', function(){
                    @this.set('facultad', this.value);
                });
            });
        });
        
        // area select2
        $(document).ready(function () {
            $('#area').select2({
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
            $('#area').on('change', function(){
                @this.set('area', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#area').select2({
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
                $('#area').on('change', function(){
                    @this.set('area', this.value);
                });
            });
        });

        // usuario select2
        $(document).ready(function () {
            $('#usuario').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: false,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando...";
                    }
                }
            });
            $('#usuario').on('change', function(){
                @this.set('usuario', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#usuario').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: false,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando...";
                        }
                    }
                });
                $('#usuario').on('change', function(){
                    @this.set('usuario', this.value);
                });
            });
        });

    </script>
@endpush