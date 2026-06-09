@extends('layouts.app')

@section('contenido')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">

                {{--- Encabezado de página ---}}
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h2 class="fw-bold text-dark mb-1">Seguimiento de Alumnos</h2>
                        <p class="text-muted mb-0">Listado general de monitoreo académico, calificaciones y asistencias</p>
                    </div>
                </div>

                {{-- TABLA DE INDUCCIÓN (Sin Exámenes) --}}
                <div class="card border border-light-subtle shadow-sm rounded-3 mb-5">
                    <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
                        <h5 class="fw-bold text-uppercase text-white mb-0">Curso de Inducción</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table id="tabla-induccion" class="table table-hover align-middle mb-0 w-100">
                                <thead class="table-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="px-3 py-3">Matrícula</th>
                                        <th class="py-3">Nombre del Alumno</th>
                                        <th class="py-3">Grupo</th>
                                        <th class="py-3">Carrera</th>
                                        <th class="py-3">Contacto</th>
                                        <th class="py-3 text-center">Pts. Ingreso</th>
                                        <th class="py-3 text-center">Asistencias</th>
                                        <th class="py-3 text-center">Riesgo</th>
                                    </tr>
                                </thead>
                                <tbody class="small text-dark">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- TABLA DE PROPEDÉUTICO (Con Exámenes) --}}
                <div class="card border border-light-subtle shadow-sm rounded-3 mb-5">
                    <div class="card-header bg-primary p-4 border-bottom border-light-subtle">
                        <h5 class="fw-bold text-uppercase text-white mb-0">Curso Propedéutico</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table id="tabla-propedeutico" class="table table-hover align-middle mb-0 w-100">
                                <thead class="table-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="px-3 py-3">Matrícula</th>
                                        <th class="py-3">Nombre del Alumno</th>
                                        <th class="py-3">Grupo</th>
                                        <th class="py-3">Carrera</th>
                                        <th class="py-3">Contacto</th>
                                        <th class="py-3 text-center">Pts. Ingreso</th>
                                        <th class="py-3 text-center">Ex. Inicial</th>
                                        <th class="py-3 text-center">Ex. Final</th>
                                        <th class="py-3 text-center">Promedio</th>
                                        <th class="py-3 text-center">Asistencias</th>
                                        <th class="py-3 text-center">Riesgo</th>
                                    </tr>
                                </thead>
                                <tbody class="small text-dark">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const opcionesEstandar = {
                language: { url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json" },
                pageLength: 25
            };

            // 1. Inicializar tabla INDUCCIÓN (De MAYOR a MENOR asistencia)
            new DataTable('#tabla-induccion', {
                ...opcionesEstandar,
                order: [[6, 'desc']], 
                ajax: { url: '{{ route('seguimiento.datos') }}?curso=induccion', dataSrc: 'data' },
                columns: [
                    { data: 'matricula', className: 'fw-bold px-3' }, //0
                    { data: 'nombre_completo' }, //1
                    { data: 'grupo' }, //2
                    { data: 'carrera', className: 'text-center' },  //3
                    { data: 'contacto' }, //4
                    { data: 'puntaje_ingreso', className: 'text-center text-dark' },//5
                    { data: 'asistencias', className: 'text-center fw-bold' }, //6
                    { data: 'riesgo', className: 'text-center', orderable: false }
                ]
            });

            // 2. Inicializar tabla PROPEDÉUTICO (De MENOR a MAYOR asistencia)
            new DataTable('#tabla-propedeutico', {
                ...opcionesEstandar,               
                order: [[9, 'desc']], 
                ajax: { url: '{{ route('seguimiento.datos') }}?curso=propedeutico', dataSrc: 'data' },
                columns: [
                    { data: 'matricula', className: 'fw-bold px-3' },
                    { data: 'nombre_completo' },
                    { data: 'grupo' },
                    { data: 'carrera', className: 'text-center' }, 
                    { data: 'contacto' },
                    { data: 'puntaje_ingreso', className: 'text-center text-dark' },
                    { data: 'examen_inicial', className: 'text-center' },
                    { data: 'examen_final', className: 'text-center fw-bold' },
                    { data: 'promedio', className: 'text-center fw-bold text-dark' },
                    { data: 'asistencias', className: 'text-center fw-bold' }, 
                    { data: 'riesgo', className: 'text-center', orderable: false }
                ]
            });
        });
    </script>
@endsection