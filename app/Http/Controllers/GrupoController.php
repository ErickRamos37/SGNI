<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use App\Models\Grupo;
use App\Models\Alumno;

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
        $prefijo = ($request->tipo_grupo === 'Propedéutico') ? 'Prope' : 'Induc';
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

            $alumnosInge = [];
            $alumnosArqui = [];
            
            for ($i = 1; $i < count($filas); $i++) {
                if (count($filas[$i]) < 10) continue; 
                
                $matricula = trim($filas[$i][5]);
                if (empty($matricula)) continue; 

                $programa = strtoupper(trim($filas[$i][4])); 
                $telefono = !empty($filas[$i][11]) ? trim($filas[$i][11]) : substr($matricula . rand(100, 999), 0, 10);
                $correo_alt = !empty($filas[$i][9]) ? trim($filas[$i][9]) : $matricula . '@sin-correo.com';

                $datosAlumno = [
                    'matricula'          => $matricula,
                    'nombre'             => substr(trim($filas[$i][6]), 0, 45),
                    'ap_pat'             => substr(trim($filas[$i][7]), 0, 25),
                    'ap_mat'             => substr(trim($filas[$i][8]), 0, 25),
                    'correo_alternativo' => substr($correo_alt, 0, 150),
                    'telefono'           => $telefono,
                ];

                if (str_contains($programa, 'ARQUITECTURA')) {
                    $datosAlumno['id_carrera'] = 2; 
                    $alumnosArqui[] = $datosAlumno;
                } else {
                    $datosAlumno['id_carrera'] = 1; 
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

        $idCurso = ($tipoGrupo === 'Propedéutico') ? 1 : 2;

        $idAdminActual = auth()->user()->num_empleado;

        for ($i = 0; $i < $totalGrupos; $i++) {
            $idTurno = ($i < $gruposManana) ? 1 : 2; 
            $prefijo = ($tipoGrupo === 'Propedéutico') ? 'Prope' : 'Induc';

            $gruposCreados[] = Grupo::create([
                'nombre_grupo' => $prefijo . ' ' . $etiqueta . ' - Gpo ' . $letras[$i],
                'id_turno'     => $idTurno,
                'id_curso'     => $idCurso, 
                'num_empleado' => auth()->user()->num_empleado, 
                'id_estado'    => 1, 
            ]);
        }

        // =========================================================
        // Revolvemos a los alumnos de forma aleatoria
        // =========================================================
        shuffle($alumnos);

        $chunks = array_chunk($alumnos, ceil(count($alumnos) / $totalGrupos));
        
        $nuevos = 0;
        $repetidos = 0;

        foreach ($gruposCreados as $index => $grupo) {
            if (isset($chunks[$index])) {
                foreach ($chunks[$index] as $data) {
                    $columnaGrupo = ($tipoGrupo === 'Propedéutico') ? 'id_grupo_propedeutico' : 'id_grupo_induccion';
                    // Insertamos o actualizamos
                    $alumno = Alumno::updateOrCreate(
                        ['matricula' => $data['matricula']],
                        [
                            'nombre'             => $data['nombre'],
                            'ap_pat'             => $data['ap_pat'],
                            'ap_mat'             => $data['ap_mat'],
                            'correo_alternativo' => $data['correo_alternativo'],
                            'telefono'           => $data['telefono'],
                            'id_carrera'         => $data['id_carrera'],
                            $columnaGrupo        => $grupo->id_grupo,
                        ]
                    );

                    if ($alumno->wasRecentlyCreated) {
                        $nuevos++;
                    } else {
                        $repetidos++;
                    }
                }
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

        // Si es Inducción (2), cargamos la relación correcta
        if ($grupo->id_curso == 2) {
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
        
        // El mismo truco para que el Excel sepa a quién exportar
        if ($grupo->id_curso == 2) {
            $grupo->load('alumnosInduccion');
            $grupo->alumnos = $grupo->alumnosInduccion; 
        } else {
            $grupo->load('alumnos');
        }
        
        $profesor = \App\Models\Usuario::where('num_empleado', $grupo->num_empleado)->first();
        $nombreDocente = $profesor ? mb_strtoupper($profesor->nombre . ' ' . $profesor->ap_pat . ' ' . $profesor->ap_mat) : 'SIN ASIGNAR';
        
        $turnoStr = $grupo->id_turno == 1 ? 'Matutino' : 'Vespertino';

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
        // 1. Buscamos TODOS los grupos que contengan 'Prope' (esto traerá a los de Inge y Arqui automáticamente)
        $grupos = Grupo::where('nombre_grupo', 'LIKE', '%Prope%')->get();

        // 2. Traemos a los profesores de la tabla usuarios
        $profesores = \App\Models\Usuario::all();

        return view('groups.crear_grupos_cursos.curso_prope', compact('grupos', 'profesores'));
    }

    public function guardarProfesores(Request $request)
    {
        // 1. Validamos que el formulario nos envíe el arreglo de 'profesores'
        $request->validate([
            'profesores' => 'required|array',
        ]);

        // 2. Iniciamos una transacción por seguridad
        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            // 3. Recorremos el arreglo. $id_grupo es la llave, $num_empleado es el valor seleccionado
            foreach ($request->profesores as $id_grupo => $num_empleado) {
                // Solo actualizamos si el administrador realmente seleccionó un profesor (no está vacío)
                if (!empty($num_empleado)) {
                    $grupo = Grupo::findOrFail($id_grupo);
                    $grupo->num_empleado = $num_empleado; // Asignamos el profe al grupo
                    $grupo->save(); // Guardamos el cambio en la base de datos
                }
            }

            // 4. Si todo salió bien, confirmamos y regresamos con mensaje de éxito
            \Illuminate\Support\Facades\DB::commit();
            return redirect()->back()->with('success', '¡Profesores asignados correctamente a los grupos!');

        } catch (\Exception $e) {
            // Si algo falla, deshacemos todo para no dejar la base de datos a medias
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withErrors(['Error al asignar profesores: ' . $e->getMessage()]);
        }
    }

    public function showCursoInduc()
    {
        // Traemos solo los grupos que tengan la palabra 'Induc'
        $grupos = Grupo::where('nombre_grupo', 'LIKE', '%Induc%')->get();
        
        // Traemos todos los profesores para el menú desplegable
        $profesores = \App\Models\Usuario::all();

        return view('groups.crear_grupos_cursos.curso_induc', compact('grupos', 'profesores'));
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

            $alumnosGenerales = []; // Solo una cubeta, aquí van todos revueltos
            
            for ($i = 1; $i < count($filas); $i++) {
                if (count($filas[$i]) < 10) continue; 
                
                $matricula = trim($filas[$i][5]);
                if (empty($matricula)) continue; 

                $programa = strtoupper(trim($filas[$i][4])); 
                $telefono = !empty($filas[$i][11]) ? trim($filas[$i][11]) : substr($matricula . rand(100, 999), 0, 10);
                $correo_alt = !empty($filas[$i][9]) ? trim($filas[$i][9]) : $matricula . '@sin-correo.com';

                // Aunque vayan en el mismo grupo, les registramos su carrera correcta en la BD por si son nuevos
                $id_carrera = str_contains($programa, 'ARQUITECTURA') ? 2 : 1;

                $alumnosGenerales[] = [
                    'matricula'          => $matricula,
                    'nombre'             => substr(trim($filas[$i][6]), 0, 45),
                    'ap_pat'             => substr(trim($filas[$i][7]), 0, 25),
                    'ap_mat'             => substr(trim($filas[$i][8]), 0, 25),
                    'correo_alternativo' => substr($correo_alt, 0, 150),
                    'telefono'           => $telefono,
                    'id_carrera'         => $id_carrera,
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