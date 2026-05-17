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
                <div class="w-25">
                    <label class="small fw-bold text-muted text-uppercase mb-1">Buscar Profesor</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control" placeholder="Matrícula, nombre o correo...">
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold text-uppercase text-dark mb-0">Lista de Personal Registrado</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="px-4 py-3">Num. Empleado</th>
                                    <th class="py-3">Nombres</th>
                                    <th class="py-3">Apellidos</th>
                                    <th class="py-3">Correo</th>
                                    <th class="py-3">Rol</th>
                                    <!-- <th class="py-3">Estado</th> -->
                                    <!-- <th class="py-3">Fecha Registro</th> -->
                                </tr>
                            </thead>
                            <tbody class="small">
                                @if($usuarios->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                        No hay personal registrado en el sistema.
                                    </td>
                                </tr>
                                @else
                                @foreach($usuarios as $usuario)
                                <tr>
                                    <td class="px-4 fw-bold text-dark">{{ $usuario->num_empleado }}</td>
                                    <td>{{ $usuario->nombre }}</td>
                                    <td>{{ $usuario->ap_pat }} {{ $usuario->ap_mat }}</td>
                                    <td class="text-muted">{{ $usuario->correo_institucional }}</td>
                                    <td>{{ $usuario->rol ? $usuario->rol->nombre_rol : 'Sin rol asignado' }}</td>
                                    <!-- <td class="text-muted"> {{ $usuario->created_at ? $usuario->created_at->format('Y-m-d') : 'Sin fecha' }}</td> -->
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection