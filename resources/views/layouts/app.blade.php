<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGNI - UABC FIAD</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div class="d-flex flex-column flex-lg-row min-vh-100">

        <div class="d-lg-none bg-primary text-white p-3 d-flex justify-content-between align-items-center shadow">
            <div class="d-flex align-items-center">
                <div class="bg-secondary text-dark fw-bold rounded p-1 me-2" style="font-size: 0.8rem;">UF</div>
                <span class="fw-bold">SGNI - FIAD</span>
            </div>
            <button class="btn text-white border-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <i class="bi bi-list fs-3">☰</i> </button>
        </div>

        <div class="offcanvas-lg offcanvas-start bg-primary text-white flex-column flex-shrink-0 p-3"
            tabindex="-1" id="sidebarMenu" style="width: 280px; min-height: 100vh;">

            <div class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <div class="bg-secondary text-dark fw-bold rounded p-2 me-2">UF</div>
                <div>
                    <span class="fs-5 fw-bold d-block lh-1">UABC FIAD</span>
                    <small class="text-white-50" style="font-size: 0.75rem;">Sistema de Gestión</small>
                </div>
                <button type="button" class="btn-close btn-close-white d-lg-none ms-auto" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu"></button>
            </div>

            <hr>

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-2">
                    <a href="#" class="nav-link text-dark bg-secondary fw-bold shadow-sm">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="#" class="nav-link text-white hover-opacity">
                        Crear Grupo (Manual)
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="#" class="nav-link text-white">
                        Registro de Asistencia
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="http://127.0.0.1:8000/vista_alumno" class="nav-link text-white">
                        Estatus de Alumno
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="#" class="nav-link text-white">
                        Cierre y Lista Final
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="#" class="nav-link text-white">
                        Alta de Profesores
                    </a>
                </li>
            </ul>

            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <strong>Usuario FIAD</strong>
                </a>
            </div>
        </div>

        <main class="flex-grow-1 p-4 bg-light overflow-auto">
            @yield('contenido')
        </main>

    </div>
</body>
</html>