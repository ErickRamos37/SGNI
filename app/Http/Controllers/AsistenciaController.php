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
use Yajra\DataTables\Facades\DataTables;

class AsistenciaController extends Controller
{
    public function paselista(Request $request)
    {
        if (auth()->user()->rol->nombre_rol === 'Administrador') {
            abort(403, 'Acceso denegado. Los administradores no pueden pasar lista.');
        }

        $lunesSemana = Carbon::now()->startOfWeek();

        // Cargamos los grupos asignados al docente autenticado (usando id_usuario de Laravel Auth)
        $grupos = Grupo::where('id_usuario', auth()->user()->id_usuario)->get();

        $idGrupo = $request->query('id_grupo');
        $grupoActual = null;

        if ($idGrupo) {
            $grupoActual = $grupos->firstWhere('id_grupo', $idGrupo);
        }

        if (!$grupoActual && $grupos->isNotEmpty()) {
            $grupoActual = $grupos->first();
        }

        $alumnosDB = collect();
        $asistenciasSemana = collect();
        $esLectura = false;

        if ($grupoActual) {
            $relacion = $grupoActual->id_curso == 1 ? 'alumnosInduccion' : 'alumnos';
            $alumnosDB = $grupoActual->$relacion;

            // Cargamos las asistencias guardadas para la semana actual
            $asistenciasSemana = Asistencia::where('id_grupo', $grupoActual->id_grupo)
                ->whereBetween('fecha', [
                    $lunesSemana->toDateString(),
                    $lunesSemana->copy()->addDays(4)->toDateString()
                ])
                ->get()
                ->groupBy('matricula');

            $estadoLectura = DB::table('estado_grupo')->whereRaw('LOWER(nombre_estado) = ?', ['lectura'])->first();
            $esLectura = $estadoLectura && ($grupoActual->id_estado == $estadoLectura->id_estado);
        }

        $alumnos = $alumnosDB->map(function ($a) {
            return (object) [
                'matricula_alumno' => $a->matricula,
                'nombres_alumno'   => $a->nombre,
                'apellidos_alumno' => ($a->ap_pat ?? '') . ' ' . ($a->ap_mat ?? ''),
                'ap_pat'           => $a->ap_pat ?? '',
                'ap_mat'           => $a->ap_mat ?? '',
            ];
        });

        return view('asistencia.paselista', compact('alumnos', 'grupos', 'grupoActual', 'asistenciasSemana', 'lunesSemana', 'esLectura'));
    }

