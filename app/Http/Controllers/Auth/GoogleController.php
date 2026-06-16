<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Usuario; 

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Validación de correo institucional
        if (!str_ends_with($googleUser->email, '@uabc.edu.mx')) {
            return redirect()->route('auth.error');
        }

        // Búsqueda del usuario en la BD
        $usuarioDB = Usuario::with('rol')->where('correo_institucional', $googleUser->email)->first();

        if (!$usuarioDB) {
            return redirect()->route('login')->with('error', 'Tu correo no está registrado...');
        }

        // Iniciar sesión en el sistema
        Auth::login($usuarioDB);

        // Redirección por NOMBRE de rol
        $nombreRol = strtolower($usuarioDB->rol->nombre_rol);
        
        switch ($nombreRol) {
            case 'administrador':
            case 'admin':
                return redirect()->route('usuarios.lista_usuarios');

            case 'profesor':
            case 'docente':
                return redirect()->route('asistencia.grupal');

            case 'psicologo':
            case 'psicóloga':
                return redirect()->route('psicologo');

            default:
                return redirect()->route('login')->with('error', 'El rol "' . $nombreRol . '" no tiene una pantalla asignada.');
        }
    }

    // Método para cerrar sesión manualmente
    public function logout()
    {
        // Como estamos usando Auth::login() arriba, lo correcto es usar Auth::logout() para destruir la sesión de Laravel de forma segura.
        Auth::logout(); 
        
        Session::forget('usuario_temp');
        Session::invalidate();
        Session::regenerateToken();
        
        return redirect()->route('login');
    }
}