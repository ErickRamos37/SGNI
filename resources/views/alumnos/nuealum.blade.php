@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-12">
            <h2 class="fw-bold mb-0 text-primary">Alta de Alumnos Tardíos</h2>
            <p class="text-muted mb-0">Registro manual de estudiantes de nuevo ingreso</p>
        </div>
    </div>

    <div id="alert-success" class="alert alert-success d-flex align-items-center d-none shadow-sm rounded-3" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-4"></i>
        <div>
            <strong>¡Éxito!</strong> El alumno se ha registrado correctamente en el sistema.
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 bg-white">
        <div class="card-body p-4">
            <form id="form-nuevo-alumno" novalidate>
                <div class="row g-4">
                    
                    <div class="col-md-4">
                        <label for="matricula" class="form-label fw-bold text-secondary">Matrícula <span class="text-danger">*</span></label>
                        <input type="number" class="form-control bg-light" id="matricula" name="matricula" placeholder="Ej. 1234567" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="nombre" class="form-label fw-bold text-secondary">Nombre(s) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light" id="nombre" name="nombre" placeholder="Nombre completo" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-md-4">
                        <label for="ap_pat" class="form-label fw-bold text-secondary">Apellido Paterno <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light" id="ap_pat" name="ap_pat" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-md-4">
                        <label for="ap_mat" class="form-label fw-bold text-secondary">Apellido Materno</label>
                        <input type="text" class="form-control bg-light" id="ap_mat" name="ap_mat">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-md-4">
                        <label for="correo_alternativo" class="form-label fw-bold text-secondary">Correo Alternativo</label>
                        <input type="email" class="form-control bg-light" id="correo_alternativo" name="correo_alternativo" placeholder="ejemplo@gmail.com">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-md-4">
                        <label for="telefono" class="form-label fw-bold text-secondary">Teléfono <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control bg-light" id="telefono" name="telefono" placeholder="Ej. 6861234567" required>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="col-md-4">
                        <label for="id_carrera" class="form-label fw-bold text-secondary">Carrera <span class="text-danger">*</span></label>
                        <select class="form-select bg-light" id="id_carrera" name="id_carrera" required>
                            <option value="" selected disabled>Seleccione una carrera...</option>
                            <option value="1">Tronco Común</option>
                            </select>
                        <div class="invalid-feedback"></div>
                    </div>

                </div>

                <hr class="mt-4 mb-3 border-light">

                <div class="d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-outline-secondary fw-bold px-4">Limpiar</button>
                    <button type="submit" id="btn-guardar" class="btn btn-primary text-white fw-bold px-5 shadow-sm">
                        <span id="btn-text">Registrar Alumno</span>
                        <span id="btn-spinner" class="spinner-border spinner-border-sm d-none" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-nuevo-alumno');
    const btnGuardar = document.getElementById('btn-guardar');
    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');
    const alertSuccess = document.getElementById('alert-success');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Prevención de doble envío
        btnGuardar.disabled = true;
        btnText.classList.add('d-none');
        btnSpinner.classList.remove('d-none');
        alertSuccess.classList.add('d-none');

        // Limpiar errores visuales
        const inputs = form.querySelectorAll('.form-control, .form-select');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
            const feedback = input.nextElementSibling;
            if(feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.innerText = '';
            }
        });

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        fetch(`/alumnos`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(async response => {
            const resData = await response.json();
            if (!response.ok) {
                throw { status: response.status, data: resData };
            }
            return resData;
        })
        .then(data => {
            form.reset();
            alertSuccess.classList.remove('d-none');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        })
        .catch(error => {
            if (error.status === 422 && error.data.errors) {
                const errors = error.data.errors;
                for (const field in errors) {
                    const input = document.getElementById(field);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = input.nextElementSibling;
                        if(feedback && feedback.classList.contains('invalid-feedback')) {
                            feedback.innerText = errors[field][0];
                        }
                    }
                }
            } else {
                alert('Ocurrió un error en el servidor. Revise la consola.');
                console.error(error);
            }
        })
        .finally(() => {
            btnGuardar.disabled = false;
            btnText.classList.remove('d-none');
            btnSpinner.classList.add('d-none');
        });
    });
});
</script>
@endsection