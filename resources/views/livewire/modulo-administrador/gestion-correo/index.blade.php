<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Gestión de Correos
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
                        Gestión de Correos
                    </li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('administrador.gestion-correo.crear') }}"
                    class="btn btn-primary btn-sm hover-elevate-up">
                    Nuevo
                </a>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid pt-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3 mb-5">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6">
                            <input class="form-control text-muted" type="search" wire:model="search"
                                placeholder="Buscar correo">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0 align-middle">
                            <thead class="bg-light-secondary">
                                <tr align="center" class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                    <th scope="col" class="col-md-1">ID</th>
                                    <th scope="col" class="col-md-3">Asunto</th>
                                    <th scope="col" class="col-md-4">Mensaje</th>
                                    <th scope="col" class="col-md-2">Enviado</th>
                                    <th scope="col" class="col-md-2">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($correos as $item)
                                    <tr>
                                        <td align="center" class="fw-bold fs-5">{{ $item->id_correo }}</td>
                                        <td>{{ $item->correo_asunto }}</td>
                                        <td>
                                            @php echo $item->correo_mensaje; @endphp
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-light-primary">
                                                Ver Enviado
                                            </button>
                                        </td>
                                        <td>
                                            {{ convertirFechaHora($item->created_at) }}
                                        </td>
                                    </tr>
                                @empty
                                    @if ($search != '')
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                No se encontraron resultados para la busqueda
                                                "{{ $search }}"
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                No hay registros
                                            </td>
                                        </tr>
                                    @endif
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- paginacion de la tabla --}}
                    @if ($correos->hasPages())
                        <div class="d-flex justify-content-between mt-5">
                            <div class="d-flex align-items-center text-gray-700">
                                Mostrando {{ $correos->firstItem() }} - {{ $correos->lastItem() }} de
                                {{ $correos->total() }} registros
                            </div>
                            <div>
                                {{ $correos->links() }}
                            </div>
                        </div>
                    @else
                        <div class="d-flex justify-content-between mt-5">
                            <div class="d-flex align-items-center text-gray-700">
                                Mostrando {{ $correos->firstItem() }} - {{ $correos->lastItem() }}
                                de
                                {{ $correos->total() }} registros
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Sede --}}
    {{-- <div wire:ignore.self class="modal fade" tabindex="-1" id="modalCanalPago">
            <div class="modal-dialog modal-dialog-scrollable">
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
                        <form autocomplete="off">
                            <div class="row">
                                <div class="mb-3 col-md-12 col-sm-12">
                                    <label class="form-label">Canal Pago<span class="text-danger">*</span></label>
                                    <input wire:model="canalPago" type="text"
                                        class="form-control @error('canalPago') is-invalid  @enderror"
                                        placeholder="Ingrese el Canal de Pago">
                                    @error('canalPago')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer col-12 d-flex justify-content-between">
                        <button type="button" wire:click="limpiar()" class="btn btn-secondary"
                            data-bs-dismiss="modal">Cancelar</button>

                        <button type="button" wire:click="guardarCanalPago" class="btn btn-primary"
                            wire:loading.attr="disabled" wire:target="guardarCanalPago">
                            <div wire:loading.remove wire:target="guardarCanalPago">
                                Guardar
                            </div>
                            <div wire:loading wire:target="guardarCanalPago">
                                Procesando <span class="spinner-border spinner-border-sm align-middle ms-2">
                            </div>
                        </button>

                    </div>
                </div>
            </div>
        </div> --}}
</div>
