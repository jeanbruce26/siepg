<div class="row g-5 gx-xl-10">
    <div class="col-md-12">
        <div class="row g-5 gx-xl-10">
            <div class="col-md-6 m-auto">
                <div id="slider" class="carousel carousel-custom slide" data-bs-ride="carousel"
                    data-bs-interval="5000">
                    <div class="carousel-inner pt-8">
                        <div class="carousel-item active">
                            <img src="https://plus.unsplash.com/premium_photo-1668383208760-928282c112a5?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                                alt="carousel" class="w-100 rounded" />
                        </div>
                        <div class="carousel-item">
                            <img src="https://images.unsplash.com/photo-1698434156107-17486c312c50?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                                alt="carousel" class="w-100 rounded" />
                        </div>
                        <div class="carousel-item">
                            <img src="https://plus.unsplash.com/premium_photo-1698527167498-3d5ec4cc9424?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                                alt="carousel" class="w-100 rounded" />
                        </div>
                        <div class="carousel-item">
                            <img src="https://plus.unsplash.com/premium_photo-1677529498544-8d4ba36f73cf?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                                alt="carousel" class="w-100 rounded" />
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center flex-wrap">
                        <ol
                            class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-primary">
                            <li data-bs-target="#slider" data-bs-slide-to="0" class="ms-1 active">
                            </li>
                            <li data-bs-target="#slider" data-bs-slide-to="1" class="ms-1"></li>
                            <li data-bs-target="#slider" data-bs-slide-to="2" class="ms-1"></li>
                            <li data-bs-target="#slider" data-bs-slide-to="3" class="ms-1"></li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row g-5">
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
                                        Si usted realizó el pago por concepto de inscripción, deberá realizar el
                                        registro de
                                        su inscripción,
                                        <strong>dando click en el botón "REGISTRAR INSCRIPCIÓN"</strong> que se
                                        encuentra en
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
                                        <strong>Se muy cuidadoso al completar cada información solicidad por el Sistema
                                            de
                                            Inscripción.</strong> Ya que, la información proporcionada tiene caracter de
                                        Declaración Jurada.
                                    </li>
                                </ul>
                                </p>
                            </div>
                        </div>
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
</div>
@push('scripts')
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
