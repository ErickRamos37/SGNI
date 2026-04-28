@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h1 class="fw-bold text-primary">Importar Reporte de Asistencias</h1>
            <p class="text-muted">Cargue el archivo Excel con los porcentajes de asistencia por alumno para el curso seleccionado.</p>
            
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4 p-md-5">
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Curso</label>
                                <select class="form-select form-select-lg border-2" required>
                                    <option value="" selected disabled>Seleccione el curso...</option>
                                    <option>Curso de Matemáticas</option>
                                    <option>Curso de Inducción</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Grupo</label>
                                <select class="form-select form-select-lg border-2" required>
                                    <option value="" selected disabled>Seleccione el grupo...</option>
                                    <option>Grupo A - Ingeniería</option>
                                    <option>Grupo B - Arquitectura</option>
                                </select>
                            </div>
                        </div>

                        <!-- <div class="mb-5">
                            <label class="form-label fw-bold">Archivo Excel (.xlsx)</label>
                            <div class="bg-light border rounded-3 p-4">
                                <input type="file" class="form-control form-control-lg border-0 bg-transparent" accept=".xlsx, .xls" required>
                                <div class="mt-2 text-muted small ps-2">
                                    <i class="bi bi-info-circle me-1"></i> Asegúrese de que el archivo contenga la columna de porcentajes.
                                </div>
                            </div>
                        </div> -->

                        <div class="mb-5">
                            <label class="form-label fw-bold mb-3">Archivo Excel (.xlsx)</label>
                            <div class="border border-2 border-dashed rounded-4 p-5 text-center bg-light">
                                <i class="bi bi-file-earmark-excel display-1 text-primary mb-3"></i>
                                <h5>Arrastre el archivo Excel aquí o haga clic para seleccionar</h5>
                                <input type="file" class="form-control mt-3" accept=".xlsx, .xls" required>
                                <div class="mt-2 text-muted small ps-2">
                                    <i class="bi bi-info-circle me-1"></i> Asegúrese de que el archivo contenga la columna de porcentajes.
                                </div>
                            </div>
                            <a href="#" class="text-decoration-none text-muted small border-bottom border-secondary">
                                Descargar plantilla de ejemplo
                            </a>
                        </div>

                        <!-- <div class="d-flex flex-column align-items-center gap-3 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 fw-bold rounded-3 shadow-sm" style="min-width: 300px;">
                                <i class="bi bi-check-circle-fill me-2"></i> Subir Asistencias
                            </button>
                            <a href="#" class="text-decoration-none text-muted small border-bottom border-secondary">
                                Descargar plantilla de ejemplo
                            </a>
                        </div> -->

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 fw-bold rounded-3 shadow-sm">
                                <i class="bi bi-check-circle-fill me-2"></i> Subir Asistencias
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection