<div>
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Pago
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
                        <li class="breadcrumb-item text-muted">Pago</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="#modalPago" wire:click="modo()" class="btn btn-primary btn-sm hover-elevate-up"
                        data-bs-toggle="modal" data-bs-target="#modalPago">Nuevo</a>
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
                            <i class="ki-outline ki-information-5 fs-2qx me-4 text-primary"></i>
                            <div class="d-flex flex-column">
                                <span class="fw-bold fs-5">
                                    A continuación se muestran los pagos registrados en el sistema. Podrá ver el detalle
                                    de
                                    cada pago, así como también el estado de cada uno de ellos.
                                </span>
                            </div>
                        </div>
                        {{-- card monto de pagos --}}
                        <div class="card shadow-sm">
                            <div class="card-body mb-0">
                                <div class="table-responsive">
                                    <div class="row g-2 mb-5">
                                        <div class="col-md-4">
                                            <select class="form-select" wire:model="filtro_estado" id="filtro_estado">
                                                <option value="all">
                                                    Seleccione un estado
                                                </option>
                                                <option value="0">
                                                    Observado
                                                </option>
                                                <option value="1">
                                                    Pendiente
                                                </option>
                                                <option value="2">
                                                    Verificado
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-select" wire:model="filtro_concepto_pago">
                                                {{-- data-control="select2" id="filtro_concepto_pago"
                                                data-placeholder="Seleccione el concepto de pago"> --}}
                                                {{-- <option></option> --}}
                                                <option value="all">Mostrar todos los pagos</option>
                                                @foreach ($concepto_pagos as $item)
                                                    <option value="{{ $item->id_concepto_pago }}">Concepto de
                                                        {{ $item->concepto_pago }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
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
                                                            wire:click="cargar_pago({{ $item->id_pago }}, false)"
                                                            class="btn btn-outline btn-outline-dashed btn-outline-info btn-active-light-info hover-scale"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modal_pago_contable">
                                                            Ver Pago
                                                        </a>
                                                        <a href="#modal_pago_editar"
                                                            wire:click="cargar_pago({{ $item->id_pago }}, true)"
                                                            class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary hover-scale"
                                                            data-bs-toggle="modal" data-bs-target="#modal_pago_editar">
                                                            Editar
                                                        </a>
                                                        <button type="button" wire:click="eliminar_pago({{ $item->id_pago }})"
                                                            class="btn btn-outline btn-outline-dashed btn-outline-danger btn-active-light-danger hover-scale">
                                                            Eliminar
                                                        </button>
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
                                <input type="text" class="form-control form-control-solid" wire:model="documento"
                                    readonly />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Nombre y Apellido
                                </label>
                                <input type="text" class="form-control form-control-solid" wire:model="nombres"
                                    readonly />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Numero de Operación
                                </label>
                                <input type="text" class="form-control form-control-solid" wire:model="operacion"
                                    readonly />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Monto
                                </label>
                                <input type="text" class="form-control form-control-solid" wire:model="monto"
                                    readonly />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Fecha de Pago
                                </label>
                                <input type="text" class="form-control form-control-solid" wire:model="fecha_pago"
                                    readonly />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Canal de Pago
                                </label>
                                <input type="text" class="form-control form-control-solid" wire:model="canal_pago"
                                    readonly />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label d-flex justify-content-between align-items-center mb-2">
                                    <span>
                                        Voucher
                                    </span>
                                    <a href="{{ asset($voucher_name) }}" target="_blank"
                                        class="btn btn-sm btn-light-info">
                                        Ver Voucher Completo
                                    </a>
                                </label>
                                <div class="form-control">
                                    <img src="{{ asset($voucher_name) }}" alt="voucher" class="img-fluid rounded">
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
                        {{-- <button type="button" wire:click="rechazar_pago" class="btn btn-danger"
                            wire:loading.attr="disabled">
                            <div wire:loading.remove wire:target="rechazar_pago">
                                Rechazar
                            </div>
                            <div wire:loading wire:target="rechazar_pago">
                                Procesando...
                            </div>
                        </button> --}}
                        <button type="button" wire:click="observar_pago" class="btn btn-danger"
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
    {{-- modal pago --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_pago_editar">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        {{ $titulo_modal_pago }}
                    </h3>

                    <div class="btn btn-icon btn-sm btn-active-light-danger ms-2" wire:click="limpiar_pago"
                        data-bs-dismiss="modal" aria-label="Close">
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
                    <form autocomplete="off" class="row g-5">
                        <div class="col-md-6">
                            <label for="documento" class="required form-label">
                                Documento de Identidad
                            </label>
                            <input type="number" wire:model="documento"
                                class="form-control @error('documento') is-invalid @enderror" placeholder="12345678"
                                id="documento" readonly />
                            @error('documento')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="operacion" class="required form-label">
                                Numero de Operación
                            </label>
                            <input type="number" wire:model="operacion"
                                class="form-control @error('operacion') is-invalid @enderror" placeholder="6543"
                                id="operacion" />
                            <span class="form-text text-muted mt-2 fst-italic">
                                Nota: Omitir los ceros a la izquierda. Ejemplo: 00001265, debe ser ingresado como 1265.
                                <br>
                            </span>
                            @error('operacion')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="monto" class="required form-label">
                                Monto de Operación
                            </label>
                            <input type="number" wire:model="monto"
                                class="form-control @error('monto') is-invalid @enderror" placeholder="00.00"
                                id="monto" />
                            @error('monto')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_pago" class="required form-label">
                                Fecha de Pago
                            </label>
                            <input type="date" wire:model="fecha_pago"
                                class="form-control @error('fecha_pago') is-invalid @enderror" id="fecha_pago"
                                max="{{ date('Y-m-d') }}" />
                            @error('fecha_pago')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="canal_pago" class="required form-label">
                                Canal de Pago
                            </label>
                            <select class="form-select @error('canal_pago') is-invalid @enderror"
                                wire:model="canal_pago" id="canal_pago">
                                <option value="">Seleccione su canal de pago</option>
                                @foreach ($canales_pagos as $item)
                                    <option value="{{ $item->id_canal_pago }}">Pago realizado en
                                        {{ $item->canal_pago }}</option>
                                @endforeach
                            </select>
                            @error('canal_pago')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="concepto_pago" class="required form-label">
                                Concepto de Pago
                            </label>
                            <select class="form-select @error('concepto_pago') is-invalid @enderror"
                                wire:model="concepto_pago" id="concepto_pago">
                                <option value="">Seleccione su concepto de pago</option>
                                @foreach ($concepto_pagos as $item)
                                    <option value="{{ $item->id_concepto_pago }}">
                                        Concepto por {{ $item->concepto_pago }} @if ($item->id_concepto_pago != 7)
                                            - S/. {{ number_format($item->concepto_pago_monto, 2, ',', '.') }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('concepto_pago')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="voucher"
                                class="@if ($modo == 'create') required @endif @if ($activar_voucher == true) required @endif form-label">
                                Voucher
                            </label>
                            <input type="file" wire:model="voucher"
                                class="form-control @error('voucher') is-invalid @enderror" id="voucher"
                                accept="image/jpeg, image/png, image/jpg" />
                            <span class="form-text text-muted mt-2 fst-italic">
                                Nota: El voucher debe ser imagen en formato JPG, JPEG, PNG y no debe superar los 2MB.
                                <br>
                            </span>
                            @error('voucher')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if ($modo == 'create')
                            <div class="col-md-12">
                                <div class="form-check mt-2">
                                    <input
                                        class="form-check-input @error('terminos_condiciones_pagos') is-invalid @enderror"
                                        type="checkbox" wire:model="terminos_condiciones_pagos"
                                        id="terminos_condiciones_pagos" />
                                    <label class="form-check-label text-dark fw-bold"
                                        for="terminos_condiciones_pagos">
                                        El registro de pago estará sujeto a revisión por el Área Contable, cualquier
                                        inconveniente será reportado. <br>
                                        Sabiendo eso "ACEPTO LOS TERMINOS Y CONDICIONES".
                                    </label>
                                </div>
                                @error('terminos_condiciones_pagos')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="limpiar_pago">
                        Cerrar
                    </button>
                    <button type="button" wire:click="alerta_guardar_pago" class="btn btn-primary"
                        style="width: 150px" @if ($voucher == null) wire:target="voucher" @endif
                        wire:loading.attr="disabled" wire:target="alerta_guardar_pago">
                        <div wire:loading.remove wire:target="alerta_guardar_pago">
                            {{ $button_modal }}
                        </div>
                        <div wire:loading wire:target="alerta_guardar_pago">
                            Procesando <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        //Select2 de Filtro
        // filtro_proceso select2
        $(document).ready(function() {
            $('#filtro_proceso').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            });
            $('#filtro_proceso').on('change', function() {
                @this.set('filtro_proceso', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_proceso').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#filtro_proceso').on('change', function() {
                    @this.set('filtro_proceso', this.value);
                });
            });
        });
        // filtro_concepto select2
        $(document).ready(function() {
            $('#filtro_concepto').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            });
            $('#filtro_concepto').on('change', function() {
                @this.set('filtro_concepto', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_concepto').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#filtro_concepto').on('change', function() {
                    @this.set('filtro_concepto', this.value);
                });
            });
        });


        //Select2 de Modal Pago
        // canal_pago select2
        $(document).ready(function() {
            $('#canal_pago').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            });
            $('#canal_pago').on('change', function() {
                @this.set('canal_pago', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#canal_pago').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#canal_pago').on('change', function() {
                    @this.set('canal_pago', this.value);
                });
            });
        });
        // concepto_pago select2
        $(document).ready(function() {
            $('#concepto_pago').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            });
            $('#concepto_pago').on('change', function() {
                @this.set('concepto_pago', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#concepto_pago').select2({
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando...";
                        }
                    }
                });
                $('#concepto_pago').on('change', function() {
                    @this.set('concepto_pago', this.value);
                });
            });
        });
    </script>
@endpush
