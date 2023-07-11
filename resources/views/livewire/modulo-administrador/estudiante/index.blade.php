<div>
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Estudiantes
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
                        <li class="breadcrumb-item text-muted">Estudiantes</li>
                    </ul>
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
                                            <label class="form-label fw-semibold">Proceso de Admisión:</label>
                                            <div>
                                                <select class="form-select" wire:model="filtro_proceso" id="filtro_proceso"  data-control="select2" data-placeholder="Seleccione el Proceso">
                                                    <option></option>
                                                    @foreach ($procesos as $item)
                                                        <option value="{{ $item->id_admision }}">{{ $item->admision }}</option>
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
                                        <th scope="col">Estudiante</th>
                                        <th scope="col" class="col-md-2">Correo</th>
                                        <th scope="col" class="col-md-1">Celular</th>
                                        <th scope="col" class="col-md-2">Dirección</th>
                                        <th scope="col" class="col-md-1">Estado</th>
                                        <th scope="col" class="col-md-2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($estudiantesModel as $item)
                                    <tr>
                                        <td align="center" class="fw-bold fs-5">{{ $item->id_persona }}</td>
                                        <td> {{ $item->numero_documento }} - {{ $item->nombre_completo }} 
                                        </td>
                                        {{-- <td align="center">
                                            {{ date('d/m/Y', strtotime($item->fecha_nacimiento)) }}
                                        </td> --}}
                                        <td>
                                            <div>{{ $item->correo }}</div>
                                            @if($item->correo_opcional)
                                                <div class="mt-2">{{ $item->correo_opcional }}</div>
                                            @endif
                                        </td>
                                        <td align="center">
                                            {{ $item->celular }}
                                        </td>
                                        @php
                                            $ubigeoDireccion = App\Models\Ubigeo::where('id_ubigeo', $item->ubigeo_direccion)->first();
                                        @endphp
                                        <td align="center">
                                            {{ $ubigeoDireccion->departamento }} - {{ $ubigeoDireccion->provincia }} - {{ $ubigeoDireccion->distrito }}
                                        </td>
                                        @php
                                            //Consultas para revisar los estados de los estudiantes (inscrito, admitido, matriculado)
                                            $validarInscripcion = App\Models\Inscripcion::where('id_persona', $item->id_persona)->first();
                                            if($validarInscripcion){
                                                $validarAdmitido = App\Models\Evaluacion::where('id_inscripcion', $validarInscripcion->id_inscripcion)->first();
                                            }
                                        @endphp
                                        <td align="center">
                                            @if($validarInscripcion)
                                                @if($validarAdmitido)
                                                    @if($validarAdmitido->evaluacion_estado_admitido == 0 && $validarAdmitido->evaluacion_estado == 3)
                                                        <span class="badge badge-light-danger fs-6 px-3 py-2">NO ADMITIDO</span>                                                        
                                                    @endif
                                                    @if($validarAdmitido->evaluacion_estado_admitido == 1 && $validarAdmitido->evaluacion_estado == 2)
                                                        <span class="badge badge-light-primary fs-6 px-3 py-2">ADMITIDO</span>
                                                    @endif
                                                    @if($validarAdmitido->evaluacion_estado_admitido == 0 && $validarAdmitido->evaluacion_estado == 1)
                                                        <span class="badge badge-light-success fs-6 px-3 py-2">INSCRITO</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-light-success fs-6 px-3 py-2">INSCRITO</span>
                                                @endif
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
                                                    <a href="#modalPersona" wire:click="cargarPersona({{ $item->id_persona }}, 3)" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modalPersona">
                                                        Detalle
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="#modalPersona" wire:click="cargarPersona({{ $item->id_persona }}, 2)" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modalPersona">
                                                        Editar
                                                    </a>
                                                </div>
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
                        @if ($estudiantesModel->hasPages())
                            <div class="d-flex justify-content-between mt-5">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $estudiantesModel->firstItem() }} - {{ $estudiantesModel->lastItem() }} de
                                    {{ $estudiantesModel->total() }} registros
                                </div>
                                <div>
                                    {{ $estudiantesModel->links() }}
                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-between mt-5">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $estudiantesModel->firstItem() }} - {{ $estudiantesModel->lastItem() }} de
                                    {{ $estudiantesModel->total() }} registros
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div> 
    </div>

    {{-- Modal Programa --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modalPersona">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        {{ $titulo }}
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
                    <form autocomplete="off" class="row g-5 {{ $modo == 3 ? 'mb-3' : '' }}">
                        <div class="col-md-12 mt-5">
                            <div class="row g-5">
                                <div class="col-md-12">
                                    <span class="col-12 fw-bold text-gray-800 fs-3">
                                        INFORMACIÓN PERSONAL
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <label for="numero_documento" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                        Número de Documento
                                    </label>
                                    <input type="text" wire:model="numero_documento"
                                        class="form-control @error('numero_documento') is-invalid @enderror"
                                        placeholder="Ingrese su número de documento" id="numero_documento" @if($modo == 3) readonly @endif />
                                        @error('numero_documento')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>                        
                                <div class="col-md-4">
                                    <label for="nombre" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                        Nombres
                                    </label>
                                    <input type="text" wire:model="nombre"
                                        class="form-control @error('nombre') is-invalid @enderror"
                                        placeholder="Ingrese su nombre" id="nombre" @if($modo == 3) readonly @endif />
                                        @error('nombre')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>                        
                                <div class="col-md-4">
                                    <label for="apellido_paterno" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                        Apellido Paterno
                                    </label>
                                    <input type="text" wire:model="apellido_paterno"
                                        class="form-control @error('apellido_paterno') is-invalid @enderror"
                                        placeholder="Ingrese su número de documento" id="apellido_paterno" @if($modo == 3) readonly @endif />
                                        @error('apellido_paterno')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>                        
                                <div class="col-md-4">
                                    <label for="apellido_materno" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                        Apellido Materno
                                    </label>
                                    <input type="text" wire:model="apellido_materno"
                                        class="form-control @error('apellido_materno') is-invalid @enderror"
                                        placeholder="Ingrese su apellido paterno" id="apellido_materno" @if($modo == 3) readonly @endif />
                                        @error('apellido_materno')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>                       
                                <div class="col-md-4">
                                    <label for="fecha_nacimiento" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                        Fecha de Nacimiento
                                    </label>
                                        <input type="date" wire:model="fecha_nacimiento" class="form-control @error('fecha_nacimiento') is-invalid @enderror" id="fecha_nacimiento" {{ $modo == 3 ? 'readonly' : '' }}>
                                        @error('fecha_nacimiento')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="genero" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                        Sexo
                                    </label>
                                    @if($modo == 3)
                                        <input type="text" wire:model="genero_detalle"
                                            class="form-control" id="genero_detalle" readonly />
                                    @else
                                        <select class="form-select @error('genero') is-invalid @enderror"
                                            wire:model="genero" id="genero" data-control="select2"
                                            data-placeholder="Seleccione su género" data-allow-clear="true"
                                            data-dropdown-parent="#modalPersona">
                                            <option></option>
                                            @foreach ($genero_model as $item)
                                                @if($item->genero_estado == 1)
                                                    <option value="{{ $item->id_genero }}">{{ $item->genero }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('genero')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="estado_civil" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                        Estado Civil
                                    </label>
                                    @if($modo == 3)
                                        <input type="text" wire:model="estado_civil_detalle"
                                            class="form-control" id="estado_civil_detalle" readonly />
                                    @else
                                        <select class="form-select @error('estado_civil') is-invalid @enderror"
                                            wire:model="estado_civil" id="estado_civil" data-control="select2"
                                            data-placeholder="Seleccione su estado civil" data-allow-clear="true"
                                            data-dropdown-parent="#modalPersona">
                                            <option></option>
                                            @foreach ($estado_civil_model as $item)
                                                @if($item->estado_civil_estado == 1)
                                                    <option value="{{ $item->id_estado_civil }}">{{ $item->estado_civil }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('estado_civil')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="discapacidad" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                        Discapacidad
                                    </label>
                                    @if($modo == 3)
                                        <input type="text" wire:model="discapacidad_detalle"
                                            class="form-control" id="discapacidad_detalle" readonly />
                                    @else
                                        <select class="form-select @error('discapacidad') is-invalid @enderror"
                                            wire:model="discapacidad" id="discapacidad" data-control="select2"
                                            data-placeholder="Seleccione su estado civil" data-allow-clear="true"
                                            data-dropdown-parent="#modalPersona">
                                            <option></option>
                                            @foreach ($discapacidad_model as $item)
                                                @if($item->discapacidad_estado == 1)
                                                    <option value="{{ $item->id_discapacidad }}">{{ $item->discapacidad }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('discapacidad')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <label for="celular" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                        Celular
                                    </label>
                                    @if($agregar_celular == false && $modo == 2)
                                        <div class="row">
                                            <div class="col-md-10">
                                                <input type="number" wire:model="celular" class="form-control @error('celular') is-invalid @enderror" id="celular" placeholder="Ingrese su número de celular">
                                            </div>
                                            <div class="col-md-1 d-flex justify-content-center align-items-center">
                                                <i class="ki-duotone ki-message-add text-success fs-2x hover-scale" data-bs-toggle="tooltip" data-bs-placement="top" title="Agregar celular opcional" wire:click="agregarCelular" style="cursor: pointer">
                                                    <i class="path1"></i>
                                                    <i class="path2"></i>
                                                    <i class="path3"></i>
                                                </i>
                                            </div>
                                        </div>
                                    @else
                                        <input type="number" wire:model="celular" class="form-control @error('celular') is-invalid @enderror" id="celular" placeholder="Ingrese su número de celular" {{ $modo == 3 ? 'readonly' : '' }}>
                                    @endif
                                    @error('celular')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if($celular_opcional || $agregar_celular == true)
                                    <div class="col-md-4">
                                        <label for="celular_opcional" class="form-label">
                                            Celular Opcional
                                        </label>
                                        @if($celular_opcional == null)
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input type="number" wire:model="celular_opcional" class="form-control @error('celular_opcional') is-invalid @enderror" id="celular_opcional" placeholder="Ingrese número opcional" {{ $modo == 3 ? 'readonly' : '' }}>
                                                </div>
                                                <div class="col-md-1 d-flex justify-content-center align-items-center">
                                                    <i class="ki-duotone ki-message-minus text-danger fs-2x hover-scale" data-bs-toggle="tooltip" data-bs-placement="top" title="Quitar celular opcional" wire:click="quitarCelular" style="cursor: pointer">
                                                        <i class="path1"></i>
                                                        <i class="path2"></i>
                                                        <i class="path3"></i>
                                                    </i>
                                                </div>
                                            </div>
                                            @error('celular_opcional')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        @else
                                            <input type="number" wire:model="celular_opcional" class="form-control @error('celular_opcional') is-invalid @enderror" id="celular_opcional" placeholder="Ingrese número opcional" {{ $modo == 3 ? 'readonly' : '' }}>
                                        @endif
                                    </div>
                                @endif
                                <div class="col-md-4">
                                    <label for="correo" class="{{ $modo != 3 ? 'required' : ''}} form-label">
                                        Correo
                                    </label>
                                    @if($agregar_correo == false && $modo == 2)
                                        <div class="row">
                                            <div class="col-md-10">
                                                <input type="email" wire:model="correo" class="form-control @error('correo') is-invalid @enderror" id="correo" placeholder="Ingrese su correo">
                                            </div>
                                            <div class="col-md-1 d-flex justify-content-center align-items-center">
                                                <i class="ki-duotone ki-message-add text-success fs-2x hover-scale" data-bs-toggle="tooltip" data-bs-placement="top" title="Agregar celular opcional" wire:click="agregarCorreo" style="cursor: pointer">
                                                    <i class="path1"></i>
                                                    <i class="path2"></i>
                                                    <i class="path3"></i>
                                                </i>
                                            </div>
                                        </div>
                                    @else
                                        <input type="email" wire:model="correo" class="form-control @error('correo') is-invalid @enderror" id="correo" placeholder="Ingrese su correo" {{ $modo == 3 ? 'readonly' : '' }}>
                                    @endif
                                    @error('correo')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if($correo_opcional || $agregar_correo == true)
                                    <div class="col-md-4">
                                        <label for="correo_opcional" class="form-label">
                                            Correo Opcional
                                        </label>
                                        @if($correo_opcional == null)
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input type="email" wire:model="correo_opcional" class="form-control @error('correo_opcional') is-invalid @enderror" id="correo_opcional" placeholder="Ingrese correo opcional" {{ $modo == 3 ? 'readonly' : '' }}>
                                                </div>
                                                <div class="col-md-1 d-flex justify-content-center align-items-center">
                                                    <i class="ki-duotone ki-message-minus text-danger fs-2x hover-scale" data-bs-toggle="tooltip" data-bs-placement="top" title="Quitar correo opcional" wire:click="quitarCorreo" style="cursor: pointer">
                                                        <i class="path1"></i>
                                                        <i class="path2"></i>
                                                        <i class="path3"></i>
                                                    </i>
                                                </div>
                                            </div>
                                            @error('correo_opcional')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        @else
                                            <input type="email" wire:model="correo_opcional" class="form-control @error('correo_opcional') is-invalid @enderror" id="correo_opcional" placeholder="Ingrese correo opcional" {{ $modo == 3 ? 'readonly' : '' }}>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        
                    </form>
                </div>
                @if($modo != 3)
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="limpiar()">
                            Cerrar
                        </button>
                        <button type="button" wire:click="guardarPrograma" class="btn btn-primary" style="width: 150px" wire:loading.attr="disabled" wire:target="guardarPrograma">
                            <div wire:loading.remove wire:target="guardarPrograma">
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
    </div>

</div>
@push('scripts')
    <script>
        //Select2 de Filtro
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
                        return "Buscando...";
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
                            return "Buscando...";
                        }
                    }
                });
                $('#filtro_proceso').on('change', function(){
                    @this.set('filtro_proceso', this.value);
                });
            });
        });

        //Select2 de Modal
        // genero select2
        $(document).ready(function () {
            $('#genero').select2({
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
            $('#genero').on('change', function(){
                @this.set('genero', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#genero').select2({
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
                $('#genero').on('change', function(){
                    @this.set('genero', this.value);
                });
            });
        });
        // estado_civil select2
        $(document).ready(function () {
            $('#estado_civil').select2({
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
            $('#estado_civil').on('change', function(){
                @this.set('estado_civil', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#estado_civil').select2({
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
                $('#estado_civil').on('change', function(){
                    @this.set('estado_civil', this.value);
                });
            });
        });
        // discapacidad select2
        $(document).ready(function () {
            $('#discapacidad').select2({
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
            $('#discapacidad').on('change', function(){
                @this.set('discapacidad', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#discapacidad').select2({
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
                $('#discapacidad').on('change', function(){
                    @this.set('discapacidad', this.value);
                });
            });
        });

    </script>
@endpush