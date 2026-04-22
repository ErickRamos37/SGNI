@extends('layouts.app')

@section('contenido')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                
                <h1 class="text-primary">¡Hola, Bootstrap está funcionando!</h1>
                <p class="lead">Esta es una página impulsada por Laravel 11 y estilizada con Bootstrap 5.</p>
                
                <button class="btn btn-primary btn-lg">Haz clic aquí</button>
                
                <div class="alert alert-success mt-4" role="alert">
                    Instalación lista para subir a producción.
                </div>

            </div>
        </div>
    </div>

@endsection