@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold text-primary">Crear Grupos (Carga Masiva)</h2>
            <p class="text-muted">Seleccione el curso e importe la lista de alumnos para generar los grupos automáticamente.</p>
            
            <div class="card border-0 shadow-sm rounded-3">    
                <div class="card-body p-4 p-md-5">
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <div class="mb-5">
                            <label class="form-label fw-bold mb-3">1. Seleccione el Curso de Aplicación</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="curso" id="matematicas" checked>
                                    <label class="btn btn-outline-primary w-100 p-4 text-start rounded-3" for="matematicas">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calculator display-6 me-3"></i>
                                            <div>
                                                <span class="d-block fw-bold fs-5">Curso de Matemáticas</span>
                                                <small>Periodo inicial de nivelación académica.</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="curso" id="induccion">
                                    <label class="btn btn-outline-primary w-100 p-4 text-start rounded-3" for="induccion">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-info-circle display-6 me-3"></i>
                                            <div>
                                                <span class="d-block fw-bold fs-5">Curso de Inducción</span>
                                                <small>Periodo de bienvenida institucional.</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold mb-3">2. Importar Lista de Estudiantes (Obligatorio)</label>
                            <div class="border border-2 border-dashed rounded-4 p-5 text-center bg-light">
                                <i class="bi bi-file-earmark-excel display-1 text-primary mb-3"></i>
                                <h5>Arrastre el archivo Excel aquí o haga clic para seleccionar</h5>
                                <input type="file" class="form-control mt-3" accept=".xlsx, .xls" required>
                                <p class="text-muted small mt-2">Formato esperado: .xlsx (Excel) con columnas: ID, Nombre, Apellido, Carrera.</p>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 fw-bold rounded-3 shadow-sm">
                                <i class="bi bi-gear-fill me-2"></i> Procesar e Importar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection