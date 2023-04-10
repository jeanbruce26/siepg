<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Pagos</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('plataforma.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Pagos</li>
                </ul>
            </div>
            @if ($admitido)
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="#modal_pago" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#modal_pago">
                    Nuevo Pago
                </a>
            </div>
            @endif
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- alerta de fecha de actualizacion de expedientes --}}
                    {{-- @if ($admision->fecha_fin < today())
                        <div class="alert bg-light-danger border border-danger d-flex alig-items-center p-5 mb-5">
                            <span class="svg-icon svg-icon-2hx svg-icon-danger me-4">
                                <i class="las la-exclamation-circle fs-2 text-danger"></i>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="fw-bold">
                                    La fecha limite para actualizar sus expedientes ha expirado
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="alert bg-light-warning border border-warning d-flex alig-items-center p-5 mb-5">
                            <span class="svg-icon svg-icon-2hx svg-icon-warning me-4">
                                <i class="las la-exclamation-triangle fs-2 text-warning"></i>
                            </span>
                            <div class="d-flex flex-column">
                                <span class="fw-bold">
                                    Recuerde que la fecha limite para actualizar sus expedientes es el {{ $fecha_fin_admision }}
                                </span>
                            </div>
                        </div>
                    @endif --}}
                    {{-- alerta para que el usuario sepa de donde abrir los expedientes --}}
                    {{-- <div class="alert bg-light-primary border border-primary d-flex alig-items-center p-5 mb-5">
                        <span class="svg-icon svg-icon-2hx svg-icon-primary me-4">
                            <i class="las la-exclamation-circle fs-2 text-primary"></i>
                        </span>
                        <div class="d-flex flex-column">
                            <span class="fw-bold">
                                Nota: Para abrir los expedientes debe hacer click en el nombre de cada uno de los expedientes
                            </span>
                        </div>
                    </div> --}}
                    {{-- tabla de pagos --}}
                    <div class="card card-body shadow-sm mb-5">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-rounded border mb-0 gy-5 gs-5">
                                <thead>
                                    <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                        <th>ID</th>
                                        <th>Concepto Pago</th>
                                        <th>Numero Operacion</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pagos as $item)
                                    <tr>
                                        <td>
                                            {{ $item->id_pago }}
                                        </td>
                                        <td>
                                            Concepto por {{ $item->concepto_pago->concepto_pago }}
                                        </td>
                                        <td>
                                            {{ $item->pago_operacion }}
                                        </td>
                                        <td>
                                            S/. {{ $item->pago_monto }}
                                        </td>
                                        <td>
                                            {{ date('d/m/Y', strtotime($item->pago_fecha)) }}
                                        </td>
                                        <td>
                                            @if ($item->pago_verificacion == 1)
                                                <span class="badge badge-warning">Pendiente</span>
                                            @elseif ($item->pago_verificacion == 2)
                                                <span class="badge badge-success">Validado</span>
                                            @else
                                                <span class="badge badge-danger">Observado</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="#modal_pago_plataforma" wire:click="cargar_pago({{ $item->id_pago }})" class="btn btn-light-info btn-sm hover-scale" data-bs-toggle="modal" data-bs-target="#modal_pago_plataforma">
                                                Editar
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- modal registro pago --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_pago_plataforma">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        {{ $titulo_modal_pago }}
                    </h3>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" wire:click="limpiar_pago">
                        <i class="bi bi-x fs-1"></i>
                    </div>
                    <!--end::Close-->
                </div>
                <div class="modal-body">
                    <form autocomplete="off" class="row g-5">
                        <div class="col-md-6">
                            <label for="documento_identidad" class="required form-label">
                                Documento de Identidad
                            </label>
                            <input type="number" wire:model="documento_identidad" class="form-control @error('documento_identidad') is-invalid @enderror" placeholder="12345678" id="documento_identidad" readonly/>
                            @error('documento_identidad')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="numero_operacion" class="required form-label">
                                Numero de Operación
                            </label>
                            <input type="number" wire:model="numero_operacion" class="form-control @error('numero_operacion') is-invalid @enderror" placeholder="6543" id="numero_operacion"/>
                            <span class="form-text text-muted mt-2 fst-italic">
                                Nota: Omitir los ceros a la izquierda. Ejemplo: 00001265, debe ser ingresado como 1265. <br>
                            </span>
                            @error('numero_operacion')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="monto_operacion" class="required form-label">
                                Monto de Operación
                            </label>
                            <input type="number" wire:model="monto_operacion" class="form-control @error('monto_operacion') is-invalid @enderror" placeholder="00.00" id="monto_operacion"/>
                            @error('monto_operacion')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_pago" class="required form-label">
                                Fecha de Pago
                            </label>
                            <input type="date" wire:model="fecha_pago" class="form-control @error('fecha_pago') is-invalid @enderror" id="fecha_pago" max="{{ date('Y-m-d') }}" />
                            @error('fecha_pago')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="canal_pago" class="required form-label">
                                Canal de Pago
                            </label>
                            <select class="form-select @error('canal_pago') is-invalid @enderror" wire:model="canal_pago" id="canal_pago" data-control="select2" data-placeholder="Seleccione su canal de pago" data-allow-clear="true" data-dropdown-parent="#modal_pago_plataforma">
                                <option></option>
                                @foreach ($canales_pagos as $item)
                                <option value="{{ $item->id_canal_pago }}">Pago realizado en {{ $item->canal_pago }}</option>
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
                            <select class="form-select @error('concepto_pago') is-invalid @enderror" wire:model="concepto_pago" id="concepto_pago" data-control="select2" data-placeholder="Seleccione su concepto de pago" data-allow-clear="true" data-dropdown-parent="#modal_pago_plataforma">
                                <option></option>
                                @foreach ($conceptos_pagos as $item)
                                <option value="{{ $item->id_concepto_pago }}">Concepto por {{ $item->concepto_pago }}</option>
                                @endforeach
                            </select>
                            @error('concepto_pago')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label for="voucher" class="@if($modo == 'create') required @endif form-label">
                                Voucher
                            </label>
                            <input type="file" wire:model="voucher" class="form-control @error('voucher') is-invalid @enderror" id="upload{{ $iteration }}" accept="image/jpeg, image/png, image/jpg" />
                            <span class="form-text text-muted mt-2 fst-italic">
                                Nota: El voucher debe ser imagen en formato JPG, JPEG, PNG y no debe superar los 2MB. <br>
                            </span>
                            @error('voucher')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="limpiar_pago">
                        Cerrar
                    </button>
                    <button type="button" wire:click="guardar_pago" class="btn btn-primary" style="width: 150px" @if ($voucher == null) wire:target="voucher" @endif wire:loading.attr="disabled" wire:target="guardar_pago">
                        <div wire:loading.remove wire:target="guardar_pago">
                            {{ $button_modal }}
                        </div>
                        <div wire:loading wire:target="guardar_pago">
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
        // filtro_canal_pago select2
        $(document).ready(function () {
            $('#filtro_canal_pago').select2({
                placeholder: 'Seleccione su canal de pago',
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
            $('#filtro_canal_pago').on('change', function(){
                @this.set('filtro_canal_pago', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#filtro_canal_pago').select2({
                    placeholder: 'Seleccione su canal de pago',
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
                $('#filtro_canal_pago').on('change', function(){
                    @this.set('filtro_canal_pago', this.value);
                });
            });
        });
    </script>
@endpush
