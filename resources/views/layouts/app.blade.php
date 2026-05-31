<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SGNI - UABC FIAD</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div class="d-flex flex-column flex-lg-row min-vh-100">

        <div class="d-flex d-lg-none bg-primary text-white p-3 justify-content-between align-items-center shadow">
            <div class="d-flex align-items-center">
                <div class="bg-secondary text-dark fw-bold rounded p-1 me-2" style="font-size: 0.8rem;">UF</div>
                <span class="fw-bold">SGNI - FIAD</span>
            </div>
            <button class="btn text-white border-white" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMenu">
                <i class="bi bi-list fs-3"></i>
            </button>
        </div>

        <div class="offcanvas-lg offcanvas-start bg-primary text-white flex-column flex-shrink-0 p-3" tabindex="-1"
            id="sidebarMenu" style="width: 280px; min-height: 100vh;">

            <div class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <div class="bg-secondary text-dark fw-bold rounded p-2 me-2">UF</div>
                <div>
                    <span class="fs-5 fw-bold d-block lh-1">UABC FIAD</span>
                    <small class="text-white-50" style="font-size: 0.75rem;">Sistema de Gestión</small>
                </div>
                <button type="button" class="btn-close btn-close-white d-lg-none ms-auto" data-bs-dismiss="offcanvas"
                    data-bs-target="#sidebarMenu"></button>
            </div>

            <hr>

            <ul class="nav nav-pills flex-column mb-auto">
                @auth
                <li class="nav-item mb-1 dropdown dropend">
                    <a href="#"
                        class="nav-link dropdown-toggle {{ request()->routeIs('asistencia.*') ? 'text-dark bg-secondary fw-bold shadow-sm' : 'text-white' }} d-flex justify-content-between align-items-center"
                        data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                        <span>Registro de Asistencia</span>
                    </a>

                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2 ms-2" style="min-width: 250px;">
                        <li>
                            <a class="dropdown-item fw-bold text-dark py-2 mb-1 rounded-2"
                                href="{{ route('asistencia.paselista') }}">
                                Pase de Lista
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-bold text-dark py-2 rounded-2"
                                href="{{ route('asistencia.grupal') }}">
                                Mostrar Grupo con Asistencias
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item mb-1 dropdown dropend">
                    <a href="#"
                        class="nav-link dropdown-toggle {{ request()->routeIs('calificaciones.*') ? 'text-dark bg-secondary fw-bold shadow-sm' : 'text-white' }} d-flex justify-content-between align-items-center"
                        data-bs-toggle="dropdown"
                        aria-expanded="{{ request()->routeIs('calificaciones.*') ? 'true' : 'false' }}"
                        style="cursor: pointer;">
                        <span>Captura de Calificaciones</span>
                    </a>

                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2 ms-2" style="min-width: 250px;">
                        <li>
                            <a class="dropdown-item fw-bold {{ request()->routeIs('calificaciones.captura') ? 'text-primary bg-light' : 'text-dark' }} py-2 mb-1 rounded-2"
                                href="{{ route('calificaciones.captura') }}">
                                Captura
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-bold {{ request()->routeIs('calificaciones.mostrar') ? 'text-primary bg-light' : 'text-dark' }} py-2 rounded-2"
                                href="{{ route('calificaciones.mostrar') }}">
                                Mostrar Calificaciones Capturadas
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('psicologo') }}"
                        class="nav-link {{ request()->routeIs('psicologo') ? 'text-dark bg-secondary fw-bold shadow-sm' : 'text-white' }}">
                        Panel Psicologico
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('alumnos.info') }}"
                        class="nav-link {{ request()->routeIs('alumnos.info') ? 'text-dark bg-secondary fw-bold shadow-sm' : 'text-white' }}">
                        Información Estudiantil
                    </a>
                </li>

                <!-- --- VISTAS DEL ADMINISTRADOR --- -->
                @if(Auth::user()->rol->nombre_rol === 'Administrador')
                <li class="nav-item mb-1 dropdown dropend">
                    <a href="#"
                        class="nav-link dropdown-toggle {{ request()->routeIs('grupos.*') || request()->routeIs('crear_grupo') ? 'text-dark bg-secondary fw-bold shadow-sm' : 'text-white' }} d-flex justify-content-between align-items-center"
                        data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                        <span>Crear Grupos</span>
                    </a>

                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2 ms-2" style="min-width: 250px;">
                <li>
                    <a class="dropdown-item fw-bold {{ request()->routeIs('crear_grupo') ? 'text-primary bg-light' : 'text-dark' }} py-2 mb-1 rounded-2"
                        href="{{ route('crear_grupo') }}">
                        Crear Grupo
                    </a>
                </li>
                <li>
                    <a class="dropdown-item fw-bold {{ request()->routeIs('grupos.generados') ? 'text-primary bg-light' : 'text-dark' }} py-2 rounded-2"
                        href="{{ route('curso_prope_creado') }}">
                        Grupos Generados
                    </a>
                </li>
            </ul>

                <li class="nav-item mb-1">
                    <a href="{{ route('alumnos.nuevo') }}"
                        class="nav-link {{ request()->routeIs('alumnos.nuevo') ? 'text-dark bg-secondary fw-bold shadow-sm' : 'text-white' }}">
                        Alta de Alumnos
                    </a>
                </li>

                <li class="nav-item mb-1 dropdown dropend">
                    <a href="#"
                        class="nav-link dropdown-toggle {{ request()->routeIs('grupos.*') ? 'text-dark bg-secondary fw-bold shadow-sm' : 'text-white' }} d-flex justify-content-between align-items-center"
                        data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                        <span>Cierre y Grupos Finales</span>
                    </a>

                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2 ms-2" style="min-width: 260px;">
                        <li>
                            <a class="dropdown-item fw-bold {{ request()->routeIs('grupos.criterios') ? 'text-primary bg-light' : 'text-dark' }} py-2 mb-1 rounded-2"
                                href="{{ route('grupos_final.criterios') }}">
                                Criterios y Creación
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-bold {{ request()->routeIs('grupos.finales') ? 'text-primary bg-light' : 'text-dark' }} py-2 rounded-2"
                                href="{{ route('grupos_final.grupos_finales') }}">
                                Grupos Finales
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-bold {{ request()->routeIs('grupos_final.modo_lectura') ? 'text-primary bg-light' : 'text-dark' }} py-2 rounded-2"
                                href="{{ route('grupos_final.modo_lectura') }}">
                                Modo Lectura
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <hr>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <!-- Mostramos el nombre guardado en la sesión -->
                    <strong>{{ Auth::user()->nombre }} {{ Auth::user()->ap_pat }} {{ Auth::user()->ap_mat }}</strong>
                </a>

                <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                    <li><span class="dropdown-item-text text-white-50" style="font-size: 0.85rem;">
                            {{ Auth::user()->correo_institucional }}
                        </span></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger fw-bold">
                                Cerrar sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @endauth
        </div>

        <main class="flex-grow-1 p-4 bg-light overflow-auto">
            @yield('contenido')
        </main>

    </div>
</body>

</html>
