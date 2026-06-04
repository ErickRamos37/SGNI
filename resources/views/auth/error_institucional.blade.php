@extends('layouts.guest')

@section('contenido')
<div class="container-fluid vh-100 d-flex justify-content-center align-items-center bg-light">
    <div class="col-11 col-md-6 col-lg-4 text-center">
        <div class="card shadow-lg border-0 rounded-4 p-5">
            <div class="text-danger mb-4">
                <i class="bi bi-exclamation-octagon-fill display-1"></i>
            </div>
            <h2 class="fw-bold text-primary">Acceso Restringido</h2>
            <p class="text-muted mt-3">
                Lo sentimos, este sistema es exclusivo para la comunidad <strong>UABC</strong>. 
                Debes iniciar sesión con tu correo <strong>@uabc.edu.mx</strong>.
            </p>
            <div class="d-grid mt-4">
                <a href="{{ route('login') }}" class="btn btn-primary py-2 fw-bold rounded-3">
                    Volver al inicio
                </a>
            </div>
        </div>
    </div>
</div>
@endsection