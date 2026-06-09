<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuscarAlumnoRequest;
use App\Http\Requests\ImportarAlumnosRequest;
use App\Http\Requests\StoreAlumnoRequest;
use App\Http\Requests\UpdateAlumnoRequest;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Curso;
use App\Models\Grupo; // Importación correcta (Aquí arriba)
use App\Imports\AlumnosImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AlumnoController extends Controller
{
    public function buscar(BuscarAlumnoRequest $request)
    {
        $alumno = Alumno::with(['carrera', 'grupoInduccion', 'grupoPropedeutico'])
                        ->find($request->matricula);

        if (!$alumno) {
            return response()->json(['message' => 'Alumno no encontrado'], 404);
        }

        return response()->json([
            'success' => true,
            'alumno'  => $alumno,
        ], 200);
    }

    /**
     * Obtiene todos los grupos en modo editable (sin filtrar por carrera)
     * Mantiene el parámetro $id_carrera para no romper la ruta de web.php ni el Fetch
     */
    public function gruposPorCarrera($id_carrera)
    {
        // 1. Buscamos directamente el id_estado = 1 (Editable) basándonos en tu BD
        $gruposEditables = Grupo::with('curso')->where('id_estado', 1)->get();

        $propedeuticos = [];
        $inducciones = [];

        foreach ($gruposEditables as $grupo) {
            // Verificamos si tiene un curso asociado
            if ($grupo->curso) {
                $nombreCurso = strtolower($grupo->curso->nombre_curso);
                
                // Si el nombre del curso tiene la palabra "induc", va a inducción
                if (str_contains($nombreCurso, 'induc')) {
                    $inducciones[] = $grupo;
                } else {
                    // Cualquier otro curso (tenga o no "prope" en el nombre) se asume propedéutico
                    $propedeuticos[] = $grupo;
                }
            } else {
                // Si por algún motivo el grupo no tiene curso o la relación falla, 
                // lo mostramos de todos modos en propedéutico para que no se oculte.
                $propedeuticos[] = $grupo;
            }
        }

        // 3. Retornamos la respuesta JSON a tu vista
        return response()->json([
            'propedeutico' => $propedeuticos,
            'induccion'    => $inducciones
        ]);
    }

    public function importar(ImportarAlumnosRequest $request)
    {
        $request->validate([
            'curso'         => 'required|string',
            'archivo_excel' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new AlumnosImport, $request->file('archivo_excel'));

        return redirect()->back()->with('success', '¡La lista de alumnos se procesó y guardó correctamente!');
    }

    public function create()
    {
        $carreras = Carrera::all();
        return view('alumnos.nuealum', compact('carreras'));
    }

    public function store(StoreAlumnoRequest $request)
    {
        $datos = $request->validated();

        $datos['correo_institucional']       = $datos['matricula'] . '@uabc.edu.mx';
        $datos['id_resultados_propedeutico'] = null;

        $alumno = Alumno::create($datos);

        return response()->json([
            'success' => true,
            'message' => 'Alumno registrado correctamente',
            'alumno'  => $alumno,
        ], 201);
    }

    public function edit($matricula)
{
    $alumno = Alumno::where('matricula', $matricula)->firstOrFail();
    $carreras = Carrera::all();
    
    $grupos = Grupo::all(); 

    return view('alumnos.editinfo', compact('alumno', 'carreras', 'grupos'));
}

    public function update(UpdateAlumnoRequest $request, $matricula)
{
    $alumno = Alumno::findOrFail($matricula);
    $datos = $request->validated();

    // 1. Extraemos el ID del grupo que viene del select único
    $id_grupo_seleccionado = $datos['id_grupo_definitivo'] ?? null;

    if ($id_grupo_seleccionado) {
        // 2. Buscamos el grupo y su curso para saber qué tipo de grupo es
        $grupo = Grupo::with('curso')->find($id_grupo_seleccionado);
        $nombreCurso = $grupo && $grupo->curso ? strtolower($grupo->curso->nombre_curso) : '';

        if (str_contains($nombreCurso, 'induc')) {
            // Es un grupo de Inducción
            $datos['id_grupo_induccion'] = $id_grupo_seleccionado;
            $datos['id_grupo_propedeutico'] = null; // Limpiamos el otro para que no esté en dos a la vez
        } else {
            // Es un grupo Propedéutico
            $datos['id_grupo_propedeutico'] = $id_grupo_seleccionado;
            $datos['id_grupo_induccion'] = null; // Limpiamos el otro
        }
    } else {
        // Si seleccionó "-- Sin Grupo --", limpiamos ambos campos
        $datos['id_grupo_induccion'] = null;
        $datos['id_grupo_propedeutico'] = null;
    }

    // 3. Eliminamos del arreglo el campo ficticio para que Laravel no intente buscarlo en la BD
    unset($datos['id_grupo_definitivo']);

    // 4. Actualizamos el alumno con las columnas reales de tu tabla
    $alumno->update($datos);

    return response()->json([
        'success' => true,
        'message' => 'Información actualizada correctamente',
        'alumno'  => $alumno->load(['grupoInduccion', 'grupoPropedeutico']),
    ], 200);
 }
}
