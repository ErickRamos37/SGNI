@extends('layouts.app')

@section('contenido')
    <div class="container-fluid py-4">

        <div class="mb-4">
            <h1 class="fw-bolder text-dark mb-1 display-6">Criterios y Creación de Grupos Finales</h1>
            <p class="text-muted">Configure los porcentajes de distribución para los criterios de asignación</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-9">

                <form id="form-grupos-finales" action="{{ route('grupos.finales.generar') }}" method="POST">
                    @csrf

                    <div class="card border-0 shadow-sm rounded-4 bg-white p-4">

                        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-text-fill text-black fs-4"></i>
                                <h5 class="mb-0 fw-bold text-uppercase text-black tracking-wider fs-6">
                                    Criterios de Asignación Fijos
                                </h5>
                            </div>
                            <span id="badge-total" class="badge bg-black fs-6 fw-bold px-3 py-2 rounded-pill shadow-sm">
                                Total: 100% / 100%
                            </span>
                        </div>

                        <div class="d-flex flex-column gap-3 mb-4" id="contenedor-criterios">

                            {{-- CRITERIO 1: PROMEDIOS ALTOS --}}
                            <div class="p-3 border border-light rounded-3 bg-light bloque-criterio">
                                <div class="row g-3 align-items-center">
                                    <div class="col-12 col-md-8">
                                        <label class="small fw-bold text-muted text-uppercase d-block mb-1">
                                            Nombre del Criterio
                                        </label>
                                        <input type="text" name="criterios[0][nombre]" class="form-control border-light shadow-none bg-white fw-semibold"
                                            value="Promedios Altos de Alumnos" readonly>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="small fw-bold text-muted text-uppercase d-block mb-1">
                                            Porcentaje (%)
                                        </label>
                                        <input type="number" name="criterios[0][valor]"
                                            class="form-control border-light shadow-none bg-white text-center fw-bold input-porcentaje"
                                            value="60" min="1" max="99" required>
                                    </div>
                                </div>
                            </div>

                            {{-- CRITERIO 2: PROMEDIOS BAJOS --}}
                            <div class="p-3 border border-light rounded-3 bg-light bloque-criterio">
                                <div class="row g-3 align-items-center">
                                    <div class="col-12 col-md-8">
                                        <label class="small fw-bold text-muted text-uppercase d-block mb-1">
                                            Nombre del Criterio
                                        </label>
                                        <input type="text" name="criterios[1][nombre]" class="form-control border-light shadow-none bg-white fw-semibold"
                                            value="Promedios Bajos" readonly>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label class="small fw-bold text-muted text-uppercase d-block mb-1">
                                            Porcentaje (%)
                                        </label>
                                        <input type="number" name="criterios[1][valor]"
                                            class="form-control border-light shadow-none bg-white text-center fw-bold input-porcentaje"
                                            value="40" min="1" max="99" required>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="alert alert-info border-0 mb-4 p-4 rounded-3 shadow-sm text-start" role="alert">
                            <p class="mb-0 fs-5 text-dark lh-base">
                                <strong>Importante:</strong> Cada porcentaje debe mantenerse obligatoriamente entre <strong>1% y 99%</strong>. Ningún grupo puede quedarse con el 0% o el 100% de la asignación.
                            </p>
                        </div>

                        <div class="d-flex justify-content-end gap-2">                          
                            <button type="button" id="btn-crear-grupos" onclick="validarYEnviarFormulario(this)"
                                class="btn btn-secondary text-white fw-bold px-4 py-2 text-uppercase shadow-sm rounded-3">
                                Crear Grupos 
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>

    </div>

    <script>
        // Función para calcular, validar rangos individuales y actualizar el estado visual (badge)
        function calcularTotalPorcentajes() {
            const inputs = document.querySelectorAll('.input-porcentaje');
            let total = 0;
            let rangosValidos = true;
            
            inputs.forEach(input => {
                const valor = parseInt(input.value) || 0;
                total += valor;

                if (valor < 1 || valor > 99) {
                    rangosValidos = false;
                    input.classList.add('is-invalid'); 
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            const badge = document.getElementById('badge-total');
            if (badge) {
                badge.innerText = `Total: ${total}% / 100%`;
                
                if (total === 100 && rangosValidos) {
                    badge.classList.remove('bg-danger');
                    badge.classList.add('bg-black');
                } else {
                    badge.classList.remove('bg-black');
                    badge.classList.add('bg-danger');
                }
            }
            return { total, rangosValidos };
        }

        // Control dinámico en tiempo real mientras el usuario escribe
        document.getElementById('contenedor-criterios').addEventListener('input', function(e) {
            if (e.target.classList.contains('input-porcentaje')) {
                let valor = parseInt(e.target.value) || 0;

                if (valor > 99) {
                    e.target.value = 99;
                }
                
                calcularTotalPorcentajes();
            }
        });

        // FUNCIÓN CORREGIDA: Fuerza el uso de SweetAlert para los errores de frontend
        function validarYEnviarFormulario(boton) {
            const analisis = calcularTotalPorcentajes();

            // Validación 1: Valores fuera de los rangos permitidos (1 a 99)
            if (!analisis.rangosValidos) {
                Swal.fire({
                    title: '¡Acción Denegada!',
                    text: '¡Valores fuera de rango! Los porcentajes deben estar estrictamente entre 1% y 99%. No se permite dejar un criterio en 0% o 100%.',
                    icon: 'error',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Entendido'
                });
                return false;
            }

            // Validación 2: La suma no da exactamente 100% (Ej: Tu caso del 101%)
            if (analisis.total !== 100) {
                Swal.fire({
                    title: '¡Acción Denegada!',
                    text: `¡No se puede proceder! La suma de los porcentajes debe ser exactamente 100%. Actualmente es de ${analisis.total}%. Ajuste los valores.`,
                    icon: 'error',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Entendido'
                });
                return false;
            }

            // Si pasa todas las validaciones, procede de forma segura
            boton.disabled = true;
            boton.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Procesando...`;

            // Envío manual del formulario
            const formulario = boton.closest('form');
            formulario.submit();
        }

        // Ejecución inicial automática para renderizar el badge la primera vez
        calcularTotalPorcentajes();
    </script>

    {{-- ========================================================= --}}
    {{-- ALERTAS DE SEGURIDAD Y ESTADÍSTICAS DEL SERVIDOR (SESIÓN)  --}}
    {{-- ========================================================= --}}
    
    {{-- Cadenero: Grupos ya existentes --}}
    @if(session('error_grupos_existentes'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Acción Denegada!',
                    text: "{{ session('error_grupos_existentes') }}",
                    icon: 'error',
                    confirmButtonColor: '#dc3545', 
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
    @endif

    {{-- Estadísticas de creación --}}
    @if(session('import_stats'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let repetidos = {{ session('import_stats.repetidos') }};
                let nuevos = {{ session('import_stats.nuevos') }};

                if(repetidos > 0) {
                    Swal.fire({
                        title: '¡Proceso Terminado!',
                        html: `Se procesó la lista correctamente. Alumnos existentes fueron asignados a sus grupos de Inducción.<br><br>
                               <div class="text-start ms-4">
                                   <i class="bi bi-check-circle-fill text-success"></i> <b>Nuevos registrados:</b> ${nuevos}<br>
                                   <i class="bi bi-arrow-repeat text-info"></i> <b>Ya existían (actualizados):</b> ${repetidos}
                               </div>`,
                        icon: 'success',
                        confirmButtonColor: '#00723F',
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    Swal.fire({
                        title: '¡Importación Exitosa!',
                        text: `Se cargaron ${nuevos} alumnos nuevos correctamente, sin duplicados.`,
                        icon: 'success',
                        confirmButtonColor: '#00723F',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        </script>
    @endif
@endsection