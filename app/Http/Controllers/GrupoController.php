<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use App\Models\Grupo;
use App\Models\Alumno;
use App\Models\Turno;
use App\Models\Curso;
use App\Models\Carrera;

use App\Models\Usuario;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GrupoController extends Controller
{
    public function store(Request $request)
    {
        // =======================================================
        // 1. EL CANDADO DE SEGURIDAD (Verificar si ya hay grupos)
        // =======================================================
        $prefijo = ($request->tipo_grupo === 'propedeutico') ? 'Prope' : 'Induc';
        $gruposExistentes = Grupo::where('nombre_grupo', 'LIKE', '%' . $prefijo . '%')->exists();

        if ($gruposExistentes) {
            // Si ya hay grupos, detenemos la ejecución inmediatamente y mandamos la alerta
            return redirect()->back()->with('error_grupos_existentes', 'Los grupos iniciales ya fueron generados anteriormente. Si deseas inscribir a más estudiantes, por favor utiliza el módulo de Alta de Alumnos tardíos.');
        }

        // =======================================================
        // 2. Si pasó el candado, validamos los datos del formulario
        // =======================================================
        $request->validate([
            'grupos_manana_inge'  => 'required|integer|min:0',
            'grupos_tarde_inge'   => 'required|integer|min:0',
            'grupos_manana_arqui' => 'required|integer|min:0',
            'grupos_tarde_arqui'  => 'required|integer|min:0',
            'archivo_alumnos'     => 'required|mimes:xlsx,xls,csv',
            'tipo_grupo'          => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('archivo_alumnos')->getRealPath());
            $filas = $spreadsheet->getActiveSheet()->toArray();

            // =========================================================
            // Leemos los encabezados de la fila 0 y los normalizamos
            // =========================================================
            $encabezados = array_map(function($col) {
                return strtolower(trim($col));
            }, $filas[0]);

            // Mapa: nombre de columna => índice numérico
            $mapa = array_flip($encabezados);

            // Precargamos las carreras de la BD para buscar dinámicamente
            $carreras = Carrera::all();

            $alumnosInge = [];
            $alumnosArqui = [];
            
            for ($i = 1; $i < count($filas); $i++) {
                $fila = $filas[$i];
                
                $matricula = isset($mapa['matricula']) ? trim($fila[$mapa['matricula']]) : '';
                if (empty($matricula)) continue; 

                $programa = isset($mapa['programa_desc']) ? strtoupper(trim($fila[$mapa['programa_desc']])) : '';
                $telefono = isset($mapa['telefono']) && !empty(trim($fila[$mapa['telefono']])) ? trim($fila[$mapa['telefono']]) : substr($matricula . rand(100, 999), 0, 10);
                $correo_alt = isset($mapa['correo_alter']) && !empty(trim($fila[$mapa['correo_alter']])) ? trim($fila[$mapa['correo_alter']]) : $matricula . '@sin-correo.com';
                $correo_inst = isset($mapa['correo']) && !empty(trim($fila[$mapa['correo']])) ? trim($fila[$mapa['correo']]) : null;
                $puntaje = isset($mapa['puntaje']) && !empty(trim($fila[$mapa['puntaje']])) ? trim($fila[$mapa['puntaje']]) : null;

                // Determinar la carrera dinámicamente buscando en la tabla carrera
                $id_carrera = 1; // Por defecto Ingeniería
                if (str_contains($programa, 'ARQUITECTURA')) {
                    $carreraObj = $carreras->first(function($c) {
                        return str_contains(strtoupper($c->nombre_carrera), 'ARQUITECTURA');
                    });
                    $id_carrera = $carreraObj ? $carreraObj->id_carrera : 2;
                } else {
                    $carreraObj = $carreras->first(function($c) {
                        return str_contains(strtoupper($c->nombre_carrera), 'INGENIERIA');
                    });
                    $id_carrera = $carreraObj ? $carreraObj->id_carrera : 1;
                }

                $datosAlumno = [
                    'matricula'             => $matricula,
                    'nombre'                => isset($mapa['nombre']) ? substr(trim($fila[$mapa['nombre']]), 0, 45) : '',
                    'ap_pat'                => isset($mapa['apellido_paterno']) ? substr(trim($fila[$mapa['apellido_paterno']]), 0, 25) : '',
                    'ap_mat'                => isset($mapa['apellido_materno']) ? substr(trim($fila[$mapa['apellido_materno']]), 0, 25) : '',
                    'correo_institucional'  => $correo_inst,
                    'correo_alternativo'    => substr($correo_alt, 0, 150),
                    'telefono'              => $telefono,
                    'puntaje_ingreso'       => $puntaje,
                    'id_carrera'            => $id_carrera,
                ];

                if (str_contains($programa, 'ARQUITECTURA')) {
                    $alumnosArqui[] = $datosAlumno;
                } else {
                    $alumnosInge[] = $datosAlumno;
                }
            }

            $statsInge = $this->procesarGrupos($request->grupos_manana_inge, $request->grupos_tarde_inge, 'Inge', $alumnosInge, $request->tipo_grupo);
            $statsArqui = $this->procesarGrupos($request->grupos_manana_arqui, $request->grupos_tarde_arqui, 'Arqui', $alumnosArqui, $request->tipo_grupo);

            $totalNuevos = $statsInge['nuevos'] + $statsArqui['nuevos'];
            $totalRepetidos = $statsInge['repetidos'] + $statsArqui['repetidos'];

            DB::commit();
            
            return redirect()->back()->with('import_stats', [
                'nuevos' => $totalNuevos,
                'repetidos' => $totalRepetidos
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['Error SQL: ' . $e->getMessage()]);
        }
    }

    private function procesarGrupos($gruposManana, $gruposTarde, $etiqueta, $alumnos, $tipoGrupo)
    {
        $totalGrupos = $gruposManana + $gruposTarde;
        
        // Si no hay grupos o alumnos, regresamos 0
        if ($totalGrupos <= 0 || count($alumnos) === 0) {
            return ['nuevos' => 0, 'repetidos' => 0];
        }

        $gruposCreados = [];
        $letras = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];

        // Buscar el curso por nombre
        $nombreCursoBuscado = ($tipoGrupo === 'propedeutico') ? 'prope' : 'induccion';
        $cursoObj = Curso::where('nombre_curso', 'LIKE', '%' . $nombreCursoBuscado . '%')->first();
        $idCurso = $cursoObj ? $cursoObj->id_curso : null;

        // Buscar los turnos por nombre
        $turnoMatutino = Turno::where('tipo_turno', 'matutino')->first();
        $turnoVespertino = Turno::where('tipo_turno', 'vespertino')->first();

        $idAdminActual = auth()->user()->num_empleado;

        for ($i = 0; $i < $totalGrupos; $i++) {
            $idTurno = ($i < $gruposManana) ? ($turnoMatutino ? $turnoMatutino->id_turno : 1) : ($turnoVespertino ? $turnoVespertino->id_turno : 2); 
            $prefijo = ($tipoGrupo === 'propedeutico') ? 'Prope' : 'Induc';

            $gruposCreados[] = Grupo::create([
                'nombre_grupo' => $prefijo . ' ' . $etiqueta . ' - Gpo ' . $letras[$i],
                'id_turno'     => $idTurno,
                'id_curso'     => $idCurso,
                'id_usuario'   => auth()->user()->id_usuario,
                'id_estado'    => 1,
            ]);
        }

        // =========================================================
        // Revolvemos a los alumnos de forma aleatoria
        // =========================================================
        shuffle($alumnos);
        
        $nuevos = 0;
        $repetidos = 0;
        $indiceGrupo = 0;

        // Algoritmo Round-Robin: Repartir como baraja para balancear los grupos
        foreach ($alumnos as $data) {
            $grupo = $gruposCreados[$indiceGrupo];
            $columnaGrupo = ($tipoGrupo === 'propedeutico') ? 'id_grupo_propedeutico' : 'id_grupo_induccion';
            
            // Insertamos o actualizamos
            $datosUpdate = [
                'nombre'             => $data['nombre'],
                'ap_pat'             => $data['ap_pat'],
                'ap_mat'             => $data['ap_mat'],
                'correo_alternativo' => $data['correo_alternativo'],
                'telefono'           => $data['telefono'],
                'id_carrera'         => $data['id_carrera'],
                $columnaGrupo        => $grupo->id_grupo,
            ];

            // Solo incluir campos opcionales si vienen con valor
            if (!empty($data['correo_institucional'])) {
                $datosUpdate['correo_institucional'] = $data['correo_institucional'];
            }
            if (!empty($data['puntaje_ingreso'])) {
                $datosUpdate['puntaje_ingreso'] = $data['puntaje_ingreso'];
            }

            $alumno = Alumno::updateOrCreate(
                ['matricula' => $data['matricula']],
                $datosUpdate
            );

            if ($alumno->wasRecentlyCreated) {
                $nuevos++;
            } else {
                $repetidos++;
            }

            // Pasamos al siguiente grupo. Si llegamos al último, volvemos a empezar.
            $indiceGrupo++;
            if ($indiceGrupo >= count($gruposCreados)) {
                $indiceGrupo = 0;
            }
        }

        return ['nuevos' => $nuevos, 'repetidos' => $repetidos];
    }

    public function showPropeCreado()
    {
        // 1. Buscamos los grupos de Ingeniería (que tengan 'Prope Inge' en el nombre)
        $gruposInge = Grupo::withCount('alumnos')
                           ->where('nombre_grupo', 'LIKE', '%Prope Inge%')
                           ->get();

        // 2. Buscamos los grupos de Arquitectura (que tengan 'Prope Arqui' en el nombre)
        $gruposArqui = Grupo::withCount('alumnos')
                            ->where('nombre_grupo', 'LIKE', '%Prope Arqui%')
                            ->get();

        // 3. Mandamos las dos listas por separado a la vista
        return view('groups.crear_grupos_cursos.curso_prope_creado', compact('gruposInge', 'gruposArqui'));
    }

    public function showInducCreado()
    {
        // Le pedimos que cuente usando la nueva relación, pero que le ponga el mismo 
        // apodo 'alumnos_count' para que tu HTML no se rompa y lo lea igualito.
        $gruposInduc = Grupo::withCount('alumnosInduccion as alumnos_count')
                            ->where('nombre_grupo', 'LIKE', '%Induc%')
                            ->get();

        return view('groups.crear_grupos_cursos.curso_induc_creado', compact('gruposInduc'));
    }

    // 2. ACTUALIZAR ESTA FUNCIÓN:
    public function showListaGrupo($id_grupo)
    {
        $grupo = Grupo::findOrFail($id_grupo);

        $cursoInduccion = Curso::where('nombre_curso', 'LIKE', '%induccion%')->first();
        $isInduccion = $cursoInduccion && $grupo->id_curso == $cursoInduccion->id_curso;

        // Si es Inducción, cargamos la relación correcta
        if ($isInduccion) {
            $grupo->load('alumnosInduccion');
            $grupo->alumnos = $grupo->alumnosInduccion; // Truco para engañar a la Vista
        } else {
            $grupo->load('alumnos');
        }

        return view('groups.crear_grupos_cursos.lista_grupo', compact('grupo'));
    }

    // 3. ACTUALIZAR ESTA FUNCIÓN:
    public function descargarLista($id_grupo)
    {
        $grupo = Grupo::findOrFail($id_grupo);
        
        $cursoInduccion = Curso::where('nombre_curso', 'LIKE', '%induccion%')->first();
        $isInduccion = $cursoInduccion && $grupo->id_curso == $cursoInduccion->id_curso;

        // El mismo truco para que el Excel sepa a quién exportar
        if ($isInduccion) {
            $grupo->load('alumnosInduccion');
            $grupo->alumnos = $grupo->alumnosInduccion; 
        } else {
            $grupo->load('alumnos');
        }
        
        $docente = \App\Models\Usuario::where('num_empleado', $grupo->id_usuario)->first();
        $nombreDocente = $docente ? mb_strtoupper($docente->nombre . ' ' . $docente->ap_pat . ' ' . $docente->ap_mat) : 'SIN ASIGNAR';
        
        $grupo->load('turno');
        $turnoStr = $grupo->turno ? ucfirst($grupo->turno->tipo_turno) : 'SIN ASIGNAR';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- ENCABEZADOS DE LA ESCUELA ---
        $sheet->setCellValue('A1', 'Universidad Autónoma de Baja California');
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('A2', 'Facultad de Ingeniería, Arquitectura y Diseño - Curso de nivelación 2026');
        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        // --- DATOS DEL GRUPO Y PROFESOR ---
        $sheet->setCellValue('B3', 'Docente:');
        $sheet->setCellValue('C3', $nombreDocente);
        $sheet->getStyle('B3:C3')->getFont()->setBold(true);

        $sheet->setCellValue('B4', 'Turno:');
        $sheet->setCellValue('C4', $turnoStr);
        $sheet->setCellValue('E4', 'Horario:');
        $sheet->setCellValue('F4', '08:00 - 13:00'); 
        $sheet->setCellValue('J4', 'Salón:');
        $sheet->setCellValue('K4', 'Por definir'); 
        $sheet->getStyle('B4:K4')->getFont()->setBold(true);

        // --- ENCABEZADOS DE LA TABLA ---
        $sheet->setCellValue('H6', 'Asistencias');
        $sheet->mergeCells('H6:L6');
        $sheet->getStyle('H6')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('H6')->getFont()->setBold(true);

        $headers = ['No.', 'Nombre', 'Apellido Paterno', 'Apellido Materno', 'Matrícula', 'Carrera a cursar', 'Correo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        $colIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($colIndex . '7', $header);
            $sheet->getStyle($colIndex . '7')->getFont()->setBold(true);
            $colIndex++;
        }

        // --- IMPRIMIR ALUMNOS ---
        $row = 8;
        $contador = 1;
        foreach ($grupo->alumnos as $alumno) {
            $sheet->setCellValue('A' . $row, $contador);
            $sheet->setCellValue('B' . $row, mb_strtoupper($alumno->nombre));
            $sheet->setCellValue('C' . $row, mb_strtoupper($alumno->ap_pat));
            $sheet->setCellValue('D' . $row, mb_strtoupper($alumno->ap_mat));
            $sheet->setCellValue('E' . $row, $alumno->matricula);
            
            $row++;
            $contador++;
        }

        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'Lista_' . str_replace(' ', '_', $grupo->nombre_grupo) . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function showCursoPrope()
    {
        // 1. Buscamos TODOS los grupos que contengan 'Prope Inge' o 'Prope Arqui'
        $gruposInge = Grupo::where('nombre_grupo', 'LIKE', '%Prope Inge%')->get();
        $gruposArqui = Grupo::where('nombre_grupo', 'LIKE', '%Prope Arqui%')->get();

        // 2. Traemos a los docentes de la tabla usuarios
        $docentes = \App\Models\Usuario::whereHas('rol', function($q) {
            $q->where('nombre_rol', 'docente');
        })->get();

        return view('groups.crear_grupos_cursos.curso_prope', compact('gruposInge', 'gruposArqui', 'docentes'));
    }

    public function guardarProfesores(Request $request)
    {
        // 1. Validamos que el formulario nos envíe el arreglo de 'docentes'
        $request->validate([
            'docentes' => 'required|array',
        ]);

        // 2. Iniciamos una transacción por seguridad
        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            // 3. Recorremos el arreglo. $id_grupo es la llave, $num_empleado es el valor seleccionado
            foreach ($request->docentes as $id_grupo => $num_empleado) {
                // Solo actualizamos si el administrador realmente seleccionó un docente (no está vacío)
                if (!empty($num_empleado)) {
                    $grupo = Grupo::findOrFail($id_grupo);
                    $grupo->id_usuario = $num_empleado;
                    $grupo->save();
                }
            }

            // 4. Si todo salió bien, confirmamos y regresamos con mensaje de éxito
            \Illuminate\Support\Facades\DB::commit();
            return redirect()->back()->with('success', '¡Docentes asignados correctamente a los grupos!');

        } catch (\Exception $e) {
            // Si algo falla, deshacemos todo para no dejar la base de datos a medias
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withErrors(['Error al asignar docentes: ' . $e->getMessage()]);
        }
    }

    public function showCursoInduc()
    {
        // Traemos solo los grupos que tengan la palabra 'Induc'
        $grupos = Grupo::where('nombre_grupo', 'LIKE', '%Induc%')->get();
        
        // Traemos todos los docentes para el menú desplegable
        $docentes = \App\Models\Usuario::whereHas('rol', function($q) {
            $q->where('nombre_rol', 'docente');
        })->get();

        return view('groups.crear_grupos_cursos.curso_induc', compact('grupos', 'docentes'));
    }

    public function storeInduc(Request $request)
    {
        // 1. EL CANDADO DE SEGURIDAD (Verificar si ya hay grupos de Inducción)
        $gruposExistentes = Grupo::where('nombre_grupo', 'LIKE', '%Induc%')->exists();

        if ($gruposExistentes) {
            return redirect()->back()->with('error_grupos_existentes', 'Los grupos de Inducción iniciales ya fueron generados. Para agregar más estudiantes, utiliza el módulo de Alumnos Tardíos.');
        }

        // 2. Validar que vengan los datos (ahora solo pedimos 2 cajitas)
        $request->validate([
            'grupos_manana'   => 'required|integer|min:0',
            'grupos_tarde'    => 'required|integer|min:0',
            'archivo_alumnos' => 'required|mimes:xlsx,xls,csv',
            'tipo_grupo'      => 'required|string' // Siempre dirá "Inducción"
        ]);

        try {
            DB::beginTransaction();

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('archivo_alumnos')->getRealPath());
            $filas = $spreadsheet->getActiveSheet()->toArray();

            // =========================================================
            // Leemos los encabezados de la fila 0 y los normalizamos
            // =========================================================
            $encabezados = array_map(function($col) {
                return strtolower(trim($col));
            }, $filas[0]);

            $mapa = array_flip($encabezados);

            // Precargamos las carreras de la BD para buscar dinámicamente
            $carreras = Carrera::all();

            $alumnosGenerales = []; // Solo una cubeta, aquí van todos revueltos
            
            for ($i = 1; $i < count($filas); $i++) {
                $fila = $filas[$i];
                
                $matricula = isset($mapa['matricula']) ? trim($fila[$mapa['matricula']]) : '';
                if (empty($matricula)) continue; 

                $programa = isset($mapa['programa_desc']) ? strtoupper(trim($fila[$mapa['programa_desc']])) : '';
                $telefono = isset($mapa['telefono']) && !empty(trim($fila[$mapa['telefono']])) ? trim($fila[$mapa['telefono']]) : substr($matricula . rand(100, 999), 0, 10);
                $correo_alt = isset($mapa['correo_alter']) && !empty(trim($fila[$mapa['correo_alter']])) ? trim($fila[$mapa['correo_alter']]) : $matricula . '@sin-correo.com';
                $correo_inst = isset($mapa['correo']) && !empty(trim($fila[$mapa['correo']])) ? trim($fila[$mapa['correo']]) : null;
                $puntaje = isset($mapa['puntaje']) && !empty(trim($fila[$mapa['puntaje']])) ? trim($fila[$mapa['puntaje']]) : null;

                // Determinar la carrera dinámicamente
                $id_carrera = 1;
                if (str_contains($programa, 'ARQUITECTURA')) {
                    $carreraObj = $carreras->first(function($c) {
                        return str_contains(strtoupper($c->nombre_carrera), 'ARQUITECTURA');
                    });
                    $id_carrera = $carreraObj ? $carreraObj->id_carrera : 2;
                } else {
                    $carreraObj = $carreras->first(function($c) {
                        return str_contains(strtoupper($c->nombre_carrera), 'INGENIERIA');
                    });
                    $id_carrera = $carreraObj ? $carreraObj->id_carrera : 1;
                }

                $alumnosGenerales[] = [
                    'matricula'             => $matricula,
                    'nombre'                => isset($mapa['nombre']) ? substr(trim($fila[$mapa['nombre']]), 0, 45) : '',
                    'ap_pat'                => isset($mapa['apellido_paterno']) ? substr(trim($fila[$mapa['apellido_paterno']]), 0, 25) : '',
                    'ap_mat'                => isset($mapa['apellido_materno']) ? substr(trim($fila[$mapa['apellido_materno']]), 0, 25) : '',
                    'correo_institucional'  => $correo_inst,
                    'correo_alternativo'    => substr($correo_alt, 0, 150),
                    'telefono'              => $telefono,
                    'puntaje_ingreso'       => $puntaje,
                    'id_carrera'            => $id_carrera,
                ];
            }

            // 3. RECICLAMOS LA MAGIA: Mandamos todos a la función ayudante que ya tenías
            $stats = $this->procesarGrupos(
                $request->grupos_manana, 
                $request->grupos_tarde, 
                'Gral', // Etiqueta para el nombre del grupo
                $alumnosGenerales, 
                $request->tipo_grupo
            );

            DB::commit();
            
            // Mandamos los resultados a la vista frontal
            return redirect()->back()->with('import_stats', [
                'nuevos' => $stats['nuevos'],
                'repetidos' => $stats['repetidos'] // Esta será la estadística estrella en Inducción
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['Error SQL: ' . $e->getMessage()]);
        }
    }
}