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
                    <a href="#" class="btn btn-primary btn-sm hover-elevate-up">Nuevo</a>
                </div>
            </div>
        </div>
        
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid pt-5">

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="d-flex justify-content-between align-items-center gap-4">
                                <div class="text-muted d-flex align-items-center">
                                    <label class="col-form-label me-2">Mostrar</label>
                                    <select class="form-select form-select-sm text-muted" wire:model="mostrar"
                                        aria-label="Default select example">
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                                <div class="text-muted d-flex align-items-center">
                                    <label class="col-form-label me-2">Tipo</label>
                                    <select class="form-select form-select-sm text-muted" wire:model="tipo"
                                        aria-label="Default select example">
                                        <option value="all" selected>Mostrar todos</option>
                                        @foreach ($tipo_trabajadores as $item)
                                            <option value="{{ $item->id_tipo_trabajador }}">{{ $item->tipo_trabajador }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="w-25">
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
                                        <th scope="col">Correo</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col" class="col-md-1">Estado</th>
                                        <th scope="col" class="col-md-2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $num = 1;
                                    @endphp
                                    @foreach ($trabajadores as $item)
                                        <tr>
                                            <td align="center" class="fs-5">
                                                @if ($num < 10)
                                                    <strong>0{{ $num }}</strong>
                                                @else
                                                    <strong>{{ $num }}</strong>
                                                @endif
                                            </td>
                                            <td align="center">{{ $item->trabajador_numero_documento }}</td>
                                            <td>
                                                <div class="d-flex justify-conten-star align-items-center">
                                                    <div class="flex-shirnk-0">
                                                        @if ($item->trabajador_perfil_url)
                                                            <img class="rounded-circle avatar-xs"
                                                                src="{{ asset($item->trabajador_perfil_url) }}"
                                                                alt="perfil Avatar">
                                                        @else
                                                            <img class="rounded-circle avatar-xs"
                                                                src="{{ asset('assets/media/avatars/blank.png') }}"
                                                                alt="perfil Avatar">
                                                        @endif
                                                    </div>
                                                    <div class="ms-2">
                                                        {{ $item->trabajador_nombre_completo }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td align="center">{{ $item->grado_academico->grado_academico }}</td>
                                            <td>{{ $item->trabajador_correo }}</td>
                                            @php
                                                $tra_tipo_tra = App\Models\TrabajadorTipoTrabajador::where('id_trabajador', $item->id_trabajador)->where('trabajador_tipo_trabajador_estado',1)->get();
                                            @endphp
                                            <td align="center" class="">
                                                @php
                                                    $coordinador_unidad = App\Models\Coordinador::where('id_trabajador', $item->id_trabajador)->first();
                                                    $administrativo = App\Models\Administrativo::where('id_trabajador', $item->id_trabajador)->first();
                                                @endphp
                                                @if ($tra_tipo_tra)
                                                    @if ($tra_tipo_tra->count() == 1)
                                                        <ul style="list-style: none; padding: 0; margin: 0;">
                                                        @foreach ($tra_tipo_tra as $item2)
                                                            @if ($item2->id_tipo_trabajador == 1)
                                                                Docente
                                                            @endif
                                                            @if ($item2->id_tipo_trabajador == 2)
                                                                Coordinador de Unidad
                                                                @if ($coordinador_unidad)
                                                                    <li class="text-gray-500">
                                                                        ({{ $coordinador_unidad->facultad->facultad }})
                                                                    </li>
                                                                @endif
                                                            @endif
                                                            @if ($item2->id_tipo_trabajador == 3)
                                                                Administrativo
                                                                @if ($administrativo)
                                                                    <li class="text-gray-500">
                                                                        ({{ $administrativo->area_administrativo->area_administrativo }})
                                                                    </li>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                        </ul>
                                                    @else
                                                        <ul style="list-style: none; padding: 0; margin: 0;">
                                                        @foreach ($tra_tipo_tra as $item2)
                                                            @if ($item2->id_tipo_trabajador == 1)
                                                                <li>Docente</li> 
                                                            @endif
                                                            @if ($item2->id_tipo_trabajador == 2)
                                                                <li>Coordinador de Unidad</li> 
                                                                @if ($coordinador_unidad)
                                                                    <li class="text-gray-500">
                                                                        ({{ $coordinador_unidad->facultad->facultad }})
                                                                    </li>
                                                                @endif
                                                            @endif
                                                            @if ($item2->id_tipo_trabajador == 3)
                                                                <li>Administrativo</li> 
                                                                @if ($administrativo)
                                                                    <li class="text-gray-500">
                                                                        ({{ $administrativo->area_administrativo->area_administrativo }})
                                                                    </li>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                        </ul>
                                                    @endif
                                                @endif
                                                @if($tra_tipo_tra->count() == 0)
                                                    No asignado
                                                @endif
                                            </td>
                                            <td align="center">
                                                @if ($item->trabajador_estado == 1)
                                                    <span style="cursor: pointer;"
                                                        wire:click="cargarAlerta({{ $item->id_trabajador }})"
                                                        class="badge text-bg-primary text-light px-3 py-2">Activo</span>
                                                @else
                                                    <span style="cursor: pointer;"
                                                        wire:click="cargarAlerta({{ $item->id_trabajador }})"
                                                        class="badge text-bg-danger text-light px-3 py-2">Inactivo</span>
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
                                                        wire:click="cargarTrabajador({{ $item->id_trabajador }})" 
                                                        class="menu-link px-3" data-bs-toggle="modal" 
                                                        data-bs-target="#modalTra">
                                                            Editar
                                                        </a>
                                                    </div>
                                                    @if ($item->trabajador_estado == 1)
                                                        <div class="menu-item px-3">
                                                            <a href="#modalAsignar"
                                                            wire:click="cargarTrabajadorId({{ $item->id_trabajador }},1)" class="menu-link px-3" data-bs-toggle="modal" 
                                                            data-bs-target="#modalAsignar">
                                                                Asignar
                                                            </a>
                                                        </div>
                                                        @if ($tra_tipo_tra->count() != 0)
                                                            <div class="menu-item px-3">
                                                                <a href="#modaldDesAsignar"
                                                                wire:click="cargarTrabajadorId({{ $item->id_trabajador }},2)" 
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
                                                        wire:click="cargarInfoTrabajador({{ $item->id_trabajador }})"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalInfo" class="menu-link px-3">
                                                            Datalle
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @php
                                            $num++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($trabajadores->count())
                                <div class="mt-2 d-flex justify-content-end text-muted">
                                    {{ $trabajadores->links() }}
                                </div>
                            @else
                                <div class="text-center p-3 text-muted">
                                    <span>No hay resultados para la busqueda "<strong>{{ $search }}</strong>" en la
                                        pagina <strong>{{ $page }}</strong> al mostrar
                                        <strong>{{ $mostrar }}</strong> por pagina</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Modal Trabajador --}}
        <div wire:ignore.self class="modal fade" id="modalTra" tabindex="-1" aria-labelledby="modalTra"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase" id="exampleModalLabel">{{ $titulo_modal }}</h5>
                        <button type="button" wire:click="limpiar()" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form novalidate>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                                    <select class="form-select @error('tipo_documento') is-invalid  @enderror"
                                        wire:model="tipo_documento">
                                        <option value="" selected>Seleccione</option>
                                        @foreach ($tipo_doc as $item)
                                            <option value="{{ $item->id_tipo_documento }}">{{ $item->tipo_documento }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tipo_documento')
                                        <span class="error text-danger">{{ $message }}</span>
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
                                    <label class="form-label">Grado <span class="text-danger">*</span></label>
                                    <select class="form-select @error('grado') is-invalid  @enderror" wire:model="grado">
                                        @foreach ($grado_academico as $item)
                                            <option value="{{ $item->id_grado_academico }}">{{ $item->grado_academico }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('grado')
                                        <span class="error text-danger">{{ $message }}</span>
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
                        <button type="button" wire:click="limpiar()" class="btn btn-secondary hover-elevate-up" data-bs-dismiss="modal">Cancelar</button>
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
        <div wire:ignore.self class="modal fade" id="modalAsignar" tabindex="-1" aria-labelledby="modalAsignar"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase" id="exampleModalLabel">{{ $titulo_modal }}</h5>
                        <button type="button" wire:click="limpiarAsignacion()" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form novalidate autocomplete="off">
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label class="form-label mb-5">Tipo de trabajador <span class="text-danger">*</span></label>

                                    @php
                                        $trabajador_tipo_trabajador_docente_select = App\Models\TrabajadorTipoTrabajador::where('id_trabajador',$trabajador_id)->where('id_tipo_trabajador',1)->where('trabajador_tipo_trabajador_estado',1)->first();
                                        $trabajador_tipo_trabajador_coordinador_select = App\Models\TrabajadorTipoTrabajador::where('id_trabajador',$trabajador_id)->where('id_tipo_trabajador',2)->where('trabajador_tipo_trabajador_estado',1)->first();
                                        $trabajador_tipo_trabajador_administrativo_select = App\Models\TrabajadorTipoTrabajador::where('id_trabajador',$trabajador_id)->where('id_tipo_trabajador',3)->where('trabajador_tipo_trabajador_estado',1)->first();
                                    @endphp

                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input me-3" type="checkbox" wire:model="docente" @if($trabajador_tipo_trabajador_docente_select) disabled @endif>
                                        <label class="form-check-label">
                                            <div class="fw-semibold text-gray-800">Docente</div>
                                        </label>
                                    </div>

                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input me-3" type="checkbox" wire:model="coordinador" @if ($administrativo_check || $trabajador_tipo_trabajador_coordinador_select) disabled @endif>
                                        <label class="form-check-label">
                                            <div class="fw-semibold text-gray-800">Coordinador de Unidad</div>
                                        </label>
                                    </div>

                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input me-3" type="checkbox" wire:model="administrativo_check" @if ($coordinador || $trabajador_tipo_trabajador_administrativo_select) disabled @endif>
                                        <label class="form-check-label">
                                            <div class="fw-semibold text-gray-800">Administrativo</div>
                                        </label>
                                    </div>
                                    
                                    <div class="border-gray-200 border mt-5 mb-2"></div>
                                </div>

                                @php
                                    $usua_activo = App\Models\Usuario::where('usuario_estado',1)->first();
                                @endphp
                                

                                @if ($tipo_docente == 1)
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div @if ($tipo_docentes == 2) class="mb-3 col-md-6" @else class="mb-3 col-md-12" @endif>
                                                <label class="form-label">Tipo de Docente <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select @error('tipo_docentes') is-invalid  @enderror"
                                                    wire:model="tipo_docentes">
                                                    <option value="" selected>Seleccione</option>
                                                    @foreach ($tipo_docente_model as $item)
                                                        <option value="{{ $item->id_tipo_docente }}">
                                                            {{ $item->tipo_docente }}</option>
                                                    @endforeach
                                                </select>
                                                @error('tipo_docentes')
                                                    <span class="error text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            @if ($tipo_docentes == 2)
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Curriculum Vitae <span
                                                            class="text-danger">*</span></label>
                                                    <input type="file"
                                                        class="form-control @error('cv') is-invalid  @enderror"
                                                        wire:model="cv" accept=".pdf">
                                                    @error('cv')
                                                        <span class="error text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                        <div class="border-bottom mb-3"></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3 col-md-12">
                                                    <label class="dorm label">Usuario <span class="text-danger">*</span></label>
                                                    <div class="col-md-12">
                                                        <input class="form-control @error('usuario_docente') is-invalid  @enderror" wire:model="usuario_docente" list="datalistOptions" type="text" placeholder="Ingrese el usuario a buscar..." @if(!$usua_activo) disabled @endif>
                                                        <datalist id="datalistOptions">
                                                            <select class="form-control @error('usuario_docente') is-invalid  @enderror" wire:model="usuario_docente">
                                                            @foreach ($usuario_model as $item)
                                                                <option value="{{ $item->usuario_correo }}" @if($item->usuario_estado == 0 || $item->usuario_estado == 2) disabled @endif>{{ $item->usuario_nombre }}</option>
                                                            @endforeach
                                                            </select>
                                                        </datalist>
                                                        @error('usuario_docente')
                                                            <span class="error text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="border-bottom mb-3"></div>
                                    </div>
                                @endif
                                @if ($tipo_coordinador == 1)
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Categoria</label>
                                                <select class="form-select @error('categoria') is-invalid  @enderror"
                                                    wire:model="categoria">
                                                    <option value="" selected>Seleccione</option>
                                                    @foreach ($categoria_docente_model as $item)
                                                        <option value="{{ $item->id_categoria_docente }}">{{ $item->categoria_docente }}</option>
                                                @endforeach
                                                </select>
                                                @error('categoria')
                                                    <span class="error text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3 col-md-6">
                                                <label class="form-label">Facultad <span class="text-danger">*</span></label>
                                                <select class="form-select @error('facultad') is-invalid  @enderror"
                                                    wire:model="facultad">
                                                    <option value="" selected>Seleccione</option>
                                                    @foreach ($facultad_model as $item)
                                                        <option value="{{ $item->id_facultad }}" @if($item->facultad_estado == 2) disabled @endif>{{ $item->facultad }}</option>
                                                    @endforeach
                                                </select>
                                                @error('facultad')
                                                    <span class="error text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($tipo_administrativo == 1)
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <label class="form-label">Area <span class="text-danger">*</span></label>
                                                <select class="form-select @error('area') is-invalid  @enderror"
                                                    wire:model="area">
                                                    <option value="" selected>Seleccione</option>
                                                    @foreach ($area_model as $item)
                                                        <option value="{{ $item->id_area_administrativo }}">
                                                            {{ $item->area_administrativo }}</option>
                                                    @endforeach
                                                </select>
                                                @error('area')
                                                    <span class="error text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($tipo_administrativo == 1 || $tipo_coordinador == 1)
                                    <div class="col-md-12">
                                        <div class="mb-3 col-md-12">
                                            <label class="dorm label">Usuario <span class="text-danger">*</span></label>
                                            <div class="col-md-12">
                                                <input class="form-control @error('usuario') is-invalid  @enderror" wire:model="usuario" list="datalistOptions" type="text" placeholder="Ingrese el usuario a buscar..." @if(!$usua_activo) disabled @endif>
                                                <datalist id="datalistOptions">
                                                    <select class="form-control @error('usuario') is-invalid  @enderror" wire:model="usuario">
                                                    @foreach ($usuario_model as $item)
                                                    <option value="{{ $item->usuario_correo }}" @if($item->usuario_estado == 0 || $item->usuario_estado == 2) disabled @endif>{{ $item->usuario_nombre }}</option>
                                                    @endforeach
                                                    </select>
                                                </datalist>
                                                @error('usuario')
                                                    <span class="error text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="border-bottom mb-3"></div>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer col-12 d-flex justify-content-between">
                        <button type="button" wire:click="limpiarAsignacion()" class="btn btn-secondary hover-elevate-up" data-bs-dismiss="modal">Cancelar</button>
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
        <div wire:ignore.self class="modal fade" id="modaldDesAsignar" tabindex="-1" aria-labelledby="modaldDesAsignar"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase" id="exampleModalLabel">{{ $titulo_modal }}</h5>
                        <button type="button" wire:click="limpiarAsignacion()" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form novalidate>
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label class="form-label">Trabajador asignado a:</label>

                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input me-3" type="checkbox" wire:model="docente" 
                                            @if ($docente == false) disabled @endif>
                                        <label class="form-check-label">
                                            <div class="fw-semibold text-gray-800">Docente</div>
                                        </label>
                                    </div>

                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input me-3" type="checkbox" wire:model="coordinador"
                                            @if ($coordinador == false) disabled @endif>
                                        <label class="form-check-label">
                                            <div class="fw-semibold text-gray-800">Coordinador de Unidad</div>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid mb-3">
                                        <input class="form-check-input me-3" type="checkbox" wire:model="administrativo_check"
                                            @if ($administrativo_check == false) disabled @endif>
                                        <label class="form-check-label">
                                            <div class="fw-semibold text-gray-800">Administrativo</div>
                                        </label>
                                    </div>
                                    
                                    <div class="border-bottom mt-3"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer col-12 d-flex justify-content-between">
                        <button type="button" wire:click="limpiarAsignacion()" class="btn btn-secondary hover-elevate-up" data-bs-dismiss="modal">Cancelar</button>
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
        <div wire:ignore.self class="modal fade" id="modalInfo" tabindex="-1" aria-labelledby="modalInfo"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase" id="exampleModalLabel">{{ $titulo_modal }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
                                                        <td>{{ $docente_model->docente_cv_url }}</td>
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
                                                        <td></td>
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
                                                        <td></td>
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
                                                        <td></td>
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
