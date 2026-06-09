<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuscarAlumnoRequest;
use App\Http\Requests\ImportarAlumnosRequest;
use App\Http\Requests\StoreAlumnoRequest;
use App\Http\Requests\UpdateAlumnoRequest;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Curso;        // ← ESTE IMPORT ES EL QUE FALTABA
use App\Models\Grupo;
use App\Imports\AlumnosImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AlumnoController extends Controller
{
    public function buscar(BuscarAlumnoRequest $request)
    {
        // ← Ahora carga también grupoPropedeutico
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

    public function gruposDisponibles(Request $request)
    {
        $idCarrera = $request->get('id_carrera');

        if (!$idCarrera) {
            return response()->json(['grupos' => []]);
        }

        $carrera = Carrera::find($idCarrera);

        if (!$carrera) {
            return response()->json(['grupos' => []], 404);
        }

        $nombreCarrera  = strtoupper($carrera->nombre_carrera);
        $esIngenieria   = str_contains($nombreCarrera, 'INGENIER');
        $esArquitectura = str_contains($nombreCarrera, 'ARQUITECT');

        // Obtener los IDs dinámicamente (sin hardcodear números)
        $idCursoInduccion    = Curso::where('nombre_curso', 'induccion')->value('id_curso');
        $idCursoPropedeutico = Curso::where('nombre_curso', 'propedeutico')->value('id_curso');

        $grupos = Grupo::with('curso')
            ->where(function ($query) use ($idCursoInduccion, $idCursoPropedeutico, $esIngenieria, $esArquitectura) {

                // Inducción: sin distinción de carrera, se muestran todos
                if ($idCursoInduccion) {
                    $query->where('id_curso', $idCursoInduccion);
                }

                // Propedéutico: filtrado por área según el nombre de la carrera
                if ($idCursoPropedeutico) {
                    $query->orWhere(function ($q2) use ($idCursoPropedeutico, $esIngenieria, $esArquitectura) {
                        $q2->where('id_curso', $idCursoPropedeutico);

                        if ($esIngenieria) {
                            $q2->where('nombre_grupo', 'like', '%Inge%');
                        } elseif ($esArquitectura) {
                            $q2->where('nombre_grupo', 'like', '%Arq%');
                        }
                    });
                }
            })
            ->get();

        return response()->json(['grupos' => $grupos]);
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
        $alumno   = Alumno::with('carrera')->findOrFail($matricula);
        $carreras = Carrera::all();

        return view('alumnos.editinfo', compact('alumno', 'carreras'));
    }

    public function update(UpdateAlumnoRequest $request, $matricula)
    {
        $alumno = Alumno::findOrFail($matricula);
        $alumno->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Información actualizada correctamente',
            'alumno'  => $alumno,
        ], 200);
    }
}