    public function grupal(Request $request)
    {
        $idGrupo = $request->query('id_grupo');
        $lunesSemana = Carbon::now()->startOfWeek();
        $userRole = auth()->user()->rol->nombre_rol;

        if ($userRole === 'Administrador') {
            $grupos = Grupo::all();
        } else {
            $grupos = Grupo::where('id_usuario', auth()->user()->id_usuario)->get();
        }

        $grupoActual = null;
        if ($idGrupo) {
            $grupoActual = $grupos->firstWhere('id_grupo', $idGrupo);
            if (!$grupoActual && $userRole === 'Administrador') {
                $grupoActual = Grupo::find($idGrupo);
            }
        }

        if (!$grupoActual && $grupos->isNotEmpty()) {
            $grupoActual = $grupos->first();
        }

        $lunesStr = $lunesSemana->toDateString();
        $martesStr = $lunesSemana->copy()->addDays(1)->toDateString();
        $miercolesStr = $lunesSemana->copy()->addDays(2)->toDateString();
        $juevesStr = $lunesSemana->copy()->addDays(3)->toDateString();
        $viernesStr = $lunesSemana->copy()->addDays(4)->toDateString();

        if ($request->ajax()) {
            if (!$grupoActual) {
                return DataTables::of(collect())->make(true);
            }

            $relacion = $grupoActual->id_curso == 1 ? 'id_grupo_induccion' : 'id_grupo_propedeutico';

            $alumnos = Alumno::where($relacion, $grupoActual->id_grupo)
                ->with(['asistencias' => function ($query) use ($grupoActual, $lunesStr, $viernesStr) {
                    $query->where('id_grupo', $grupoActual->id_grupo)
                          ->whereBetween('fecha', [$lunesStr, $viernesStr]);
                }])
                ->select('alumno.*');

            return DataTables::of($alumnos)
                ->addColumn('nombre_completo', function ($row) {
                    return trim("{$row->nombre} {$row->ap_pat} {$row->ap_mat}");
                })
                ->filterColumn('nombre_completo', function ($query, $keyword) {
                    $sql = "CONCAT(nombre, ' ', ap_pat, ' ', IFNULL(ap_mat, '')) LIKE ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->addColumn('lunes', function ($row) use ($lunesStr) {
                    return $this->generarBadgeAsistencia($row, $lunesStr);
                })
                ->addColumn('martes', function ($row) use ($martesStr) {
                    return $this->generarBadgeAsistencia($row, $martesStr);
                })
                ->addColumn('miercoles', function ($row) use ($miercolesStr) {
                    return $this->generarBadgeAsistencia($row, $miercolesStr);
                })
                ->addColumn('jueves', function ($row) use ($juevesStr) {
                    return $this->generarBadgeAsistencia($row, $juevesStr);
                })
                ->addColumn('viernes', function ($row) use ($viernesStr) {
                    return $this->generarBadgeAsistencia($row, $viernesStr);
                })
                ->addColumn('porcentaje', function ($row) {
                    $asistenciasSemana = $row->asistencias;
                    $totalPresente = $asistenciasSemana->where('asistio', 1)->count();
                    $porcentaje = round(($totalPresente / 5) * 100);
                    $colorClass = $porcentaje >= 80 ? 'text-primary' : ($porcentaje >= 60 ? 'text-warning' : 'text-danger');
                    return '<span class="fw-bold ' . $colorClass . '">' . $porcentaje . '%</span>';
                })
                ->rawColumns(['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'porcentaje'])
                ->make(true);
        }

        $diasSemana = [];
        for ($i = 0; $i < 5; $i++) {
            $diasSemana[] = $lunesSemana->copy()->addDays($i);
        }

        $totalAlumnos = $grupoActual ? ($grupoActual->id_curso == 1 ? $grupoActual->alumnosInduccion()->count() : $grupoActual->alumnos()->count()) : 0;

        return view('asistencia.grupal', compact('grupos', 'grupoActual', 'diasSemana', 'lunesSemana', 'totalAlumnos'));
    }

    private function generarBadgeAsistencia($alumno, $fecha)
    {
        $asistencia = $alumno->asistencias->firstWhere('fecha', $fecha);

        if (is_null($asistencia)) {
            return '<span class="text-muted">—</span>';
        }

        if ($asistencia->asistio == 1) {
            return '<span class="badge bg-primary rounded-pill px-3 py-2">P</span>';
        } else {
            return '<span class="badge bg-danger rounded-pill px-3 py-2">A</span>';
        }
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

        // SEGURIDAD: Verificar si el grupo está en Modo Lectura
        $estadoLectura = DB::table('estado_grupo')->whereRaw('LOWER(nombre_estado) = ?', ['lectura'])->first();
        if ($estadoLectura && $grupo->id_estado == $estadoLectura->id_estado) {
            return response()->json([
                'success' => false,
                'mensaje' => 'El grupo se encuentra en modo lectura (Bloqueado) y no puede ser editado actualmente.'
            ], 403);
        }

        // id_curso = 1 → Inducción, id_curso = 2 → Propedéutico
        $columnaGrupo = ($grupo->id_curso == 1)
            ? 'id_grupo_induccion'
            : 'id_grupo_propedeutico';

        try {
            $lunesSemana = Carbon::now()->startOfWeek();

            DB::transaction(function () use ($request, $idGrupo, $columnaGrupo, $lunesSemana) {
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