<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GrupoFinalController extends Controller
{
    /**
     * Muestra la pantalla inicial de criterios (80/20).
     */
    public function configurar()
    {
        return view('grupos_finales.criterios');
    }

    /**
     * Muestra la tabla de grupos finales generados.
     */
    public function gruposFinales()
    {
        // Inicializamos como coleccion vacia para simular que no hay grupos creados aun
        // Esto evita el error de variable no definida y activa el estado vacio en Blade
        $gruposFinales = collect();

        return view('grupos_finales.grupos_finales', compact('gruposFinales'));
    }
}
