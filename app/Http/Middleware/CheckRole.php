<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Los ...$roles permiten recibir múltiples roles separados por coma en web.php
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Verifica si el usuario está logueado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Obtener el nombre del rol del usuario (gracias a la relación en el modelo)
        $userRole = Auth::user()->rol->nombre_rol;

        // 3. Verifica si el rol del usuario está dentro de los roles permitidos
        if (!in_array($userRole, $roles)) {
            // Si no tiene permiso, lanzamos un error 403 (Prohibido)
            abort(403, 'Acceso denegado. No tienes permisos para ver esta sección.');
        }

        return $next($request);
    }
}
