@extends('layouts.app')

@section('contenido')
<div class="container-fluid py-4 px-4">
    <header class="mb-4">
        <h2 class="fw-bold m-0 text-dark">Cierre y Grupos Finales</h2>
        <p class="text-muted">Consolidación de todos los grupos y creación de grupos finales</p>
    </header>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-person-gear text-primary fs-4 me-2"></i>
                <h5 class="card-title fw-bold m-0">Creación de Grupos Finales</h5>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Alumnos con promedio alto (%)</label>
                    <input type="number" id="peso-alto" class="form-control" value="70">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">Alumnos con promedio bajo (%)</label>
                    <input type="number" id="peso-bajo" class="form-control" value="30">
                </div>
            </div>
            <div class="alert alert-success mt-4 border-0 d-flex align-items-center py-2" style="background-color: #e8f5e9;">
                <i class="bi bi-check-circle-fill text-success me-2"></i>
                <span class="small text-success fw-medium">Configuración válida: Promedio alto 70% + Promedio bajo 30% = 100%</span>
            </div>
            <button type="button" id="btn-crear" class="btn btn-primary w-100 fw-bold shadow-sm py-2">Crear Grupos</button>
        </div>
    </div>

    <div id="stats-row" class="row g-3 mb-4 d-none">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <small class="text-muted fw-bold text-uppercase">Total de Estudiantes</small>
                <h3 class="fw-bold m-0">9</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 border-start border-success border-4">
                <small class="text-muted fw-bold text-uppercase">Aprobados</small>
                <div class="d-flex align-items-center gap-2">
                    <h3 class="fw-bold m-0 text-success">7</h3>
                    <span class="text-success small">77.8%</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 border-start border-danger border-4">
                <small class="text-muted fw-bold text-uppercase">Reprobados</small>
                <div class="d-flex align-items-center gap-2">
                    <h3 class="fw-bold m-0 text-danger">2</h3>
                    <span class="text-danger small">22.2%</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3 border-start border-primary border-4">
                <small class="text-muted fw-bold text-uppercase">Promedio General</small>
                <h3 class="fw-bold m-0 text-primary">83.44</h3>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover" style="font-size: 0.85rem;">
                <thead class="table-light text-muted">
                    <tr>
                        <th class="ps-4">MATRÍCULA</th>
                        <th>NOMBRE</th>
                        <th>GRUPO</th>
                        <th class="text-center">PROMEDIO ALTO</th>
                        <th class="text-center">PROMEDIO BAJO</th>
                        <th class="text-center">CALIF. FINAL</th>
                        <th class="pe-4 text-center">ESTADO</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $estudiantes = [
                            ['mat' => '330000', 'n' => 'Juan Carlos Méndez', 'g' => 'Grupo A - Ingeniería', 'pa' => 85, 'pb' => 95, 'cf' => 88.00],
                            ['mat' => '330001', 'n' => 'María Fernanda López', 'g' => 'Grupo A - Ingeniería', 'pa' => 92, 'pb' => 100, 'cf' => 94.40],
                            ['mat' => '330002', 'n' => 'Diego Armando Ruiz', 'g' => 'Grupo A - Ingeniería', 'pa' => 78, 'pb' => 85, 'cf' => 80.10],
                            ['mat' => '330003', 'n' => 'Ana Gabriela Torres', 'g' => 'Grupo A - Ingeniería', 'pa' => 88, 'pb' => 90, 'cf' => 88.60],
                            ['mat' => '330004', 'n' => 'Roberto Carlos Sánchez', 'g' => 'Grupo A - Ingeniería', 'pa' => 65, 'pb' => 75, 'cf' => 68.00],
                        ];
                    @endphp
                    @foreach($estudiantes as $e)
                    <tr>
                        <td class="ps-4 text-muted fw-bold">{{ $e['mat'] }}</td>
                        <td class="fw-medium text-dark">{{ $e['n'] }}</td>
                        <td class="text-muted">{{ $e['g'] }}</td>
                        <td class="text-center">{{ $e['pa'] }}</td>
                        <td class="text-center">{{ $e['pb'] }}</td>
                        <td class="text-center text-muted calif-final">-</td>
                        <td class="pe-4 text-center">
                            <span class="badge rounded-pill badge-estado bg-secondary bg-opacity-25 text-secondary px-3" data-cf="{{ $e['cf'] }}">Pendiente</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <button id="btn-reporte" class="btn btn-secondary fw-bold px-4 shadow-sm text-dark opacity-50" disabled>
            <i class="bi bi-download me-2"></i> Generar Reporte Consolidado
        </button>
        <button id="btn-finalizar" type="button" class="btn btn-primary fw-bold px-4 shadow-sm d-none align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalCierre">
            <i class="bi bi-lock-fill"></i> Cerrar Ciclo y Generar Listas Finales
        </button>
    </div>
</div>

<div class="modal fade" id="modalCierre" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-4 text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <i class="bi bi-exclamation-triangle text-warning fs-3 me-2"></i>
                    <h5 class="fw-bold m-0">¿Cerrar el ciclo?</h5>
                </div>
                <p class="text-muted mb-4">Esta acción bloqueará todas las calificaciones y las convertirá en solo lectura. No podrá modificar los porcentajes ni recalcular las calificaciones después de confirmar.</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary w-100 fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary w-100 fw-bold">Confirmar y Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('btn-crear').addEventListener('click', function() {
        // 1. Mostrar Estadísticas
        document.getElementById('stats-row').classList.remove('d-none');

        // 2. Actualizar Tabla
        const filasCalif = document.querySelectorAll('.calif-final');
        const filasBadges = document.querySelectorAll('.badge-estado');

        filasCalif.forEach((td, index) => {
            const badge = filasBadges[index];
            const calif = badge.getAttribute('data-cf');

            // Poner calificación
            td.innerText = calif;
            td.classList.remove('text-muted');
            td.classList.add('fw-bold');

            // Cambiar Badge
            badge.innerText = parseFloat(calif) >= 70 ? 'Aprobado' : 'Reprobado';
            badge.classList.remove('bg-secondary', 'bg-opacity-25', 'text-secondary');
            if(parseFloat(calif) >= 70) {
                badge.classList.add('bg-success');
                badge.style.color = '#fff';
            } else {
                badge.classList.add('bg-danger');
                badge.style.color = '#fff';
            }
        });

        // 3. Habilitar botones inferiores
        const btnReporte = document.getElementById('btn-reporte');
        btnReporte.disabled = false;
        btnReporte.classList.remove('opacity-50');

        const btnFinalizar = document.getElementById('btn-finalizar');
        btnFinalizar.classList.remove('d-none');
        btnFinalizar.classList.add('d-flex');
    });
</script>
@endsection
