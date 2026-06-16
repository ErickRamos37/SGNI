@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-4">
    <h2 class="fw-bold text-primary mb-1">Crear Grupos</h2>
    <p class="text-muted mb-4">Seleccione el programa y configure los grupos</p>

    <div class="card border-0 shadow rounded-3 bg-white">
        
        <div class="card-header bg-transparent border-bottom py-3 px-4">
            <div class="d-flex align-items-center text-dark fw-bolder fs-6">
                <i class="bi bi-calendar-event-fill me-2 text-muted"></i>
                <span>SELECCIONE PROGRAMA</span>
            </div>
        </div>

        <div class="card-body p-4 p-md-5">
            <div class="row g-4 justify-content-center">
                
                <div class="col-md-6 col-lg-5">
                    <div class="card h-100 border border-2 border-light-subtle rounded-4 p-4 text-center list-group-item-action position-relative">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center py-3">
                            
                            <div class="text-primary mb-3">
                                <i class="bi bi-book-half display-3 fw-bold"></i>
                            </div>
                            
                            <h3 class="fw-bolder text-dark mb-2 fs-4">Curso Propedéutico</h3>
                            <span class="text-muted small">Período inicial de nivelación</span>
                            
                            <a href="{{ route('curso_prope') }}" class="stretched-link"></a>
                            
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-5">
                    <div class="card h-100 border border-2 border-light-subtle rounded-4 p-4 text-center list-group-item-action position-relative">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center py-3">
                            
                            <div class="text-secondary mb-3">
                                <i class="bi bi-book-half display-3 fw-bold"></i>
                            </div>
                            
                            <h3 class="fw-bolder text-dark mb-2 fs-4">Curso Inducción</h3>
                            <span class="text-muted small">Período de refuerzo y evaluación</span>
                            
                            <a href="curso_induc" class="stretched-link"></a>
                            
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection