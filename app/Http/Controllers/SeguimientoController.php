<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use App\Models\Grupo;

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
}