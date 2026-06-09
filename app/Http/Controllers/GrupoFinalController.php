<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Grupo; // <-- IMPORTANTE: Agregamos el modelo Grupo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\GenerarDistribucionRequest;

class GrupoFinalController extends Controller
{
    /**
     * Muestra la pantalla inicial de criterios (80/20).
     */
    public function configurar()
    {
        return view('grupos_finales.criterios');
    }

    /**
     * Muestra la tabla de grupos finales generados.
     */
    public function gruposFinales()
    {
        // 1. Traemos los grupos de Tronco Común (Excluimos los que dicen ARQ)
        $gruposTC = Grupo::with(['turno', 'alumnosFinales.carrera'])
            ->withCount('alumnosFinales')
            ->where('id_curso', 3)
            ->where('nombre_grupo', 'not like', '%ARQ%')
            ->orderBy('nombre_grupo', 'asc')
            ->get();

        // 2. Traemos los grupos de Arquitectura (Solo los que dicen ARQ)
        $gruposArq = Grupo::with(['turno', 'alumnosFinales.carrera'])
            ->withCount('alumnosFinales')
            ->where('id_curso', 3)
            ->where('nombre_grupo', 'like', '%ARQ%')
            ->orderBy('nombre_grupo', 'asc')
            ->get();

        return view('grupos_finales.grupos_finales', compact('gruposTC', 'gruposArq'));
    }

