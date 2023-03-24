<div>
    
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Trabajador
                    </h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('administrador.dashboard') }}" class="text-muted text-hover-primary">
                                Dashboard
                            </a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Trabajador</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <!--begin::Primary button-->
                    <a href="#" class="btn btn-primary btn-sm hover-elevate-up">Nuevo</a>
                    <!--end::Primary button-->
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-fluid pt-5">

                <!--begin::Row-->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-5">
                            <div class="d-flex justify-content-between align-items-center gap-4">
                                <div class="text-muted d-flex align-items-center">
                                    <label class="col-form-label me-2">Mostrar</label>
                                    <select class="form-select form-select-sm text-muted" wire:model="mostrar"
                                        aria-label="Default select example">
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                                <div class="text-muted d-flex align-items-center">
                                    <label class="col-form-label me-2">Tipo</label>
                                    <select class="form-select form-select-sm text-muted" wire:model="tipo"
                                        aria-label="Default select example">
                                        <option value="all" selected>Mostrar todos</option>
                                        @foreach ($tipo_trabajadores as $item)
                                            <option value="{{ $item->tipo_trabajador_id }}">{{ $item->tipo_trabajador }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="w-25">
                                <input class="form-control form-control-sm text-muted" type="search" wire:model="search"
                                    placeholder="Buscar...">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-rounded border gy-4 gs-4 mb-0 align-middle">
                                <thead class="bg-light-primary">
                                    <tr align="center" class="fw-bold fs-5">
                                        <th scope="col" class="col-md-1">ID</th>
                                        <th scope="col" class="col-md-1">Documento</th>
                                        <th scope="col" class="col-md-3">Nombres</th>
                                        <th scope="col" class="col-md-1">Grado</th>
                                        <th scope="col" class="col-md-2">Correo</th>
                                        <th scope="col" class="col-md-2">Tipo</th>
                                        <th scope="col" class="col-md-1">Estado</th>
                                        <th scope="col" class="col-md-1">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $num = 1;
                                    @endphp
                                    @foreach ($trabajadores as $item)
                                        <tr>
                                            <td align="center"  class="fs-5">
                                                @if ($num < 10)
                                                    <strong>0{{ $num }}</strong>
                                                @else
                                                    <strong>{{ $num }}</strong>
                                                @endif
                                            </td>
                                            <td align="center">{{ $item->trabajador_numero_documento }}</td>
                                            <td>
                                                <div class="d-flex justify-conten-star align-items-center">
                                                    <div class="flex-shirnk-0">
                                                        @if ($item->trabajador_perfil)
                                                            <img class="rounded-circle avatar-xs"
                                                                src="{{ asset($item->trabajador_perfil) }}"
                                                                alt="perfil Avatar">
                                                        @else
                                                            <img class="rounded-circle avatar-xs"
                                                                src="{{ asset('assets/images/avatar.png') }}"
                                                                alt="perfil Avatar">
                                                        @endif
                                                    </div>
                                                    <div class="ms-2">
                                                        {{ $item->trabajador_nombres }} {{ $item->trabajador_apellidos }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td align="center">{{ $item->trabajador_grado }}</td>
                                            <td>{{ $item->trabajador_correo }}</td>
                                            @php
                                                $tra_tipo_tra = App\Models\TrabajadorTipoTrabajador::where('trabajador_id', $item->trabajador_id)->where('trabajador_tipo_trabajador_estado',1)->get();
                                            @endphp
                                            <td align="center" class="">
                                                @php
                                                    $coordinador_unidad = App\Models\Coordinador::where('trabajador_id', $item->trabajador_id)->first();
                                                    $administrativo = App\Models\Administrativo::where('trabajador_id', $item->trabajador_id)->first();
                                                @endphp
                                                @if ($tra_tipo_tra)
                                                    @if ($tra_tipo_tra->count() == 1)
                                                        <ul style="list-style: none; padding: 0; margin: 0;">
                                                        @foreach ($tra_tipo_tra as $item2)
                                                            @if ($item2->tipo_trabajador_id == 1)
                                                                Docente
                                                            @endif
                                                            @if ($item2->tipo_trabajador_id == 2)
                                                                Coordinador de Unidad
                                                                @if ($coordinador_unidad)
                                                                    <li style="font-size: 12px; color: #414141a6;">({{ $coordinador_unidad->facultad->facultad }})</li>
                                                                @endif
                                                            @endif
                                                            @if ($item2->tipo_trabajador_id == 3)
                                                                Administrativo
                                                                @if ($administrativo)
                                                                    <li style="font-size: 12px; color: #000000a6;">({{ $administrativo->area_administrativo->area }})</li>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                        </ul>
                                                    @else
                                                        <ul style="list-style: none; padding: 0; margin: 0;">
                                                        @foreach ($tra_tipo_tra as $item2)
                                                            @if ($item2->tipo_trabajador_id == 1)
                                                                <li>Docente</li> 
                                                            @endif
                                                            @if ($item2->tipo_trabajador_id == 2)
                                                                <li>Coordinador de Unidad</li> 
                                                                @if ($coordinador_unidad)
                                                                    <li style="font-size: 12px; color: #000000a6;">({{ $coordinador_unidad->facultad->facultad }})</li>
                                                                @endif
                                                            @endif
                                                            @if ($item2->tipo_trabajador_id == 3)
                                                                <li>Administrativo</li> 
                                                                @if ($administrativo)
                                                                    <li style="font-size: 12px; color: #000000a6;">({{ $administrativo->AreaAdministrativo->area }})</li>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                        </ul>
                                                    @endif
                                                @endif
                                                @if($tra_tipo_tra->count() == 0)
                                                    No asignado
                                                @endif
                                            </td>
                                            <td align="center">
                                                @if ($item->trabajador_estado == 1)
                                                    <span style="cursor: pointer;"
                                                        wire:click="cargarAlerta({{ $item->trabajador_id }})"
                                                        class="badge text-bg-primary">Activo</span>
                                                @else
                                                    <span style="cursor: pointer;"
                                                        wire:click="cargarAlerta({{ $item->trabajador_id }})"
                                                        class="badge text-bg-danger">Inactivo</span>
                                                @endif
                                            </td>
                                            <td align="center">
                                                <div class="hstack gap-3 flex-wrap justify-content-center">
                                                    <a href="#modalTra"
                                                        wire:click="cargarTrabajador({{ $item->trabajador_id }})"
                                                        class="link-success fs-16" data-bs-toggle="modal"
                                                        data-bs-target="#modalTra"><i class="ri-edit-2-line"></i></a>
                                                    @if ($item->trabajador_estado == 1)
                                                    <a href="#modalAsignar"
                                                        wire:click="cargarTrabajadorId({{ $item->trabajador_id }},1)"
                                                        class="link-info fs-16"data-bs-toggle="modal"
                                                        data-bs-target="#modalAsignar"><i class="ri-user-add-line"></i></a>
                                                        @if ($tra_tipo_tra->count() != 0)
                                                        <a href="#modaldDesAsignar"
                                                        wire:click="cargarTrabajadorId({{ $item->trabajador_id }},2)"
                                                        class="link-danger fs-16"data-bs-toggle="modal"
                                                        data-bs-target="#modaldDesAsignar"><i class="ri-user-unfollow-line
                                                        "></i></a>
                                                        @endif
                                                    @endif
                                                    <a href="#modalInfo"
                                                        wire:click="cargarInfoTrabajador({{ $item->trabajador_id }})"
                                                        class="link-warning fs-16"data-bs-toggle="modal"
                                                        data-bs-target="#modalInfo"><i class="ri-information-line"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        @php
                                            $num++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($trabajadores->count())
                                <div class="mt-2 d-flex justify-content-end text-muted">
                                    {{ $trabajadores->links() }}
                                </div>
                            @else
                                <div class="text-center p-3 text-muted">
                                    <span>No hay resultados para la busqueda "<strong>{{ $search }}</strong>" en la
                                        pagina <strong>{{ $page }}</strong> al mostrar
                                        <strong>{{ $mostrar }}</strong> por pagina</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!--end::Row-->

            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->

</div>
