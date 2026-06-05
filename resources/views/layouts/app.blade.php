<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SGNI - UABC FIAD</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <style>
        /* Animación de despliege (rotación) */
        .nav-link .toggle-icon {
            transition: transform 0.2s ease-in-out;
            transform: rotate(0deg);
        }

        .nav-link[aria-expanded="true"] .toggle-icon {
            transform: rotate(-180deg);
        }
    </style>

</head>

<body>
    <div class="d-flex flex-column flex-lg-row min-vh-100">

        {{--- Topbar móvil --}}
        <div class="d-flex d-lg-none bg-primary text-white p-3 justify-content-between align-items-center shadow">
            <div class="d-flex align-items-center">
                <div class="bg-secondary text-dark fw-bold rounded p-1 me-2 small">UF</div>
                <span class="fw-bold">SGNI - FIAD</span>
            </div>
            <button class="btn text-white border-white" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarMenu">
                <i class="bi bi-list fs-3"></i>
            </button>
        </div>

        {{--- Sidebar --}}
        <div class="offcanvas-lg offcanvas-start bg-primary text-white d-flex flex-column flex-shrink-0 p-3 min-vh-100" tabindex="-1"
            id="sidebarMenu">

            <div class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <div class="bg-secondary text-dark fw-bold rounded p-2 me-2">UF</div>
                <div>
                    <span class="fs-5 fw-bold d-block lh-1">UABC FIAD</span>
                    <small class="text-white-50 small">Sistema de Gestión</small>
                </div>
                <button type="button" class="btn-close btn-close-white d-lg-none ms-auto" data-bs-dismiss="offcanvas"
                    data-bs-target="#sidebarMenu"></button>
            </div>

            <hr>

            {{--- Navegación principal --}}
            <ul class="nav nav-pills flex-column mb-auto w-100">

                @auth

                {{-- Crear Grupos --}}
                <li class="nav-item mb-1 dropdown dropend">
                    <a href="#"
                        class="nav-link w-100 d-flex justify-content-between align-items-center
                               {{ request()->routeIs('curso_prope', 'curso_induc', 'grupos_final.criterios')
                                   ? 'text-dark bg-secondary fw-bold shadow-sm'
                                   : 'text-white' }}"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span>Crear Grupos</span>
                        <i class="bi bi-chevron-right toggle-icon ms-3 flex-shrink-0"></i>
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2">
                        <li>
                            <a class="dropdown-item fw-bold py-2 mb-1 rounded-2
                                      {{ request()->routeIs('curso_prope') ? 'text-primary bg-light' : 'text-dark' }}"
                                href="{{ route('curso_prope') }}">
                                Propedéutico
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-bold py-2 mb-1 rounded-2
                                      {{ request()->routeIs('curso_induc') ? 'text-primary bg-light' : 'text-dark' }}"
                                href="{{ route('curso_induc') }}">
                                Inducción
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-bold py-2 mb-1 rounded-2
                                      {{ request()->routeIs('grupos_final.criterios') ? 'text-primary bg-light' : 'text-dark' }}"
                                href="{{ route('grupos_final.criterios') }}">
                                Primer Semestre
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Ver Grupos --}}
                <li class="nav-item mb-1 dropdown dropend">
                    <a href="#"
                        class="nav-link w-100 d-flex justify-content-between align-items-center
                               {{ request()->routeIs('curso_prope_creado', 'curso_induc_creado', 'grupos_final.grupos_finales')
                                   ? 'text-dark bg-secondary fw-bold shadow-sm'
                                   : 'text-white' }}"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span>Ver Grupos</span>
                        <i class="bi bi-chevron-right toggle-icon ms-3 flex-shrink-0"></i>
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2">
                        <li>
                            <a class="dropdown-item fw-bold py-2 rounded-2
                                      {{ request()->routeIs('curso_prope_creado') ? 'text-primary bg-light' : 'text-dark' }}"
                                href="{{ route('curso_prope_creado') }}">
                                Propedéutico
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-bold py-2 rounded-2
                                      {{ request()->routeIs('curso_induc_creado') ? 'text-primary bg-light' : 'text-dark' }}"
                                href="{{ route('curso_induc_creado') }}">
                                Inducción
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-bold py-2 rounded-2
                                      {{ request()->routeIs('grupos_final.grupos_finales') ? 'text-primary bg-light' : 'text-dark' }}"
                                href="{{ route('grupos_final.grupos_finales') }}">
                                Primer Semestre
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Alumnos --}}
                <li class="nav-item mb-1 dropdown dropend">
                    <a href="#"
                        class="nav-link w-100 d-flex justify-content-between align-items-center
                               {{ request()->routeIs('alumnos.nuevo', 'alumnos.info', 'psicologo')
                                   ? 'text-dark bg-secondary fw-bold shadow-sm'
                                   : 'text-white' }}"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span>Alumnos</span>
                        <i class="bi bi-chevron-right toggle-icon ms-3 flex-shrink-0"></i>
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2">
                        <li>
                            <a class="dropdown-item fw-bold py-2 rounded-2
                                      {{ request()->routeIs('alumnos.nuevo') ? 'text-primary bg-light' : 'text-dark' }}"
                                href="{{ route('alumnos.nuevo') }}">
                                Alta de Alumnos
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-bold py-2 rounded-2
                                      {{ request()->routeIs('alumnos.info') ? 'text-primary bg-light' : 'text-dark' }}"
                                href="{{ route('alumnos.info') }}">
                                Buscar Alumno
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-bold py-2 rounded-2
                                      {{ request()->routeIs('psicologo') ? 'text-primary bg-light' : 'text-dark' }}"
                                href="{{ route('psicologo') }}">
                                Seguimiento del Alumno
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Capturar Asistencia --}}
                <li class="nav-item mb-1 dropdown dropend">
                    <a href="#"
                        class="nav-link w-100 d-flex justify-content-between align-items-center
                               {{ request()->routeIs('asistencia.paselista', 'asistencia.grupal', 'asistencias.importar')
                                   ? 'text-dark bg-secondary fw-bold shadow-sm'
                                   : 'text-white' }}"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span>Capturar Asistencia</span>
                        <i class="bi bi-chevron-right toggle-icon ms-3 flex-shrink-0"></i>
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2">
                        <li>
                            <a class="dropdown-item fw-bold py-2 rounded-2
                                      {{ request()->routeIs('asistencia.paselista') ? 'text-primary bg-light' : 'text-dark' }}"
                                href="{{ route('asistencia.paselista') }}">
                                Pasar Lista
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-bold py-2 rounded-2
                                      {{ request()->routeIs('asistencia.grupal') ? 'text-primary bg-light' : 'text-dark' }}"
                                href="{{ route('asistencia.grupal') }}">
                                Mostrar Asistencias
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Capturar Calificaciones --}}
                <li class="nav-item mb-1 dropdown dropend">
                    <a href="#"
                        class="nav-link w-100 d-flex justify-content-between align-items-center
                               {{ request()->routeIs('calificaciones.captura', 'calificaciones.mostrar')
                                   ? 'text-dark bg-secondary fw-bold shadow-sm'
                                   : 'text-white' }}"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span>Capturar Calificaciones</span>
                        <i class="bi bi-chevron-right toggle-icon ms-3 flex-shrink-0"></i>
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2">
                        <li>
                            <a class="dropdown-item fw-bold py-2 rounded-2
                                      {{ request()->routeIs('calificaciones.captura') ? 'text-primary bg-light' : 'text-dark' }}"
                                href="{{ route('calificaciones.captura') }}">
                                Captura
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-bold py-2 rounded-2
                                      {{ request()->routeIs('calificaciones.mostrar') ? 'text-primary bg-light' : 'text-dark' }}"
                                href="{{ route('calificaciones.mostrar') }}">
                                Mostrar Calificaciones Capturadas
                            </a>
                        </li>
                    </ul>
                </li>

                {{--- Vistas del Administrador ---}}
                @if(Auth::user()->rol->nombre_rol === 'Administrador')

                {{-- Personal --}}
                <li class="nav-item mb-1 dropdown dropend">
                    <a href="#"
                        class="nav-link w-100 d-flex justify-content-between align-items-center
                               {{ request()->routeIs('usuarios.alta_usuarios', 'usuarios.index')
                                   ? 'text-dark bg-secondary fw-bold shadow-sm'
                                   : 'text-white' }}"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span>Personal</span>
                        <i class="bi bi-chevron-right toggle-icon ms-3 flex-shrink-0"></i>
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0 rounded-3 p-2">
                        <li>
                            <a class="dropdown-item fw-bold py-2 mb-1 rounded-2
                                      {{ request()->routeIs('usuarios.alta_usuarios') ? 'text-primary bg-light' : 'text-dark' }}"
                                href="{{ route('usuarios.alta_usuarios') }}">
                                Alta Personal
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-bold py-2 rounded-2
                                      {{ request()->routeIs('usuarios.index') ? 'text-primary bg-light' : 'text-dark' }}"
                                href="{{ route('usuarios.index') }}">
                                Lista del Personal
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Modo Lectura (sin submenú) --}}
                <li class="nav-item mb-1">
                    <a href="{{ route('grupos_final.modo_lectura') }}"
                        class="nav-link {{ request()->routeIs('grupos_final.modo_lectura') ? 'text-dark bg-secondary fw-bold shadow-sm' : 'text-white' }}">
                        Modo Lectura
                    </a>
                </li>

                @endif

            </ul>

            <hr>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <!-- Mostramos el nombre guardado en la sesión -->
                    <strong>{{ Auth::user()->nombre }} {{ Auth::user()->ap_pat }} {{ Auth::user()->ap_mat }}</strong>
                </a>

                <ul class="dropdown-menu dropdown-menu-white text-small shadow">
                    <li><span class="dropdown-item-text text-dark-50">
                            {{ Auth::user()->correo_institucional }}
                        </span></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger bg-light">
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