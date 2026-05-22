<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session; // Para manejar la sesión manual

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    // 2. Modifica la que te recibe de regreso (donde explotó)
    public function handleGoogleCallback()
    {
        // Le agregamos el stateless() justo antes del user()
        $googleUser = Socialite::driver('google')->stateless()->user();

        if (!str_ends_with($googleUser->email, '@uabc.edu.mx')) {
            return redirect()->route('auth.error');
        }

        $usuarioDB = Usuario::with('rol')->where('correo_institucional', $googleUser->email)->first();

        if (!$usuarioDB) {
            return redirect()->route('login')->with('error', 'Tu correo no está registrado...');
        }

        // --- LA LÍNEA QUE HACE EXPLOTAR TODO ---
        Auth::login($usuarioDB);

        

        $nombreRol = strtolower($usuarioDB->rol->nombre_rol);

        switch ($nombreRol) {
            case 'administrador':
                return redirect()->route('usuarios.lista_usuarios');
            
            case 'profesor':
            case 'docente':
                return redirect()->route('asistencia.grupal');
            
            case 'psicologo':
            case 'psicóloga':
                return redirect()->route('psicologo');
            
            default:
                return redirect()->route('login')->with('error', 'El rol no tiene pantalla asignada.');
        }
    }

    // Método para cerrar sesión manualmente
    public function logout()
    {
        Session::forget('usuario_temp');
        return redirect()->route('login');
    }
}