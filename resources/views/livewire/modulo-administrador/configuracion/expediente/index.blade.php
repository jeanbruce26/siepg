<div>
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Expediente
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
                        <li class="breadcrumb-item text-muted">Expediente</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="#modalExpediente" wire:click="modo()" class="btn btn-primary btn-sm hover-elevate-up" data-bs-toggle="modal" data-bs-target="#modalExpediente">Nuevo</a>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid pt-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="d-flex justify-content-between align-items-center gap-4">
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
                                        <th>Expediente</th>
                                        <th>Texto de Complemento</th>
                                        <th scope="col" class="col-md-1">Requerido</th>
                                        <th scope="col" class="col-md-1">Tipo</th>
                                        <th scope="col" class="col-md-1">Estado</th>
                                        <th scope="col" class="col-md-1">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($expedienteModel as $item)
                                    <tr>
                                        <td align="center" class="fw-bold fs-5">{{ $item->id_expediente }}</td>
                                        <td>{{ $item->expediente }}</td>
                                        <td>
                                            @if($item->expediente_complemento == null)
                                                <span >-</span>
                                            @endif
                                            {{ $item->expediente_complemento }}
                                        </td>
                                        <td align="center">
                                            <span class="svg-icon svg-icon-2x {{ ($item->expediente_requerido == 1) ? 'svg-icon-success' : 'svg-icon-danger' }}">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                                    <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor"/>
                                                </svg>
                                            </span>
                                            @switch($item->expediente_requerido)
                                                @case(1)
                                                    <span class="ms-1">Si</span>
                                                    @break
                                                @case(2)
                                                    <span class="ms-1">No</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td align="center">
                                            {{-- tipo => 0 = maestria y doctorado, 1 = maestria, 2 = doctorado --}}
                                            @switch($item->expediente_tipo)
                                                @case(0)
                                                    <span class="badge badge-light-primary">Maestría y Doctorado</span>
                                                    @break
                                                @case(1)
                                                    <span class="badge badge-light-primary">Maestría</span>

                                                    @break
                                                @case(2)
                                                    <span class="badge badge-light-primary">Doctorado</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td align="center">
                                            @if ($item->expediente_estado == 1)
                                                <span style="cursor: pointer;" wire:click="cargarAlertaEstado({{ $item->id_expediente }})" class="badge text-bg-success text-light hover-elevate-down">Activo<span class="pulse-ring"></span></span>
                                            @else
                                                <span style="cursor: pointer;" wire:click="cargarAlertaEstado({{ $item->id_expediente }})" class="badge text-bg-danger text-light hover-elevate-down">Inactivo <span class="pulse-ring"></span></span>
                                            @endif
                                        </td>
                                        <td align="center">
                                            <a class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
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
                                                    <a href="#modalExpediente"
                                                    wire:click="cargarExpediente({{ $item->id_expediente }})" 
                                                    class="menu-link px-3" data-bs-toggle="modal" 
                                                    data-bs-target="#modalExpediente">
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

    {{-- Modal Sede --}}
    <div wire:ignore.self class="modal fade" id="modalExpediente" tabindex="-1" aria-labelledby="modalExpediente"
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
                        <div class="row">
                            <div class="mb-8 col-md-12">
                                <label class="form-label">Expediente <span class="text-danger">*</span></label>
                                <input wire:model="expediente" type="text" class="form-control @error('expediente') is-invalid  @enderror" placeholder="Ingrese el expediente">
                                @error('expediente') <span class="error text-danger" >{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-8 col-md-12">
                                <label class="form-label">Texto de complemento del archivo </label>
                                <input wire:model="complemento" type="text" class="form-control @error('complemento') is-invalid  @enderror" placeholder="Ingrese el nombre del archivo">
                                @error('complemento') <span class="error text-danger" >{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-8 col-md-12">
                                <label class="form-label">Nombre del archivo <span class="text-danger">*</span></label>
                                <input wire:model="nombre_archivo" type="text" class="form-control @error('nombre_archivo') is-invalid  @enderror" placeholder="Ingrese el nombre del archivo">
                                @error('nombre_archivo') <span class="error text-danger" >{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-8 col-md-12">
                                <label class="form-label">Requerido <span class="text-danger">*</span></label>
                                <div class="d-flex align-items-center">
                                    <div class="form-check form-check-custom form-check-success form-check-solid me-15">
                                        <input class="form-check-input @error('requerido') is-invalid @enderror" type="radio" name="requerido" wire:model="requerido" value="1" id="requerido_si" style="cursor: pointer">
                                        <span class="radio-label form-check-label" wire:click="$set('requerido', '1')" style="cursor: pointer; user-select: none;">Si</span>
                                    </div>
                                    <div class="form-check form-check-custom form-check-danger form-check-solid">
                                        <input class="form-check-input @error('requerido') is-invalid @enderror" type="radio" name="requerido" wire:model="requerido" value="2" id="requerido_no" style="cursor: pointer">
                                        <span class="radio-label form-check-label" wire:click="$set('requerido', '2')" style="cursor: pointer; user-select: none;">No</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-8 col-md-12">
                                <label class="form-label">Tipo de expediente <span class="text-danger">*</span></label>
                                <select class="form-select @error('tipo') is-invalid  @enderror" wire:model="tipo">
                                    <option value="" selected>Seleccione</option>
                                    {{-- tipo => 0 = maestria y doctorado, 1 = maestria, 2 = doctorado --}}
                                    <option value="0">Maestría y Doctorado</option>
                                    <option value="1">Maestría</option>
                                    <option value="2">Doctorado</option>
                                </select>
                                @error('tipo')
                                    <span class="error text-danger" >{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer col-12 d-flex justify-content-between">
                    <button type="button" wire:click="limpiar()" class="btn btn-secondary hover-elevate-up" data-bs-dismiss="modal">Cancelar</button>                    
                    <button type="button" wire:click="guardarExpediente()" class="btn btn-primary hover-elevate-up">Guardar</button>
                </div>
            </div>
        </div>
    </div>

</div>
