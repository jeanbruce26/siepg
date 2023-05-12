<div>
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Inscripción de Pago
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
                        <li class="breadcrumb-item text-muted">Inscripción de Pago</li>
                    </ul>
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
                                        <th scope="col" class="col-md-3">Inscripción</th>
                                        <th scope="col" class="col-md-2">Pago</th>
                                        <th scope="col" class="col-md-2">Canal de Pago</th>
                                        <th scope="col" class="col-md-2">Concepto de Pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($inscripcion_pagos as $item)
                                    <tr>
                                        <td align="center" class="fw-bold fs-5">{{ $item->id_inscripcion }}</td>
                                        <td>{{ $item->persona->apellido_paterno }} {{ $item->persona->apellido_materno }}, {{ $item->persona->nombre }} - {{ $item->persona->numero_documento }}</td>
                                        <td align="center">S/. {{ $item->pago->pago_monto }}</td>
                                        <td align="center">{{ $item->pago->canal_pago->canal_pago }}</td>
                                        <td align="center">{{ $item->pago->concepto_pago->concepto_pago }}</td>
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

