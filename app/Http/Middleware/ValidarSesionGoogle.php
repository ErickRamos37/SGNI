<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ValidarSesionGoogle
{
    public function handle(Request $request, Closure $next)
    {
        // Revisamos si existe 'usuario_temp' en la sesión
        if (!Session::has('usuario_temp')) {
            // Si no existe, lo enviamos de regreso al login
            return redirect()->route('login');
        }

        // Si existe, lo dejamos pasar a las rutas protegidas
        return $next($request);
    }
}