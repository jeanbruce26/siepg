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
                                <input class="form-control form-control-sm text-muted" type="search"
                                    wire:model="search" placeholder="Buscar...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-rounded border gy-4 gs-4 mb-0 align-middle">
                                <thead class="bg-light-primary">
                                    <tr align="center"
                                        class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                        <th scope="col">ID</th>
                                        <th scope="col" class="col-md-4">Inscripción</th>
                                        <th scope="col">Nro. Operación</th>
                                        <th scope="col">Pago</th>
                                        <th scope="col">F. Pago</th>
                                        <th scope="col" class="col-md-1">Verificacion</th>
                                        <th scope="col">Canal de Pago</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($inscripcion_pagos as $item)
                                        <tr>
                                            <td align="center" class="fw-bold fs-6">
                                                {{ $item->id_inscripcion }}
                                            </td>
                                            <td>
                                                {{ $item->persona->apellido_paterno }}
                                                {{ $item->persona->apellido_materno }}, {{ $item->persona->nombre }} -
                                                {{ $item->persona->numero_documento }}
                                            </td>
                                            <td align="center">
                                                {{ $item->pago->pago_operacion }}
                                            </td>
                                            <td align="center">
                                                S/. {{ $item->pago->pago_monto }}
                                            </td>
                                            <td align="center">
                                                {{ convertirFechaHora($item->pago->pago_fecha) }}
                                            </td>
                                            <td align="center">
                                                @if ($item->pago->pago_verificacion == 1)
                                                    <span class="badge badge-warning fs-6">Pendiente</span>
                                                @elseif ($item->pago->pago_verificacion == 2)
                                                    <span class="badge badge-success fs-6">Verificado</span>
                                                @else
                                                    <span class="badge badge-danger fs-6">Anulado</span>
                                                @endif
                                            </td>
                                            <td align="center">
                                                {{ $item->pago->concepto_pago->concepto_pago }}
                                            </td>
                                            <td align="center">
                                                <a href="{{ asset($item->pago->pago_voucher_url) }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-dark">
                                                    Ver
                                                </a>
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
                        @if ($inscripcion_pagos->hasPages())
                            <div class="d-flex justify-content-between mt-5">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $inscripcion_pagos->firstItem() }} -
                                    {{ $inscripcion_pagos->lastItem() }} de
                                    {{ $inscripcion_pagos->total() }} registros
                                </div>
                                <div>
                                    {{ $inscripcion_pagos->links() }}
                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-between mt-5">
                                <div class="d-flex align-items-center text-gray-700">
                                    Mostrando {{ $inscripcion_pagos->firstItem() }} -
                                    {{ $inscripcion_pagos->lastItem() }} de
                                    {{ $inscripcion_pagos->total() }} registros
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
