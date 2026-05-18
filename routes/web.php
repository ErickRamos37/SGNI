<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Middleware\ValidarSesionGoogle; // Importa el Middleware


// Rutas del referentes al inicio de sesion
Route::get('/', function () {
    return view('auth.login');
})->name('login');

// Ruta para iniciar el proceso
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
// Ruta de retorno (Callback)
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Ruta para el error de correo no institucional
Route::get('/auth/error', function () {
    return view('auth.error_institucional');
})->name('auth.error');

Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');

// Grupo de Rutas Protegidas (Solo para usuarios logueados)
Route::middleware([ValidarSesionGoogle::class])->group(function () {
    // Ruta para el dashboard
    Route::get('/dashboard', function () {
        return view('dashboard.dashboard');
    })->name('dashboard');

    Route::get('/asistencias/importar', function () {
        return view('attendance.importar_asistencias');
    })->name('asistencias.importar');

    Route::get('/cierre', function () {
        return view('grupos_finales.cierre');
    })->name('cierre');

    // Alta de los usuarios
    Route::get('/usuarios/alta', [UsuarioController::class, 'create'])->name('usuarios.alta_usuarios');

    Route::post('/usuarios/alta', [UsuarioController::class, 'store'])->name('usuarios.store');
    // Tabla de los usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.lista_usuarios');

        Route::get('/usuarios/lista', function () {
        return view('usuarios.lista_usuarios');
    })->name('usuarios.lista');
    
    // Importación de excel para la creación de los grupos
        Route::get('/grupos/importar', function () {
        return view('groups.importar_alumnos');
    })->name('grupos.importar');

    Route::get('/psicologo', function () {
        return view('panel_psicologia.psicologo');
    })->name('psicologo');

    Route::get('/crear_grupo', function () {
        return view('groups.crear_grupos_cursos.crear_grupo');
    })->name('crear_grupo');

    Route::get('/curso_prope', function () {
        return view('groups.crear_grupos_cursos.curso_prope');
    })->name('curso_prope');

    Route::get('/curso_induc', function () {
        return view('groups.crear_grupos_cursos.curso_induc');
    })->name('curso_induc');

    Route::post('/grupos/importar', [AlumnoController::class, 'importar'])->name('alumnos.importar.post');
});
