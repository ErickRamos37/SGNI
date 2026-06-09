@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            {{-- Botón para regresar a la vista anterior --}}
            <a href="{{ route('grupos_finales.lista') }}" class="text-primary text-decoration-none fw-semibold mb-3 d-inline-block">
                <i class="bi bi-arrow-left"></i> Atrás
            </a>

            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Lista de Grupo Final</h2>
                    <p class="text-muted mb-0">{{ $grupo->nombre_grupo }}</p>
                    <div class="mt-3">
                        {{-- Botón que apunta a la nueva ruta de exportación --}}
                        <a href="{{ route('grupos_finales.descargar_lista', $grupo->id_grupo) }}" class="btn btn-outline-dark px-5 fw-semibold rounded-3 d-inline-flex align-items-center gap-2">
                            <i class="bi bi-download"></i> DESCARGAR LISTA
                        </a>
                    </div>
                </div>
                <div class="w-25">
                    <label class="small fw-bold text-muted text-uppercase mb-1">Buscar</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" placeholder="Buscar alumno..." id="search-registro">
                    </div>
                </div>
            </div>

            <div class="card border border-light-subtle shadow-sm rounded-3">
                
                <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
                    <h5 class="fw-bold text-uppercase text-white mb-0">Alumnos Asignados</h5>
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
                                
                                {{-- Usamos alumnosFinales ya que es la relación de los grupos definitivos --}}
                                @forelse($grupo->alumnosFinales as $alumno)
                                <tr class="registro-row" data-search="{{ strtolower($alumno->matricula . ' ' . $alumno->nombre . ' ' . $alumno->ap_pat . ' ' . $alumno->ap_mat . ' ' . ($alumno->correo_institucional ?? $alumno->correo_alternativo)) }}">
                                    <td class="px-4 py-3 fw-bold text-dark">{{ $alumno->matricula }}</td>
                                    
                                    <td class="py-3 text-dark">
                                        {{ $alumno->nombre }} {{ $alumno->ap_pat }} {{ $alumno->ap_mat }}
                                    </td>
                                    
                                    <td class="py-3 text-muted">
                                        {{ $alumno->correo_institucional ?? $alumno->correo_alternativo ?? 'N/A' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="bi bi-person-x display-6 d-block mb-2 text-light-subtle"></i>
                                        No hay alumnos asignados a este grupo todavía.
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white border-top border-light-subtle p-3 d-flex justify-content-center">
                    <small class="text-muted">{{ $grupo->alumnosFinales->count() }} estudiantes en el grupo</small>
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