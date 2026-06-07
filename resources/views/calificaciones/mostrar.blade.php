@extends('layouts.app')

@section('contenido')
    <div class="container-fluid py-1">
        <div class="row justify-content-center">
            <div class="col-12">

                {{-- Encabezado principal y menu de seleccion --}}
                <div class="row align-items-end g-3 mb-4">
                    <div class="col-12 col-md-8">
                        <h1 class="fw-bold text-dark mb-1">Captura de Calificaciones</h1>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="text-muted small fw-bold">Grupo:</span>
                            {{-- Menu desplegable que redirige a la ruta del grupo seleccionado --}}
                            <select id="select-grupo"
                                class="form-select form-select-sm border-0 bg-transparent fw-bold text-primary py-3 ps-2 pe-5 w-auto shadow-none"
                                onchange="location = this.value;" style="cursor: pointer; min-width: 220px;">
                                <option value="{{ route('calificaciones.mostrar') }}" {{ !$grupo ? 'selected' : '' }}>
                                    --Seleccione un Grupo--
                                </option>
                                @foreach ($grupos as $g)
                                    <option value="{{ route('calificaciones.mostrar', $g->id_grupo ?? $g->id) }}"
                                        {{ $grupo && $grupo->id_grupo == ($g->id_grupo ?? $g->id) ? 'selected' : '' }}>
                                        {{ $g->nombre_grupo ?? ($g->nombre ?? 'Grupo ' . ($g->id_grupo ?? $g->id)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Boton condicional para descargar el reporte del grupo en Excel --}}
                        @if ($grupo)
                            <a href="#" id="btn-descargar-lista"
                                data-url="{{ route('calificaciones.exportar', $grupo->id_grupo ?? $grupo->id) }}"
                                class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                Descargar Lista
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Contenedor para mostrar mensajes de alerta via AJAX --}}
                <div id="alert-container-ajax" class="d-none mb-3">
                    <div class="alert alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert"
                        id="alert-box-ajax">
                        <i class="fs-4 me-3" id="alert-icon-ajax"></i>
                        <div id="alert-message-ajax"></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>

                @if ($grupo)
                    {{-- Estructura de la tarjeta que aloja la tabla de DataTables --}}
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4 bg-white">

                        <div class="card-header bg-primary p-4 border-bottom">
                            <h5 class="fw-bold text-uppercase text-white mb-0">Tabla de Calificaciones</h5>
                        </div>

                        <div class="card-body p-4">
                            <div class="table-responsive">
                                {{-- Tabla base; las filas del tbody se inyectan desde el servidor --}}
                                <table id="tabla-estudiantes" class="table table-hover align-middle mb-0"
                                    style="width:100%">
                                    <thead class="table-light text-muted small text-uppercase">
                                        <tr>
                                            <th class="px-2" style="width: 15%;">Matrícula</th>
                                            <th class="py-3" style="width: 45%;">Nombre del Alumno</th>
                                            <th class="py-3 text-center" style="width: 20%;">Examen Diagnóstico</th>
                                            <th class="py-3 text-center" style="width: 20%;">Examen Propedéutico Final</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small border-0">
                                        {{-- DataTables inyectara las filas aqui --}}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Pie de tarjeta unificado con los botones alineados a los extremos --}}
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center px-4 py-3 border-top border-light rounded-bottom-3">
                            <button type="button" onclick="window.history.back();"
                                class="btn btn-outline-dark px-4 fw-semibold rounded-3 d-inline-flex align-items-center gap-2">
                                <span>Regresar</span>
                            </button>
                            <div class="d-flex gap-2">
                                <button type="button" id="btn-guardar-batch"
                                    class="btn btn-outline-dark px-5 fw-semibold rounded-3">
                                    Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Vista de estado vacio reestructurada con card-body y card-footer unificados --}}
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4 bg-white">
                        <div class="card-body text-center p-5">
                            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-light border border-2"
                                style="width: 75px; height: 75px;">
                                <i class="bi bi-inbox display-6 d-block mb-2 text-light-subtle"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">No se ha seleccionado un grupo</h4>
                            <p class="text-muted col-md-6 mx-auto mb-0 small">
                                Por favor, elija un grupo propedéutico desde el menú desplegable superior para visualizar el
                                listado de alumnos y capturar sus calificaciones.
                            </p>
                        </div>

                        {{-- Pie de tarjeta unificado para el estado vacio alineado a la izquierda --}}
                        <div class="card-footer bg-white d-flex justify-content-start align-items-center px-4 py-3 border-top border-light rounded-bottom-3">
                            <button type="button" onclick="window.history.back();"
                                class="btn btn-outline-dark px-4 fw-semibold rounded-3 d-inline-flex align-items-center gap-2">
                                <span>Regresar</span>
                            </button>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Alerta clasica de Blade para redirecciones con mensajes de exito --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Carga Exitosa!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonColor: '#00723F',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endif

    <script type="module">
        $(document).ready(function() {
            @if ($grupo)
                // 1. Inicializacion y configuracion de DataTables de lado del servidor
                let tablaEstudiantes = $('#tabla-estudiantes').DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: 15, // Paginacion por defecto
                    lengthChange: true, // Muestra el selector de cantidad de filas
                    lengthMenu: [
                        [10, 15, 20, 25, 30, 35, 40, 45, 50],
                        [10, 15, 20, 25, 30, 35, 40, 45, 50]
                    ],
                    ajax: {
                        url: "{{ route('calificaciones.data', $grupo->id_grupo ?? $grupo->id) }}", // Ruta JSON de datos
                        type: 'GET'
                    },
                    language: {
                        // Diccionario de traduccion estatica local para evitar consultas externas
                        processing: "Procesando...",
                        search: "Buscar Matrícula:",
                        lengthMenu: "Mostrar _MENU_ registros",
                        info: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                        infoFiltered: "(filtrado de un total de _MAX_ registros)",
                        infoPostFix: "",
                        loadingRecords: "Cargando...",
                        zeroRecords: "No se encontraron resultados",
                        emptyTable: "Ningún dato disponible en esta tabla",
                        paginate: {
                            first: "Primero",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "Último"
                        },
                        aria: {
                            sortAscending: ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    },
                    columns: [{
                            data: 'matricula',
                            name: 'matricula',
                            className: 'fw-bold text-dark ps-2'
                        },
                        {
                            data: 'nombre_completo',
                            name: 'nombre',
                            searchable: false,
                            orderable: false
                        },
                        {
                            data: 'input_inicial',
                            name: 'input_inicial',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'input_final',
                            name: 'input_final',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ]
                });

                // 2. Delegacion de eventos para validar calificaciones de filas dinamicas en tiempo real
                $('#tabla-estudiantes').on('input', '.input-score', function() {
                    let valString = $(this).val();

                    // Limita la entrada a un maximo de dos numeros decimales
                    if (valString.includes('.')) {
                        const partes = valString.split('.');
                        if (partes[1].length > 2) {
                            valString = partes[0] + '.' + partes[1].slice(0, 2);
                            $(this).val(valString);
                        }
                    }

                    const valFloat = parseFloat(valString);

                    // Restringe valores numericos fijos entre 0 y 100
                    if (!isNaN(valFloat) && valFloat > 100) {
                        $(this).val("100");
                    }
                    if (!isNaN(valFloat) && valFloat < 0) {
                        $(this).val("0");
                    }

                    // Remueve ceros a la izquierda innecesarios (ej. cambia 05 por 5)
                    if (valString.length > 1 && valString.startsWith('0') && !valString.startsWith('0.')) {
                        $(this).val(valFloat);
                        valString = $(this).val();
                    }

                    // Cambio dinamico del color del borde del input si la calificacion es reprobatoria
                    if (!isNaN(valFloat) && valFloat < 60) {
                        $(this).removeClass('text-dark border-light bg-light').addClass(
                            'text-danger border-danger');
                    } else {
                        $(this).removeClass('text-danger border-danger').addClass(
                            'text-dark border-light bg-light');
                    }
                });

                // Recoleccion y envio en lote de las calificaciones de la pagina actual visible
                $('#btn-guardar-batch').on('click', function() {
                    const btn = $(this);
                    const filas = $('#tabla-estudiantes tbody tr.student-row');
                    const calificacionesPayload = {};
                    let tieneDatos = false;
                    let calificacionInvalida = false;

                    filas.each(function() {
                        const fila = $(this);
                        const inputInicial = fila.find('input[data-field="examen_inicial"]');
                        const inputFinal = fila.find('input[data-field="examen_final"]');
                        const matricula = fila.attr('data-matricula');

                        if (matricula && inputInicial.length && inputFinal.length) {
                            const valIni = inputInicial.val();
                            const valFin = inputFinal.val();
                            const notaIni = valIni !== '' ? parseFloat(valIni) : null;
                            const notaFin = valFin !== '' ? parseFloat(valFin) : null;

                            if ((notaIni !== null && (notaIni < 0 || notaIni > 100)) ||
                                (notaFin !== null && (notaFin < 0 || notaFin > 100))) {
                                calificacionInvalida = true;
                            }

                            calificacionesPayload[matricula.trim()] = {
                                examen_inicial: notaIni,
                                examen_final: notaFin
                            };
                            tieneDatos = true;
                        }
                    });

                    if (!tieneDatos) {
                        Swal.fire({
                            title: '¡Tabla Vacia!',
                            text: 'No hay registros válidos para actualizar.',
                            icon: 'warning',
                            confirmButtonColor: '#dc3545',
                            confirmButtonText: 'Aceptar'
                        });
                        return;
                    }

                    // Inactiva el boton durante el guardado para prevenir multiples clics del usuario
                    btn.prop('disabled', true).html(
                        '<span class="spinner-border spinner-border-sm" role="status"></span> GUARDANDO...'
                    );

                    // API Fetch para enviar los datos JSON de manera asincrona al controlador
                    fetch("{{ route('calificaciones.updateBatch') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                calificaciones: calificacionesPayload
                            })
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Error en el servidor');
                            return response.json();
                        })
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: '¡Carga Exitosa!',
                                    text: 'Las calificaciones se actualizaron con éxito en la base de datos.',
                                    icon: 'success',
                                    confirmButtonColor: '#00723F',
                                    confirmButtonText: 'Aceptar'
                                }).then(() => {
                                    // Restablece el boton a su estado original listo para volver a usarse
                                    btn.prop('disabled', false).html('Guardar Cambios');
                                });
                            } else {
                                throw new Error(data.message);
                            }
                        })
                        .catch(error => {
                            // En caso de error, registra el log y rehabilita el boton para permitir reintentos
                            console.error('Error:', error);
                            Swal.fire({
                                title: '¡Error al Guardar!',
                                text: 'Ocurrio un inconveniente al actualizar las calificaciones en el servidor.',
                                icon: 'error',
                                confirmButtonColor: '#dc3545',
                                confirmButtonText: 'Entendido'
                            });
                            btn.prop('disabled', false).html('Guardar Cambios');
                        });
                });
            @endif

            // Manejo del click en el boton externo para la descarga del archivo Excel
            // Se mantiene la verificacion de existencia para evitar errores fatales en la pagina
            const btnDescargar = document.getElementById('btn-descargar-lista');
            if (btnDescargar) {
                btnDescargar.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Como el boton existe, la ruta generada por Laravel siempre estara presente
                    const urlExportar = this.getAttribute('data-url');
                    window.location.href = urlExportar;
                });
            }
        });
    </script>
@endsection
