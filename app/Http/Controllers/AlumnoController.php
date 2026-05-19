<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuscarAlumnoRequest;
use App\Http\Requests\ImportarAlumnosRequest;
use App\Http\Requests\StoreAlumnoRequest;
use App\Models\Alumno;
use App\Imports\AlumnosImport;
use Maatwebsite\Excel\Facades\Excel;

class AlumnoController extends Controller
{
    public function buscar(BuscarAlumnoRequest $request)
    {
        $alumno = Alumno::with('carrera')->find($request->matricula);

        if (!$alumno) {
            return response()->json(['message' => 'Alumno no encontrado'], 404);
        }

        return response()->json([
            'success' => true,
            'alumno' => $alumno
        ], 200);
    }

    public function importar(ImportarAlumnosRequest $request)
    {
        Excel::import(new AlumnosImport, $request->file('archivo_excel'));

        $request->validate([
            'curso' => 'required|string',
            'archivo_excel' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new AlumnosImport, $request->file('archivo_excel'));
        return redirect()->back()->with('success', '¡La lista de alumnos se procesó y guardó correctamente en la base de datos!');
    }

    public function store(StoreAlumnoRequest $request)
    {
        $datos = $request->validated();
        $datos['correo_institucional'] = $datos['matricula'] . '@uabc.edu.mx';
        $datos['puntaje_ingreso'] = 0;
        $datos['id_resultados_propedeutico'] = 1;

        $alumno = Alumno::create($datos);

        return response()->json([
            'success' => true,
            'message' => 'Alumno registrado correctamente',
            'alumno' => $alumno
        ], 201);
    }
}
