<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;

class SeguimientoController extends Controller
{
    public function index()
{
    // Traemos absolutamente todos los alumnos con todas sus relaciones
    $alumnos = Alumno::with([
        'asistencias',
        'seguimientoAcademico.situacionAlum',
        'resultadosPropedeutico'
    ])->get();

    // Enviamos una sola lista unificada a la vista
    return view('panel_psicologia.psicologo', compact('alumnos'));
}
}