@extends('layouts.app')

@section('contenido')
    <div class="container-fluid py-3">
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="row align-items-end g-3 mb-4">
                    <div class="col-12 col-md-6">
                        <h1 class="fw-extrabold text-dark mb-1 display-6" style="font-weight: 800;">Captura de Calificaciones
                        </h1>

                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="text-muted small fw-bold">Grupo:</span>
                            <select id="select-grupo"
                                class="form-select form-select-sm border-0 bg-transparent fw-bold text-primary p-3 w-auto shadow-none"
                                onchange="location = this.value;" style="cursor: pointer;">
                                <option value="{{ route('calificaciones.mostrar') }}" {{ !$grupo ? 'selected' : '' }}>
                                    --Seleccione un Grupo--
                                </option>
                                @foreach ($grupos as $g)
                                    <option value="{{ route('calificaciones.mostrar', $g->id_grupo ?? $g->id) }}"
                                        {{ $grupo && $grupo->id_grupo == ($g->id_grupo ?? $g->id) ? 'selected' : '' }}>
                                        {{ $g->nombre ?? ($g->nombre_grupo ?? 'Grupo ' . ($g->id_grupo ?? $g->id)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if ($grupo)
                            <a href="#" id="btn-descargar-lista"
                                data-url="{{ route('calificaciones.exportar', $grupo->id_grupo ?? $grupo->id) }}"
                                class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                Descargar Lista
                            </a>
                        @endif
                    </div>

                    @if ($grupo)
                        <div class="col-12 col-md-6 text-md-end">
                            <label for="search-alumno"
                                class="small fw-bold text-muted text-uppercase d-block mb-1 tracking-wider"
                                style="font-size: 0.75rem;">Buscar Estudiante</label>
                            <div class="d-flex justify-content-md-end">
                                <div class="input-group bg-white rounded-3 shadow-sm border border-light"
                                    style="max-width: 300px;">
                                    <span class="input-group-text bg-white border-0 text-muted pe-1">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" id="search-alumno"
                                        class="form-control border-0 bg-white shadow-none small ps-2 text-dark"
                                        placeholder="Matrícula o nombre...">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Contenedor de Alertas AJAX --}}
                <div id="alert-container-ajax" class="d-none mb-3">
                    <div class="alert alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert"
                        id="alert-box-ajax">
                        <i class="fs-4 me-3" id="alert-icon-ajax"></i>
                        <div id="alert-message-ajax"></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>

                @if ($grupo)
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4 bg-white">

                        <div class="bg-primary text-white px-4 py-3">
                            <h4 class="mb-0 fw-bold text-uppercase tracking-wider fs-5">Tabla de Calificaciones</h4>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0" id="tabla-estudiantes">
                                    <thead class="table-light border-bottom border-1 text-uppercase"
                                        style="font-size: 0.8rem;">
                                        <tr>
                                            <th class="body-color fw-bold py-3 ps-4" style="width: 15%;">Matrícula</th>
                                            <th class="body-color fw-bold py-3" style="width: 45%;">Nombre del Alumno</th>
                                            <th class="body-color fw-bold py-3 text-center" style="width: 20%;">Examen
                                                Diagnóstico</th>
                                            <th class="body-color fw-bold py-3 text-center" style="width: 20%;">Examen
                                                Propedéutico Final</th>
                                        </tr>
                                    </thead>

                                    <tbody class="border-0">
                                        @forelse($alumnos as $alumno)
                                            @php
                                                $notaInicial = $alumno->resultadosPropedeutico->examen_inicial ?? null;
                                                $notaFinal = $alumno->resultadosPropedeutico->examen_final ?? null;
                                            @endphp
                                            <tr data-matricula="{{ $alumno->matricula }}"
                                                class="border-bottom border-light student-row">
                                                <td class="ps-4 fw-bold text-dark fs-6 tracking-wide">
                                                    {{ $alumno->matricula }}
                                                </td>
                                                <td class="fw-semibold body-color student-name">
                                                    {{ $alumno->nombre }} {{ $alumno->ap_pat }} {{ $alumno->ap_mat }}
                                                </td>
                                                <td>
                                                    <div class="col-9 col-md-7 mx-auto">
                                                        <input type="number"
                                                            class="form-control text-center fw-bold rounded-3 shadow-sm input-score {{ !is_null($notaInicial) && $notaInicial < 70 ? 'text-danger border-danger' : 'text-dark border-light bg-light' }}"
                                                            data-field="examen_inicial" min="0" max="100"
                                                            step="0.01" value="{{ $notaInicial }}" placeholder="-">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="col-9 col-md-7 mx-auto">
                                                        <input type="number"
                                                            class="form-control text-center fw-bold rounded-3 shadow-sm input-score {{ !is_null($notaFinal) && $notaFinal < 70 ? 'text-danger border-danger' : 'text-dark border-light bg-light' }}"
                                                            data-field="examen_final" min="0" max="100"
                                                            step="0.01" value="{{ $notaFinal }}" placeholder="-">
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5 text-muted">
                                                    <i class="bi bi-people display-4 d-block mb-3 text-secondary"></i>
                                                    No hay alumnos registrados en este grupo.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div
                            class="card-footer bg-white d-flex justify-content-between align-items-center px-4 py-3 border-top border-light rounded-bottom-3">
                            <span class="text-muted small fw-bold" id="contador-estudiantes">
                                {{ $alumnos->count() }} estudiantes mostrados
                            </span>

                            <button type="button" id="btn-guardar-batch"
                                class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                    </div>

                    @if ($alumnos->hasPages())
                        <div class="mt-3 text-center">
                            <small class="text-muted fw-bold d-block mb-2 text-uppercase tracking-wider"
                                style="font-size: 0.75rem;">
                                Página {{ $alumnos->currentPage() }} de {{ $alumnos->lastPage() }}
                            </small>
                            <div class="d-flex justify-content-center">
                                {{ $alumnos->links('pagination::simple-bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                @else
                    <div class="card border-0 shadow-sm p-5 rounded-4 bg-white text-center my-4">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle text-primary bg-light border border-primary border-2"
                            style="width: 75px; height: 75px;">
                            <i class="bi bi-folder-symlink display-6"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">No se ha seleccionado un grupo</h4>
                        <p class="text-muted col-md-6 mx-auto mb-0">
                            Por favor, elija un grupo propedéutico desde el menú desplegable superior para visualizar el
                            listado de alumnos y capturar sus calificaciones.
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Carga Exitosa!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonColor: '#00723F',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Formato o Datos Incorrectos!',
                    html: '<p class="text-muted small text-center mb-0">El contenido o la estructura interna es incorrecto. Por favor, verifique los campos.</p>',
                    icon: 'error',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. CONTROL DE BÚSQUEDA EN TIEMPO REAL
            const searchInput = document.getElementById('search-alumno');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase().trim();
                    const rows = document.querySelectorAll('.student-row');
                    let visibles = 0;

                    rows.forEach(row => {
                        const matricula = row.getAttribute('data-matricula').toLowerCase();
                        const nombre = row.querySelector('.student-name').textContent.toLowerCase();

                        if (matricula.includes(query) || nombre.includes(query)) {
                            row.classList.remove('d-none');
                            visibles++;
                        } else {
                            row.classList.add('d-none');
                        }
                    });

                    document.getElementById('contador-estudiantes').textContent =
                        `${visibles} estudiantes mostrados`;
                });
            }

            document.querySelectorAll('.input-score').forEach(input => {
                input.addEventListener('input', function() {
                    let valString = this.value;

                    // Si contiene un punto, recortar a máximo 2 decimales
                    if (valString.includes('.')) {
                        const partes = valString.split('.');
                        if (partes[1].length > 2) {
                            valString = partes[0] + '.' + partes[1].slice(0, 2);
                            this.value = valString; // Forzar el recorte en la caja de texto
                        }
                    }

                    const valFloat = parseFloat(valString);

                    // Impedir que escriban más de 100
                    if (!isNaN(valFloat) && valFloat > 100) {
                        this.value = "100";
                    }

                    // Impedir números negativos
                    if (!isNaN(valFloat) && valFloat < 0) {
                        this.value = "0";
                    }

                    if (valString.length > 1 && valString.startsWith('0') && !valString.startsWith('0.')) {
                        this.value = valFloat;
                        valString = this.value; // Actualizamos la cadena limpia
                    }

                    // Control Visual de Color (Reprobados < 70 en Rojo)
                    if (!isNaN(valFloat) && valFloat < 60) {
                        this.classList.remove('text-dark', 'border-light', 'bg-light');
                        this.classList.add('text-danger', 'border-danger');
                    } else {
                        this.classList.remove('text-danger', 'border-danger');
                        this.classList.add('text-dark', 'border-light', 'bg-light');
                    }
                });
            });

            const btnGuardarBatch = document.getElementById('btn-guardar-batch');
            if (btnGuardarBatch) {
                btnGuardarBatch.addEventListener('click', function() {
                    const btn = this;
                    const filas = document.querySelectorAll('#tabla-estudiantes tbody tr.student-row');
                    const calificacionesPayload = {};
                    let calificacionInvalida = false;
                    let tieneDatos = false;

                    filas.forEach(fila => {
                        const inputInicial = fila.querySelector(
                            'input[data-field="examen_inicial"]');
                        const inputFinal = fila.querySelector('input[data-field="examen_final"]');
                        const matricula = fila.getAttribute('data-matricula');

                        if (matricula && inputInicial && inputFinal) {
                            const notaIni = inputInicial.value !== '' ? parseFloat(inputInicial
                                .value) : null;
                            const notaFin = inputFinal.value !== '' ? parseFloat(inputFinal.value) :
                                null;

                            if ((notaIni !== null && (notaIni < 0 || notaIni > 100)) ||
                                (notaFin !== null && (notaFin < 0 || notaFin > 100))) {
                                calificacionInvalida = true;
                            }

                            calificacionesPayload[matricula.trim()] = {
                                examen_inicial: notaIni,
                                examen_final: notaFin
                            };
                            tieneDatos = true;
                        }
                    });

                    if (calificacionInvalida) {
                        Swal.fire({
                            title: '¡Calificaciones Fuera de Rango!',
                            text: 'Las calificaciones deben ser un valor numérico entre 0 y 100.',
                            icon: 'error',
                            confirmButtonColor: '#dc3545',
                            confirmButtonText: 'Corregir datos'
                        });
                        return;
                    }

                    if (!tieneDatos) {
                        Swal.fire({
                            title: '¡Tabla Vacía!',
                            text: 'No hay registros válidos para actualizar.',
                            icon: 'warning',
                            confirmButtonColor: '#dc3545',
                            confirmButtonText: 'Aceptar'
                        });
                        return;
                    }

                    btn.disabled = true;
                    btn.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status"></span> GUARDANDO...';

                    fetch("{{ route('calificaciones.updateBatch') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                calificaciones: calificacionesPayload
                            })
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Error en el servidor');
                            return response.json();
                        })
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: '¡Carga Exitosa!',
                                    text: 'Las calificaciones se actualizaron con éxito en la base de datos.',
                                    icon: 'success',
                                    confirmButtonColor: '#00723F',
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                throw new Error(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: '¡Error al Guardar!',
                                text: 'Ocurrió un inconveniente al actualizar las notas en el servidor.',
                                icon: 'error',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'Entendido'
                            });
                            btn.disabled = false;
                            btn.innerHTML = 'Guardar';
                        });
                });
            }

            const btnDescargar = document.getElementById('btn-descargar-lista');
            if (btnDescargar) {
                btnDescargar.addEventListener('click', function(e) {
                    e.preventDefault();
                    const urlExportar = this.getAttribute('data-url');
                    if (urlExportar) {
                        window.location.href = urlExportar;
                    } else {
                        Swal.fire({
                            title: '¡Error de Ruta!',
                            text: 'No se pudo obtener la ruta de descarga.',
                            icon: 'error',
                            confirmButtonColor: '#dc3545',
                            confirmButtonText: 'Entendido'
                        });
                    }
                });
            }
        });
    </script>
@endsection
