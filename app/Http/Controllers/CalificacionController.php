<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadCalificacionesRequest;
use App\Http\Requests\UpdateCalificacionesBatchRequest;
use App\Imports\CalificacionesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;
use App\Models\Grupo;
use App\Models\Alumno;
use App\Models\ResultadosPropedeutico;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Exports\GrupoCalificacionesExport;

class CalificacionController extends Controller
{
    public function showCaptura()
    {
        return view('calificaciones.captura');
    }

    public function upload(UploadCalificacionesRequest $request): RedirectResponse
    {
        try {
            Excel::import(new CalificacionesImport, $request->file('archivo_excel'));

            return redirect()->back()->with('success', '¡Excelente! Las calificaciones del grupo se han cargado y procesado con éxito.');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors([
                'archivo_excel' => 'Error al procesar la estructura interna del archivo: ' . $e->getMessage()
            ]);
        }
    }

    public function exportarGrupo($id_grupo)
    {
        $grupo = Grupo::findOrFail($id_grupo);
        // Reemplazamos espacios para que el nombre del archivo no de problemas
        $nombreArchivo = 'Calificaciones_' . str_replace(' ', '_', $grupo->nombre) . '.xlsx';

        return Excel::download(new GrupoCalificacionesExport($id_grupo), $nombreArchivo);
    }

    public function indexByGrupo($id_grupo = null)
    {
        $grupos = Grupo::where('id_curso', 2)->get();

        $grupo = null;
        $alumnos = collect();

        if ($id_grupo) {
            $grupo = Grupo::where('id_curso', 2)->find($id_grupo);
            if (!$grupo) {
                $grupo = (object) [
                    'id_grupo' => $id_grupo,
                    'nombre' => 'Grupo ' . $id_grupo . ' (Temporal)',
                    'id_curso' => 2
                ];
            }
            $alumnos = Alumno::where('id_grupo_propedeutico', $id_grupo)
                ->with('resultadosPropedeutico')
                ->paginate(15);
        }

        return view('calificaciones.mostrar', compact('grupos', 'grupo', 'alumnos'));
    }

    public function updateBatch(UpdateCalificacionesBatchRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            foreach ($data['calificaciones'] as $matricula => $scores) {
                $alumno = Alumno::where('matricula', $matricula)->first();

                if ($alumno) {
                    if ($alumno->id_resultados_propedeutico) {
                        ResultadosPropedeutico::where('id_resultados_propedeutico', $alumno->id_resultados_propedeutico)
                            ->update([
                                'examen_inicial' => $scores['examen_inicial'],
                                'examen_final' => $scores['examen_final']
                            ]);
                    } else {
                        $idCursoDefecto = 1;

                        if ($alumno->id_grupo_propedeutico) {
                            $grupo = Grupo::find($alumno->id_grupo_propedeutico);
                            if ($grupo && $grupo->id_curso) {
                                $idCursoDefecto = $grupo->id_curso;
                            }
                        }

                        $nuevasNotas = ResultadosPropedeutico::create([
                            'examen_inicial' => $scores['examen_inicial'],
                            'examen_final' => $scores['examen_final'],
                            'id_curso' => $idCursoDefecto
                        ]);

                        $alumno->update([
                            'id_resultados_propedeutico' => $nuevasNotas->id_resultados_propedeutico
                        ]);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => '¡Cambios guardados con éxito en la base de datos!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Surgió un inconveniente al actualizar las notas: ' . $e->getMessage()
            ], 500);
        }
    }
    public function guardarTabla(Request $request)
    {
        $calificaciones = $request->input('calificaciones', []);

        foreach ($calificaciones as $calif) {
            $alumno = Alumno::where('matricula', $calif['matricula'])->first();

            if ($alumno) {
                if ($alumno->id_resultados_propedeutico) {
                    ResultadosPropedeutico::where('id_resultados_propedeutico', $alumno->id_resultados_propedeutico)
                        ->update([
                            'examen_inicial' => $calif['examen_inicial'],
                            'examen_final' => $calif['examen_final'],
                        ]);
                } else {
                    $nuevasNotas = ResultadosPropedeutico::create([
                        'examen_inicial' => $calif['examen_inicial'],
                        'examen_final' => $calif['examen_final'],
                        'id_curso' => 2
                    ]);

                    $alumno->id_resultados_propedeutico = $nuevasNotas->id_resultados_propedeutico ?? $nuevasNotas->id;
                    $alumno->save();
                }
            }
        }

        return response()->json(['status' => 'success', 'message' => '¡Cambios guardados en la base de datos!']);
    }
}
