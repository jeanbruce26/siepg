<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="300px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div class="app-sidebar-logo px-8" id="kt_app_sidebar_logo">
        <a href="{{ route('plataforma.inicio') }}">
            <img alt="Logo" src="{{ asset('assets/media/logos/logo-largo-light.png') }}" height="42"
                class="app-sidebar-logo-default theme-light-show">
            <img alt="Logo" src="{{ asset('assets/media/logos/logo-largo-dark.png') }}" height="42"
                class="app-sidebar-logo-default theme-dark-show">
            <img alt="Logo" src="{{ asset('assets/media/logos/logo-pg.png') }}" height="30"
                class="app-sidebar-logo-minimize">
        </a>
        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize"
            wire:click="toggle_sidebar">
            <span class="svg-icon svg-icon-2 rotate-180">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.5"
                        d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                        fill="currentColor" />
                    <path
                        d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                        fill="currentColor" />
                </svg>
            </span>
        </div>
    </div>
    @if ($mostrar_perfil == true)
    <div class="aside-toolbar flex-column-auto px-8 border-bottom border-2" id="kt_aside_toolbar">
        <div class="aside-user d-flex align-items-sm-center justify-content-center py-8">
            <div class="symbol symbol-70px">
                @if ($usuario->usuario_estudiante_perfil_url)
                    <img src="{{ asset($usuario->usuario_estudiante_perfil_url) }}" alt="avatar" class="shadow-sm"/>
                @else
                    <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="avatar" class="shadow-sm"/>
                @endif
            </div>
            <div class="aside-user-info flex-row-fluid flex-wrap ms-5">
                <div class="d-flex">
                    <div class="flex-grow-1 me-2">
                        <span class="fs-4 text-gray-800 fw-bold">
                            @php $nombre = explode(' ', $persona->nombre); @endphp
                            {{ ucwords(strtolower($nombre[0])) }} {{ ucwords(strtolower($persona->apellido_paterno)) }}
                        </span>
                        <span class="text-gray-600 fw-semibold d-block fs-6 mb-1">
                            @if ($admitido)
                                {{ $admitido->admitido_codigo }}
                            @else
                                {{ $usuario->usuario_estudiante }}
                            @endif
                        </span>
                        <div class="d-flex align-items-center text-success fw-semibold mt-0 fs-7">
                            <span class="bullet bullet-dot bg-success me-2"></span>
                            en linea
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
            data-kt-scroll-save-state="true">
            <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu"
                data-kt-menu="true" data-kt-menu-expand="false">
                <div class="menu-item">
                    {{-- {{ request()->is('administrador') ? 'active' : '' }} -> sirve para poner activo el modulo --}}
                    <a class="menu-link {{ request()->routeIs('plataforma.inicio') ? 'active' : '' }}"
                        href="{{ route('plataforma.inicio') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-category fs-1">
                                <i class="path1"></i>
                                <i class="path2"></i>
                                <i class="path3"></i>
                                <i class="path4"></i>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold fs-4">Inicio</span>
                    </a>
                </div>
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('plataforma.admision') || request()->routeIs('plataforma.expediente') ? 'show active' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-abstract-26 fs-1">
                                <i class="path1"></i>
                                <i class="path2"></i>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold fs-4">Proceso de Admisión</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion menu-active-bg">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('plataforma.admision') ? 'active' : '' }}"
                                href="{{ route('plataforma.admision') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title fs-4">Admisión</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('plataforma.expediente') ? 'active' : '' }}"
                                href="{{ route('plataforma.expediente') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title fs-4">Expedientes</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-5">Menu</span>
                    </div>
                </div>
                @if ($constancia)
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('plataforma.constancia') ? 'active' : '' }}"
                            href="{{ route('plataforma.constancia') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-bookmark fs-1   ">
                                    <i class="path1"></i>
                                    <i class="path2"></i>
                                </i>
                            </span>
                            <span class="menu-title fw-semibold fs-4">Constancia de Ingreso</span>
                        </a>
                    </div>
                @endif
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->is('plataforma/estado-cuenta') || request()->routeIs('plataforma.pago') ? 'show active' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-office-bag fs-1">
                                <i class="path1"></i>
                                <i class="path2"></i>
                                <i class="path3"></i>
                                <i class="path4"></i>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold fs-4">Gestión de Pagos</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion menu-active-bg">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('plataforma.pago') ? 'active' : '' }}"
                                href="{{ route('plataforma.pago') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title fs-4">Pagos</span>
                            </a>
                        </div>
                        @if ($admitido)
                            <div class="menu-item">
                                <a class="menu-link {{ request()->is('plataforma/estado-cuenta') ? 'active' : '' }}"
                                    href="">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title fs-4">Estado de Cuenta</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
