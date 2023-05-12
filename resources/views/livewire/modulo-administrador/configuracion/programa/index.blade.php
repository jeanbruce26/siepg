<div>
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Programa
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
                        <li class="breadcrumb-item text-muted">Programa</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="#modalPrograma" wire:click="modo()" class="btn btn-primary btn-sm hover-elevate-up" data-bs-toggle="modal" data-bs-target="#modalPrograma">Nuevo</a>
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
                                    <select class="form-select form-select-sm text-muted" wire:model="buscar_programa" aria-label="Default select example">
                                        <option value="all">Seleccione un programa</option>
                                        @foreach ($programa_model as $item)
                                            <option value="{{ $item->id_programa }}">{{ $item->programa }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="text-muted d-flex align-items-center">
                                    <select class="form-select form-select-sm text-muted" wire:model="buscar_plan" aria-label="Default select example">
                                        <option value="all" selected>Seleccione el Plan</option>
                                        @foreach ($plan_model as $item)
                                            <option value="{{ $item->id_plan }}">{{ $item->plan }}</option>
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
                                        <th scope="col" class="col-md-3">Programas</th>
                                        <th scope="col" class="col-md-1">Plan</th>
                                        <th scope="col" class="col-md-2">Sede</th>
                                        <th scope="col" class="col-md-2">Modalidad</th>
                                        <th scope="col" class="col-md-2">Estado</th>
                                        <th scope="col" class="col-md-1">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($programas as $item)
                                    <tr>
                                        <td align="center" class="fw-bold fs-5">{{ $item->id_mencion }}</td>
                                        <td>{{ $item->descripcion_programa }}</td>
                                        <td>{{ $item->subprograma }}</td>
                                        <td>
                                            @if($item->mencion == null)
                                                SIN MENCION
                                            @else
                                            {{ $item->mencion }}
                                            @endif
                                        </td>

                                        <td align="center">{{ $item->plan_codigo }}</td>
                                        <td align="center">
                                            @if ($item->plan_estado == 1)
                                                <span style="cursor: pointer;" wire:click="cargarAlerta({{ $item->id_plan }})" class="badge text-bg-success text-light hover-elevate-down">Activo<span class="pulse-ring"></span></span>
                                            @else
                                                <span style="cursor: pointer;" wire:click="cargarAlerta({{ $item->id_plan }})" class="badge text-bg-danger text-light hover-elevate-down">Inactivo <span class="pulse-ring"></span></span>
                                            @endif
                                        </td>
                                        <td align="center">

                                            <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
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
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="#modalPlan"
                                                    wire:click="cargarPlan({{ $item->id_plan }})" 
                                                    class="menu-link px-3" data-bs-toggle="modal" 
                                                    data-bs-target="#modalPlan">
                                                        Editar
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        <div class="text-center p-3 text-muted">
                                            <span>No hay resultados para la busqueda "<strong>{{ $search }}</strong>"</span>
                                        </div>
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