    /**
     * Algoritmo central de distribución de grupos (80/20).
     * Retorna JSON estricto cumpliendo la norma del SGNI.
     */
    public function generarDistribucion(GenerarDistribucionRequest $request)
    {
        $porcentajeAlto = (int) $request->validated()['porcentaje_alto'];
        $limitePorGrupo = 30; 

        DB::beginTransaction();

        try {
            // 1. LIMPIEZA PREVIA: Sacamos a TODOS de sus grupos definitivos actuales
            Alumno::whereNotNull('id_grupo_definitivo')->update(['id_grupo_definitivo' => null]);

            // 2. Traer el universo de alumnos de inducción
            $alumnos = Alumno::whereNotNull('id_grupo_induccion')
                ->orderBy('puntaje_ingreso', 'desc')
                ->get();

            if ($alumnos->isEmpty()) {
                return response()->json([
                    'message' => 'No hay alumnos registrados en cursos previos para distribuir.'
                ], 400);
            }

            // 3. DESENREDAR ALUMNOS: Separación estricta por ID de Carrera
            $alumnosTC = $alumnos->where('id_carrera', 1)->values();
            $alumnosArq = $alumnos->where('id_carrera', 2)->values(); // Aseguramos que solo sea Arq (ID 2)

            // 4. CONFIGURACIÓN DE GRUPOS
            $configTC = [
                ['nombre' => 'Grupo 11', 'turno' => 2], ['nombre' => 'Grupo 12', 'turno' => 2],
                ['nombre' => 'Grupo 14', 'turno' => 2], ['nombre' => 'Grupo 17', 'turno' => 2],
                ['nombre' => 'Grupo 19', 'turno' => 2], ['nombre' => 'Grupo 15', 'turno' => 3],
                ['nombre' => 'Grupo 18', 'turno' => 3], ['nombre' => 'Grupo 13', 'turno' => 1],
                ['nombre' => 'Grupo 16', 'turno' => 1],
            ];

            $configArq = [
                ['nombre' => 'TC ARQ 101', 'turno' => 2], // Matutino
                ['nombre' => 'TC ARQ 105', 'turno' => 3], // Intermedio
                ['nombre' => 'TC ARQ 103', 'turno' => 1], // Vespertino
            ];

            $idCurso = 3;

            // 5. FUNCIÓN AISLADA (Modificada para crear grupos obligatoriamente)
            $procesarBloque = function($listaAlumnos, $configuracion) use ($porcentajeAlto, $limitePorGrupo, $idCurso) {
                $gruposIds = [];
                $conteos = [];

                // PASO A: Crear los grupos en la BD SIEMPRE (incluso si no hay alumnos de esta carrera)
                foreach ($configuracion as $config) {
                    $grupo = Grupo::updateOrCreate(
                        ['nombre_grupo' => $config['nombre'], 'id_curso' => $idCurso],
                        ['id_turno' => $config['turno'], 'id_usuario' => auth()->id() ?? 1, 'id_estado' => 1]
                    );
                    $gruposIds[] = $grupo->id_grupo;
                    $conteos[$grupo->id_grupo] = 0;
                }

                // PASO B: Si la lista de alumnos está vacía, ya creamos los grupos, podemos salir.
                if ($listaAlumnos->isEmpty()) return;

                // PASO C: Si hay alumnos, procedemos a distribuir (80/20)
                $total = $listaAlumnos->count();
                $cantidadAltos = (int) round($total * ($porcentajeAlto / 100));
                $altos = $listaAlumnos->take($cantidadAltos);
                $bajos = $listaAlumnos->slice($cantidadAltos);
                $totalGrupos = count($gruposIds);
                $idx = 0;

                $distribuir = function($lista) use (&$idx, &$conteos, $gruposIds, $totalGrupos, $limitePorGrupo) {
                    foreach ($lista as $alumno) {
                        $intentos = 0;
                        while ($conteos[$gruposIds[$idx]] >= $limitePorGrupo) {
                            $idx = ($idx + 1) % $totalGrupos;
                            $intentos++;
                            if ($intentos >= $totalGrupos) return false;
                        }
                        $alumno->id_grupo_definitivo = $gruposIds[$idx];
                        $alumno->save();
                        $conteos[$gruposIds[$idx]]++;
                        $idx = ($idx + 1) % $totalGrupos;
                    }
                    return true;
                };

                if ($distribuir($altos)) {
                    $distribuir($bajos);
                }
            };

            // 6. Ejecutar la distribución para ambos bloques
            $procesarBloque($alumnosTC, $configTC);
            $procesarBloque($alumnosArq, $configArq);

            DB::commit();

            return response()->json([
                'message' => 'Grupos de Tronco Común y Arquitectura generados de forma independiente.',
                'redirect_url' => route('grupos_finales.lista')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Ocurrió un error interno.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Muestra la lista de estudiantes de un grupo final específico.
     */
    public function verListaGrupo($id)
    {
        // Traemos el grupo junto con los alumnos que le fueron asignados
        // Nota: Asegúrate de usar la relación correcta, aquí asumo 'alumnosFinales' 
        // basada en tu vista anterior. Si en tu modelo se llama 'alumnos', cámbialo.
        $grupo = Grupo::with('alumnosFinales')->findOrFail($id);

        return view('grupos_finales.lista_grupo_final', compact('grupo'));
    }

    /**
     * Exporta la lista de estudiantes en formato CSV (Compatible con Excel).
     */
    public function descargarLista($id)
    {
        // Traemos el grupo con sus relaciones: alumnosFinales (y su carrera) y el turno
        $grupo = Grupo::with(['alumnosFinales.carrera', 'turno'])->findOrFail($id);
        
        // Buscamos al docente asignado (si existe)
        $docente = \App\Models\Usuario::where('num_empleado', $grupo->id_usuario)->first();
        $nombreDocente = $docente ? mb_strtoupper($docente->nombre . ' ' . $docente->ap_pat . ' ' . $docente->ap_mat) : 'SIN ASIGNAR';
        
        // Obtenemos el turno
        $turnoStr = $grupo->turno ? ucfirst(strtolower($grupo->turno->tipo_turno)) : 'SIN ASIGNAR';

        // Inicializamos el creador de Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- ENCABEZADOS DE LA ESCUELA ---
        $sheet->setCellValue('A1', 'Universidad Autónoma de Baja California');
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        // Cambié el título para que refleje que ya es Primer Semestre (Grupos Definitivos)
        $sheet->setCellValue('A2', 'Facultad de Ingeniería, Arquitectura y Diseño - Primer Semestre 2026');
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
        $sheet->setCellValue('F4', 'Por definir'); // El horario se queda por definir por ahora
        $sheet->setCellValue('J4', 'Salón:');
        $sheet->setCellValue('K4', 'Por definir'); 
        $sheet->getStyle('B4:K4')->getFont()->setBold(true);

        // --- ENCABEZADOS DE LA TABLA DE ASISTENCIAS ---
        $sheet->setCellValue('H6', 'Asistencias');
        $sheet->mergeCells('H6:L6');
        $sheet->getStyle('H6')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('H6')->getFont()->setBold(true);

        // Columnas principales
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
        
        foreach ($grupo->alumnosFinales as $alumno) {
            $sheet->setCellValue('A' . $row, $contador);
            $sheet->setCellValue('B' . $row, mb_strtoupper($alumno->nombre));
            $sheet->setCellValue('C' . $row, mb_strtoupper($alumno->ap_pat));
            $sheet->setCellValue('D' . $row, mb_strtoupper($alumno->ap_mat));
            $sheet->setCellValue('E' . $row, $alumno->matricula);
            
            // Extraemos dinámicamente la carrera a cursar
            $carrera = $alumno->carrera ? mb_strtoupper($alumno->carrera->nombre_carrera) : 'SIN ASIGNAR';
            $sheet->setCellValue('F' . $row, $carrera);
            
            // Extraemos el correo
            $correo = $alumno->correo_institucional ?? $alumno->correo_alternativo ?? 'SIN CORREO';
            $sheet->setCellValue('G' . $row, strtolower($correo));
            
            $row++;
            $contador++;
        }

        // --- AUTOAJUSTAR COLUMNAS ---
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Generamos el nombre de descarga combinando el texto de Primer Semestre con el nombre del Grupo
        $fileName = 'Lista_Primer_Semestre_' . str_replace(' ', '_', $grupo->nombre_grupo) . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Retornamos el archivo para su descarga automática
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}