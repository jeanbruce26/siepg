<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Constancia de Ingreso
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('plataforma.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        Constancia de Ingreso
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    @if ($verificacion_pago == true)
                        {{-- modulo para generar constancia de ingreso --}}
                        <div class="row">
                            <div class="col-md-5">
                                <div class="card card-body shadow-sm">
                                    <div class="bg-light-info px-10 py-5 rounded-4 mx-auto mb-5">
                                        <i class="ki-duotone ki-document fs-5x text-info">
                                            <i class="path1"></i>
                                            <i class="path2"></i>
                                        </i>
                                    </div>
                                    <h2 class="card-title mb-5 text-center">
                                        Constancia de Ingreso
                                    </h2>
                                    @if ($constancia->constancia_ingreso_url)
                                        <a download="constancia-de-ingreso" href="{{ asset($constancia->constancia_ingreso_url) }}" class="btn btn-info">
                                            Descargar Constancia de Ingreso
                                        </a>
                                    @else
                                        <button wire:click="alerta_generar_constancia" type="button" class="btn btn-info">
                                            Generar Constancia de Ingreso
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-7">
                                @if ($constancia->constancia_ingreso_url)
                                    <embed src="{{ asset($constancia->constancia_ingreso_url) }}" class="rounded" type="application/pdf" width="100%" height="600px" />
                                @else
                                    <div class="card card-body shadow-sm bg-light-info border border-3 border-info d-flex flex-column justify-content-center align-items-center" style="height: 500px">
                                        <div class="bg-body px-10 py-5 rounded-4 mx-auto mb-5">
                                            <i class="ki-duotone ki-information-3 fs-5x text-info">
                                                <i class="path1"></i>
                                                <i class="path2"></i>
                                                <i class="path3"></i>
                                            </i>
                                        </div>
                                        <h4 class="card-title mb-5 text-center">
                                            Genere su Constancia de Ingreso para que le apareza el PDF aqui.
                                        </h4>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        {{-- alerta --}}
                        <div class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                            <i class="ki-duotone ki-information-5 fs-2qx me-4 text-primary">
                                <i class="path1"></i>
                                <i class="path2"></i>
                                <i class="path3"></i>
                            </i>
                            <div class="d-flex flex-column">
                                <span class="fw-bold fs-5">
                                    ¡Atención!
                                </span>
                                <span class="fw-bold fs-6">
                                    Le recordamos que en caso de ser rechazado el Pago de su Constancia de Ingreso, deberá volver a realizar el pago para poder reemplazar el comprobante de pago registrado anteriormente.
                                </span>
                            </div>
                        </div>
                        {{-- alerta de verificacion de pago --}}
                        <div class="card card-body shadow-sm bg-light-info border border-3 border-info d-flex flex-column justify-content-center align-items-center" style="height: 400px">
                            <div class="bg-body px-10 py-5 rounded-4 mx-auto mb-5">
                                <i class="ki-duotone ki-information-3 fs-5x text-info">
                                    <i class="path1"></i>
                                    <i class="path2"></i>
                                    <i class="path3"></i>
                                </i>
                            </div>
                            <h3 class="card-title mb-5 text-center">
                                Su Pago por Concepto de Constancia de Ingreso aún no ha sido verificado. <br>Espere a que sea verificado para poder generar su constancia de ingreso.
                            </h3>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- modal registro pago --}}
    {{-- <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_pago_plataforma">
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
                                <option value="{{ $item->id_concepto_pago }}" @if($item->id_concepto_pago == 1) disabled @endif>
                                    Concepto por {{ $item->concepto_pago }} - S/. {{ number_format($item->concepto_pago_monto, 2, ',', '.') }}
                                </option>
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
                        @if ($modo == 'create')
                            <div class="col-md-12">
                                <div class="form-check mt-2">
                                    <input class="form-check-input @error('terminos_condiciones_pagos') is-invalid @enderror" type="checkbox" wire:model="terminos_condiciones_pagos" id="terminos_condiciones_pagos" />
                                    <label class="form-check-label text-dark fw-bold" for="terminos_condiciones_pagos">
                                        El registro de pago estará sujeto a revisión por el Área Contable, cualquier inconveniente será reportado. <br>
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
                    <button type="button" wire:click="alerta_guardar_pago" class="btn btn-primary" style="width: 150px" @if ($voucher == null) wire:target="voucher" @endif wire:loading.attr="disabled" wire:target="alerta_guardar_pago">
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
    </div> --}}
</div>
@push('scripts')
    {{-- <script>
        // canal_pago select2
        $(document).ready(function () {
            $('#canal_pago').select2({
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
            $('#canal_pago').on('change', function(){
                @this.set('canal_pago', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#canal_pago').select2({
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
                $('#canal_pago').on('change', function(){
                    @this.set('canal_pago', this.value);
                });
            });
        });
        // concepto_pago select2
        $(document).ready(function () {
            $('#concepto_pago').select2({
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
            $('#concepto_pago').on('change', function(){
                @this.set('concepto_pago', this.value);
            });
            Livewire.hook('message.processed', (message, component) => {
                $('#concepto_pago').select2({
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
                $('#concepto_pago').on('change', function(){
                    @this.set('concepto_pago', this.value);
                });
            });
        });
    </script> --}}
@endpush
