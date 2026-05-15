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

                    <form action="{{ route('usuarios.store') }}" method="POST">
                        @csrf 
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Número de Empleado</label>
                                <input type="number" name="num_empleado" class="form-control" placeholder="Ej: 12345" required>
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
                            <label class="form-label fw-bold small text-uppercase text-muted"><i class="bi bi-envelope me-2"></i>Correo Electrónico Institucional</label>
                            <input type="email" name="correo_institucional" class="form-control" placeholder="nombre.apellido@uabc.edu.mx" required>
                        </div>

                        <div class="mb-5 p-4 border rounded bg-white shadow-sm">
                            <label class="form-label fw-bold text-primary mb-3 text-uppercase"><i class="bi bi-shield-lock me-2"></i>Rol en el Sistema</label>
                            <p class="small text-muted mb-3">Seleccione el nivel de acceso que tendrá este usuario.</p>
                            
                            <select name="id_rol" class="form-select form-select-lg border-primary border-opacity-50" required>
                                <option value="" selected disabled>-- Seleccione un rol --</option>
                                <option value="1">Docente</option>
                                <option value="2">Directivo</option>
                                <option value="3">Psicopedagogía</option>
                                <option value="4">Administrador</option>
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-secondary px-5 py-2 fw-bold text-dark rounded-pill shadow-sm">
                                GUARDAR REGISTRO
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection