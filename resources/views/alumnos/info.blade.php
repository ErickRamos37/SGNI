@extends('layouts.app')

@section('contenido')
<div class="container-fluid">

    {{-- ─── Encabezado de página + Buscador ──────────────────────── --}}
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Información Estudiantil</h2>
            <p class="text-muted mb-0">Perfil completo del estudiante</p>
        </div>
        <div class="w-25">
            <label class="small fw-bold text-muted text-uppercase mb-1">Buscar Alumno</label>
            <form id="form-buscar-alumno">
                <div class="input-group shadow-sm has-validation">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="number" id="matricula-input" class="form-control border-start-0 ps-0" placeholder="Ingrese matrícula..." required>
                    <button type="submit" id="btn-buscar" class="btn btn-outline-dark fw-semibold px-4">
                        <span id="btn-text">Buscar</span>
                        <span id="btn-spinner" class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                    </button>
                    <div id="matricula-error" class="invalid-feedback"></div>
                </div>
            </form>
        </div>
    </div>

    {{-- ─── Contenido del perfil (oculto hasta buscar) ────────────── --}}
    <div id="contenedor-info-alumno" class="d-none">

        {{-- Banner del alumno --}}
        <div class="card bg-primary text-white border border-light-subtle shadow-sm rounded-3 mb-4">
            <div class="card-body d-flex align-items-center p-4">
                <div class="bg-white text-primary rounded-circle d-flex justify-content-center align-items-center me-3 shadow-sm p-3">
                    <i class="bi bi-person fs-1"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0" id="lbl-nombre-completo">-</h3>
                    <p class="mb-0 fw-medium">Matrícula: <span id="lbl-matricula">-</span></p>
                </div>
            </div>
        </div>

        {{-- Tarjetas de información --}}
        <div class="row g-4">

            {{-- Información de contacto --}}
            <div class="col-md-6">
                <div class="card border border-light-subtle shadow-sm rounded-3 h-100">
                    <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
                        <h5 class="fw-bold text-uppercase text-white mb-0">Información de contacto</h5>
                    </div>
                    <div class="card-body bg-light p-4">

                        <div class="bg-white rounded-3 p-3 mb-3 shadow-sm border border-light">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-person-fill text-primary me-2"></i>
                                <small class="text-secondary text-uppercase fw-bold">Nombres</small>
                            </div>
                            <span class="fs-6 fw-bold text-dark" id="lbl-nombres">-</span>
                        </div>

                        <div class="bg-white rounded-3 p-3 mb-3 shadow-sm border border-light">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-person-fill text-primary me-2"></i>
                                <small class="text-secondary text-uppercase fw-bold">Apellidos</small>
                            </div>
                            <span class="fs-6 fw-bold text-dark" id="lbl-apellidos">-</span>
                        </div>

                        <div class="bg-white rounded-3 p-3 mb-3 shadow-sm border border-light">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-telephone-fill text-secondary me-2"></i>
                                <small class="text-secondary text-uppercase fw-bold">Teléfono</small>
                            </div>
                            <span class="fs-6 fw-bold text-dark" id="lbl-telefono">-</span>
                        </div>

                        <div class="bg-white rounded-3 p-3 shadow-sm border border-light">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-envelope-fill text-secondary me-2"></i>
                                <small class="text-secondary text-uppercase fw-bold">Correo Institucional</small>
                            </div>
                            <span class="fs-6 fw-bold text-dark" id="lbl-correo">-</span>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Información de admisión --}}
            <div class="col-md-6">
                <div class="card border border-light-subtle shadow-sm rounded-3 h-100">
                    <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
                        <h5 class="fw-bold text-uppercase text-white mb-0">Información de admisión</h5>
                    </div>
                    <div class="card-body bg-light p-4">

                        <div class="bg-white rounded-3 p-3 mb-3 shadow-sm border border-light">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-award-fill text-primary me-2"></i>
                                <small class="text-secondary text-uppercase fw-bold">Puntaje de Ingreso</small>
                            </div>
                            <div>
                                <span class="fs-1 fw-bold text-primary" id="lbl-puntaje">-</span>
                                <span class="text-muted ms-2">de 1300 puntos</span>
                            </div>
                        </div>

                        <div class="bg-white rounded-3 p-3 mb-3 shadow-sm border border-light">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-book-fill text-secondary me-2"></i>
                                <small class="text-secondary text-uppercase fw-bold">Carrera</small>
                            </div>
                            <span class="fs-6 fw-bold text-dark" id="lbl-carrera">-</span>
                        </div>

                        <div class="bg-white rounded-3 p-3 shadow-sm border border-light">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-people-fill text-secondary me-2"></i>
                                <small class="text-secondary text-uppercase fw-bold">Grupo de Inducción</small>
                            </div>
                            <span class="fs-6 fw-bold text-dark" id="lbl-grupo">-</span>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-buscar-alumno');
    const inputMatricula = document.getElementById('matricula-input');
    const btnBuscar = document.getElementById('btn-buscar');
    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');
    const containerInfo = document.getElementById('contenedor-info-alumno');
    const errorFeedback = document.getElementById('matricula-error');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const matricula = inputMatricula.value.trim();
        
        // 1. Prevención de Doble Envío y Spinner
        btnBuscar.disabled = true;
        btnText.classList.add('d-none');
        btnSpinner.classList.remove('d-none');
        
        // Limpiar errores previos
        inputMatricula.classList.remove('is-invalid');
        errorFeedback.innerText = '';
        containerInfo.classList.add('d-none'); // Ocultar info si hay una nueva busqueda

        // 2. Envío Asíncrono (Fetch API)
        fetch(`/alumnos/buscar`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ matricula: matricula })
        })
        .then(async response => {
            const data = await response.json();
            
            // Si el código no es 2xx, lanzamos el error para procesarlo en el catch
            if (!response.ok) {
                throw { status: response.status, data: data };
            }
            return data;
        })
        .then(data => {
            // 3. Respuesta Exitosa: Llenar el DOM
            const alumno = data.alumno;
            const apellidos = `${alumno.ap_pat} ${alumno.ap_mat || ''}`.trim();

            document.getElementById('lbl-nombre-completo').innerText = `${alumno.nombre} ${apellidos}`;
            document.getElementById('lbl-matricula').innerText = alumno.matricula;
            
            document.getElementById('lbl-nombres').innerText = alumno.nombre;
            document.getElementById('lbl-apellidos').innerText = apellidos;
            document.getElementById('lbl-telefono').innerText = alumno.telefono || 'No registrado';
            document.getElementById('lbl-correo').innerText = alumno.correo_institucional || 'No registrado';
            
            document.getElementById('lbl-puntaje').innerText = alumno.puntaje_ingreso || 'N/A';
            
            // Asumiendo que mandas las relaciones desde el controlador (con "with")
            document.getElementById('lbl-carrera').innerText = alumno.carrera ? alumno.carrera.nombre_carrera : 'No asignada';
            document.getElementById('lbl-grupo').innerText = alumno.grupos ? alumno.grupos.nombre_grupo : 'No asignado';

            // Mostrar el contenedor con la animación
            containerInfo.classList.remove('d-none');
            inputMatricula.value = ''; // Opcional: limpiar input
        })
        .catch(error => {
            // 4. Manejo de Errores Visuales (422 Validación o 404 No encontrado)
            inputMatricula.classList.add('is-invalid');
            
            if (error.status === 422 && error.data.errors) {
                // Errores de validación del FormRequest
                errorFeedback.innerText = error.data.errors.matricula[0];
            } else if (error.status === 404) {
                errorFeedback.innerText = 'No se encontró ningún alumno con esa matrícula.';
            } else {
                errorFeedback.innerText = 'Ocurrió un error en el servidor. Intente más tarde.';
            }
        })
        .finally(() => {
            // Restaurar botón
            btnBuscar.disabled = false;
            btnText.classList.remove('d-none');
            btnSpinner.classList.add('d-none');
        });
    });
});
</script>
@endsection