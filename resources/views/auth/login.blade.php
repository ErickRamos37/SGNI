@extends('layouts.guest')

@section('contenido')
<div class="container-fluid vh-100 d-flex justify-content-center align-items-center" 
     style="background: linear-gradient;">
    
    <div class="card shadow-lg p-5 text-center" style="width: 100%; max-width: 400px; border-radius: 20px;">
        <div class="mb-4">
            <h1 class="fw-bold text-success">SGNI</h1>
            <p class="text-muted">Sistema de Gestión Institucional</p>
        </div>

        <div class="d-grid">
            <a href="#" class="btn btn-dark btn-lg d-flex align-items-center justify-content-center" style="border-radius: 10px;">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" 
                     alt="Google" style="width: 22px; margin-right: 12px; filter: brightness(0) invert(1);">
                Acceder con Google
            </a>
        </div>
    </div>
</div>
@endsection