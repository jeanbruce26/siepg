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
            {{-- <div class="d-flex align-items-center gap-2 gap-lg-3">
                <div class="m-0">
                    <a href="#" class="btn btn-sm btn-flex bg-body btn-color-gray-700 btn-active-color-primary shadow-sm fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <span class="svg-icon svg-icon-6 svg-icon-muted me-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor" />
                            </svg>
                        </span>
                        Filtrar por Proceso de Admisión
                    </a>
                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="menu_expediente" wire:ignore.self>
                        <div class="px-7 py-5">
                            <div class="fs-5 text-dark fw-bold">
                                Opciones de filtrado
                            </div>
                        </div>
                        <div class="separator border-gray-200"></div>
                        <form class="px-7 py-5" wire:submit.prevent="aplicar_filtro">
                            <div class="mb-10">
                                <label class="form-label fw-semibold">Proceso de Admisión:</label>
                                <div>
                                    <select class="form-select" wire:model="filtro_proceso" id="filtro_proceso"  data-control="select2" data-placeholder="Seleccione">
                                        $@foreach ($admisiones as $item)
                                        <option value="{{ $item->id_admision }}">{{ $item->admision }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" wire:click="resetear_filtro" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Resetear</button>
                                <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Aplicar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- alerta --}}
                    <div class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                        <i class="ki-duotone ki-information-5 fs-2qx me-4 text-primary">
                            <i class="path1"></i>
                            <i class="path2"></i>
                            <i class="path3"></i>
                        </i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-5">
                                A continuación se muestran los pagos registrados en el sistema. Podrá ver el detalle de cada pago, así como también el estado de cada uno de ellos.
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
                                        <select class="form-select" wire:model="filtro_concepto_pago" data-control="select2" id="filtro_concepto_pago" data-placeholder="Seleccione el concepto de pago">
                                            <option></option>
                                            <option value="all">Mostrar todos los pagos</option>
                                            @foreach ($concepto_pagos as $item)
                                                <option value="{{ $item->id_concepto_pago }}">Concepto de {{ $item->concepto_pago }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 ps-3">
                                        <input type="search" wire:model="search" class="form-control w-100" placeholder="Buscar..."/>
                                    </div>
                                </div>
                                <table class="table table-hover table-rounded align-middle table-row-bordered border mb-0 gy-4 gs-4">
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
                                                        <span class="badge badge-warning fs-6">No Verificado</span>
                                                    @elseif ($item->pago_verificacion == 0 && $item->pago_estado == 0)
                                                        <span class="badge badge-danger fs-6">Rechazado</span>
                                                    @elseif ($item->pago_verificacion == 0 && ($item->pago_estado == 1 || $item->pago_estado == 2))
                                                        <span class="badge badge-danger fs-6">Observado</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <a href="#modal_pago_contable" wire:click="cargar_pago({{ $item->id_pago }})" class="btn btn-light-info btn-sm hover-scale" data-bs-toggle="modal" data-bs-target="#modal_pago_contable">
                                                        Ver Pago
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            @if ($search != '')
                                                <tr>
                                                    <td colspan="8" class="text-center text-muted">
                                                        No se encontraron resultados para la busqueda "{{ $search }}"
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
                                            Mostrando {{ $pagos->firstItem() }} - {{ $pagos->lastItem() }} de {{ $pagos->total()}} registros
                                        </div>
                                        <div>
                                            {{ $pagos->links() }}
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex justify-content-between mt-5">
                                        <div class="d-flex align-items-center text-gray-700">
                                            Mostrando {{ $pagos->firstItem() }} - {{ $pagos->lastItem() }} de {{ $pagos->total()}} registros
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
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_pago_contable" data-bs-keyboard="false" aria-labelledby="modal_pago_contable" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Validar Pago
                    </h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" wire:click="limpiar">
                        <i class="bi bi-x fs-1"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <form autocomplete="off">
                        <div class="mb-5">
                            <label for="expediente" class="form-label d-flex justify-content-between align-items-center mb-2">
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
                        <div class="">
                            <label for="observacion" class="form-label">
                                Observacion
                            </label>
                            <textarea class="form-control @error('observacion') is-invalid @enderror" id="observacion" wire:model="observacion" rows="3"></textarea>
                            @error('observacion')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="limpiar">
                        Cerrar
                    </button>
                    <button type="button" wire:click="rechazar_pago" class="btn btn-danger" wire:loading.attr="disabled">
                        <div wire:loading.remove wire:target="rechazar_pago">
                            Rechazar
                        </div>
                        <div wire:loading wire:target="rechazar_pago">
                            Procesando...
                        </div>
                    </button>
                    <button type="button" wire:click="observar_pago" class="btn btn-warning" wire:loading.attr="disabled">
                        <div wire:loading.remove wire:target="observar_pago">
                            Observar
                        </div>
                        <div wire:loading wire:target="observar_pago">
                            Procesando...
                        </div>
                    </button>
                    <button type="button" wire:click="validar_pago" class="btn btn-primary" wire:loading.attr="disabled">
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
@push('scripts')
    <script>
        // filtro_concepto_pago select2
        $(document).ready(function () {
            $('#filtro_concepto_pago').select2({
                placeholder: 'Seleccione su concepto de pago',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                    searching: function () {
                        return "Buscando..";
                    }
                }
            });
            $('#filtro_concepto_pago').on('change', function(){
                @this.set('filtro_concepto_pago', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_concepto_pago').select2({
                    placeholder: 'Seleccione su concepto de pago',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando..";
                        }
                    }
                });
                $('#filtro_concepto_pago').on('change', function(){
                    @this.set('filtro_concepto_pago', this.value);
                });
            });
        });
    </script>
@endpush
