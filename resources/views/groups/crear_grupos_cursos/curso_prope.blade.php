@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    {{-- Lógica de control de pestañas --}}
    @php
        $tabActive = request('tab', 'crear');
    @endphp

    {{-- ─── Encabezado de página ─────────────────────────────────── --}}
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Crear Grupos</h2>
            <p class="text-muted mb-0">Seleccione el programa y configure los grupos</p>
            <a href="{{ route('crear_grupo') }}" class="text-primary small text-decoration-none fw-semibold d-inline-flex align-items-center mt-2">
                Atras
            </a>
        </div>
    </div>

    {{-- ─── Banner: Programa Seleccionado ────────────────────────── --}}
    <div class="card bg-primary text-white border border-light-subtle shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <small class="text-white-50 text-uppercase fw-bold d-block mb-1">
                Programa Seleccionado
            </small>
            <h3 class="fw-bold mb-0">Curso Propedéutico</h3>
        </div>
    </div>

    {{-- ─── Barra de Navegación por Pestañas ─────────────────────── --}}
    <div class="mb-4">
        <div class="d-inline-flex rounded-pill border bg-white shadow-sm p-1 gap-1">
            <a href="?tab=crear" class="btn btn-sm rounded-pill px-4 py-2 fw-semibold text-decoration-none {{ $tabActive === 'crear' ? 'btn-outline-dark active' : 'btn-outline-dark border-0' }}">
                Crear Grupos
            </a>
            <a href="?tab=asignar" class="btn btn-sm rounded-pill px-4 py-2 fw-semibold text-decoration-none {{ $tabActive === 'asignar' ? 'btn-outline-dark active' : 'btn-outline-dark border-0' }}">
                Asignar Profesores a Grupos
            </a>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- CONDICIONAL PRINCIPAL DE LAS PESTAÑAS                     --}}
    {{-- ========================================================= --}}
    @if($tabActive === 'crear')

        {{-- VISTA: CREAR GRUPOS (CON ASISTENTE PASO A PASO) --}}
        <form action="{{ route('grupos.store') }}" method="POST" enctype="multipart/form-data" id="formCrearGrupos">
            @csrf
            <input type="hidden" name="tipo_grupo" value="Propedéutico">

            {{-- ==================== PASO 1: SUBIR EXCEL ==================== --}}
            <div id="paso1">
                <div class="card border border-light-subtle shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold text-primary mb-4 d-flex align-items-center">
                            <i class="bi bi-file-earmark-excel-fill text-primary me-2 fs-3"></i>
                            Paso 1: Importar Lista de Estudiantes
                            <span class="text-danger ms-1">*</span>
                        </h5>

                        <p class="small text-muted mb-4">
                            Suba el archivo Excel (.xlsx) general con la lista de estudiantes. El sistema los separará automáticamente.
                        </p>

                        <label class="border border-3 border-dark border-dashed rounded-3 bg-light bg-opacity-25 p-5 text-center mb-3 d-block w-100 cursor-pointer">
                            <input type="file" name="archivo_alumnos" id="archivo_alumnos" class="d-none" accept=".xlsx" required>
                            <div class="py-3">
                                <i class="bi bi-cloud-arrow-up text-primary display-3 mb-3 d-block"></i>
                                <h5 class="fw-bold text-dark mb-1" id="nombre_archivo">Arrastre el archivo aquí</h5>
                                <p class="text-muted small mb-3">o haga clic para seleccionar</p>
                                <div class="d-inline-flex align-items-center badge bg-white text-dark border px-3 py-2 rounded-2 small shadow-sm">
                                    <i class="bi bi-filetype-xlsx text-dark me-1 fs-6"></i> Formato: .xlsx (Excel)
                                </div>
                            </div>
                        </label>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="alert bg-info-subtle border border-info-subtle text-dark rounded-3 d-flex align-items-center p-3 mb-0" role="alert">
                            <i class="bi bi-info-circle-fill fs-5 me-3 text-info"></i>
                            <div class="small">
                                <strong>Formato esperado:</strong> El archivo debe contener  "unidad_desc|programaestudios|programa_des|matricula|Nombre|apellido_paterno|apellido_materno".
                            </div>
                        </div>

                        <hr class="my-4 border-light-subtle">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" id="btnCancelar" onclick="window.history.back();" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                Cancelar
                            </button>
                            <button type="button" id="btnSiguiente" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                Siguiente
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==================== PASO 2: CONFIGURAR GRUPOS ==================== --}}
            <div id="paso2" class="d-none">
                <div class="card border border-light-subtle shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold text-primary mb-4 d-flex align-items-center">
                            <i class="bi bi-sliders text-warning me-2 fs-3"></i>
                            Paso 2: Configurar Cantidad de Grupos
                        </h5>

                        {{-- INGENIERÍA --}}
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-gear-fill text-secondary fs-4 me-2"></i>
                            <span class="fw-bold text-dark text-uppercase fs-6">Grupos para Ingeniería</span>
                        </div>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-3 text-center border border-light-subtle">
                                    <label for="grupos_manana_inge" class="form-label text-dark fw-semibold d-block mb-2 small text-uppercase">Mañana</label>
                                    <input type="number" name="grupos_manana_inge" id="grupos_manana_inge" class="form-control shadow-sm text-center fw-bold fs-5 mx-auto w-50" value="0" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-3 text-center border border-light-subtle">
                                    <label for="grupos_tarde_inge" class="form-label text-dark fw-semibold d-block mb-2 small text-uppercase">Tarde</label>
                                    <input type="number" name="grupos_tarde_inge" id="grupos_tarde_inge" class="form-control shadow-sm text-center fw-bold fs-5 mx-auto w-50" value="0" min="0" required>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 border-light-subtle">

                        {{-- ARQUITECTURA --}}
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-palette-fill text-secondary fs-4 me-2"></i>
                            <span class="fw-bold text-dark text-uppercase fs-6">Grupos para Arquitectura y Diseño</span>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-3 text-center border border-light-subtle">
                                    <label for="grupos_manana_arqui" class="form-label text-dark fw-semibold d-block mb-2 small text-uppercase">Mañana</label>
                                    <input type="number" name="grupos_manana_arqui" id="grupos_manana_arqui" class="form-control shadow-sm text-center fw-bold fs-5 mx-auto w-50" value="0" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-3 text-center border border-light-subtle">
                                    <label for="grupos_tarde_arqui" class="form-label text-dark fw-semibold d-block mb-2 small text-uppercase">Tarde</label>
                                    <input type="number" name="grupos_tarde_arqui" id="grupos_tarde_arqui" class="form-control shadow-sm text-center fw-bold fs-5 mx-auto w-50" value="0" min="0" required>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 border-light-subtle">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" id="btnAtras" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                Atras
                            </button>
                            <button type="submit" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                Crear
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Importamos SweetAlert2 para todo el funcionamiento del formulario --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // Mostrar nombre del archivo al seleccionarlo
            document.getElementById('archivo_alumnos').addEventListener('change', function(e) {
                if(this.files.length > 0) {
                    document.getElementById('nombre_archivo').innerText = this.files[0].name;
                }
            });

            // Lógica del botón Siguiente
            document.getElementById('btnSiguiente').addEventListener('click', function() {
                const inputArchivo = document.getElementById('archivo_alumnos');
                
                // Validación con SweetAlert2 en lugar del alert() nativo
                if(inputArchivo.files.length === 0) {
                    Swal.fire({
                        title: 'Archivo requerido',
                        text: 'Por favor selecciona un archivo Excel con la lista de estudiantes antes de continuar al Paso 2.',
                        icon: 'warning',
                        customClass: { confirmButton: 'btn btn-primary' },
                        buttonsStyling: false,
                        confirmButtonText: '<i class="bi bi-hand-thumbs-up-fill me-1"></i> Entendido'
                    });
                    return;
                }

                // Ocultar paso 1, mostrar paso 2
                document.getElementById('paso1').classList.add('d-none');
                document.getElementById('paso2').classList.remove('d-none');
            });

            // Lógica del botón Atrás
            document.getElementById('btnAtras').addEventListener('click', function() {
                // Ocultar paso 2, mostrar paso 1
                document.getElementById('paso2').classList.add('d-none');
                document.getElementById('paso1').classList.remove('d-none');
            });
        </script>
    @else

        {{-- ========================================================= --}}
        {{-- VISTA: ASIGNAR PROFESORES A GRUPOS                        --}}
        {{-- ========================================================= --}}
        <form action="{{ route('grupos.guardar_profesores') }}" method="POST">
            @csrf
            <div class="card border border-light-subtle shadow-sm rounded-3">

                <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
                    <h5 class="fw-bold text-uppercase text-white mb-0">
                        Asignar Profesores a Grupos
                    </h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="px-4 py-3">Grupo</th>
                                    <th class="py-3">Profesor Asignado</th>
                                    <th class="py-3">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @foreach($grupos as $grupo)
                                <tr class="registro-row" data-search="{{ strtolower($grupo->nombre_grupo) }}">
                                    <td class="px-4 py-3 fw-bold text-dark">
                                        {{ $grupo->nombre_grupo }}
                                    </td>
                                    <td class="py-3 text-dark">
                                        @if($grupo->num_empleado && $grupo->num_empleado != auth()->user()->num_empleado)
                                            @php
                                                $profeAsignado = $profesores->firstWhere('num_empleado', $grupo->num_empleado);
                                            @endphp
                                            <span class="text-primary fw-semibold">
                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                {{ $profeAsignado ? $profeAsignado->nombre . ' ' . $profeAsignado->ap_pat : 'Profesor Asignado' }}
                                            </span>
                                        @else
                                            <span class="text-danger fw-semibold">Sin asignar</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <select name="profesores[{{ $grupo->id_grupo }}]" class="form-select shadow-sm">
                                            <option value="" selected disabled>Seleccionar profesor</option>
                                            @foreach($profesores as $profe)
                                                <option value="{{ $profe->num_empleado }}" {{ $grupo->num_empleado == $profe->num_empleado ? 'selected' : '' }}>
                                                    {{ $profe->nombre }} {{ $profe->ap_pat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white border-top border-light-subtle p-3 d-flex justify-content-end gap-2">
                    <button type="button" onclick="window.history.back();" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                        Guardar
                    </button>
                </div>

            </div>{{-- /card --}}
        </form>

    @endif

    {{-- ========================================================= --}}
    {{-- ALERTA EMERGENTE (SWEETALERT2) PARA RESULTADOS DE IMPORTACIÓN --}}
    {{-- ========================================================= --}}
    @if(session('import_stats'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let repetidos = {{ session('import_stats.repetidos') }};
                let nuevos = {{ session('import_stats.nuevos') }};

                if(repetidos > 0) {
                    Swal.fire({
                        title: '¡Cargando alumnos repetidos!',
                        html: `Se detectó que parte de la lista ya existía en el sistema.<br><br>
                               <div class="text-start ms-4">
                                   <i class="bi bi-check-circle-fill text-success"></i> <b>Nuevos registrados:</b> ${nuevos}<br>
                                   <i class="bi bi-exclamation-triangle-fill text-warning"></i> <b>Repetidos (ignorados):</b> ${repetidos}
                               </div>`,
                        icon: 'warning',
                        customClass: { confirmButton: 'btn btn-primary' },
                        buttonsStyling: false,
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    Swal.fire({
                        title: '¡Creación de Grupos!',
                        text: `Se crearon los grupos correctamente.`,
                        icon: 'success',
                        customClass: { confirmButton: 'btn btn-primary' },
                        buttonsStyling: false,
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        </script>
    @endif
    {{-- ========================================================= --}}
    {{-- ALERTA DE SEGURIDAD: GRUPOS YA EXISTENTES                 --}}
    {{-- ========================================================= --}}
    @if(session('error_grupos_existentes'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Acción Denegada!',
                    text: "{{ session('error_grupos_existentes') }}",
                    icon: 'error',
                    customClass: { confirmButton: 'btn btn-danger' },
                    buttonsStyling: false,
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
    @endif

</div>
@endsection