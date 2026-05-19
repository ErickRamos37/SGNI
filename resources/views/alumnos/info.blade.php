@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="text-primary fw-bold">Información Estudiantil</h2>
            <p class="text-muted mb-0">Perfil completo del estudiante</p>
        </div>
        <div class="col-md-6 d-flex justify-content-md-end mt-3 mt-md-0">
            <form id="form-buscar-alumno" class="d-flex w-100" style="max-width: 400px;">
                <div class="w-100 position-relative">
                    <input type="number" id="matricula-input" class="form-control rounded-start" placeholder="Ingrese matrícula..." required>
                    <div id="matricula-error" class="invalid-feedback position-absolute"></div>
                </div>
                <button type="submit" id="btn-buscar" class="btn btn-primary text-white rounded-end px-4 fw-bold shadow-sm">
                    <span id="btn-text">Buscar</span>
                    <span id="btn-spinner" class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                </button>
            </form>
        </div>
    </div>

    <div id="contenedor-info-alumno" class="d-none">
        
        <div class="card bg-primary text-white border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body d-flex align-items-center p-4">
                <div class="bg-white text-primary rounded-circle d-flex justify-content-center align-items-center me-3 shadow-sm" style="width: 70px; height: 70px;">
                    <i class="bi bi-person fs-1"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0" id="lbl-nombre-completo">-</h3>
                    <p class="mb-0 fw-medium">Matrícula: <span id="lbl-matricula">-</span></p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-transparent">
                    <div class="card-header bg-primary text-white text-uppercase fw-bold py-3 border-0 rounded-top-4">
                        Información de contacto
                    </div>
                    <div class="card-body bg-light rounded-bottom-4 p-4">
                        
                        <div class="bg-white rounded-3 p-3 mb-3 shadow-sm border border-light">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-person-fill text-primary me-2"></i>
                                <small class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Nombres</small>
                            </div>
                            <span class="fs-6 fw-bold text-dark" id="lbl-nombres">-</span>
                        </div>

                        <div class="bg-white rounded-3 p-3 mb-3 shadow-sm border border-light">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-person-fill text-primary me-2"></i>
                                <small class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Apellidos</small>
                            </div>
                            <span class="fs-6 fw-bold text-dark" id="lbl-apellidos">-</span>
                        </div>

                        <div class="bg-white rounded-3 p-3 mb-3 shadow-sm border border-light">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-telephone-fill text-secondary me-2"></i>
                                <small class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Teléfono</small>
                            </div>
                            <span class="fs-6 fw-bold text-dark" id="lbl-telefono">-</span>
                        </div>

                        <div class="bg-white rounded-3 p-3 shadow-sm border border-light">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-envelope-fill text-secondary me-2"></i>
                                <small class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Correo Institucional</small>
                            </div>
                            <span class="fs-6 fw-bold text-dark" id="lbl-correo">-</span>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-transparent">
                    <div class="card-header bg-secondary text-white text-uppercase fw-bold py-3 border-0 rounded-top-4">
                        Información de admisión
                    </div>
                    <div class="card-body bg-light rounded-bottom-4 p-4">
                        
                        <div class="bg-white rounded-3 p-3 mb-3 shadow-sm border border-light">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-award-fill text-primary me-2"></i>
                                <small class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Puntaje de Ingreso</small>
                            </div>
                            <div>
                                <span class="fs-1 fw-bold text-primary" id="lbl-puntaje">-</span>
                                <span class="text-muted ms-2">de 1300 puntos</span>
                            </div>
                        </div>

                        <div class="bg-white rounded-3 p-3 mb-3 shadow-sm border border-light">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-book-fill text-secondary me-2"></i>
                                <small class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Carrera</small>
                            </div>
                            <span class="fs-6 fw-bold text-dark" id="lbl-carrera">-</span>
                        </div>

                        <div class="bg-white rounded-3 p-3 shadow-sm border border-light">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-people-fill text-secondary me-2"></i>
                                <small class="text-secondary text-uppercase fw-bold" style="font-size: 0.75rem;">Grupo de Inducción</small>
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