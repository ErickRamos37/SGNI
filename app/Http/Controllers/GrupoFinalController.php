<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Grupo;
use App\Models\Alumno;

class GrupoFinalController extends Controller
{
    public function index()
    {
        return view('admin.grupos_final.criterios');
    }

    public function generar(Request $request)
    {
        // =======================================================
        // 1. VALIDACIÓN DE LOS DATOS DEL FORMULARIO
        // =======================================================
        $request->validate([
            'criterios' => 'required|array|min:2',
            'criterios.0.valor' => 'required|integer|min:1|max:99',
            'criterios.1.valor' => 'required|integer|min:1|max:99',
        ]);

        $porcentajeAltos = (int) $request->input('criterios.0.valor');
        $porcentajeBajos = (int) $request->input('criterios.1.valor');

        // Doble validación de seguridad por si vulneran el frontend
        if (($porcentajeAltos + $porcentajeBajos) !== 100) {
            return redirect()->back()->with('error_grupos_existentes', 'La suma de los porcentajes debe ser exactamente 100%.');
        }

        // =======================================================
        // 2. CANDADO DE SEGURIDAD (¿Ya se asignaron grupos finales?)
        // =======================================================
        // Verificamos si ya hay alumnos que tengan asignado un grupo final (ajusta 'id_grupo_final' a tu columna real)
        $asignacionPrevia = Alumno::whereNotNull('id_grupo_definitivo')->exists();

        if ($asignacionPrevia) {
            return redirect()->back()->with('error_grupos_existentes', 'Los grupos finales ya fueron generados anteriormente. No se puede repetir este proceso.');
        }

        // Traemos los grupos finales disponibles en la BD (Ajusta la consulta según cómo identifiques tus grupos finales)
        $gruposFinales = Grupo::where('nombre_grupo', 'LIKE', '%Final%')->get();

        if ($gruposFinales->isEmpty()) {
            return redirect()->back()->with('error_grupos_existentes', 'No hay grupos finales creados en el sistema. Créelos antes de ejecutar la asignación.');
        }

        $numGrupos = $gruposFinales->count();

        // =======================================================
        // 3. OBTENER Y EVALUAR ALUMNOS
        // =======================================================
        // Traemos a los alumnos (puedes agregar los "with" si necesitas cargar relaciones de calificaciones)
        $alumnos = Alumno::all();

        if ($alumnos->isEmpty()) {
            return redirect()->back()->with('error_grupos_existentes', 'No hay alumnos registrados para asignar.');
        }

        // Calculamos el promedio o puntaje de cada alumno
        foreach ($alumnos as $alumno) {
            /* * AQUÍ DEFINES CÓMO SE CALCULA EL PROMEDIO. 
             * Ejemplo: $promedio = ($alumno->calificacion_examen + $alumno->puntaje_admision) / 2;
             * Por ahora, usaré un campo ficticio o 0 si no existe.
             */
            $promedio = $alumno->puntaje_admision ?? rand(50, 100); // <-- AJUSTA ESTA LÓGICA A TUS COLUMNAS REALES
            $alumno->score_algoritmo = $promedio;
        }

        // =======================================================
        // 4. ORDENAR Y DIVIDIR EN BLOQUES (ALTOS Y BAJOS)
        // =======================================================
        // Ordenamos de mayor a menor calificación
        $alumnosOrdenados = $alumnos->sortByDesc('score_algoritmo')->values();

        $totalAlumnos = $alumnosOrdenados->count();
        $cantidadAltos = (int) round($totalAlumnos * ($porcentajeAltos / 100));

        // Separamos las dos listas
        $poolAltos = $alumnosOrdenados->take($cantidadAltos)->values();
        $poolBajos = $alumnosOrdenados->slice($cantidadAltos)->values();

        // =======================================================
        // 5. DISTRIBUCIÓN EQUITATIVA (TRANSACCIÓN SEGURA)
        // =======================================================
        try {
            DB::beginTransaction();

            $alumnosAsignados = 0;

            // Repartimos el pool de Promedios Altos como si fueran cartas (Round-Robin)
            // Esto garantiza que el alumno #1 vaya al Grupo A, el #2 al Grupo B... manteniendo los grupos balanceados
            foreach ($poolAltos as $index => $alumno) {
                $indiceGrupo = $index % $numGrupos; 
                $grupoDestino = $gruposFinales[$indiceGrupo];

                $alumno->id_grupo_definitivo = $grupoDestino->id_grupo; // <-- Ajusta al nombre de tu columna
                $alumno->save();
                $alumnosAsignados++;
            }

            // Repartimos el pool de Promedios Bajos de la misma manera
            foreach ($poolBajos as $index => $alumno) {
                $indiceGrupo = $index % $numGrupos; 
                $grupoDestino = $gruposFinales[$indiceGrupo];

                $alumno->id_grupo_definitivo = $grupoDestino->id_grupo; // <-- Ajusta al nombre de tu columna
                $alumno->save();
                $alumnosAsignados++;
            }

            DB::commit();

            // Retornamos usando la variable de sesión 'import_stats' para que tu SweetAlert lo detecte automáticamente
            return redirect()->back()->with('import_stats', [
                'nuevos' => $alumnosAsignados, // Reutilizamos esta variable para mostrar total de asignados
                'repetidos' => 0 
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error_grupos_existentes', 'Error interno al generar los grupos: ' . $e->getMessage());
        }
    }

    public function gruposFinales()
{
    $grupos = Grupo::withCount('alumnosDefinitivos')
        ->with('alumnosDefinitivos')
        ->orderBy('nombre_grupo')
        ->get();

    return view('admin.grupos_final.grupos_finales', compact('grupos'));
}

}