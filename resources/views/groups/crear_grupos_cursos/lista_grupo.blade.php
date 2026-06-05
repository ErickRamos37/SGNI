@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <a href="{{ url()->previous() }}" class="text-primary text-decoration-none fw-semibold mb-3 d-inline-block">
                Atras
            </a>

            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Lista del Grupo</h2>
                    <p class="text-muted mb-0">{{ $grupo->nombre_grupo }}</p>
                    <div class="mt-3">
                        <a href="{{ route('grupos.descargar_lista', $grupo->id_grupo) }}" class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                            DESCARGAR LISTA
                        </a>
                    </div>
                </div>
                <div class="w-25">
                    <label class="small fw-bold text-muted text-uppercase mb-1">Buscar</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Buscar..." id="search-registro">
                    </div>
                </div>
            </div>

            <div class="card border border-light-subtle shadow-sm rounded-3">
                
                <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
                    <h5 class="fw-bold text-uppercase text-white mb-0">Alumnos del Grupo</h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="px-4 py-3">Matrícula</th>
                                    <th class="py-3">Nombre Completo</th>
                                    <th class="py-3">Correo</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                
                                {{-- Ciclo para imprimir los alumnos reales de este grupo --}}
                                @foreach($grupo->alumnos as $alumno)
                                <tr class="registro-row" data-search="{{ strtolower($alumno->matricula . ' ' . $alumno->nombre . ' ' . $alumno->ap_pat . ' ' . $alumno->ap_mat . ' ' . ($alumno->correo_institucional ?? $alumno->correo_alternativo)) }}">
                                    <td class="px-4 py-3 fw-bold text-dark">{{ $alumno->matricula }}</td>
                                    
                                    {{-- Concatenamos el nombre y los apellidos --}}
                                    <td class="py-3 text-dark">
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
                </div>

                {{-- FOOTER DE LA TABLA --}}
                <div class="card-footer bg-white border-top border-light-subtle p-3 d-flex justify-content-center">
                    {{-- Contamos automáticamente cuántos alumnos tiene el grupo --}}
                    <small class="text-muted">{{ $grupo->alumnos->count() }} estudiantes en el grupo</small>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-registro');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.registro-row');
                
                rows.forEach(row => {
                    const dataSearch = row.getAttribute('data-search');
                    if (dataSearch.includes(query)) {
                        row.classList.remove('d-none');
                    } else {
                        row.classList.add('d-none');
                    }
                });
            });
        }
    });
</script>
@endsection