<div>
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Tipo de Seguimiento de Expedientes
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
                            <a href="{{ route('administrador.expediente') }}" class="text-muted text-hover-primary">
                                Tipo de Seguimiento de Expedientes
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="#modalTipoSeguimiento" wire:click="modo()" class="btn btn-primary btn-sm hover-elevate-up" data-bs-toggle="modal" data-bs-target="#modalTipoSeguimiento">Nuevo</a>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid pt-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-end align-items-center mb-5">
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
                                        <th scope="col">Tipo de Seguimiento</th>
                                        <th scope="col">Titulo</th>
                                        <th scope="col" class="col-md-1">Estado</th>
                                        <th scope="col" class="col-md-2">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($tipoSeguimientoModel as $item)
                                    <tr>
                                        <td align="center" class="fw-bold fs-5">{{ $item->id_tipo_seguimiento }}</td>
                                        <td>{{ $item->tipo_seguimiento }}</td>
                                        <td>{{ $item->tipo_seguimiento_titulo }}</td>
                                        <td align="center">
                                            @if ($item->tipo_seguimiento_estado == 1)
                                                <span style="cursor: pointer;" wire:click="cargarAlertaEstado({{ $item->id_tipo_seguimiento }})" class="badge text-bg-success text-light hover-elevate-down px-3 py-2">Activo</span>
                                            @else
                                                <span style="cursor: pointer;" wire:click="cargarAlertaEstado({{ $item->id_tipo_seguimiento }})" class="badge text-bg-danger text-light hover-elevate-down px-3 py-2">Inactivo</span></span>
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
                                            <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="#modalTipoSeguimiento" wire:click="cargarTipoSeguimiento({{ $item->id_tipo_seguimiento }}, 3)" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modalTipoSeguimiento">
                                                        Detalle
                                                    </a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="#modalTipoSeguimiento" wire:click="cargarTipoSeguimiento({{ $item->id_tipo_seguimiento }}, 2)" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#modalTipoSeguimiento">
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
                        @if ($tipoSeguimientoModel->hasPages())
                            <div class="d-flex justify-content-between mt-5">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $tipoSeguimientoModel->firstItem() }} - {{ $tipoSeguimientoModel->lastItem() }} de
                                    {{ $tipoSeguimientoModel->total() }} registros
                                </div>
                                <div>
                                    {{ $tipoSeguimientoModel->links() }}
                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-between mt-5">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $tipoSeguimientoModel->firstItem() }} - {{ $tipoSeguimientoModel->lastItem() }} de
                                    {{ $tipoSeguimientoModel->total() }} registros
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Expediente --}}
    <div wire:ignore.self class="modal fade" id="modalTipoSeguimiento" tabindex="-1" aria-labelledby="modalTipoSeguimiento"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $titulo }}</h5>
                    <button type="button" wire:click="limpiar()" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form novalidate>
                        <div class="row g-5 {{ $modo == 3 ? 'mb-3' : '' }}">
                            <div class="col-md-12">
                                <label class="form-label">Tipo de Seguimiento <span class="text-danger">*</span></label>
                                <input wire:model="tipo_seguimiento" type="text" class="form-control @error('tipo_seguimiento') is-invalid  @enderror" placeholder="Ingrese el tipo de seguimiento" @if($modo == 3) readonly @endif>
                                @error('tipo_seguimiento') <span class="error text-danger" >{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Título del Seguimiento <span class="text-danger">*</span></label>
                                <textarea wire:model="tipo_seguimiento_titulo" class="form-control @error('tipo_seguimiento_titulo') is-invalid  @enderror" placeholder="Ingrese la descripción del seguimiento" @if($modo == 3) readonly @endif></textarea>
                                @error('tipo_seguimiento_titulo') <span class="error text-danger" >{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Descripción del Seguimiento <span class="text-danger">*</span></label>
                                <textarea wire:model="tipo_seguimiento_descripcion" class="form-control @error('tipo_seguimiento_descripcion') is-invalid  @enderror" placeholder="Ingrese la descripción del seguimiento" @if($modo == 3) readonly @endif></textarea>
                                @error('tipo_seguimiento_descripcion') <span class="error text-danger" >{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </form>
                </div>
                @if($modo == 2 || $modo == 1)
                    <div class="modal-footer col-12 d-flex justify-content-between">
                        <button type="button" wire:click="limpiar()" class="btn btn-secondary hover-elevate-up" data-bs-dismiss="modal">Cancelar</button>                    
                        <button type="button" wire:click="guardarTipoSeguimiento()" class="btn btn-primary hover-elevate-up">Guardar</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>