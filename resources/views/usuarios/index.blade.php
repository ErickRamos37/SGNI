@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Personal - Lista</h2>
                    <p class="text-muted mb-0">Listado completo de personal registrado</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-primary p-4 border-bottom">
                    <h5 class="fw-bold text-uppercase text-white mb-0">Lista de Personal Registrado</h5>
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
                                    <th class="py-3">Rol</th>
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
        $('#tabla-usuarios').DataTable({
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
    });
</script>
@endsection