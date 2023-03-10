<div>
    <div class="row g-5 gx-xl-10">
        {{-- <div class="col-xl-12">
            <div class="card shadow-sm">
                <div class="px-6 py-5">
                    <span class="fw-bolder fs-3">
                        Inicio
                    </span>
                </div>
            </div>
        </div> --}}
        <div class="col-xl-6">
            <div class="card shadow-sm" style="background-color: #ffffeb">
                <div class="px-6 py-5">
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
                        </ul>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card shadow-sm" style="background-color: #f1fcf0">
                <div class="px-6 py-5">
                    <span class="fw-bolder fs-4">
                        Recomendación antes de comenzar su inscripción:
                    </span>
                    <p>
                        <ul class="fs-6">
                            <li class="mb-3">
                                Puedes realizar tu inscripción al día siguiente de haber realizado tu pago.
                            </li>
                            <li class="mb-3">
                                <strong>Ten a mano tu Documento de Identidad.</strong> <br>
                                La información solicitada debe ser escrita tal cual este en el.
                            </li>
                            <li class="mb-3">
                                <strong>Proporciona datos fidedignos (auténticos).</strong> <br>
                                Recuerda que la información que proporciones sera derivada a la <strong>Oficina Central de Admisión</strong>
                            </li>
                            <li class="mb-3">
                                <strong>Se muy cuidadoso al completar cada información solicidad por el Sistema de Inscripción.</strong> <br>
                                Ya que, la información proporcionada tiene caracter de <strong>Declaración Jurada.</strong>
                            </li>
                        </ul>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <div class="card shadow-sm" style="background-color: #f4fdfd">
                <div class="px-6 py-5">
                    <span class="fw-bolder fs-4">
                        Requisitos para realizar su inscripción:
                    </span>
                    <p>
                        <ul class="fs-6">
                            @foreach ($expedientes as $item)
                            <li class="mb-3">
                                <strong>{{$item->tipo_doc}} {{$item->complemento}} @if($item->expediente_tipo == 1) (para Maestria). @elseif ($item->expediente_tipo == 2) (para Doctorado). @endif</strong>
                            </li>
                            @endforeach
                            <li class="mb-3">
                                <strong>Todo formato subido a la plataforma, deberá ser en PDF.</strong>
                            </li>
                        </ul>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="text-center mt-3">
                <a href="#" class="btn btn-success hover-scale w-50">
                    REGISTRAR PAGO
                </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="text-center mt-3">
                <a href="#" class="btn btn-primary hover-scale w-50">
                    INICIAR INSCRIPCIÓN
                </a>
            </div>
        </div>
    </div>
</div>
