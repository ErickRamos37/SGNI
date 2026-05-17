@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold text-dark">Alta de Personal - Registro</h2>
            <p class="text-muted">Registre nuevo personal docente o administrativo</p>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold text-primary mb-4 text-uppercase"><i class="bi bi-person-plus me-2"></i> Registrar Docente en Sistema</h5>

                    <div id="mensajeExito" class="alert alert-success d-none mb-3"></div>

                    <form id="formAltaUsuario" action="{{ route('usuarios.store') }}" method="POST">
                        @csrf
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="num_empleado" class="form-label">Número de Empleado</label>
                                <input type="text" name="num_empleado" id="num_empleado" class="form-control" placeholder="Ej: 12345" required>
                                <div class="invalid-feedback" id="error-num_empleado"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Nombre(s)</label>
                                <input type="text" name="nombre" class="form-control" placeholder="Ej: Juan Carlos" required>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Apellido Paterno</label>
                                <input type="text" name="ap_pat" class="form-control" placeholder="Ej: López" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Apellido Materno</label>
                                <input type="text" name="ap_mat" class="form-control" placeholder="Ej: Martínez">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="correo_institucional" class="form-label">Correo Institucional</label>
                            <input type="email" name="correo_institucional" id="correo_institucional" class="form-control" placeholder="ejemplo@uabc.edu.mx" required>
                            <div class="invalid-feedback" id="error-correo_institucional"></div>
                        </div>
                </div>

                <div class="mb-5 p-4 border rounded bg-white shadow-sm">
                    <label class="form-label fw-bold text-primary mb-3 text-uppercase"><i class="bi bi-shield-lock me-2"></i>Rol en el Sistema</label>
                    <p class="small text-muted mb-3">Seleccione el nivel de acceso que tendrá este usuario.</p>

                    <select name="id_rol" class="form-select border-primary border-opacity-50" required>
                        <option value="" selected disabled>-- Seleccione un rol --</option>
                        @foreach($roles as $rol)
                        <option value="{{ $rol->id_rol }}">{{ $rol->nombre_rol }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" id="btnGuardar" class="btn btn-secondary px-5 py-2 fw-bold text-dark rounded-pill shadow-sm">
                        GUARDAR
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formAltaUsuario');
    const btnGuardar = document.getElementById('btnGuardar');
    const mensajeExito = document.getElementById('mensajeExito');

    form.addEventListener('submit', async function(e) {
        e.preventDefault(); // Evita que la página parpadee o recargue

        // 1. Bloquea el botón para evitar que el usuario le dé doble clic
        btnGuardar.disabled = true;
        btnGuardar.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';

        // 2. Limpia cualquier error rojo que haya quedado de un intento anterior
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.innerHTML = '');
        mensajeExito.classList.add('d-none');

        try {
            // Recolecta todo lo que el usuario escribió
            const formData = new FormData(form);

            // 3. Enviamos la petición asíncrona a Laravel
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    // Evitamos redireccionamiento y recibimos los errores en JSON"
                    'Accept': 'application/json', 
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            // 4. Se analiza la respuesta
            if (response.ok) {
                // El Form Request aprobó y se guardó en BD
                mensajeExito.innerHTML = `<i class="bi bi-check-circle-fill"></i> ${data.message}`;
                mensajeExito.classList.remove('d-none');
                
                form.reset(); // Vacia el formulario
                
            } else if (response.status === 422) {
                // Error 422: Falló la validación del Form Request
                const errores = data.errors;
                
                // Recorremos los errores y pintamos de rojo los inputs correspondientes
                for (const campo in errores) {
                    const input = document.getElementById(campo);
                    const errorDiv = document.getElementById(`error-${campo}`);

                    if (input && errorDiv) {
                        input.classList.add('is-invalid'); // Le agrega el borde rojo al input
                        errorDiv.innerHTML = errores[campo][0]; // Escribe el mensaje
                    }
                }
            } else {
                alert('Ocurrió un error inesperado en el servidor.');
            }

        } catch (error) {
            console.error('Error:', error);
            alert('Error de conexión. Revisa tu internet.');
        } finally {
            // 5. Pase lo que pase, desbloquea el botón al terminar
            btnGuardar.disabled = false;
            btnGuardar.innerHTML = 'Guardar';
        }
    });
});
</script>
@endsection