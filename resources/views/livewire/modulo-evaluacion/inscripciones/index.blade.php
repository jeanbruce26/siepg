<div>
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                    Programas a Evaluar
                </h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('coordinador.inicio') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    {{-- <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('coordinador.inicio') }}" class="text-muted text-hover-primary">Evaluaciones</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Programas</li> --}}
                </ul>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">
            <div class="row mb-5 mb-xl-10">
                <div class="col-md-12 mb-md-5 mb-xl-10">
                    {{-- alerta --}}
                    <div class="alert bg-light-primary border border-3 border-primary d-flex align-items-center p-5 mb-5">
                        <i class="ki-outline ki-information-5 fs-2qx me-4 text-primary"></i>
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-5">
                                Los datos mostrados en esta sección son referentes a la evaluación de los postulantes por proceso de admisión.
                            </span>
                        </div>
                    </div>
                    {{-- card monto de pagos --}}
                    <div class="row g-5 mb-5">
                        @foreach ($evaluaciones as $item)
                            @php
                                $inscritos = App\Models\Inscripcion::join('programa_proceso', 'programa_proceso.id_programa_proceso', '=', 'inscripcion.id_programa_proceso')
                                    ->join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                                    ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                                    ->where('programa_proceso.id_programa_proceso', $item->id_programa_proceso)
                                    ->where('inscripcion.inscripcion_estado', 1)
                                    ->count();
                                $programa_proceso = App\Models\ProgramaProceso::join('programa_plan', 'programa_plan.id_programa_plan', '=', 'programa_proceso.id_programa_plan')
                                    ->join('programa', 'programa.id_programa', '=', 'programa_plan.id_programa')
                                    ->where('programa_proceso.id_programa_proceso', $item->id_programa_proceso)
                                    ->first();
                                $programa = App\Models\Programa::find($programa_proceso->id_programa);
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="card shadow-sm" style="height: 270px">
                                    <div class="h-100 px-6 py-4 d-flex flex-column justify-content-center aling-items-between">
                                        <div class="mb-5 text-center">
                                            <span class="fs-2 text-gray-800 fw-bold">
                                                @if ($programa->mencion)
                                                    MENCION EN {{ $programa->mencion }}
                                                @else
                                                    {{ $programa->programa }} EN {{ $programa->subprograma }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="mb-5 text-center">
                                            <span class="fs-1" style="font-weight: 700;">
                                                {{ $inscritos }}
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column row-gap-5">
                                            <a href="{{ route('evaluacion.inscripciones', ['id_programa_proceso' => $item->id_programa_proceso]) }}" class="btn btn-info w-100">
                                                Ir a Evaluación
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
