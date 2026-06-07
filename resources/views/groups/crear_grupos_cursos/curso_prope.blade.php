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
                Asignar Docentes a Grupos
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

            {{-- ==================== PASO 1: CONFIGURAR GRUPOS ==================== --}}
            <div id="paso1">
                <div class="card border border-light-subtle shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold text-primary mb-4 d-flex align-items-center">
                            Paso 1: Configurar Cantidad de Grupos
                        </h5>

                        {{-- INGENIERÍA --}}
                        <div class="d-flex align-items-center mb-3">
                            <span class="fw-bold text-dark text-uppercase fs-6">Grupos para Ingeniería</span>
                        </div>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-3 text-center border border-light-subtle">
                                    <label for="grupos_manana_inge" class="form-label text-dark fw-semibold d-block mb-2 small text-uppercase">Matutino</label>
                                    <input type="number" name="grupos_manana_inge" id="grupos_manana_inge" class="form-control shadow-sm text-center fw-bold fs-5 mx-auto w-50" placeholder="Ej. 2" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-3 text-center border border-light-subtle">
                                    <label for="grupos_tarde_inge" class="form-label text-dark fw-semibold d-block mb-2 small text-uppercase">Vespertino</label>
                                    <input type="number" name="grupos_tarde_inge" id="grupos_tarde_inge" class="form-control shadow-sm text-center fw-bold fs-5 mx-auto w-50" placeholder="Ej. 2" min="0">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 border-light-subtle">

                        {{-- ARQUITECTURA --}}
                        <div class="d-flex align-items-center mb-3">
                            <span class="fw-bold text-dark text-uppercase fs-6">Grupos para Arquitectura y Diseño</span>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-3 text-center border border-light-subtle">
                                    <label for="grupos_manana_arqui" class="form-label text-dark fw-semibold d-block mb-2 small text-uppercase">Matutino</label>
                                    <input type="number" name="grupos_manana_arqui" id="grupos_manana_arqui" class="form-control shadow-sm text-center fw-bold fs-5 mx-auto w-50" placeholder="Ej. 2" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-3 text-center border border-light-subtle">
                                    <label for="grupos_tarde_arqui" class="form-label text-dark fw-semibold d-block mb-2 small text-uppercase">Vespertino</label>
                                    <input type="number" name="grupos_tarde_arqui" id="grupos_tarde_arqui" class="form-control shadow-sm text-center fw-bold fs-5 mx-auto w-50" placeholder="Ej. 2" min="0">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 border-light-subtle">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" id="btnSiguiente" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                Siguiente
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ==================== PASO 2: SUBIR EXCEL ==================== --}}
            <div id="paso2" class="d-none">
                <div class="card border border-light-subtle shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold text-primary mb-4 d-flex align-items-center">
                            <i class="bi bi-file-earmark-excel-fill text-primary me-2 fs-3"></i>
                            Paso 2: Importar Lista de Estudiantes
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
                            <button type="button" id="btnCancelar" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                Cancelar
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
                const iMananaInge = document.getElementById('grupos_manana_inge');
                const iTardeInge = document.getElementById('grupos_tarde_inge');
                const iMananaArqui = document.getElementById('grupos_manana_arqui');
                const iTardeArqui = document.getElementById('grupos_tarde_arqui');

                let mInge = parseInt(iMananaInge.value) || 0;
                let tInge = parseInt(iTardeInge.value) || 0;
                let mArqui = parseInt(iMananaArqui.value) || 0;
                let tArqui = parseInt(iTardeArqui.value) || 0;
                
                if(mInge === 0 && tInge === 0 && mArqui === 0 && tArqui === 0) {
                    Swal.fire({
                        title: 'Faltan datos',
                        text: 'Por favor, solicita al menos un grupo en cualquiera de los programas u horarios para continuar.',
                        icon: 'warning',
                        customClass: { confirmButton: 'btn btn-primary' },
                        buttonsStyling: false,
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }

                // Autocompletar vacíos con 0
                if(iMananaInge.value === '') iMananaInge.value = '0';
                if(iTardeInge.value === '') iTardeInge.value = '0';
                if(iMananaArqui.value === '') iMananaArqui.value = '0';
                if(iTardeArqui.value === '') iTardeArqui.value = '0';

                // Ocultar paso 1, mostrar paso 2
                document.getElementById('paso1').classList.add('d-none');
                document.getElementById('paso2').classList.remove('d-none');
            });

            // Lógica del botón Atrás
            document.getElementById('btnCancelar').addEventListener('click', function() {
                // Ocultar paso 2, mostrar paso 1
                document.getElementById('paso2').classList.add('d-none');
                document.getElementById('paso1').classList.remove('d-none');
            });

            // Validación al crear (submit del formulario completo)
            document.getElementById('formCrearGrupos').addEventListener('submit', function(e) {
                const inputArchivo = document.getElementById('archivo_alumnos');
                if(inputArchivo.files.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Archivo requerido',
                        text: 'Por favor selecciona un archivo Excel con la lista de estudiantes antes de crear los grupos.',
                        icon: 'warning',
                        customClass: { confirmButton: 'btn btn-primary' },
                        buttonsStyling: false,
                        confirmButtonText: '<i class="bi bi-hand-thumbs-up-fill me-1"></i> Entendido'
                    });
                }
            });
        </script>
    @else

        {{-- ========================================================= --}}
        {{-- VISTA: ASIGNAR PROFESORES A GRUPOS                        --}}
        {{-- ========================================================= --}}
        <form action="{{ route('grupos.guardar_profesores') }}" method="POST">
            @csrf
            
            {{-- SECCIÓN INGENIERÍA --}}
            <div class="card border border-light-subtle shadow-sm rounded-3 mb-4 overflow-hidden">
                <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
                    <h5 class="fw-bold text-uppercase text-white mb-0">
                        Asignar Docentes - Ingeniería
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="px-4 py-3 border-bottom-0">Grupo</th>
                                    <th class="py-3 border-bottom-0">Docente Asignado</th>
                                    <th class="py-3 border-bottom-0">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @forelse($gruposInge as $grupo)
                                <tr class="registro-row" data-search="{{ strtolower($grupo->nombre_grupo) }}">
                                    <td class="px-4 py-3 fw-bold text-dark border-light-subtle">
                                        {{ $grupo->nombre_grupo }}
                                    </td>
                                    <td class="py-3 text-dark border-light-subtle">
                                        @if($grupo->num_empleado && $grupo->num_empleado != auth()->user()->num_empleado)
                                            @php
                                                $docenteAsignado = $docentes->firstWhere('num_empleado', $grupo->num_empleado);
                                            @endphp
                                            <span class="text-primary fw-semibold bg-primary bg-opacity-10 px-3 py-1 rounded-pill">
                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                {{ $docenteAsignado ? $docenteAsignado->nombre . ' ' . $docenteAsignado->ap_pat : 'Docente Asignado' }}
                                            </span>
                                        @else
                                            <span class="text-danger fw-semibold bg-danger bg-opacity-10 px-3 py-1 rounded-pill">Sin asignar</span>
                                        @endif
                                    </td>
                                    <td class="py-3 border-light-subtle">
                                        <select name="docentes[{{ $grupo->id_grupo }}]" class="form-select shadow-sm border-light-subtle">
                                            <option value="" selected disabled>Seleccionar docente</option>
                                            @foreach($docentes as $docente)
                                                <option value="{{ $docente->num_empleado }}" {{ $grupo->num_empleado == $docente->num_empleado ? 'selected' : '' }}>
                                                    {{ $docente->nombre }} {{ $docente->ap_pat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted border-light-subtle">
                                        <i class="bi bi-inbox display-6 d-block mb-2 text-light-subtle"></i>
                                        Aún no hay grupos creados para Ingeniería.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN ARQUITECTURA --}}
            <div class="card border border-light-subtle shadow-sm rounded-3 mb-4 overflow-hidden">
                <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
                    <h5 class="fw-bold text-uppercase text-white mb-0">
                        Asignar Docentes - Arquitectura y Diseño
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="px-4 py-3 border-bottom-0">Grupo</th>
                                    <th class="py-3 border-bottom-0">Docente Asignado</th>
                                    <th class="py-3 border-bottom-0">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @forelse($gruposArqui as $grupo)
                                <tr class="registro-row" data-search="{{ strtolower($grupo->nombre_grupo) }}">
                                    <td class="px-4 py-3 fw-bold text-dark border-light-subtle">
                                        {{ $grupo->nombre_grupo }}
                                    </td>
                                    <td class="py-3 text-dark border-light-subtle">
                                        @if($grupo->num_empleado && $grupo->num_empleado != auth()->user()->num_empleado)
                                            @php
                                                $docenteAsignado = $docentes->firstWhere('num_empleado', $grupo->num_empleado);
                                            @endphp
                                            <span class="text-primary fw-semibold bg-primary bg-opacity-10 px-3 py-1 rounded-pill">
                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                {{ $docenteAsignado ? $docenteAsignado->nombre . ' ' . $docenteAsignado->ap_pat : 'Docente Asignado' }}
                                            </span>
                                        @else
                                            <span class="text-danger fw-semibold bg-danger bg-opacity-10 px-3 py-1 rounded-pill">Sin asignar</span>
                                        @endif
                                    </td>
                                    <td class="py-3 border-light-subtle">
                                        <select name="docentes[{{ $grupo->id_grupo }}]" class="form-select shadow-sm border-light-subtle">
                                            <option value="" selected disabled>Seleccionar docente</option>
                                            @foreach($docentes as $docente)
                                                <option value="{{ $docente->num_empleado }}" {{ $grupo->num_empleado == $docente->num_empleado ? 'selected' : '' }}>
                                                    {{ $docente->nombre }} {{ $docente->ap_pat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted border-light-subtle">
                                        <i class="bi bi-inbox display-6 d-block mb-2 text-light-subtle"></i>
                                        Aún no hay grupos creados para Arquitectura y Diseño.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- BARRA DE ACCIÓN INFERIOR --}}
            <div class="d-flex justify-content-end align-items-center gap-2 mb-4">
                <span class="small text-muted fw-semibold me-3">
                    {{ $gruposInge->count() + $gruposArqui->count() }} grupos disponibles
                </span>
                <button type="button" onclick="window.history.back();" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                    Guardar
                </button>
            </div>
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
                    customClass: { confirmButton: 'btn btn-primary' },
                    buttonsStyling: false,
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
    @endif

</div>
@endsection