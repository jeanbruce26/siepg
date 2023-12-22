<div class="">
    <div class="row g-5 gx-xl-10">
        <div class="col-md-12">
            <div class="row g-5 gx-xl-10">
                <div class="col-md-12">
                    <div class="d-flex justify-content-end mt-3 gap-5">
                        <button type="button" class="btn btn-primary hover-elevate-up px-10" wire:click="ingresar">
                            REALIZAR INSCRIPCIÓN
                        </button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card shadow-sm bg-light-success">
                        <div class="px-7 py-6">
                            <span class="fw-bolder fs-4">
                                Estimado/a postulante:
                            </span>
                            <p>
                            <ul class="fs-6">
                                <li class="mb-3">
                                    Si usted realizó el pago por concepto de inscripción, deberá realizar el registro de
                                    su inscripción,
                                    <strong>dando click en el botón "REGISTRAR INSCRIPCIÓN"</strong> que se encuentra en
                                    la parte superior de esta página.
                                    Una vez que haya realizado el registro de su inscripción, deberá esperar a la
                                    validación de su inscripción.
                                </li>
                                <li class="mb-3">
                                    Una vez que finalice el proceso, se generará su ficha de inscripción
                                    correspondiente.
                                </li>
                                <li class="mb-3">
                                    Cualquier incidencia o consulta, puede comunicarse a
                                    <strong>admision_posgrado@unu.edu.pe</strong>
                                </li>
                                <li class="mb-3">
                                    <strong>Proporciona datos fidedignos (auténticos).</strong> Recuerda que la
                                    información que proporciones sera derivada a la Oficina Central de Admisión
                                </li>
                                <li class="mb-3">
                                    <strong>Se muy cuidadoso al completar cada información solicidad por el Sistema de
                                        Inscripción.</strong> Ya que, la información proporcionada tiene caracter de
                                    Declaración Jurada.
                                </li>
                            </ul>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card shadow-sm bg-light-warning">
                        <div class="px-7 py-6">
                            <span class="fw-bolder fs-4">
                                Recomendación antes de comenzar su inscripción:
                            </span>
                            <p>
                            <ul class="fs-6">
                                <li class="mb-3">
                                    Fotocopia ampliada de DNI. En casos de postulantes extranjeros. Fotocopia legalizada
                                    de carnet de extranjería.
                                </li>
                                <li class="mb-3">
                                    Constancia en línea otorgado por la SUNEDU del maximo grado Académico.
                                </li>
                                <li class="mb-3">
                                    Curriculum Vitae DOCUMENTADO. Ultimos 5 años.
                                </li>
                                <li class="mb-3">
                                    Tema tentativo del Proyecto de tesis (solo para postulantes al Doctorado).
                                </li>
                            </ul>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-4 g-5">
            <div class="row gap-5">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="px-7 py-6">
                            <div class="text-center d-flex flex-column">
                                <span class="fw-bolder fs-2 my-3">
                                    Iniciar Inscripción
                                </span>
                                <span class="fw-bolder fs-6 my-3 text-muted">
                                    Rellene los siguientes campos para iniciar su inscripción.
                                </span>
                            </div>
                            <div class="my-5">
                                <form autocomplete="off" wire:submit.prevent="iniciar_inscripcion">
                                    <div class="mb-5">
                                        <label for="documento_identidad" class="required form-label">
                                            Documento de Identidad
                                        </label>
                                        <input type="text" wire:model="documento_identidad_inscripcion"
                                            class="form-control @error('documento_identidad_inscripcion') is-invalid @enderror"
                                            placeholder="12345678" id="documento_identidad" />
                                        @error('documento_identidad_inscripcion')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-5">
                                        <label for="numero_operacion" class="required form-label">
                                            Numero de Operación
                                        </label>
                                        <input type="text" wire:model="numero_operacion_inscripcion"
                                            class="form-control @error('numero_operacion_inscripcion') is-invalid @enderror"
                                            placeholder="6543" id="numero_operacion" />
                                        @error('numero_operacion_inscripcion')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mt-5">
                                        <button type="submit" class="btn btn-primary w-100"
                                            wire:loading.attr="disabled" wire:target="iniciar_inscripcion">
                                            <div wire:loading.remove wire:target="iniciar_inscripcion">
                                                Iniciar Inscripción
                                            </div>
                                            <div wire:loading wire:target="iniciar_inscripcion">
                                                Procesando...
                                                <span class="spinner-border spinner-border-sm align-middle"></span>
                                            </div>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    @if (session()->has('message'))
                        <div class="alert bg-opacity-15 bg-danger border border-4 border-danger d-flex flex-center flex-column py-10 px-10">
                            <span class="svg-icon svg-icon-danger svg-icon-5x mb-5">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"/>
                                    <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor"/>
                                    <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor"/>
                                </svg>
                            </span>
                            <div class="text-center">
                                <h2 class="mb-5" style="font-weight: 700">
                                    {{ session('message') }}
                                </h2>
                                @if (session()->has('message2'))
                                    <div class="separator separator-dashed border-danger opacity-20 mb-5"></div>
                                    @if (session()->has('observacion'))
                                        <div class="mb-2 text-danger fs-6" style="font-weight: 700">
                                            Observación: {{ session('observacion') }}
                                        </div>
                                    @endif
                                    <div class="mb-9 text-dark fs-6 fw-semibold">
                                        {{ session('message2') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div> --}}
    </div>
    {{-- modal formas de pago --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_formas_pago">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Formas de Pago
                    </h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close" wire:click="limpiar_registro_pago">
                        <i class="bi bi-x fs-1"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <embed src="{{ asset('assets_pdf/manual-tipo-voucher.pdf') }}" class="rounded"
                        type="application/pdf" width="100%" height="700" />
                </div>
            </div>
        </div>
    </div>
    {{-- modal registro pago --}}
    <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_registro_pago">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Registro de Pago
                    </h3>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close" wire:click="limpiar_registro_pago">
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
                            <input type="number" wire:model="documento_identidad"
                                class="form-control @error('documento_identidad') is-invalid @enderror"
                                placeholder="12345678" id="documento_identidad" />
                            @error('documento_identidad')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="numero_operacion" class="required form-label">
                                Numero de Operación
                            </label>
                            <input type="number" wire:model="numero_operacion"
                                class="form-control @error('numero_operacion') is-invalid @enderror" placeholder="6543"
                                id="numero_operacion" />
                            <span class="form-text text-muted mt-2 fst-italic">
                                Nota: Omitir los ceros a la izquierda. Ejemplo: 00001265, debe ser ingresado como 1265.
                                <br>
                            </span>
                            @error('numero_operacion')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="monto_operacion" class="required form-label">
                                Monto de Operación
                            </label>
                            <input type="number" wire:model="monto_operacion"
                                class="form-control @error('monto_operacion') is-invalid @enderror" placeholder="00.00"
                                id="monto_operacion" />
                            @error('monto_operacion')
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
                                wire:model="canal_pago" id="canal_pago" data-control="select2"
                                data-placeholder="Seleccione su canal de pago" data-allow-clear="true"
                                data-hide-search="true" data-dropdown-parent="#modal_registro_pago">
                                <option></option>
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
                            <label for="voucher" class="required form-label">
                                Voucher
                            </label>
                            <input type="file" wire:model="voucher"
                                class="form-control @error('voucher') is-invalid @enderror"
                                id="upload{{ $iteration }}" accept="image/jpeg, image/png, image/jpg" />
                            <span class="form-text text-muted mt-2 fst-italic">
                                Nota: El voucher debe ser imagen en formato JPG, JPEG, PNG y no debe superar los 2MB.
                                <br>
                            </span>
                            @error('voucher')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                        wire:click="limpiar_registro_pago">
                        Cerrar
                    </button>
                    <button type="button" wire:click="registrar_pago" class="btn btn-primary" style="width: 150px"
                        @if ($voucher == null) disabled @endif wire:loading.attr="disabled"
                        wire:target="registrar_pago">
                        <div wire:loading.remove wire:target="registrar_pago, voucher">
                            Registrar Pago
                        </div>
                        <div wire:loading wire:target="registrar_pago">
                            Procesando...
                        </div>
                        <div wire:loading wire:target="voucher">
                            Cargando...
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        // canal_pago select2
        $(document).ready(function() {
            $('#canal_pago').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                // minimumResultsForSearch: Infinity,
                language: {
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando..";
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
                    // minimumResultsForSearch: Infinity,
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        },
                        searching: function() {
                            return "Buscando..";
                        }
                    }
                });
                $('#canal_pago').on('change', function() {
                    @this.set('canal_pago', this.value);
                });
            });
        });
    </script>

    <script>
        toastr.options = {
            "closeButton": true,
            "debug": true,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toastr-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        window.addEventListener('toast-basico', event => {
            if (event.detail.type == 'success')
                toastr.success(event.detail.message, event.detail.title);
            else if (event.detail.type == 'error')
                toastr.error(event.detail.message, event.detail.title);
            else if (event.detail.type == 'warning')
                toastr.warning(event.detail.message, event.detail.title);
            else if (event.detail.type == 'info')
                toastr.info(event.detail.message, event.detail.title);
            else
                toastr.info(event.detail.message, event.detail.title);
        })
    </script>
@endpush
