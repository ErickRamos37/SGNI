<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Grupo;
use App\Models\Carrera;
use App\Models\Curso;
use App\Models\Turno;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\GenerarDistribucionRequest;

class GrupoFinalController extends Controller
{
    /**
     * Helper privado para obtener el ID del Curso Final de forma semantica
     */
    private function obtenerIdCursoFinal()
    {
        // Buscamos el curso que no sea propedeutico ni induccion, asumiendo que es el definitivo
        return Curso::where('nombre_curso', 'not like', '%prope%')
                    ->where('nombre_curso', 'not like', '%induc%')
                    ->value('id_curso') ?? 3;
    }

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
        $idCursoFinal = $this->obtenerIdCursoFinal();

        // 1. Traemos los grupos de Tronco Comun (Excluimos los que dicen ARQ)
        $gruposTC = Grupo::with(['turno', 'alumnosFinales.carrera'])
            ->withCount('alumnosFinales')
            ->where('id_curso', $idCursoFinal)
            ->where('nombre_grupo', 'not like', '%ARQ%')
            ->orderBy('nombre_grupo', 'asc')
            ->get();

        // 2. Traemos los grupos de Arquitectura (Solo los que dicen ARQ)
        $gruposArq = Grupo::with(['turno', 'alumnosFinales.carrera'])
            ->withCount('alumnosFinales')
            ->where('id_curso', $idCursoFinal)
            ->where('nombre_grupo', 'like', '%ARQ%')
            ->orderBy('nombre_grupo', 'asc')
            ->get();

