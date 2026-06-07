@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    @php
        $tabActive = request('tab', 'crear');
    @endphp

    {{-- 1. Header Principal --}}
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Crear Grupos</h2>
            <p class="text-muted mb-0">Seleccione el programa y configure los grupos</p>
        </div>
    </div>

    {{-- 2. Banner: Programa Seleccionado --}}
    <div class="card bg-primary text-white border border-light-subtle shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <small class="text-white-50 text-uppercase fw-bold d-block mb-1">
                Programa Seleccionado
            </small>
            <h3 class="fw-bold mb-0">Curso de Inducción</h3>
        </div>
    </div>

    {{-- 3. Barra de Navegación por Pestañas --}}
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
        <form action="{{ route('grupos_induc.store') }}" method="POST" enctype="multipart/form-data" id="formCrearGrupos">
            @csrf
            <input type="hidden" name="tipo_grupo" value="induccion">

            {{-- ==================== PASO 1: CONFIGURAR GRUPOS ==================== --}}
            <div id="paso1">
                <div class="card border border-light-subtle shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold text-primary mb-4 d-flex align-items-center">
                            Paso 1: Configurar Cantidad de Grupos
                            <span class="text-danger ms-1">*</span>
                        </h5>
                        
                        {{-- GRUPOS GENERALES (INDUC NO SEPARA CARRERAS) --}}
                        <div class="d-flex align-items-center mb-3">
                            <span class="fw-bold text-dark text-uppercase fs-6">Grupos Generales (Tronco Común)</span>
                        </div>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-3 text-center border border-light-subtle">
                                    <label for="grupos_manana" class="form-label text-dark fw-semibold d-block mb-2 small text-uppercase">Matutino</label>
                                    <input type="number" name="grupos_manana" id="grupos_manana" class="form-control shadow-sm text-center fw-bold fs-5 mx-auto w-50" placeholder="Ej. 2" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light rounded-3 p-3 text-center border border-light-subtle">
                                    <label for="grupos_tarde" class="form-label text-dark fw-semibold d-block mb-2 small text-uppercase">Vespertino</label>
                                    <input type="number" name="grupos_tarde" id="grupos_tarde" class="form-control shadow-sm text-center fw-bold fs-5 mx-auto w-50" placeholder="Ej. 2" min="0">
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
                            Suba el archivo Excel (.xlsx) general con la lista de estudiantes. El sistema detectará a los alumnos nuevos y omitirá a los que ya existen.
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

        {{-- SCRIPT DEL ASISTENTE Y SWEETALERT2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.getElementById('archivo_alumnos').addEventListener('change', function(e) {
                if(this.files.length > 0) {
                    document.getElementById('nombre_archivo').innerText = this.files[0].name;
                }
            });

            document.getElementById('btnSiguiente').addEventListener('click', function() {
                const inputManana = document.getElementById('grupos_manana');
                const inputTarde = document.getElementById('grupos_tarde');
                
                let manana = parseInt(inputManana.value) || 0;
                let tarde = parseInt(inputTarde.value) || 0;
                
                if(manana === 0 && tarde === 0) {
                    Swal.fire({
                        title: 'Faltan datos',
                        text: 'Por favor, solicita al menos un grupo (ya sea en turno matutino o vespertino) para continuar.',
                        icon: 'warning',
                        customClass: { confirmButton: 'btn btn-primary' },
                        buttonsStyling: false,
                        confirmButtonText: ' Entendido'
                    });
                    return;
                }

                // Autocompletar los vacíos con 0 para evitar errores al guardar
                if(inputManana.value === '') inputManana.value = '0';
                if(inputTarde.value === '') inputTarde.value = '0';

                document.getElementById('paso1').classList.add('d-none');
                document.getElementById('paso2').classList.remove('d-none');
            });

            document.getElementById('btnCancelar').addEventListener('click', function() {
                document.getElementById('paso2').classList.add('d-none');
                document.getElementById('paso1').classList.remove('d-none');
            });

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
        {{-- Usamos la MISMA ruta de guardar profesores porque la lógica de BD es idéntica --}}
        <form action="{{ route('grupos.guardar_profesores') }}" method="POST">
            @csrf
            <div class="card border border-light-subtle shadow-sm rounded-3 mb-4 overflow-hidden">
                <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
                    <h5 class="fw-bold text-uppercase text-white mb-0">
                        Asignar Docentes a Grupos de Inducción
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
                                @forelse($grupos as $grupo)
                                <tr class="registro-row" data-search="{{ strtolower($grupo->nombre_grupo) }}">
                                    <td class="px-4 py-3 fw-bold text-dark border-light-subtle">
                                        {{ $grupo->nombre_grupo }}
                                    </td>
                                    <td class="py-3 text-dark border-light-subtle">
                                        @if($grupo->id_usuario && $grupo->id_usuario != auth()->user()->id_usuario)
                                            @php
                                                $docenteAsignado = $docentes->firstWhere('id_usuario', $grupo->id_usuario);
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
                                                <option value="{{ $docente->id_usuario }}" {{ $grupo->id_usuario == $docente->id_usuario ? 'selected' : '' }}>
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
                                        Aún no hay grupos creados para Inducción.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>{{-- /card --}}

            {{-- BARRA DE ACCIÓN INFERIOR --}}
            <div class="d-flex justify-content-end align-items-center gap-2 mb-4">
                <span class="small text-muted fw-semibold me-3">
                    {{ $grupos->count() }} grupos disponibles
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
                    customClass: { confirmButton: 'btn btn-primary' },
                    buttonsStyling: false,
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
                        customClass: { confirmButton: 'btn btn-primary' },
                        buttonsStyling: false,
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    Swal.fire({
                        title: '¡Importación Exitosa!',
                        text: `Se cargaron ${nuevos} alumnos nuevos correctamente, sin duplicados.`,
                        icon: 'success',
                        customClass: { confirmButton: 'btn btn-primary' },
                        buttonsStyling: false,
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        </script>
    @endif

</div>
@endsection