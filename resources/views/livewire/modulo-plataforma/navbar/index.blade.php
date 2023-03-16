<div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
    <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
        @if ($usuario->usuario_estudiante_perfil)
        <img src="{{ asset($usuario->usuario_estudiante_perfil) }}" alt="avatar" />
        @else
        <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="avatar" />
        @endif
    </div>
    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
        <div class="menu-item px-3">
            <div class="menu-content d-flex align-items-center px-3">
                <div class="symbol symbol-50px me-5">
                    @if ($usuario->usuario_estudiante_perfil)
                    <img src="{{ asset($usuario->usuario_estudiante_perfil) }}" alt="avatar" />
                    @else
                    <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="avatar" />
                    @endif
                </div>
                <div class="d-flex flex-column">
                    <div class="fw-bold d-flex align-items-center fs-5">
                        {{ ucwords(strtolower($persona->nombres)) }} {{ ucwords(strtolower($persona->apell_pater)) }} {{ ucwords(strtolower($persona->apell_mater)) }}
                        {{-- <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span> --}}
                    </div>
                    <span style="cursor: pointer;" class="fw-semibold text-muted text-hover-primary fs-7">{{ $usuario->usuario_estudiante }}</span>
                </div>
            </div>
        </div>
        <div class="separator my-2"></div>
        <div class="menu-item px-5">
            <a href="{{ route('plataforma.perfil') }}" class="menu-link px-5">
                Mi perfil
            </a>
        </div>
        <div class="separator my-2"></div>
        <div class="menu-item px-5">
            <a style="cursor: pointer;" wire:click="cerrar_sesion" class="menu-link px-5">
                Cerrar sesión
            </a>
        </div>
    </div>
</div>