        return view('grupos_finales.grupos_finales', compact('gruposTC', 'gruposArq'));
    }

    /**
     * Algoritmo central de distribucion de grupos (80/20) libre de IDs fijos.
     */
    public function generarDistribucion(GenerarDistribucionRequest $request)
    {
        $porcentajeAlto = (int) $request->validated()['porcentaje_alto'];
        $limitePorGrupo = 30;

        DB::beginTransaction();

        try {
            // RESOLUCION SEMANTICA DE ENTORNO: Buscamos IDs mediante texto
            $idCarreraTC = Carrera::where('nombre_carrera', 'like', '%tronco%')->value('id_carrera') ?? 1;
            $idCarreraArq = Carrera::where('nombre_carrera', 'like', '%arq%')->value('id_carrera') ?? 2;
            $idCursoFinal = $this->obtenerIdCursoFinal();

            $idTurnoMatutino = Turno::where('tipo_turno', 'like', '%matutino%')->value('id_turno') ?? 2;
            $idTurnoVespertino = Turno::where('tipo_turno', 'like', '%vespertino%')->value('id_turno') ?? 1;
            $idTurnoIntermedio = Turno::where('tipo_turno', 'like', '%intermedio%')->value('id_turno') ?? 3;

            $idEstadoActivo = DB::table('estado_grupo')->where('nombre_estado', 'like', '%activo%')->value('id_estado') ?? 1;

            // 1. LIMPIEZA PREVIA: Sacamos a TODOS de sus grupos definitivos actuales
            Alumno::whereNotNull('id_grupo_definitivo')->update(['id_grupo_definitivo' => null]);

            // 2. Traer el universo de alumnos que vienen de induccion
            $alumnos = Alumno::whereNotNull('id_grupo_induccion')
                ->orderBy('puntaje_ingreso', 'desc')
                ->get();

            if ($alumnos->isEmpty()) {
                return response()->json([
                    'message' => 'No hay alumnos registrados en cursos previos para distribuir.'
                ], 400);
            }

            // 3. DESENREDAR ALUMNOS: Separacion por variables semanticas dinamicas
            $alumnosTC = $alumnos->where('id_carrera', $idCarreraTC)->values();
            $alumnosArq = $alumnos->where('id_carrera', $idCarreraArq)->values();

            // 4. CONFIGURACION DE GRUPOS CON TURNOS DINAMICOS
            $configTC = [
                ['nombre' => 'Grupo 11', 'turno' => $idTurnoMatutino], ['nombre' => 'Grupo 12', 'turno' => $idTurnoMatutino],
                ['nombre' => 'Grupo 14', 'turno' => $idTurnoMatutino], ['nombre' => 'Grupo 17', 'turno' => $idTurnoMatutino],
                ['nombre' => 'Grupo 19', 'turno' => $idTurnoMatutino], ['nombre' => 'Grupo 15', 'turno' => $idTurnoIntermedio],
                ['nombre' => 'Grupo 18', 'turno' => $idTurnoIntermedio], ['nombre' => 'Grupo 13', 'turno' => $idTurnoVespertino],
                ['nombre' => 'Grupo 16', 'turno' => $idTurnoVespertino],
            ];

            $configArq = [
                ['nombre' => 'TC ARQ 101', 'turno' => $idTurnoMatutino],
                ['nombre' => 'TC ARQ 105', 'turno' => $idTurnoIntermedio],
                ['nombre' => 'TC ARQ 103', 'turno' => $idTurnoVespertino],
            ];

            // 5. FUNCIÓON AISLADA DE PROCESAMIENTO
            $procesarBloque = function($listaAlumnos, $configuracion) use ($porcentajeAlto, $limitePorGrupo, $idCursoFinal, $idEstadoActivo) {
                $gruposIds = [];
                $conteos = [];

                // PASO A: Crear o actualizar los grupos en la BD de forma dinamica
                foreach ($configuracion as $config) {
                    $grupo = Grupo::updateOrCreate(
                        ['nombre_grupo' => $config['nombre'], 'id_curso' => $idCursoFinal],
                        ['id_turno' => $config['turno'], 'id_usuario' => auth()->id() ?? 1, 'id_estado' => $idEstadoActivo]
                    );
                    $gruposIds[] = $grupo->id_grupo;
                    $conteos[$grupo->id_grupo] = 0;
                }

                if ($listaAlumnos->isEmpty()) return;

                // PASO B: Distribucion balanceada (80/20)
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

            // 6. Ejecutar la distribucion de bloques independientes
            $procesarBloque($alumnosTC, $configTC);
            $procesarBloque($alumnosArq, $configArq);

            DB::commit();

            return response()->json([
                'message' => 'Grupos de Tronco Comun y Arquitectura generados de forma independiente.',
                'redirect_url' => route('grupos_finales.lista')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Ocurrio un error interno.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Muestra la lista de estudiantes de un grupo final especifico.
     */
    public function verListaGrupo($id)
    {
        $grupo = Grupo::with('alumnosFinales')->findOrFail($id);
        return view('grupos_finales.lista_grupo_final', compact('grupo'));
    }

    /**
     * Exporta la lista de estudiantes en formato Excel (PhpSpreadsheet).
     */
    public function descargarLista($id)
    {
        $grupo = Grupo::with(['alumnosFinales.carrera', 'turno'])->findOrFail($id);

        $docente = Usuario::where('num_empleado', $grupo->id_usuario)->first();
        $nombreDocente = $docente ? mb_strtoupper($docente->nombre . ' ' . $docente->ap_pat . ' ' . $docente->ap_mat) : 'SIN ASIGNAR';

        $turnoStr = $grupo->turno ? ucfirst(strtolower($grupo->turno->tipo_turno)) : 'SIN ASIGNAR';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- ENCABEZADOS DE LA INSTITUCION ---
        $sheet->setCellValue('A1', 'Universidad Autonoma de Baja California');
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $sheet->setCellValue('A2', 'Facultad de Ingenieria, Arquitectura y Diseno - Primer Semestre 2026');
        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        // --- DATOS DEL GRUPO ---
        $sheet->setCellValue('B3', 'Docente:');
        $sheet->setCellValue('C3', $nombreDocente);
        $sheet->getStyle('B3:C3')->getFont()->setBold(true);

        $sheet->setCellValue('B4', 'Turno:');
        $sheet->setCellValue('C4', $turnoStr);
        $sheet->setCellValue('E4', 'Horario:');
        $sheet->setCellValue('F4', 'Por definir');
        $sheet->setCellValue('J4', 'Salon:');
        $sheet->setCellValue('K4', 'Por definir');
        $sheet->getStyle('B4:K4')->getFont()->setBold(true);

        // --- PANEL DE ASISTENCIAS ---
        $sheet->setCellValue('H6', 'Asistencias');
        $sheet->mergeCells('H6:L6');
        $sheet->getStyle('H6')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('H6')->getFont()->setBold(true);

        $headers = ['No.', 'Nombre', 'Apellido Paterno', 'Apellido Materno', 'Matricula', 'Carrera a cursar', 'Correo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes'];
        $colIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($colIndex . '7', $header);
            $sheet->getStyle($colIndex . '7')->getFont()->setBold(true);
            $colIndex++;
        }

        // --- RENDERIZADO DE FILAS DE ALUMNOS ---
        $row = 8;
        $contador = 1;

        foreach ($grupo->alumnosFinales as $alumno) {
            $sheet->setCellValue('A' . $row, $contador);
            $sheet->setCellValue('B' . $row, mb_strtoupper($alumno->nombre));
            $sheet->setCellValue('C' . $row, mb_strtoupper($alumno->ap_pat));
            $sheet->setCellValue('D' . $row, mb_strtoupper($alumno->ap_mat));
            $sheet->setCellValue('E' . $row, $alumno->matricula);

            $carrera = $alumno->carrera ? mb_strtoupper($alumno->carrera->nombre_carrera) : 'SIN ASIGNAR';
            $sheet->setCellValue('F' . $row, $carrera);

            $correo = $alumno->correo_institucional ?? $alumno->correo_alternativo ?? 'SIN CORREO';
            $sheet->setCellValue('G' . $row, strtolower($correo));

            $row++;
            $contador++;
        }

        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'Lista_Primer_Semestre_' . str_replace(' ', '_', $grupo->nombre_grupo) . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
