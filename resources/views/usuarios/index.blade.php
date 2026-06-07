@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Personal</h2>
                    <p class="text-muted mb-0">Listado completo de personal registrado</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-primary p-4 border-bottom">
                    <h5 class="fw-bold text-uppercase text-white mb-0">Lista de Personal</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="tabla-usuarios" class="table table-hover align-middle mb-0" style="width:100%">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="px-2">Num. Empleado</th>
                                    <th class="py-3">Nombres</th>
                                    <th class="py-3">Apellidos</th>
                                    <th class="py-3">Correo</th>
                                    <th class="py-3">Cargo</th>
                                    <th class="py-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module">
    $(document).ready(function() {
        let tablaUsuarios = $('#tabla-usuarios').DataTable({
            processing: true, // Muestra el texto "Cargando..."
            serverSide: true, // Delega la paginación y búsqueda al servidor (Laravel)
            ajax: "{{ route('usuarios.index') }}", // Llama al método index() por AJAX
            columns: [
                // Los nombres deben coincidir con la Base de Datos para las búsquedas.
                // El atributo 'data' es lo que devuelve el Controlador.
                {
                    data: 'num_empleado',
                    name: 'num_empleado',
                    className: 'fw-bold text-dark'
                },
                {
                    data: 'nombre',
                    name: 'nombre'
                },
                {
                    data: 'apellidos',
                    name: 'apellidos',
                    orderable: false,
                    searchable: false
                }, // Columna virtual
                {
                    data: 'correo_institucional',
                    name: 'correo_institucional',
                    className: 'text-muted'
                },
                {
                    data: 'rol_nombre',
                    name: 'rol.nombre_rol',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'acciones',
                    name: 'acciones',
                    orderable: false,
                    searchable: false
                }
            ]
        });
        // ---------------------------------------------------------
        // Script para Eliminar con Confirmación
        // ---------------------------------------------------------
        // Usamos delegación de eventos ('on') porque los botones son dinámicos
        $('#tabla-usuarios').on('click', '.btn-eliminar', function(e) {
            e.preventDefault();

            // Obtenemos la URL que pusimos en el data-url del botón
            let urlDelete = $(this).data('url');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "El usuario será eliminado permanentemente del sistema.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                // 1. Inyectamos las clases de Bootstrap
                customClass: {
                    confirmButton: 'btn btn-danger me-3', // Botón rojo con margen derecho
                    cancelButton: 'btn btn-dark' // Botón oscuro con efecto hover
                },
                // 2. Apagamos los estilos nativos de SweetAlert para que Bootstrap haga su magia
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {

                    // Hacemos la petición DELETE por Fetch
                    // Hacemos la petición DELETE por Fetch
                    fetch(urlDelete, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Token de seguridad de Laravel
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success || data.message) {
                                // Alerta de Éxito estilizada
                                Swal.fire({
                                    title: 'Usuario Eliminado',
                                    text: data.message || 'El registro ha sido eliminado.',
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar',
                                    customClass: {
                                        confirmButton: 'btn btn-dark'
                                    },
                                    buttonsStyling: false
                                });
                                // Recarga la tabla sin perder la paginación actual
                                tablaUsuarios.ajax.reload(null, false);
                            } else {
                                // Alerta de Error (Devuelto por el backend) estilizada
                                Swal.fire({
                                    title: 'Error',
                                    text: data.message || 'No se pudo eliminar el registro.',
                                    icon: 'error',
                                    confirmButtonText: 'Entendido',
                                    customClass: {
                                        confirmButton: 'btn btn-dark'
                                    },
                                    buttonsStyling: false
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Alerta de Error (Problema de conexión o servidor) estilizada
                            Swal.fire({
                                title: 'Error',
                                text: 'Hubo un problema de conexión.',
                                icon: 'error',
                                confirmButtonText: 'Entendido',
                                customClass: {
                                    confirmButton: 'btn btn-dark'
                                },
                                buttonsStyling: false
                            });
                        });
                }
            });
        });
    });
</script>
@endsection