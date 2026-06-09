<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Alumno;
use App\Models\Asistencia;
use App\Models\Grupo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AsistenciaController extends Controller
{
    public function paselista()
    {
        $asistenciasExcel = session('alumnos_importados', []);
        $alumnosDB = Alumno::all();

        if ($alumnosDB->isEmpty() && !empty($asistenciasExcel)) {
            $alumnos = collect($asistenciasExcel)->map(function ($item) {
                return (object) [
                    'matricula_alumno' => $item['matricula'],
                    'nombres_alumno'   => $item['nombre_solo'] ?? 'ALUMNO',
                    'apellidos_alumno' => ($item['ap_pat'] ?? '') . ' ' . ($item['ap_mat'] ?? ''),
                    'ap_pat'           => $item['ap_pat'] ?? '',
                    'ap_mat'           => $item['ap_mat'] ?? '',
                ];
            });
        } else {
            $alumnos = $alumnosDB->map(function ($a) {
                return (object) [
                    'matricula_alumno' => $a->matricula,
                    'nombres_alumno'   => $a->nombre,
                    'apellidos_alumno' => ($a->ap_pat ?? '') . ' ' . ($a->ap_mat ?? ''),
                    'ap_pat'           => $a->ap_pat ?? '',
                    'ap_mat'           => $a->ap_mat ?? '',
                ];
            });
        }

        // Cargamos los grupos para el <select> del blade
        $grupos = Grupo::all();

        return view('asistencia.paselista', compact('alumnos', 'asistenciasExcel', 'grupos'));
    }

    public function grupal()
    {
        $idGrupo = request('id_grupo');

        // Grupos del profesor autenticado
        $grupos = \App\Models\Grupo::where('num_empleado', auth()->user()->num_empleado)->get();

        $alumnos     = collect();
        $grupoActual = null;
        $diasSemana  = [];
        $lunesSemana = Carbon::now()->startOfWeek();

        // Fechas de lunes a viernes de la semana actual
        for ($i = 0; $i < 5; $i++) {
            $diasSemana[] = $lunesSemana->copy()->addDays($i);
        }

        if ($idGrupo) {
            $grupoActual = \App\Models\Grupo::find($idGrupo);

            if ($grupoActual) {
                $relacion = $grupoActual->id_curso == 2 ? 'alumnosInduccion' : 'alumnos';

                $grupoActual->load([$relacion . '.asistencias' => function ($query) use ($lunesSemana) {
                    $query->whereBetween('fecha', [
                        $lunesSemana->toDateString(),
                        $lunesSemana->copy()->addDays(4)->toDateString()
                    ]);
                }]);

                $alumnos = $grupoActual->id_curso == 2
                    ? $grupoActual->alumnosInduccion
                    : $grupoActual->alumnos;
            }
        }

        return view('asistencia.grupal', compact('grupos', 'grupoActual', 'alumnos', 'diasSemana'));
    }

    public function procesar(Request $request)
    {
        $request->validate([
            'archivo_asistencia' => 'required|file|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            $archivo      = $request->file('archivo_asistencia');
            $spreadsheet  = IOFactory::load($archivo->getRealPath());
            $filas        = $spreadsheet->getActiveSheet()->toArray();

            $alumnos = [];
            for ($i = 7; $i < count($filas); $i++) {
                $fila = $filas[$i];
                if (!isset($fila[4]) || empty(trim($fila[4]))) continue;

                $alumnos[] = [
                    'matricula'   => trim($fila[4]),
                    'nombre_solo' => mb_strtoupper($fila[1] ?? ''),
                    'ap_pat'      => mb_strtoupper($fila[2] ?? ''),
                    'ap_mat'      => mb_strtoupper($fila[3] ?? ''),
                    'correo'      => $fila[6] ?? '',
                    'lunes'       => $this->evaluarAsistencia($fila[7]  ?? null),
                    'martes'      => $this->evaluarAsistencia($fila[8]  ?? null),
                    'miercoles'   => $this->evaluarAsistencia($fila[9]  ?? null),
                    'jueves'      => $this->evaluarAsistencia($fila[10] ?? null),
                    'viernes'     => $this->evaluarAsistencia($fila[11] ?? null),
                ];
            }

            session(['alumnos_importados' => $alumnos]);

            return response()->json([
                'success' => true,
                'mensaje' => 'Archivo procesado con éxito'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'mensaje' => $e->getMessage()], 500);
        }
    }

    private function evaluarAsistencia($valor): bool
    {
        return in_array(strtolower(trim($valor ?? '')), ['presente', 'p', '1', 'sí', 'si', 'x', 'asistencia']);
    }

    public function guardarMasivo(Request $request)
    {
        // FIX 1: Eliminada la validación que rechazaba id_grupo = 1 sin justificación
        $idGrupo = (int) $request->id_grupo;

        if ($idGrupo <= 0) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error: El grupo seleccionado no es válido (ID: ' . $idGrupo . ')'
            ], 400);
        }

        // FIX 2: Detectamos el tipo de grupo para saber qué columna actualizar
        $grupo = Grupo::find($idGrupo);
        if (!$grupo) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Error: No existe un grupo con ID ' . $idGrupo
            ], 404);
        }

        // id_curso = 1 → Propedéutico, id_curso = 2 → Inducción
        $columnaGrupo = ($grupo->id_curso == 2)
            ? 'id_grupo_induccion'
            : 'id_grupo_propedeutico';

        try {
            DB::transaction(function () use ($request, $idGrupo, $columnaGrupo) {
                $lunesSemana = Carbon::now()->startOfWeek();

                foreach ($request->asistencias as $data) {
                    $matricula = $data['matricula'];

                    // Si no tiene correo, generamos uno único basado en la matrícula
                    $correo = !empty($data['correo'])
                        ? $data['correo']
                        : $matricula . '@sin-correo.uabc.mx';

                    $alumno = Alumno::updateOrCreate(
                        ['matricula' => $matricula],
                        [
                            'nombre'               => $data['nombre'] ?? 'ALUMNO',
                            'ap_pat'               => $data['ap_pat'] ?? '',
                            'ap_mat'               => $data['ap_mat'] ?? null,
                            'correo_institucional' => $correo,
                            'correo_alternativo'   => $correo,
                            'telefono'             => $data['telefono'] ?? $matricula,
                            'id_carrera'           => 1,
                            $columnaGrupo          => $idGrupo,
                        ]
                    );

                    $dias = [
                        0 => $data['lunes'],
                        1 => $data['martes'],
                        2 => $data['miercoles'],
                        3 => $data['jueves'],
                        4 => $data['viernes'],
                    ];

                    foreach ($dias as $idx => $asistio) {
                        Asistencia::updateOrCreate(
                            [
                                'matricula'        => $alumno->matricula,
                                'id_grupo'         => $idGrupo,
                                'fecha'     => $lunesSemana->copy()->addDays($idx)->toDateString(),
                            ],
                            ['asistio' => $asistio ? 1 : 0]
                        );
                    }
                }
            });

            return response()->json(['success' => true, 'mensaje' => 'Asistencias guardadas exitosamente.']);

        } catch (\Exception $e) {
            Log::error("Error en guardarMasivo: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'mensaje' => 'Error en base de datos: ' . $e->getMessage()
            ], 500);
        }
    }
}