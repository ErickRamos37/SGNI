<?php

namespace App\Http\Controllers\Auth;

//use App\Models\Usuario;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario; // Importar el modelo Usuario
use Illuminate\Support\Facades\Session;

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
                return redirect()->route('auth.error');
            }

            // 2. Buscar al usuario y traer también su ROL (Eager Loading)
            $usuarioDB = Usuario::with('rol')->where('correo_institucional', $googleUser->email)->first();

            // Si no existe en la BD
            if (!$usuarioDB) {
                return redirect()->route('login')->with('error', 'Tu correo no está registrado en el sistema. Contacta al administrador.');
            }

            // 3. Extraer el nombre del rol (¡OJO! Asegúrate de que la columna se llame así en tu BD)
            // A veces le ponen 'nombre', 'nombre_rol', o 'descripcion'. Yo usaré 'nombre_rol' como ejemplo.
            $nombreRol = strtolower($usuarioDB->rol->nombre_rol);

            // 4. Guardar en sesión
            Session::put('usuario_temp', [
                'nombre' => $googleUser->name,
                'correo' => $googleUser->email,
                'avatar' => $googleUser->avatar,
                'rol' => $nombreRol, // Ahora guardamos el nombre para que lo puedas usar en las vistas
                'num_empleado' => $usuarioDB->num_empleado,
                'is_logged' => true
            ]);

            // 5. Redirección por NOMBRE de rol
            switch ($nombreRol) {
                case 'administrador':
                case 'admin':
                    return redirect()->route('usuarios.lista_usuarios');

                case 'profesor':
                case 'docente':
                    // Aquí lo mandas a tus vistas de asistencia que armaste
                    return redirect()->route('asistencia.grupal');

                case 'psicologo':
                case 'psicóloga':
                    return redirect()->route('psicologo');

                default:
                    // Si le pusieron un rol raro como "invitado"
                    return redirect()->route('login')->with('error', 'El rol "' . $nombreRol . '" no tiene una pantalla asignada.');
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
            return redirect()->route('grupos.importar');

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
