@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold text-dark">Alta de Alumnos Tardíos</h2>
            <p class="text-muted">Registro manual de estudiantes de nuevo ingreso</p>

            <div class="mb-4">
                <div class="card border border-light-subtle shadow-sm rounded-3 bg-white h-100">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold text-primary mb-4 d-flex align-items-center">
                            <i class="bi bi-person-plus me-2 fs-4"></i>
                            <span>Registrar alumno</span>
                        </h5>

                        <div id="mensajeExito" class="alert alert-success d-none mb-4 rounded-3 shadow-sm"></div>

                        <form id="formNuevoAlumno" action="{{ url('/alumnos') }}" method="POST">
                            @csrf

                            <div class="row g-4 mb-4">
                                <div class="col-md-6 col-xl-4">
                                    <label for="matricula" class="form-label text-dark fw-semibold">Matrícula <span class="text-danger">*</span></label>
                                    <input type="number" name="matricula" id="matricula" class="form-control shadow-sm" placeholder="Ej: 1234567" required>
                                    <div class="invalid-feedback" id="error-matricula"></div>
                                </div>
                                <div class="col-md-6 col-xl-8">
                                    <label for="nombre" class="form-label text-dark fw-semibold">Nombre(s) <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" id="nombre" class="form-control shadow-sm" placeholder="Ej: Juan Carlos" required>
                                    <div class="invalid-feedback" id="error-nombre"></div>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label for="ap_pat" class="form-label text-dark fw-semibold">Apellido paterno <span class="text-danger">*</span></label>
                                    <input type="text" name="ap_pat" id="ap_pat" class="form-control shadow-sm" placeholder="Ej: López" required>
                                    <div class="invalid-feedback" id="error-ap_pat"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="ap_mat" class="form-label text-dark fw-semibold">Apellido materno</label>
                                    <input type="text" name="ap_mat" id="ap_mat" class="form-control shadow-sm" placeholder="Ej: Martínez">
                                    <div class="invalid-feedback" id="error-ap_mat"></div>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label for="correo_alternativo" class="form-label text-dark fw-semibold">Correo alternativo</label>
                                    <input type="email" name="correo_alternativo" id="correo_alternativo" class="form-control shadow-sm" placeholder="ejemplo@gmail.com">
                                    <div class="invalid-feedback" id="error-correo_alternativo"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label text-dark fw-semibold">Teléfono <span class="text-danger">*</span></label>
                                    <input type="tel" name="telefono" id="telefono" class="form-control shadow-sm" placeholder="Ej: 6861234567" required>
                                    <div class="invalid-feedback" id="error-telefono"></div>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label for="id_carrera" class="form-label text-dark fw-semibold">Carrera <span class="text-danger">*</span></label>
                                    <select name="id_carrera" id="id_carrera" class="form-select shadow-sm" required>
                                        <option value="" selected disabled>-- Seleccione una carrera --</option>
                                        <option value="1">Tronco Común</option>
                                    </select>
                                    <div class="invalid-feedback" id="error-id_carrera"></div>
                                </div>
                            </div>

                            <hr class="my-4 border-light-subtle">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ url('/alumnos') }}" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                    Cancelar
                                </a>
                                <button type="submit" id="btnGuardar" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formNuevoAlumno');
        const btnGuardar = document.getElementById('btnGuardar');
        const mensajeExito = document.getElementById('mensajeExito');

        // Delegación de eventos para limpiar errores al escribir
        form.addEventListener('input', function(e) {
            if (e.target.classList.contains('is-invalid')) {
                e.target.classList.remove('is-invalid');
                const errorDiv = document.getElementById(`error-${e.target.id}`);
                if (errorDiv) {
                    errorDiv.innerHTML = '';
                }
            }
        });

        // Limpiar selects al cambiar de opción
        form.addEventListener('change', function(e) {
            if (e.target.tagName === 'SELECT' && e.target.classList.contains('is-invalid')) {
                e.target.classList.remove('is-invalid');
                const errorDiv = document.getElementById(`error-${e.target.id}`);
                if (errorDiv) {
                    errorDiv.innerHTML = '';
                }
            }
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Bloquear botón
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';

            // Limpiar errores visuales
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.innerHTML = '');
            mensajeExito.classList.add('d-none');

            try {
                const formData = new FormData(form);

                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    mensajeExito.innerHTML = `<i class="bi bi-check-circle-fill"></i> ${data.message || 'El alumno se ha registrado correctamente en el sistema.'}`;
                    mensajeExito.classList.remove('d-none');
                    form.reset();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else if (response.status === 422) {
                    const errores = data.errors;

                    for (const campo in errores) {
                        const input = document.getElementById(campo);
                        const errorDiv = document.getElementById(`error-${campo}`);

                        if (input && errorDiv) {
                            input.classList.add('is-invalid');
                            errorDiv.innerHTML = errores[campo][0];
                        }
                    }
                } else {
                    alert('Ocurrió un error inesperado en el servidor.');
                }

            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión. Revisa tu internet.');
            } finally {
                // Desbloquear botón
                btnGuardar.disabled = false;
                btnGuardar.innerHTML = 'Guardar';
            }
        });
    });
</script>
@endsection