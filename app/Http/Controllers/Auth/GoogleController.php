<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session; // Para manejar la sesión manual

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // 1. Validar si el correo es institucional
            if (!str_ends_with($googleUser->email, '@uabc.edu.mx')) {
                // Si no es UABC, lo mandamos a una ruta de error
                return redirect()->route('auth.error');
            }

            // 2. SIMULAR LOGIN (Guardar en sesión en lugar de DB)
            // Guardamos un array con la info que necesitamos
            Session::put('usuario_temp', [
                'nombre' => $googleUser->name,
                'correo' => $googleUser->email,
                'avatar' => $googleUser->avatar,
                'is_logged' => true
            ]);

            // 3. Redirigir al Dashboard
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Error en la conexión.');
        }
    }

    // Método para cerrar sesión manualmente
    public function logout()
    {
        Session::forget('usuario_temp');
        return redirect()->route('login');
    }
}