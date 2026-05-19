<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario; // Importar el modelo Usuario

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

            // 2. Busca el usuario en la base de datos
            $usuario = Usuario::with('rol')->where('correo_institucional', $googleUser->email)->first();

            if (!$usuario) {
                // Si es de la UABC pero NO lo diste de alta en tu tabla, lo rebotamos.
                return redirect()->route('login')->with('error', 'Tu correo no está registrado en el sistema. Contacta al Administrador.');
            }

            // Se usa el login nativo de Laravel
            // Esto autentica al usuario y Laravel ya sabe qué Rol tiene
            Auth::login($usuario);


            // // 2. SIMULAR LOGIN (Guardar en sesión en lugar de DB)
            // // Guardamos un array con la info que necesitamos
            // Session::put('usuario_temp', [
            //     'nombre' => $googleUser->name,
            //     'correo' => $googleUser->email,
            //     'avatar' => $googleUser->avatar,
            //     'is_logged' => true
            // ]);

            // 4. Redirigir al Dashboard
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Error en la conexión.');
        }
    }

    // Método para cerrar sesión manualmente
    public function logout()
    {
        Auth::logout(); // Cierra la sesión nativa
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}