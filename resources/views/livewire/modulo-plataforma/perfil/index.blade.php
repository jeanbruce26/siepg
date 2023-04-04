<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
        {{-- alerta para que el usuario sepa que datos puede modificar --}}
        <div class="alert bg-light-primary border border-primary d-flex alig-items-center p-5 mb-5">
            <span class="svg-icon svg-icon-2hx svg-icon-primary me-4">
                <i class="las la-exclamation-circle fs-2 text-primary"></i>
            </span>
            <div class="d-flex flex-column">
                <span class="fw-bold">
                    Nota: Solo se podrán modificar los datos de foto de perfil y contraseña
                </span>
            </div>
        </div>
        {{-- Card del perfil del usuario --}}
        <div class="card mb-5 mb-xl-10 shadow-sm">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-5">
                    <div class="me-10 mb-5">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            @if ($usuario->usuario_estudiante_perfil_url)
                            <img src="{{ asset($usuario->usuario_estudiante_perfil_url) }}" alt="perfil">
                            @else
                            <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="perfil">
                            @endif
                            <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-5">
                                    <a class="text-gray-900 fw-bold me-4" style="font-size: 2.3rem">
                                        {{ ucwords(strtolower($persona->nombre)) }} {{ ucwords(strtolower($persona->apellido_paterno)) }} {{ ucwords(strtolower($persona->apellido_materno)) }}
                                    </a>
                                    <span class="svg-icon svg-icon-1 svg-icon-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32px" height="32px" viewBox="0 0 24 24">
                                                <path d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z" fill="currentColor"></path>
                                                <path d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z" fill="white"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="d-flex flex-wrap fw-semibold fs-4 mb-2">
                                    <span class="d-flex align-items-center text-gray-600">
                                        Documento de Identidad:
                                    </span>
                                    <span class="d-flex align-items-center text-gray-800 ms-2 fw-bold">
                                        {{ $persona->numero_documento }}
                                    </span>
                                </div>
                                <div class="d-flex flex-wrap fw-semibold fs-4 mb-2">
                                    <span class="d-flex align-items-center text-gray-600">
                                        Proceso de Admisión:
                                    </span>
                                    <span class="d-flex align-items-center text-gray-800 ms-2 fw-bold">
                                        {{ ucwords(strtolower($inscripcion->programa_proceso->admision->admision)) }}
                                    </span>
                                </div>
                                <div class="d-flex flex-wrap fw-semibold fs-4 mb-2">
                                    <span class="d-flex align-items-center text-gray-600">
                                        Programa:
                                    </span>
                                    <span class="d-flex align-items-center text-gray-800 ms-2 fw-bold">
                                        @if ($admitido)
                                            @if ($admitido->programa_proceso->programa_plan->programa->mencion == null)
                                                {{ ucwords(strtolower($admitido->programa_proceso->programa_plan->programa->programa)) }} en {{ ucwords(strtolower($admitido->programa_proceso->programa_plan->programa->subprograma)) }}
                                            @else
                                                {{ ucwords(strtolower($admitido->programa_proceso->programa_plan->programa->programa)) }} en {{ ucwords(strtolower($admitido->programa_proceso->programa_plan->programa->subprograma)) }} con mención en {{ ucwords(strtolower($admitido->programa_proceso->programa_plan->programa->mencion)) }}
                                            @endif
                                        @else
                                            @if ($inscripcion->programa_proceso->programa_plan->programa->mencion == null)
                                                {{ ucwords(strtolower($inscripcion->programa_proceso->programa_plan->programa->programa)) }} en {{ ucwords(strtolower($inscripcion->programa_proceso->programa_plan->programa->subprograma)) }}
                                            @else
                                                {{ ucwords(strtolower($inscripcion->programa_proceso->programa_plan->programa->programa)) }} en {{ ucwords(strtolower($inscripcion->programa_proceso->programa_plan->programa->subprograma)) }} con mención en {{ ucwords(strtolower($inscripcion->programa_proceso->programa_plan->programa->mencion)) }}
                                            @endif
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex my-4">
                                <a href="#modal_perfil" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modal_perfil">
                                    Editar Perfil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- modal perfil --}}
            <div wire:ignore.self class="modal fade" tabindex="-1" id="modal_perfil">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">
                                Editar Perfil
                            </h3>
                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close" wire:click="limpiar_perfil">
                                <i class="bi bi-x fs-1"></i>
                            </div>
                        </div>
                        <div class="modal-body">
                            <form autocomplete="off">
                                <div class="mb-5">
                                    <label for="perfil" class="form-label">
                                        Foto de Perfil
                                    </label>
                                    <br>
                                    <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url({{ asset('assets/media/avatars/blank.png') }})">
                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url(@if($perfil) {{ asset($perfil->temporaryUrl()) }} @elseif($usuario->usuario_estudiante_perfil_url) {{ asset($usuario->usuario_estudiante_perfil_url) }} @else {{ asset('assets/media/avatars/blank.png') }} @endif)"></div>
                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change avatar" data-bs-original-title="Change avatar" data-kt-initialized="1">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <input type="file" wire:model="perfil" accept=".png, .jpg, .jpeg" id="upload{{ $iteration }}">
                                            <input type="hidden" name="avatar_remove">
                                        </label>
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar" data-bs-original-title="Cancel avatar" data-kt-initialized="1">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" wire:click="remove_avatar" data-bs-toggle="tooltip" aria-label="Remove avatar" data-bs-original-title="Remove avatar" data-kt-initialized="1">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                    </div>
                                    <div class="form-text">Formato de imagen:  png, jpg, jpeg.</div>
                                    @error('perfil')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-5">
                                    <label for="password" class="form-label">
                                        Nueva Contraseña
                                    </label>
                                    <input type="password" wire:model="password" class="form-control @error('password') is-invalid @enderror" placeholder="Ingrese su nueva contraseña" id="password"/>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-5">
                                    <label for="confirm_password" class="form-label">
                                        Confirmar Contraseña
                                    </label>
                                    <input type="password" wire:model="confirm_password" class="form-control @error('confirm_password') is-invalid @enderror" placeholder="Confirmar su nueva contraseña" id="confirm_password"/>
                                    @error('confirm_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal" wire:click="limpiar_perfil">
                                Cerrar
                            </button>
                            <button type="button" wire:click="actualizar_perfil" class="btn btn-primary" style="width: 160px" wire:loading.attr="disabled">
                                <div wire:loading.remove wire:target="actualizar_perfil">
                                    Actualizar Datos
                                </div>
                                <div wire:loading wire:target="actualizar_perfil">
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
