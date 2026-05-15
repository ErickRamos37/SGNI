@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Alta de Personal - Lista</h2>
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
                                    <th class="py-3">Fecha Registro</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                <tr>
                                    <td class="px-4 fw-bold text-dark">12345</td>
                                    <td>José Manuel</td>
                                    <td>García López</td>
                                    <td class="text-muted">jose.garcia@uabc.edu.mx</td>
                                    <td><span class="badge bg-light text-dark border">Docente</span></td>
                                    <td class="text-muted">2026-01-15</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection