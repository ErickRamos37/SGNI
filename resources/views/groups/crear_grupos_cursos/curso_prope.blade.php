@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-4">

    {{-- Lógica de control de pestañas nativa de Laravel/Blade --}}
    @php
        $tabActive = request('tab', 'crear');
    @endphp

    {{-- 1. Header Principal --}}
    <div class="mb-4">
        <h1 class="fw-bold text-dark mb-1">
            Crear Grupos
        </h1>
        <p class="text-muted mb-0">
            Seleccione el programa y configure los grupos
        </p>
        <a href="crear_grupo" class="text-primary small text-decoration-none fw-semibold d-inline-flex align-items-center mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver a selección de programa
        </a>
    </div>

    {{-- 2. Banner: Programa Seleccionado --}}
    <div class="bg-primary text-white p-4 mb-4 rounded-3 shadow-sm">
        <small class="text-white-50 text-uppercase fw-bold d-block mb-1 tracking-wide">
            Programa Seleccionado
        </small>
        <h3 class="fw-bold mb-0">
            Curso Propedéutico
        </h3>
    </div>

    {{-- 3. Barra de Navegación por Pestañas (Cambio Dinámico de Estados Visuales) --}}
    <div class="bg-light border rounded-3 p-1 d-inline-flex align-items-center mb-4">
        <a href="?tab=crear" class="btn {{ $tabActive === 'crear' ? 'btn-primary shadow-sm' : 'btn-light text-dark border-0 bg-transparent' }} fw-semibold rounded-3 px-4 py-2 me-1">
            Crear Grupos
        </a>
        <a href="?tab=asignar" class="btn {{ $tabActive === 'asignar' ? 'btn-primary shadow-sm' : 'btn-light text-dark border-0 bg-transparent' }} fw-semibold rounded-3 px-4 py-2">
            Asignar Profesores a Grupos
        </a>
    </div>

    {{-- CONDICIONAL DE BLADE PARA CONMUTAR EL CONTENIDO DE LA VISTA --}}
    @if($tabActive === 'crear')

        {{-- ========================================================= --}}
        {{-- VISTA: CREAR GRUPOS                                       --}}
        {{-- ========================================================= --}}

        {{-- 4. Card: Cantidad de grupos a crear --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body p-4">
                
                {{-- Encabezado interno con icono amarillo/dorado --}}
                <div class="d-flex align-items-center mb-4">
                    <i class="bi bi-people text-warning fs-4 me-2"></i>
                    <span class="fw-bold text-dark text-uppercase tracking-wide fs-6">
                        Cantidad de Grupos a Crear
                    </span>
                </div>

                {{-- Columnas Interiores (Sistema de Rejilla) --}}
                <div class="row g-4">

                    {{-- Columna Izquierda: Grupos en la mañana --}}
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-4 text-center border border-light-subtle">
                            <small class="text-uppercase fw-semibold text-muted d-block mb-3 tracking-wider small">
                                Grupos en la Mañana
                            </small>
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="bg-white border rounded-3 py-2 shadow-sm border-secondary-subtle">
                                        <span class="fw-bold fs-4 text-dark">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Columna Derecha: Grupos en la tarde --}}
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-4 text-center border border-light-subtle">
                            <small class="text-uppercase fw-semibold text-muted d-block mb-3 tracking-wider small">
                                Grupos en la Tarde
                            </small>
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="bg-white border rounded-3 py-2 shadow-sm border-secondary-subtle">
                                        <span class="fw-bold fs-4 text-dark">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        {{-- 5. Card: Importar lista de estudiantes --}}
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-transparent border-bottom-0 pt-4 px-4 pb-0">
                <div class="d-flex align-items-center text-dark fw-bold">
                    <i class="bi bi-file-earmark-excel-fill text-warning me-2 fs-3"></i>
                    <span class="text-uppercase tracking-wide fs-5">Importar Lista de Estudiantes</span>
                    <span class="text-danger ms-1">*</span>
                </div>
            </div>
            <div class="card-body p-4">
                <p class="small text-muted mb-4">
                    Este archivo es <strong>OBLIGATORIO</strong>. Suba un archivo Excel (.xlsx) con la lista de estudiantes.
                </p>

                {{-- Dropzone: Contenedor interactivo nativo (Simulado con Label sin estilos inline) --}}
                <label class="border border-3 border-warning border-dashed rounded-3 bg-light bg-opacity-25 p-5 text-center mb-3 d-block w-100 cursor-pointer">
                    <input type="file" class="d-none" accept=".xlsx">
                    <div class="py-3">
                        <i class="bi bi-cloud-arrow-up text-primary display-3 mb-3 d-block"></i>
                        <h5 class="fw-bold text-dark mb-1">Arrastre el archivo aquí</h5>
                        <p class="text-muted small mb-3">o haga clic para seleccionar</p>
                        <div class="d-inline-flex align-items-center badge bg-white text-dark border px-3 py-2 rounded-2 small shadow-sm">
                            <i class="bi bi-filetype-xlsx text-dark me-1 fs-6"></i> Formato: .xlsx (Excel)
                        </div>
                    </div>
                </label>

                {{-- Alert Informativa de Formato --}}
                <div class="alert bg-info-subtle border border-info-subtle text-dark rounded-3 d-flex align-items-center p-3 mb-0" role="alert">
                    <i class="bi bi-info-circle-fill fs-5 me-3 text-info"></i>
                    <div class="small">
                        <strong>Formato esperado:</strong> El archivo Excel debe contener columnas: Matrícula, Nombre, Apellido, Carrera.
                    </div>
                </div>
            </div>
        </div>

        {{-- 6. Footer Button --}}
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-warning btn-lg text-white fw-bold px-5 text-uppercase shadow-sm">
                CREAR GRUPOS
            </button>
        </div>

    @else

        {{-- ========================================================= --}}
        {{-- VISTA: ASIGNAR PROFESORES A GRUPOS (NUEVA SECCIÓN)         --}}
        {{-- ========================================================= --}}

        <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
            
            {{-- Encabezado de la Tarjeta (Verde Institucional) --}}
            <div class="card-header bg-primary py-3 px-4 border-0">
                <h5 class="text-white fw-bold text-uppercase mb-0 tracking-wide fs-6">
                    Crear Grupos y Asignar Profesores a Grupos
                </h5>
            </div>

            {{-- Tabla Principal --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase">
                            <tr>
                                <th class="px-4 py-3 text-muted fw-bold small">Grupo</th>
                                <th class="px-4 py-3 text-muted fw-bold small">Profesor Asignado</th>
                                <th class="px-4 py-3 text-muted fw-bold small">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            {{-- Fila Grupo A --}}
                            <tr>
                                <td class="px-4 py-3 fw-bold text-dark">
                                    Propedéutico - Grupo A
                                </td>
                                <td class="px-4 py-3 text-muted">
                                    Sin asignar
                                </td>
                                <td class="px-4 py-3">
                                    <select class="form-select w-100">
                                        <option selected disabled>Seleccionar profesor</option>
                                        <option value="1">Dr. Alejandro Martínez</option>
                                        <option value="2">Mtra. Sofía Rodríguez</option>
                                    </select>
                                </td>
                            </tr>
                            {{-- Fila Grupo B --}}
                            <tr>
                                <td class="px-4 py-3 fw-bold text-dark">
                                    Propedéutico - Grupo B
                                </td>
                                <td class="px-4 py-3 text-muted">
                                    Sin asignar
                                </td>
                                <td class="px-4 py-3">
                                    <select class="form-select w-100">
                                        <option selected disabled>Seleccionar profesor</option>
                                        <option value="1">Dr. Alejandro Martínez</option>
                                        <option value="2">Mtra. Sofía Rodríguez</option>
                                    </select>
                                </td>
                            </tr>
                            {{-- Fila Grupo C --}}
                            <tr>
                                <td class="px-4 py-3 fw-bold text-dark">
                                    Propedéutico - Grupo C
                                </td>
                                <td class="px-4 py-3 text-muted">
                                    Sin asignar
                                </td>
                                <td class="px-4 py-3">
                                    <select class="form-select w-100">
                                        <option selected disabled>Seleccionar profesor</option>
                                        <option value="1">Dr. Alejandro Martínez</option>
                                        <option value="2">Mtra. Sofía Rodríguez</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pie de la Tarjeta (Footer Integrado con Flexbox) --}}
            <div class="card-footer bg-light border-top-0 p-4 d-flex justify-content-between align-items-center">
                <span class="small text-muted fw-semibold">
                    3 grupos disponibles • 0 asignados
                </span>
                <button type="button" class="btn btn-warning text-white fw-bold px-5 text-uppercase shadow-sm">
                    GUARDAR
                </button>
            </div>

        </div>

    @endif

</div>
@endsection