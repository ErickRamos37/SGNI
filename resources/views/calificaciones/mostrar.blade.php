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
                                    --Seleccione un Grupo--  </option>
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
                                class="btn btn-secondary btn-sm fw-bold text-white shadow-sm rounded-3">
                                <i class="bi bi-download me-1"></i> DESCARGAR LISTA
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
                                            <th class="body-color fw-bold py-3" style="width: 45%;">Nombre del Alumno
                                            </th>
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
                                                    {{ $alumno->ap_pat }} {{ $alumno->ap_mat }} {{ $alumno->nombre }}
                                                </td>

                                                <td>
                                                    <div class="col-9 col-md-7 mx-auto">
                                                        <input type="number"
                                                            class="form-control text-center fw-bold rounded-3 shadow-sm input-score {{ !is_null($notaInicial) && $notaInicial < 70 ? 'text-danger border-danger' : 'text-dark border-light bg-light' }}"
                                                            data-field="examen_inicial" min="0" max="100"
                                                            value="{{ $notaInicial }}" placeholder="-">
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="col-9 col-md-7 mx-auto">
                                                        <input type="number"
                                                            class="form-control text-center fw-bold rounded-3 shadow-sm input-score {{ !is_null($notaFinal) && $notaFinal < 70 ? 'text-danger border-danger' : 'text-dark border-light bg-light' }}"
                                                            data-field="examen_final" min="0" max="100"
                                                            value="{{ $notaFinal }}" placeholder="-">
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
                                        @endempty
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
                            class="btn btn-secondary text-white fw-bold text-uppercase px-4 py-2 rounded-3 shadow-sm d-inline-flex align-items-center gap-2">
                            <i class="bi bi-save-fill"></i> Guardar
                        </button>
                    </div>

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
        </div>
    </div>
@else
    <div class="card border-0 shadow-sm p-5 rounded-4 bg-white text-center my-4">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle text-warning bg-light border border-warning border-2"
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

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const btnGuardarBatch = document.getElementById('btn-guardar-batch');

        if (btnGuardarBatch) {
            btnGuardarBatch.addEventListener('click', function() {
                const btn = this;
                const filas = document.querySelectorAll('#tabla-estudiantes tbody tr.student-row');
                const datos = [];
                filas.forEach(fila => {
                    const inputInicial = fila.querySelector(
                        'input[data-field="examen_inicial"]');
                    const inputFinal = fila.querySelector('input[data-field="examen_final"]');
                    const matricula = fila.getAttribute('data-matricula');

                    if (matricula && inputInicial && inputFinal) {
                        datos.push({
                            matricula: matricula.trim(),
                            examen_inicial: inputInicial.value !== '' ? parseFloat(
                                inputInicial.value) : null,
                            examen_final: inputFinal.value !== '' ? parseFloat(
                                inputFinal.value) : null
                        });
                    }
                });

                if (datos.length === 0) {
                    alert('No hay alumnos válidos en la tabla para guardar.');
                    return;
                }

                btn.disabled = true;
                btn.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status"></span> GUARDANDO...';

                fetch("{{ route('calificaciones.guardarTablaDirecto') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            calificaciones: datos
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Respuesta incorrecta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            alert(
                                '¡Listo! Las calificaciones se actualizaron con éxito en la base de datos.'
                            );
                            window.location
                                .reload();
                        } else {
                            alert('Error: ' + data.message);
                            btn.disabled = false;
                            btn.innerHTML = '<i class="bi bi-save-fill"></i> Guardar';
                        }
                    })
                    .catch(error => {
                        console.error('Error detallado:', error);
                        alert('Inconveniente de comunicación con el servidor al intentar guardar.');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-save-fill"></i> Guardar';
                    });
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
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
                const val = parseInt(this.value);
                if (!isNaN(val) && val < 70) {
                    this.classList.remove('text-dark', 'border-light', 'bg-light');
                    this.classList.add('text-danger', 'border-danger');
                } else {
                    this.classList.remove('text-danger', 'border-danger');
                    this.classList.add('text-dark', 'border-light', 'bg-light');
                }
            });
        });

        const btnGuardar = document.getElementById('btn-guardar-batch');
        if (btnGuardar) {
            btnGuardar.addEventListener('click', function() {
                const btn = this;
                const rows = document.querySelectorAll('tbody tr[data-matricula]');
                const calificacionesPayload = {};

                rows.forEach(row => {
                    const matricula = row.getAttribute('data-matricula');
                    const inputInicial = row.querySelector(
                        'input[data-field="examen_inicial"]');
                    const inputFinal = row.querySelector('input[data-field="examen_final"]');

                    if (inputInicial.value !== '' || inputFinal.value !== '') {
                        calificacionesPayload[matricula] = {
                            examen_inicial: inputInicial.value !== '' ? parseInt(
                                inputInicial.value) : null,
                            examen_final: inputFinal.value !== '' ? parseInt(inputFinal
                                .value) : null
                        };
                    }
                });

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
                    .then(response => response.json())
                    .then(data => {
                        const alertContainer = document.getElementById('alert-container-ajax');
                        const alertBox = document.getElementById('alert-box-ajax');
                        const alertIcon = document.getElementById('alert-icon-ajax');
                        const alertMessage = document.getElementById('alert-message-ajax');

                        alertContainer.classList.remove('d-none');
                        alertMessage.textContent = data.message;

                        if (data.status === 'success') {
                            alertBox.className =
                                "alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm rounded-3";
                            alertIcon.className = "bi bi-check-circle-fill fs-4 me-3";
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                            setTimeout(() => {
                                window.location.reload();
                            }, 1200);
                        } else {
                            alertBox.className =
                                "alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm rounded-3";
                            alertIcon.className = "bi bi-exclamation-triangle-fill fs-4 me-3";
                            btn.disabled = false;
                            btn.innerHTML = '<i class="bi bi-save-fill"></i> GUARDAR';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Inconveniente de comunicación con el servidor.');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="bi bi-save-fill"></i> GUARDAR';
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
                    alert('No se pudo obtener la ruta de descarga.');
                }
            });
        }
    });
</script>
@endsection
