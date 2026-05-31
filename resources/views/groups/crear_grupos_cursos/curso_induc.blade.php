@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-4">

    @php
        $tabActive = request('tab', 'crear');
    @endphp

    {{-- 1. Header Principal --}}
    <div class="mb-4">
        <h1 class="fw-bold text-dark mb-1">Crear Grupos</h1>
        <p class="text-muted mb-0">Seleccione el programa y configure los grupos</p>
        <a href="{{ route('crear_grupo') }}" class="text-primary small text-decoration-none fw-semibold d-inline-flex align-items-center mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver a selección de programa
        </a>
    </div>

    {{-- 2. Banner: Programa Seleccionado --}}
    <div class="bg-primary text-white p-4 mb-4 rounded-3 shadow-sm">
        <small class="text-white-50 text-uppercase fw-bold d-block mb-1 tracking-wide">
            Programa Seleccionado
        </small>
        <h3 class="fw-bold mb-0">Curso de Inducción</h3>
    </div>

    {{-- 3. Barra de Navegación por Pestañas --}}
    <div class="bg-light border rounded-3 p-1 d-inline-flex align-items-center mb-4">
        <a href="?tab=crear" class="btn {{ $tabActive === 'crear' ? 'btn-primary shadow-sm' : 'btn-light text-dark border-0 bg-transparent' }} fw-semibold rounded-3 px-4 py-2 me-1">
            Crear Grupos
        </a>
        <a href="?tab=asignar" class="btn {{ $tabActive === 'asignar' ? 'btn-primary shadow-sm' : 'btn-light text-dark border-0 bg-transparent' }} fw-semibold rounded-3 px-4 py-2">
            Asignar Profesores a Grupos
        </a>
    </div>

    {{-- ========================================================= --}}
    {{-- CONDICIONAL PRINCIPAL DE LAS PESTAÑAS                     --}}
    {{-- ========================================================= --}}
    @if($tabActive === 'crear')

        {{-- VISTA: CREAR GRUPOS (CON ASISTENTE PASO A PASO) --}}
        <form action="{{ route('grupos_induc.store') }}" method="POST" enctype="multipart/form-data" id="formCrearGrupos">
            @csrf
            <input type="hidden" name="tipo_grupo" value="Inducción">

            {{-- ==================== PASO 1: SUBIR EXCEL ==================== --}}
            <div id="paso1">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 px-4 pb-0">
                        <div class="d-flex align-items-center text-dark fw-bold">
                            <i class="bi bi-file-earmark-excel-fill text-warning me-2 fs-3"></i>
                            <span class="text-uppercase tracking-wide fs-5">Paso 1: Importar Lista de Estudiantes</span>
                            <span class="text-danger ms-1">*</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <p class="small text-muted mb-4">
                            Suba el archivo Excel (.xlsx) general con la lista de estudiantes. El sistema detectará a los alumnos nuevos y omitirá a los que ya existen.
                        </p>

                        <label class="border border-3 border-warning border-dashed rounded-3 bg-light bg-opacity-25 p-5 text-center mb-3 d-block w-100 cursor-pointer">
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
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="button" id="btnSiguiente" class="btn btn-primary btn-lg fw-bold px-5 text-uppercase shadow-sm">
                        Siguiente <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>

            {{-- ==================== PASO 2: CONFIGURAR GRUPOS ==================== --}}
            <div id="paso2" class="d-none">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-transparent border-bottom-0 pt-4 px-4 pb-0">
                        <div class="d-flex align-items-center text-dark fw-bold">
                            <i class="bi bi-sliders text-warning me-2 fs-3"></i>
                            <span class="text-uppercase tracking-wide fs-5">Paso 2: Configurar Cantidad de Grupos</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        
                        {{-- GRUPOS GENERALES (INDUC NO SEPARA CARRERAS) --}}
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-people-fill text-secondary fs-4 me-2"></i>
                            <span class="fw-bold text-dark text-uppercase tracking-wide fs-6">Grupos Generales (Tronco Común)</span>
                        </div>
                        <div class="row g-4 mb-2">
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-3 text-center border border-light-subtle">
                                    <label for="grupos_manana" class="text-uppercase fw-semibold text-muted d-block mb-2 small">Mañana</label>
                                    <input type="number" name="grupos_manana" id="grupos_manana" class="form-control text-center fw-bold fs-5 mx-auto w-50" value="0" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-3 text-center border border-light-subtle">
                                    <label for="grupos_tarde" class="text-uppercase fw-semibold text-muted d-block mb-2 small">Tarde</label>
                                    <input type="number" name="grupos_tarde" id="grupos_tarde" class="form-control text-center fw-bold fs-5 mx-auto w-50" value="0" min="0" required>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" id="btnAtras" class="btn btn-light btn-lg text-dark fw-bold px-4 border shadow-sm">
                        <i class="bi bi-arrow-left me-2"></i> Atrás
                    </button>
                    <button type="submit" class="btn btn-warning btn-lg text-white fw-bold px-5 text-uppercase shadow-sm">
                        CREAR GRUPOS E IMPORTAR
                    </button>
                </div>
            </div>
        </form>

        {{-- SCRIPT DEL ASISTENTE Y SWEETALERT2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.getElementById('archivo_alumnos').addEventListener('change', function(e) {
                if(this.files.length > 0) {
                    document.getElementById('nombre_archivo').innerText = this.files[0].name;
                }
            });

            document.getElementById('btnSiguiente').addEventListener('click', function() {
                const inputArchivo = document.getElementById('archivo_alumnos');
                if(inputArchivo.files.length === 0) {
                    Swal.fire({
                        title: 'Archivo requerido',
                        text: '¡Ey! Por favor selecciona un archivo Excel con la lista de estudiantes antes de continuar al Paso 2.',
                        icon: 'warning',
                        confirmButtonColor: '#00723F',
                        confirmButtonText: '<i class="bi bi-hand-thumbs-up-fill me-1"></i> Entendido'
                    });
                    return;
                }
                document.getElementById('paso1').classList.add('d-none');
                document.getElementById('paso2').classList.remove('d-none');
            });

            document.getElementById('btnAtras').addEventListener('click', function() {
                document.getElementById('paso2').classList.add('d-none');
                document.getElementById('paso1').classList.remove('d-none');
            });
        </script>

    @else

        {{-- ========================================================= --}}
        {{-- VISTA: ASIGNAR PROFESORES A GRUPOS                        --}}
        {{-- ========================================================= --}}
        {{-- Usamos la MISMA ruta de guardar profesores porque la lógica de BD es idéntica --}}
        <form action="{{ route('grupos.guardar_profesores') }}" method="POST">
            @csrf
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-primary py-3 px-4 border-0">
                    <h5 class="text-white fw-bold text-uppercase mb-0 tracking-wide fs-6">
                        Asignar Profesores a Grupos de Inducción
                    </h5>
                </div>

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
                                @forelse($grupos as $grupo)
                                <tr>
                                    <td class="px-4 py-3 fw-bold text-dark">
                                        {{ $grupo->nombre_grupo }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($grupo->num_empleado && $grupo->num_empleado != 23)
                                            @php
                                                $profeAsignado = $profesores->firstWhere('num_empleado', $grupo->num_empleado);
                                            @endphp
                                            <span style="color: #00723F; font-weight: 600;">
                                                <i class="bi bi-check-circle-fill me-1"></i>
                                                {{ $profeAsignado ? $profeAsignado->nombre . ' ' . $profeAsignado->ap_pat : 'Profesor Asignado' }}
                                            </span>
                                        @else
                                            <span class="text-danger fw-semibold">Sin asignar</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <select name="profesores[{{ $grupo->id_grupo }}]" class="form-select w-100">
                                            <option value="" selected disabled>Seleccionar profesor</option>
                                            @foreach($profesores as $profe)
                                                <option value="{{ $profe->num_empleado }}" {{ $grupo->num_empleado == $profe->num_empleado ? 'selected' : '' }}>
                                                    {{ $profe->nombre }} {{ $profe->ap_pat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Aún no hay grupos creados para Inducción.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-light border-top-0 p-4 d-flex justify-content-between align-items-center">
                    <span class="small text-muted fw-semibold">
                        {{ $grupos->count() }} grupos disponibles
                    </span>
                    <button type="submit" class="btn btn-warning text-white fw-bold px-5 text-uppercase shadow-sm">
                        GUARDAR ASIGNACIONES
                    </button>
                </div>
            </div>
        </form>

    @endif

    {{-- ========================================================= --}}
    {{-- ALERTAS DE SEGURIDAD Y ESTADÍSTICAS DEL SERVIDOR          --}}
    {{-- ========================================================= --}}
    
    {{-- Cadenero: Grupos ya existentes --}}
    @if(session('error_grupos_existentes'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Acción Denegada!',
                    text: "{{ session('error_grupos_existentes') }}",
                    icon: 'error',
                    confirmButtonColor: '#dc3545', 
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
    @endif

    {{-- Estadísticas de creación --}}
    @if(session('import_stats'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let repetidos = {{ session('import_stats.repetidos') }};
                let nuevos = {{ session('import_stats.nuevos') }};

                if(repetidos > 0) {
                    Swal.fire({
                        title: '¡Proceso Terminado!',
                        html: `Se procesó la lista correctamente. Alumnos existentes fueron asignados a sus grupos de Inducción.<br><br>
                               <div class="text-start ms-4">
                                   <i class="bi bi-check-circle-fill text-success"></i> <b>Nuevos registrados:</b> ${nuevos}<br>
                                   <i class="bi bi-arrow-repeat text-info"></i> <b>Ya existían (actualizados):</b> ${repetidos}
                               </div>`,
                        icon: 'success',
                        confirmButtonColor: '#00723F',
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    Swal.fire({
                        title: '¡Importación Exitosa!',
                        text: `Se cargaron ${nuevos} alumnos nuevos correctamente, sin duplicados.`,
                        icon: 'success',
                        confirmButtonColor: '#00723F',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        </script>
    @endif

</div>
@endsection