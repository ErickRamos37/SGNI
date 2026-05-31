@extends('layouts.app')

@section('contenido')

<div class="mb-4">
    {{-- Botón de Volver (Regresa a la página anterior automáticamente) --}}
    <a href="{{ url()->previous() }}" class="text-primary text-decoration-none fw-semibold mb-3 d-inline-block">
        <i class="bi bi-arrow-left me-1"></i> Volver
    </a>

    {{-- Título Dinámico --}}
    <h2 class="fw-bold fs-2 mb-1">Lista del Grupo</h2>
    {{-- Pintamos el nombre real del grupo --}}
    <p class="text-muted fs-5 mb-4">{{ $grupo->nombre_grupo }}</p>

    {{-- Botón de Descargar (Amarillo) --}}
    <a href="{{ route('grupos.descargar_lista', $grupo->id_grupo) }}" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-sm text-decoration-none">
        <i class="bi bi-download me-2"></i> DESCARGAR LISTA
    </a>
</div>

{{-- TABLA DE ALUMNOS --}}
<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    
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
                    <th class="py-3 text-uppercase small fw-bold text-muted">Nombre Completo</th>
                    <th class="py-3 text-uppercase small fw-bold text-muted">Correo</th>
                </tr>
            </thead>
            <tbody>
                
                {{-- Ciclo para imprimir los alumnos reales de este grupo --}}
                @foreach($grupo->alumnos as $alumno)
                <tr>
                    <td class="px-4 py-3 fw-bold text-dark">{{ $alumno->matricula }}</td>
                    
                    {{-- Concatenamos el nombre y los apellidos --}}
                    <td class="py-3 fw-semibold">
                        {{ $alumno->nombre }} {{ $alumno->ap_pat }} {{ $alumno->ap_mat }}
                    </td>
                    
                    {{-- Mostramos el correo institucional, y si no tiene, el alternativo --}}
                    <td class="py-3 text-muted">
                        {{ $alumno->correo_institucional ?? $alumno->correo_alternativo }}
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    {{-- FOOTER DE LA TABLA --}}
    <div class="px-4 py-3 bg-light border-top">
        {{-- Contamos automáticamente cuántos alumnos tiene el grupo --}}
        <small class="text-muted">{{ $grupo->alumnos->count() }} estudiantes en el grupo</small>
    </div>
</div>

@endsection