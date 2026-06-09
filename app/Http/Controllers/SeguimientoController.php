<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use App\Models\Grupo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeguimientoController extends Controller
{
    public function index()
    {
        // 1. Cargamos los grupos con su relación específica de Propedéutico
        $gruposPropedeutico = Grupo::where('id_curso', 1)
            ->with([
                'alumnosPropedeutico.asistencias',
                'alumnosPropedeutico.seguimientoAcademico.situacionAlum',
                'alumnosPropedeutico.resultadosPropedeutico'
            ])->get();

        // 2. Buscamos por id_curso = 2 (Inducción)
        $gruposInduccion = Grupo::where('id_curso', 2)
            ->with([
                'alumnosInduccion.asistencias',
                'alumnosInduccion.seguimientoAcademico.situacionAlum'
            ])->get();

        // 3. Enviamos las colecciones a la vista
        return view('panel_psicologia.psicologo', compact('gruposPropedeutico', 'gruposInduccion'));
    }


    public function getDatosSeguimiento(Request $request)
    {
        $usuario = Auth::user();
        $tipoCurso = $request->query('curso'); // 'induccion' o 'propedeutico'

        $query = DB::table('alumno')
            ->select(
                'alumno.matricula',
                DB::raw("CONCAT(alumno.nombre, ' ', alumno.ap_pat, ' ', alumno.ap_mat) as nombre_completo"),
                'alumno.correo_institucional',
                'alumno.telefono',
                'carrera.nombre_carrera as nombre_carrera',
                'alumno.puntaje_ingreso',
                'alumno.id_grupo_propedeutico',
                'alumno.id_grupo_induccion'
            )
            ->leftJoin('carrera', 'alumno.id_carrera', '=', 'carrera.id_carrera');

        // LÓGICA EXCLUSIVA PARA PROPEDÉUTICO (Trae exámenes)
        if ($tipoCurso == 'propedeutico') {
            $query->leftJoin('grupos as grupo_prope', 'alumno.id_grupo_propedeutico', '=', 'grupo_prope.id_grupo')
                ->leftJoin('resultados_propedeutico as rp', 'alumno.id_resultados_propedeutico', '=', 'rp.id_resultados_propedeutico')
                ->addSelect('grupo_prope.nombre_grupo as nombre_grupo_prope', 'rp.examen_inicial', 'rp.examen_final')
                ->whereNotNull('alumno.id_grupo_propedeutico');
        }
        // LÓGICA EXCLUSIVA PARA INDUCCIÓN (Sin exámenes)
        elseif ($tipoCurso == 'induccion') {
            $query->leftJoin('grupos as grupo_induc', 'alumno.id_grupo_induccion', '=', 'grupo_induc.id_grupo')
                ->addSelect('grupo_induc.nombre_grupo as nombre_grupo_induc')
                ->whereNotNull('alumno.id_grupo_induccion');
        }

        $alumnos = $query->get();
        $matriculas = $alumnos->pluck('matricula');

        // Traemos las asistencias PLANAS
        $asistenciasBD = DB::table('asistencias')
            ->whereIn('matricula', $matriculas)
            ->get();

        $data = $alumnos->map(function ($alumno) use ($asistenciasBD, $tipoCurso) {

            $idGrupoActual = ($tipoCurso == 'propedeutico')
                ? $alumno->id_grupo_propedeutico
                : $alumno->id_grupo_induccion;

            $asistenciasAlumno = $asistenciasBD->filter(function ($item) use ($alumno, $idGrupoActual) {
                return trim($item->matricula) === trim($alumno->matricula)
                    && (int) $item->id_grupo === (int) $idGrupoActual;
            });

            $totalClases = $asistenciasAlumno->count();

            // Filtramos las que tienen 'asistio' en 1
            $clasesAsistidas = $asistenciasAlumno->filter(function ($item) {
                return (int) $item->asistio === 1;
            })->count();

            $porcentajeAsistencia = $totalClases > 0 ? round(($clasesAsistidas / $totalClases) * 100) : 0;

            if ($totalClases == 0) {
                // Gris claro opaco con letras negras
                $riesgoHtml = '<span class="badge rounded-pill text-dark px-3 py-2 text-uppercase fw-bold" style="background-color: #E5E7E9; border: 1px solid #BDC3C7;">Sin Registro</span>';
            } elseif ($porcentajeAsistencia < 60) {
                // Rojo/Rosa opaco elegante (tipo salmón oscuro/ladrillo suave) con letras negras
                $riesgoHtml = '<span class="badge rounded-pill text-dark px-3 py-2 text-uppercase fw-bold" style="background-color: #F5B7B1;">Riesgo Alto</span>';
            } elseif ($porcentajeAsistencia <= 80) {
                // Amarillo mostaza/ocre suave con letras negras
                $riesgoHtml = '<span class="badge rounded-pill text-dark px-3 py-2 text-uppercase fw-bold" style="background-color: #F9E79F;">Riesgo Medio</span>';
            } else {
                // Verde menta pastel elegante con letras negras para combinar con el resto
                $riesgoHtml = '<span class="badge rounded-pill text-dark px-3 py-2 text-uppercase fw-bold" style="background-color: #A9DFBF;">Regular</span>';
            }

            // Datos base compartidos por ambas tablas
            $resultado = [
                'matricula' => $alumno->matricula ?? 'N/A',
                'nombre_completo' => $alumno->nombre_completo ?? 'Sin Nombre',
                'carrera' => $alumno->nombre_carrera ?? 'Sin Carrera',
                'contacto' => '<small>' . ($alumno->telefono ?? 'Sin teléfono') . '<br>' . ($alumno->correo_institucional ?? 'Sin correo') . '</small>',
                'puntaje_ingreso' => $alumno->puntaje_ingreso ?? 0,
                'asistencias' => $porcentajeAsistencia . '%',
                'riesgo' => $riesgoHtml
            ];

            // Datos que solo se agregan dependiendo del curso
            if ($tipoCurso == 'propedeutico') {
                $resultado['grupo'] = '<strong>' . ($alumno->nombre_grupo_prope ?? 'Sin grupo') . '</strong>';
                $resultado['examen_inicial'] = $alumno->examen_inicial ?? 0;
                $resultado['examen_final'] = $alumno->examen_final ?? 0;

                $promedio = 0;
                if (is_numeric($alumno->examen_inicial) && is_numeric($alumno->examen_final)) {
                    $promedio = ($alumno->examen_inicial + $alumno->examen_final) / 2;
                }
                $resultado['promedio'] = $promedio;
            } else {
                $resultado['grupo'] = '<strong>' . ($alumno->nombre_grupo_induc ?? 'Sin grupo') . '</strong>';
            }

            return $resultado;
        });

        return response()->json(['data' => $data]);
    }
}