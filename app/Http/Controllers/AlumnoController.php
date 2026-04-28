<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    /**
     * Muestra tu vista personalizada de alumnos.
     */
    public function create()
    {
        // Laravel buscará el archivo resources/views/vista_alumno.blade.php
        return view('vista_alumno');
    }

    /**
     * Este método recibirá los datos cuando presiones el botón de "Crear"
     */
    public function store(Request $request)
    {
        // Por ahora solo para probar que los datos llegan:
        return $request->all();
    }
}