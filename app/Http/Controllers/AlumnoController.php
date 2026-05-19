<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\AlumnosImport;
use Maatwebsite\Excel\Facades\Excel;

class AlumnoController extends Controller
{
    public function importar(Request $request)
    {
        $request->validate([
            'curso' => 'required|string',
            'archivo_excel' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new AlumnosImport, $request->file('archivo_excel'));
        return redirect()->back()->with('success', '¡La lista de alumnos se procesó y guardó correctamente en la base de datos!');
    }
}
