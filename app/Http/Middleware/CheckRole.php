<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Verifica si el usuario está logueado
        if (!Auth::check()) {
            // TRAMPA 2: Si Laravel pierde tu sesión, va a imprimir esto
            dd('¡El cadenero te pateó! Auth::check() está diciendo que no tienes sesión.');
            // return redirect()->route('login');
        }

        // 2. Obtener el nombre del rol del usuario
        $userRole = Auth::user()->rol->nombre_rol;

        // 3. Verifica si el rol del usuario está dentro de los roles permitidos
        if (!in_array($userRole, $roles)) {
            abort(403, 'Acceso denegado. No tienes permisos para ver esta sección.');
        }

        return $next($request);
    }
}