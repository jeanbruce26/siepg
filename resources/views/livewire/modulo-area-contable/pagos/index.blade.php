<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Pagos</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('contable.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Pagos</li>
                </ul>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- alerta --}}
                    <div
                        class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                        <i class="ki-duotone ki-information-5 fs-2qx me-4 text-primary">
                            <i class="path1"></i>
                            <i class="path2"></i>
                            <i class="path3"></i>
                        </i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-5">
                                A continuación se muestran los pagos registrados en el sistema. Podrá ver el detalle de
                                cada pago, así como también el estado de cada uno de ellos.
                            </span>
                        </div>
                    </div>
                    {{-- card monto de pagos --}}
                    <div class="card shadow-sm">
                        <div class="card-body mb-0">
                            <div class="table-responsive">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="col-md-4 pe-3"></div>
                                    <div class="col-md-4 px-3">
                                        <select class="form-select" wire:model="filtro_concepto_pago"
                                            data-control="select2" id="filtro_concepto_pago"
                                            data-placeholder="Seleccione el concepto de pago">
                                            <option></option>
                                            <option value="all">Mostrar todos los pagos</option>
                                            @foreach ($concepto_pagos as $item)
                                                <option value="{{ $item->id_concepto_pago }}">Concepto de
                                                    {{ $item->concepto_pago }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 ps-3">
                                        <input type="search" wire:model="search" class="form-control w-100"
                                            placeholder="Buscar..." />
                                    </div>
                                </div>
                                <table
                                    class="table table-hover table-rounded align-middle table-row-bordered border mb-0 gy-4 gs-4">
                                    <thead class="bg-light-warning">
                                        <tr class="fw-bold fs-5 text-gray-800 border-bottom-2 border-gray-200">
                                            <th>ID</th>
                                            <th>Numero Documento</th>
                                            <th>Numero Operacion</th>
                                            <th>Monto</th>
                                            <th>Fecha</th>
                                            <th>Concepto de Pago</th>
                                            <th>Estado</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pagos as $item)
                                            <tr>
                                                <td class="fw-bold">
                                                    {{ $item->id_pago }}
                                                </td>
                                                <td>
                                                    {{ $item->pago_documento }}
                                                </td>
                                                <td>
                                                    {{ $item->pago_operacion }}
                                                </td>
                                                <td>
                                                    S/. {{ number_format($item->pago_monto, 2, ',', '.') }}
                                                </td>
                                                <td>
                                                    {{ date('d/m/Y', strtotime($item->pago_fecha)) }}
                                                </td>
                                                <td>
                                                    Concepto de {{ $item->concepto_pago->concepto_pago }}
                                                </td>
                                                <td>
                                                    @if ($item->pago_verificacion == 2)
                                                        <span class="badge badge-success fs-6">Validado</span>
                                                    @elseif ($item->pago_verificacion == 1)
                                                        <span class="badge badge-warning fs-6">Pendiente</span>
                                                    @elseif ($item->pago_verificacion == 0 && $item->pago_estado == 0)
                                                        <span class="badge badge-danger fs-6">Rechazado</span>
                                                    @elseif ($item->pago_verificacion == 0 && ($item->pago_estado == 1 || $item->pago_estado == 2))
                                                        <span class="badge badge-danger fs-6">Observado</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="#modal_pago_contable"
                                                        wire:click="cargar_pago({{ $item->id_pago }})"
                                                        class="btn btn-light-info btn-sm hover-scale"
                                                        data-bs-toggle="modal" data-bs-target="#modal_pago_contable">
                                                        Ver Pago
                                                    </a>
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
                                @if ($pagos->hasPages())
                                    <div class="d-flex justify-content-between mt-5">
                                        <div class="d-flex align-items-center text-gray-700">
                                            Mostrando {{ $pagos->firstItem() }} - {{ $pagos->lastItem() }} de
                                            {{ $pagos->total() }} registros
                                        </div>
                                        <div>
                                            {{ $pagos->links() }}
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex justify-content-between mt-5">
                                        <div class="d-flex align-items-center text-gray-700">
                                            Mostrando {{ $pagos->firstItem() }} - {{ $pagos->lastItem() }} de
                                            {{ $pagos->total() }} registros
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal pago --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_pago_contable" data-bs-keyboard="false"
        aria-labelledby="modal_pago_contable" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Validar Pago
                    </h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close" wire:click="limpiar">
                        <i class="bi bi-x fs-1"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <form autocomplete="off">
                        <div class="row g-5 mb-5">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Numero de Documento
                                </label>
                                <input type="text" class="form-control form-control-solid" wire:model="documento" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Nombre y Apellido
                                </label>
                                <input type="text" class="form-control form-control-solid" wire:model="nombres" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Numero de Operación
                                </label>
                                <input type="text" class="form-control form-control-solid" wire:model="operacion" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Monto
                                </label>
                                <input type="text" class="form-control form-control-solid" wire:model="monto" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Fecha de Pago
                                </label>
                                <input type="text" class="form-control form-control-solid" wire:model="fecha_pago" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Canal de Pago
                                </label>
                                <input type="text" class="form-control form-control-solid" wire:model="canal_pago" readonly/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label d-flex justify-content-between align-items-center mb-2">
                                    <span>
                                        Voucher
                                    </span>
                                    <a href="{{ asset($voucher) }}" target="_blank" class="btn btn-sm btn-light-info">
                                        Ver Voucher Completo
                                    </a>
                                </label>
                                <div class="form-control">
                                    <img src="{{ asset($voucher) }}" alt="voucher" class="img-fluid rounded">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="observacion" class="form-label">
                                    Observacion
                                </label>
                                <textarea class="form-control @error('observacion') is-invalid @enderror" id="observacion" wire:model="observacion"
                                    rows="5"></textarea>
                                @error('observacion')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="limpiar">
                        Cerrar
                    </button>
                    <div class="">
                        <button type="button" wire:click="rechazar_pago" class="btn btn-danger"
                            wire:loading.attr="disabled">
                            <div wire:loading.remove wire:target="rechazar_pago">
                                Rechazar
                            </div>
                            <div wire:loading wire:target="rechazar_pago">
                                Procesando...
                            </div>
                        </button>
                        <button type="button" wire:click="observar_pago" class="btn btn-warning"
                            wire:loading.attr="disabled">
                            <div wire:loading.remove wire:target="observar_pago">
                                Observar
                            </div>
                            <div wire:loading wire:target="observar_pago">
                                Procesando...
                            </div>
                        </button>
                        <button type="button" wire:click="validar_pago" class="btn btn-primary"
                            wire:loading.attr="disabled">
                            <div wire:loading.remove wire:target="validar_pago">
                                Validar
                            </div>
                            <div wire:loading wire:target="validar_pago">
                                Procesando...
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // filtro_concepto_pago select2
        $(document).ready(function() {
            $('#filtro_concepto_pago').select2({
                placeholder: 'Seleccione su concepto de pago',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando..";
                    }
                }
            });
            $('#filtro_concepto_pago').on('change', function() {
                @this.set('filtro_concepto_pago', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_concepto_pago').select2({
                    placeholder: 'Seleccione su concepto de pago',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    }
                });
                $('#filtro_concepto_pago').on('change', function() {
                    @this.set('filtro_concepto_pago', this.value);
                });
            });
        });
    </script>
@endpush
