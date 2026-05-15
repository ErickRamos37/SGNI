<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\AlumnosImport;
use Maatwebsite\Excel\Facades\Excel;

class AlumnoController extends Controller
{
    public function importar(Request $request)
    {
        // 1. Se valida por seguridad que el usuario realmente haya enviado lo que se pide
        $request->validate([
            'curso' => 'required|string',
            'archivo_excel' => 'required|mimes:xlsx,xls' // Solo se permiten archivos Excel
        ]);

        // 2. Se extrae el archivo que viene en la petición
        // $archivo = $request->file('archivo_excel');

        // 3. Mostramos los datos recibidos
        // dd([
        //     'Mensaje' => '¡Conexión exitosa!',
        //     'Curso Seleccionado' => $request->curso,
        //     'Nombre del Archivo' => $archivo->getClientOriginalName(),
        //     'Tamaño (Bytes)' => $archivo->getSize(),
        // ]);

        // 2. Se pasa la clase especialista y el archivo subido
        Excel::import(new AlumnosImport, $request->file('archivo_excel'));

        // 3. Redirigir de vuelta a la página con un mensaje de éxito
        return redirect()->back()->with('success', '¡La lista de alumnos se procesó y guardó correctamente en la base de datos!');
    }
}
