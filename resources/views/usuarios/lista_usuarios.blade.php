@extends('layouts.app')

@section('contenido')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">Personal - Lista</h2>
                    <p class="text-muted mb-0">Listado completo de personal registrado</p>
                </div>
                <div class="w-25">
                    <label class="small fw-bold text-muted text-uppercase mb-1">Buscar Personal</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="search-personal" class="form-control border-start-0 ps-0" placeholder="Número de empleado, nombre...">
                    </div>
                </div>
            </div>

            <div class="card border border-light-subtle shadow-sm rounded-3">
                
                <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
                    <h5 class="fw-bold text-uppercase text-white mb-0">Lista de Personal Registrado</h5>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th class="px-4 py-3">Num. Empleado</th>
                                    <th class="py-3">Nombres</th>
                                    <th class="py-3">Apellidos</th>
                                    <th class="py-3">Correo</th>
                                    <th class="py-3">Rol</th>                                </tr>
                            </thead>
                            
                            <tbody class="small">
                                @forelse($usuarios as $usuario)
                                <tr class="empleado-row" data-search="{{ strtolower($usuario->num_empleado . ' ' . $usuario->nombre . ' ' . $usuario->ap_pat . ' ' . $usuario->ap_mat . ' ' . $usuario->correo_institucional) }}">
                                    
                                    <td class="px-4 fw-bold text-dark">{{ $usuario->num_empleado }}</td>
                                    <td class="text-dark">{{ $usuario->nombre }}</td>
                                    <td class="text-dark">{{ $usuario->ap_pat }} {{ $usuario->ap_mat }}</td>
                                    <td class="text-muted">{{ $usuario->correo_institucional }}</td>
                                    <td class="text-dark">{{ $usuario->rol ? $usuario->rol->nombre_rol : 'Sin rol asignado' }}</td>
                                    
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox display-6 d-block mb-2 text-light-subtle"></i>
                                        No hay personal registrado en el sistema.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            
                        </table>
                    </div>
                </div>
                
                @if(method_exists($usuarios, 'hasPages') && $usuarios->hasPages())
                <div class="card-footer bg-white border-top border-light-subtle p-3 d-flex justify-content-center">
                    {{ $usuarios->links('pagination::simple-bootstrap-4') }}
                </div>
                @endif
                
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-personal');
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const rows = document.querySelectorAll('.empleado-row');

                rows.forEach(row => {
                    // Leemos toda la info del empleado desde el atributo oculto data-search
                    const searchData = row.getAttribute('data-search');
                    
                    if (searchData.includes(query)) {
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