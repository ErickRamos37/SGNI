@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold text-dark">Edición de Personal</h2>
            <p class="text-muted">Actualizar información del personal</p>

            <div class="mb-4">
                <div class="card border border-light-subtle shadow-sm rounded-3 bg-white h-100">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold text-primary mb-4 d-flex align-items-center">
                            <i class="bi bi-pencil-square me-2 fs-4"></i>
                            <span>Editar personal</span>
                        </h5>

                        {{-- Formulario principal de edición con Fetch AJAX --}}
                        <form id="formEdicionUsuario" action="{{ route('usuarios.update', $usuario->id_usuario ?? $usuario->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-4 mb-4">
                                <div class="col-md-6 col-xl-4">
                                    <label for="num_empleado" class="form-label text-dark fw-semibold">Número de empleado <span class="text-danger">*</span></label>
                                    <input type="text" name="num_empleado" id="num_empleado" class="form-control shadow-sm" placeholder="Ej: 12345" value="{{ old('num_empleado', $usuario->num_empleado) }}" required>
                                    <div class="invalid-feedback" id="error-num_empleado"></div>
                                </div>
                                <div class="col-md-6 col-xl-8">
                                    <label for="nombre" class="form-label text-dark fw-semibold">Nombre(s) <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" id="nombre" class="form-control shadow-sm" placeholder="Ej: Juan Carlos" value="{{ old('nombre', $usuario->nombre) }}" required>
                                    <div class="invalid-feedback" id="error-nombre"></div>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label for="ap_pat" class="form-label text-dark fw-semibold">Apellido paterno <span class="text-danger">*</span></label>
                                    <input type="text" name="ap_pat" id="ap_pat" class="form-control shadow-sm" placeholder="Ej: López" value="{{ old('ap_pat', $usuario->ap_pat) }}" required>
                                    <div class="invalid-feedback" id="error-ap_pat"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="ap_mat" class="form-label text-dark fw-semibold">Apellido materno</label>
                                    <input type="text" name="ap_mat" id="ap_mat" class="form-control shadow-sm" placeholder="Ej: Martínez" value="{{ old('ap_mat', $usuario->ap_mat) }}">
                                    <div class="invalid-feedback" id="error-ap_mat"></div>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label for="correo_institucional" class="form-label text-dark fw-semibold">Correo institucional <span class="text-danger">*</span></label>
                                    <input type="email" name="correo_institucional" id="correo_institucional" class="form-control shadow-sm" placeholder="ejemplo@uabc.edu.mx" value="{{ old('correo_institucional', $usuario->correo_institucional) }}" required>
                                    <div class="invalid-feedback" id="error-correo_institucional"></div>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label for="id_rol" class="form-label text-dark fw-semibold">Cargo <span class="text-danger">*</span></label>
                                    <select name="id_rol" id="id_rol" class="form-select shadow-sm" required>
                                        <option value="" disabled>-- Seleccione un rol --</option>
                                        @foreach($roles as $rol)
                                        <option value="{{ $rol->id_rol }}" {{ old('id_rol', $usuario->id_rol) == $rol->id_rol ? 'selected' : '' }}>
                                            {{ $rol->nombre_rol }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="error-id_rol"></div>
                                </div>
                            </div>

                            <hr class="my-4 border-light-subtle">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('formEdicionUsuario');
        const btnGuardar = document.getElementById('btnGuardar');

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

        // Este evento asegura que los <select> también se limpien al cambiar de opción
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

            // 1. Bloquea el botón para evitar doble clic
            btnGuardar.disabled = true;
            btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';

            // 2. Limpia cualquier error rojo
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.innerHTML = '');

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
                    Swal.fire({
                        title: '¡Acción Exitosa!',
                        text: data.message,
                        icon: 'success',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false,
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        // Esta validación revisa si el usuario hizo clic en "Aceptar"
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('usuarios.index') }}"; // Redirige al índice
                        }
                    });
                } else if (response.status === 422) {
                    // Error 422: Falló la validación
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
                btnGuardar.disabled = false;
                btnGuardar.innerHTML = 'Guardar';
            }
        });
    });
</script>
@endsection