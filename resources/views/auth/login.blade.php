@extends('layouts.guest')

@section('contenido')
<div class="container-fluid vh-100 d-flex justify-content-center align-items-center bg-primary">
    
    <div class="col-11 col-sm-8 col-md-6 col-lg-4 col-xl-3">
        
        <div class="card shadow-lg p-4 p-md-5 text-center border-0 rounded-4">
            
            <div class="mb-4">
                <div class="bg-secondary d-inline-flex justify-content-center align-items-center rounded-3 p-3 text-white">
                    <i class="bi bi-mortarboard-fill display-3"></i>
                </div>
            </div>

            <div class="mb-4">
                <h1 class="fw-bold text-primary mb-1">UABC FIAD</h1>
                <p class="text-muted mb-1 fw-bold">Sistema de Gestión Institucional</p>
                <p class="text-muted small">Bienvenido, accede con tu cuenta institucional.</p>
            </div>

            <div class="d-grid">
                <a href="#" class="btn btn-outline-dark py-2 d-flex align-items-center justify-content-center rounded-3 shadow-sm">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" 
                         alt="Google" 
                         width="20" 
                         class="me-2">
                    Continuar con Google
                </a>
            </div>

            <div class="mt-4">
                <p class="text-muted small mb-0">Al iniciar sesión, aceptas los términos y condiciones de UABC.</p>
            </div>
        </div>
    </div>
</div>
@endsection