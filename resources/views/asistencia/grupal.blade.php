@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-2">
    
    {{-- Encabezado y Buscador (Intactos del diseño original) --}}
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="fw-bold text-dark mb-1">Lista de Asistencia Grupal</h1>
            <p class="text-dark fw-semibold mb-3" style="opacity: 0.7;">Grupo: Sin asignar</p>
            
            {{-- Botón deshabilitado porque no hay nada que descargar --}}
            <button class="btn btn-secondary text-dark fw-bold px-4 py-2 shadow-sm rounded-3 border-0" disabled>
                <i class="bi bi-download me-2"></i> DESCARGAR LISTA
            </button>
        </div>
        
        <div class="col-md-6 d-flex justify-content-md-end mt-4 mt-md-0">
            <div style="width: 100%; max-width: 350px;">
                <label class="form-label small fw-bold text-dark text-uppercase mb-1" style="opacity: 0.7; font-size: 0.75rem;">Buscar Estudiante</label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-white border-end-0 text-dark" style="opacity: 0.5;">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0" placeholder="Matrícula o nombre..." disabled>
                </div>
            </div>
        </div>
    </div>

    {{-- Tarjeta Principal --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        
        {{-- Header Verde UABC --}}
        <div class="card-header bg-primary text-white py-3 border-0">
            <h5 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 0.5px;">Registro de Asistencia Semanal</h5>
        </div>

        {{-- ESTADO VACÍO (Empty State) --}}
        <div class="card-body p-5 text-center">
            <div class="py-5">
                <i class="bi bi-people text-muted opacity-50 mb-3" style="font-size: 4rem;"></i>
                <h3 class="fw-bold text-dark">No hay alumnos registrados</h3>
                <p class="text-muted fs-5 mb-0">Aún no se han generado los grupos o asignado estudiantes a esta sección.</p>
            </div>
        </div>

    </div>
</div>
@endsection