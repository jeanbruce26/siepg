<div class="">
    <div class="row g-5 gx-xl-10">
        <div class="col-md-8">
            <div class="row g-5 gx-xl-10">
                <div class="col-md-12">
                    <div class="card shadow-sm bg-light-success">
                        <div class="px-7 py-6">
                            <span class="fw-bolder fs-4">
                                Estimado/a postulante:
                            </span>
                            <p>
                                <ul class="fs-6">
                                    <li class="mb-3">
                                        Si usted realizó el pago por concepto de inscripción, deberá habilitar su comprobante de pago o voucher,
                                        <strong>dando click en el botón "REGISTRAR PAGO"</strong> ubicado en la parte inferior.
                                        Una vez que haya habilitado su voucher, podrá continuar con el proceso de inscripción mediante esta plataforma.
                                    </li>
                                    <li class="mb-3">
                                        Una vez que finalice el proceso, se generará su ficha de inscripción correspondiente.
                                    </li>
                                    <li class="mb-3">
                                        Cualquier incidencia o consulta, puede comunicarse a <strong>admision_posgrado@unu.edu.pe</strong>
                                    </li>
                                    <li class="mb-3">
                                        <strong>Proporciona datos fidedignos (auténticos).</strong> Recuerda que la información que proporciones sera derivada a la Oficina Central de Admisión
                                    </li>
                                    <li class="mb-3">
                                        <strong>Se muy cuidadoso al completar cada información solicidad por el Sistema de Inscripción.</strong> Ya que, la información proporcionada tiene caracter de Declaración Jurada.
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
                                        Fotocopia ampliada de DNI. En casos de postulantes extranjeros. Fotocopia legalizada de carnet de extranjería.
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
                <div class="col-md-12">
                    <div class="text-center mt-3">
                        <a href="#modal_registro_pago" wire:click="cargar_registro_pago" class="btn btn-success hover-scale w-50" data-bs-toggle="modal" data-bs-target="#modal_registro_pago">
                            REGISTRAR PAGO
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 g-5">
            <div class="row">
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
                                        <input type="text" wire:model="documento_identidad_inscripcion" class="form-control @error('documento_identidad_inscripcion') is-invalid @enderror" placeholder="12345678" id="documento_identidad"/>
                                        @error('documento_identidad_inscripcion')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-5">
                                        <label for="numero_operacion" class="required form-label">
                                            Numero de Operación
                                        </label>
                                        <input type="text" wire:model="numero_operacion_inscripcion" class="form-control @error('numero_operacion_inscripcion') is-invalid @enderror" placeholder="6543" id="numero_operacion"/>
                                        @error('numero_operacion_inscripcion')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if (session()->has('message'))
                                        <div class="mt-5">
                                            <!--begin::Alert-->
                                            <div class="alert alert-danger d-flex align-items-center p-5">
                                                <!--begin::Content-->
                                                <span class="fw-bold text-center">
                                                    {{ session('message') }}
                                                </span>
                                                <!--end::Content-->
                                            </div>
                                            <!--end::Alert-->
                                        </div>
                                    @endif
                                    <div class="mt-5">
                                        <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled" wire:target="iniciar_inscripcion">
                                            <div wire:loading.remove wire:target="iniciar_inscripcion">
                                                Iniciar Inscripción
                                            </div>
                                            <div wire:loading wire:target="iniciar_inscripcion" class="py-1">
                                                <span class="spinner-border spinner-border-sm align-middle"></span>
                                            </div>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" wire:click="limpiar_registro_pago">
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
                            <input type="number" wire:model="documento_identidad" class="form-control @error('documento_identidad') is-invalid @enderror" placeholder="12345678" id="documento_identidad"/>
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
                            <select class="form-select @error('canal_pago') is-invalid @enderror" wire:model="canal_pago" id="canal_pago" data-control="select2" data-placeholder="Seleccione su canal de pago" data-allow-clear="true" data-dropdown-parent="#modal_registro_pago">
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
                            <label for="voucher" class="required form-label">
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
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="limpiar_registro_pago">
                        Cerrar
                    </button>
                    <button type="button" wire:click="registrar_pago" class="btn btn-primary" style="width: 150px" @if ($voucher == null) disabled @endif wire:loading.attr="disabled" wire:target="registrar_pago">
                        <div wire:loading.remove wire:target="registrar_pago">
                            Registrar Pago
                        </div>
                        <div wire:loading wire:target="registrar_pago">
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
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
        $(document).ready(function () {
            $('#canal_pago').select2({
                placeholder: 'Seleccione',
                allowClear: true,
                width: '100%',
                selectOnClose: true,
                minimumResultsForSearch: Infinity,
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
                    placeholder: 'Seleccione',
                    allowClear: true,
                    width: '100%',
                    selectOnClose: true,
                    minimumResultsForSearch: Infinity,
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
    </script>
@endpush
