<div class="card rounded-0 w-100">
    <div class="card-header pe-5">
        <div class="card-title fw-bold">
            Notificaciones
        </div>
        <div class="card-toolbar">
            <div class="btn btn-sm btn-icon btn-active-light-danger" id="modulo_notioficaciones_close">
                <span class="svg-icon svg-icon-2x">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor"/>
                    <rect x="7" y="15.3137" width="12" height="2" rx="1" transform="rotate(-45 7 15.3137)" fill="currentColor"/>
                    <rect x="8.41422" y="7" width="12" height="2" rx="1" transform="rotate(45 8.41422 7)" fill="currentColor"/>
                    </svg>
                </span>
            </div>
        </div>
    </div>
    <div class="card-body hover-scroll-overlay-y">
        @if ($observaciones->count() > 0)
            @foreach ($observaciones as $item2)
                <a href="{{ route('plataforma.pago') }}">
                    <div class="bg-opacity-15 bg-info d-flex flex-column shadow flex-sm-row p-5 mb-5 hover-scale rounded-3">
                        <div class="d-flex flex-column w-100">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="fs-5 fw-bold text-dark">
                                    Observación de Pago
                                </div>
                                <div class="text-muted fs-6">
                                    {{ Carbon\Carbon::parse($item2->pago_observacion_creacion)->diffForHumans() }}
                                </div>
                            </div>
                            <span class="text-dark">
                                -> {{ $item2->pago_observacion }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        @else
            <div class="alert bg-light-secondary d-flex flex-column shadow flex-sm-row p-5 mb-5 hover-scale">
                <div class="d-flex flex-column pe-0 pe-sm-10">
                    <span class="text-muted fs-5">
                        No hay notificaciones
                    </span>
                </div>
            </div>
        @endif
    </div>
</div>
