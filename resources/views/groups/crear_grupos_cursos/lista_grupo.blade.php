@extends('layouts.app')

@section('contenido')

<div class="mb-4">
    {{-- Botón de Volver (Regresa a la página anterior automáticamente) --}}
    <a href="{{ url()->previous() }}" class="text-primary text-decoration-none fw-semibold mb-3 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>

    {{-- Título Dinámico --}}
    <h2 class="fw-bold fs-2 mb-1">Lista del Grupo</h2>
    {{-- Si la variable $nombreGrupo existe la pinta, si no, pone un texto por defecto para que no explote ahorita --}}
    <p class="text-muted fs-5 mb-4">{{ $nombreGrupo ?? 'Propedéutico Mañana 1-A' }}</p>

    {{-- Botón de Descargar --}}
    <button class="btn btn-secondary text-dark fw-bold rounded-pill px-4 py-2 shadow-sm">
        <i class="bi bi-download me-2"></i> DESCARGAR LISTA
    </button>
</div>

{{-- TABLA DE ALUMNOS --}}
<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    
    {{-- HEADER DE LA TABLA (PRIMARY = VERDE UABC) --}}
    <div class="px-4 py-3 bg-primary">
        <h5 class="text-white fw-bold mb-0 text-uppercase">
            Alumnos del Grupo
        </h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="px-4 py-3 text-uppercase small fw-bold text-muted">Matrícula</th>
                    <th class="py-3 text-uppercase small fw-bold text-muted">Nombre</th>
                    <th class="py-3 text-uppercase small fw-bold text-muted">Correo</th>
                </tr>
            </thead>
            <tbody>
                
                {{-- Aquí irá el @foreach($alumnos as $alumno) cuando conecten la BD --}}
                
                <tr>
                    <td class="px-4 py-3 fw-bold text-dark">00123456</td>
                    <td class="py-3 fw-semibold">Ana María González López</td>
                    <td class="py-3 text-muted">ana.gonzalez@uabc.edu.mx</td>
                </tr>

                <tr>
                    <td class="px-4 py-3 fw-bold text-dark">00123457</td>
                    <td class="py-3 fw-semibold">Carlos Eduardo Martínez Torres</td>
                    <td class="py-3 text-muted">carlos.martinez@uabc.edu.mx</td>
                </tr>

                <tr>
                    <td class="px-4 py-3 fw-bold text-dark">00123458</td>
                    <td class="py-3 fw-semibold">Diana Patricia Rodríguez Cruz</td>
                    <td class="py-3 text-muted">diana.rodriguez@uabc.edu.mx</td>
                </tr>

                <tr>
                    <td class="px-4 py-3 fw-bold text-dark">00123459</td>
                    <td class="py-3 fw-semibold">Fernando Javier Hernández Pérez</td>
                    <td class="py-3 text-muted">fernando.hernandez@uabc.edu.mx</td>
                </tr>

                <tr>
                    <td class="px-4 py-3 fw-bold text-dark">00123460</td>
                    <td class="py-3 fw-semibold">Gabriela Alejandra Torres Silva</td>
                    <td class="py-3 text-muted">gabriela.torres@uabc.edu.mx</td>
                </tr>

                {{-- Fin del @foreach --}}

            </tbody>
        </table>
    </div>

    {{-- FOOTER DE LA TABLA --}}
    <div class="px-4 py-3 bg-light border-top">
        <small class="text-muted">5 estudiantes en el grupo</small>
    </div>
</div>

@endsection