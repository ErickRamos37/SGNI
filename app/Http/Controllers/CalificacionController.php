<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadCalificacionesRequest;
use App\Http\Requests\UploadCalificacionesBatchRequest;
use App\Imports\CalificacionesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;
use App\Models\Grupo;
use App\Models\Alumno;
use App\Models\ResultadosPropedeutico;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Exports\GrupoCalificacionesExport;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CalificacionController extends Controller
{
    // Muestra la vista principal de captura de calificaciones
    public function showCaptura()
    {
        return view('calificaciones.captura');
    }

    // Descarga el archivo Excel de plantilla desde la carpeta publica
    public function descargarFormatoBase()
    {
        $rutaArchivo = public_path('formatos/calificaciones_formato.xlsx');
        if (!File::exists($rutaArchivo)) {
            return redirect()->back()->withErrors([
                'archivo_excel' => 'Error del sistema: El archivo de formato base no se encuentra en la carpeta public/formatos/.'
            ]);
        }
        return response()->download($rutaArchivo, 'calificaciones_formato.xlsx');
    }

    // Procesa e importa las calificaciones masivas desde un archivo Excel cargado
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

    // Exporta las calificaciones actuales de un grupo a un archivo Excel numerado
    public function exportarGrupo($id_grupo)
    {
        $grupo = Grupo::findOrFail($id_grupo);
        $nombreGrupo = $grupo->nombre_grupo ?? $grupo->nombre;
        $nombreArchivo = 'lista_calificaciones' . str_replace(' ', '_', $nombreGrupo) . '.xlsx';

        // Verificamos la existencia de la plantilla oficial en la carpeta public
        $rutaTemplate = public_path('formatos/calificaciones_formato.xlsx');
        if (!file_exists($rutaTemplate)) {
            return redirect()->back()->withErrors([
                'archivo_excel' => 'Error del sistema: El archivo base FormatoCalificaciones.xlsx no se encuentra en public/formatos/.'
            ]);
        }

        // Creamos una respuesta de descarga fluida nativa de Laravel
        $response = new StreamedResponse(function () use ($rutaTemplate, $id_grupo) {
            // Cargamos la plantilla directamente con el lector original de PhpSpreadsheet
            $spreadsheet = IOFactory::load($rutaTemplate);
            $sheet = $spreadsheet->getActiveSheet();

            // Consultamos los alumnos pertenecientes al grupo
            $alumnos = Alumno::where('id_grupo_propedeutico', $id_grupo)
                ->with('resultadosPropedeutico')
                ->get();

            // Con base en la imagen, los renglones de los alumnos inician en la fila 8
            $filaActual = 8;

            foreach ($alumnos as $alumno) {
                // Insertamos los datos en las columnas independientes calculadas de la imagen
                $sheet->setCellValue('B' . $filaActual, $alumno->nombre);
                $sheet->setCellValue('C' . $filaActual, $alumno->ap_pat);
                $sheet->setCellValue('D' . $filaActual, $alumno->ap_mat);
                $sheet->setCellValue('E' . $filaActual, $alumno->matricula);

                // Si el alumno cuenta con calificaciones, las inyectamos en las columnas H e I
                if ($alumno->resultadosPropedeutico) {
                    $sheet->setCellValue('H' . $filaActual, $alumno->resultadosPropedeutico->examen_inicial);
                    $sheet->setCellValue('I' . $filaActual, $alumno->resultadosPropedeutico->examen_final);
                }

                $filaActual++;
            }

            // Escribimos el archivo directamente en la salida del navegador
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        });

        // Configuramos las cabeceras HTTP necesarias para forzar la descarga del Excel
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $nombreArchivo . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    // Inicializa la vista de calificaciones y filtra los grupos semanticamente por su nombre
    public function indexByGrupo($id_grupo = null)
    {
        // Se buscan grupos cuyo nombre contenga "prope"
        $grupos = Grupo::where('nombre_grupo', 'LIKE', '%prope%')->get();
        $grupo = null;
        $alumnos = collect(); // Coleccion vacia; la carga de alumnos ahora se delega a DataTables por AJAX

        if ($id_grupo) {
            $grupo = Grupo::where('nombre_grupo', 'LIKE', '%prope%')->find($id_grupo);

            if (!$grupo) {
                $grupo = (object) [
                    'id_grupo' => $id_grupo,
                    'nombre_grupo' => 'Grupo ' . $id_grupo . ' (Temporal)',
                    'id_curso' => 2
                ];
            }
        }

        return view('calificaciones.mostrar', compact('grupos', 'grupo', 'alumnos'));
    }

    // Procesa y retorna los datos de los alumnos en formato JSON estructurado para Server-Side DataTables
    public function getAlumnosData(Request $request, $id_grupo)
    {
        if ($request->ajax()) {
            $alumnos = Alumno::where('id_grupo_propedeutico', $id_grupo)
                ->with('resultadosPropedeutico')
                ->select('alumno.*'); // Se usa el nombre de la tabla en singular

            return DataTables::eloquent($alumnos)
                // Retorna el nombre completo concatenado
                ->addColumn('nombre_completo', function ($alumno) {
                    return trim("{$alumno->nombre} {$alumno->ap_pat} {$alumno->ap_mat}");
                })
                // Construye el input HTML dinamico para el examen inicial
                ->addColumn('input_inicial', function ($alumno) {
                    $nota = $alumno->resultadosPropedeutico->examen_inicial ?? null;
                    $colorClass = (!is_null($nota) && $nota < 70) ? 'text-danger border-danger' : 'text-dark border-light bg-light';

                    return '<div class="col-9 col-md-7 mx-auto">
                                <input type="number"
                                    class="form-control text-center fw-bold rounded-3 shadow-sm input-score ' . $colorClass . '"
                                    data-field="examen_inicial" min="0" max="100"
                                    value="' . $nota . '" placeholder="-">
                            </div>';
                })
                // Construye el input HTML dinamico para el examen final
                ->addColumn('input_final', function ($alumno) {
                    $nota = $alumno->resultadosPropedeutico->examen_final ?? null;
                    $colorClass = (!is_null($nota) && $nota < 70) ? 'text-danger border-danger' : 'text-dark border-light bg-light';

                    return '<div class="col-9 col-md-7 mx-auto">
                                <input type="number"
                                    class="form-control text-center fw-bold rounded-3 shadow-sm input-score ' . $colorClass . '"
                                    data-field="examen_final" min="0" max="100"
                                    value="' . $nota . '" placeholder="-">
                            </div>';
                })
                // Agrega metadatos necesarios a las filas <tr> generadas por DataTables
                ->setRowAttr([
                    'data-matricula' => function ($alumno) {
                        return $alumno->matricula;
                    },
                    'class' => 'border-bottom border-light student-row'
                ])
                // Indica que las columnas de los inputs contienen codigo HTML valido
                ->rawColumns(['input_inicial', 'input_final'])
                ->make(true);
        }
    }

    // Guarda o actualiza en lote las calificaciones enviadas desde la pagina visible de DataTables
    public function updateBatch(UploadCalificacionesBatchRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            foreach ($data['calificaciones'] as $matricula => $scores) {
                $alumno = Alumno::where('matricula', $matricula)->first();

                if ($alumno) {
                    // Si ya tiene registro de calificaciones, actualiza los valores
                    if ($alumno->id_resultados_propedeutico) {
                        ResultadosPropedeutico::where('id_resultados_propedeutico', $alumno->id_resultados_propedeutico)
                            ->update([
                                'examen_inicial' => $scores['examen_inicial'],
                                'examen_final' => $scores['examen_final']
                            ]);
                    } else {
                        // Si es un registro nuevo, busca el curso y genera las notas base
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

    // Metodo alternativo de guardado manual para procesamiento por Request estandar
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
