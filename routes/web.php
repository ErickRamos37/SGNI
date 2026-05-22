<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Middleware\CheckRole; // Importa el Middleware
use App\Http\Controllers\CalificacionController;

// --- Rutas del referentes al inicio de sesion ---
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

// --- Grupo de Rutas Protegidas (Solo para usuarios logueados) ---
Route::middleware(['auth'])->group(function () {
    
    // Rutas EXCLUSIVAS para Administradores
    Route::middleware(['rol:Administrador'])->group(function () {
        // Alta de los usuarios
        Route::get('/usuarios/alta', [UsuarioController::class, 'create'])->name('usuarios.alta_usuarios');

        Route::post('/usuarios/alta', [UsuarioController::class, 'store'])->name('usuarios.store');
        // Tabla de los usuarios
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.lista_usuarios');

        Route::get('/usuarios/lista', function () {
            return view('usuarios.lista_usuarios');
        })->name('usuarios.lista');
    });

    // Si se quiere agregar una ruta para Administrador Y Directivo:
    // Route::middleware(['rol:Administrador,Directivo'])->group(...)

    Route::get('/asistencias/importar', function () {
        return view('attendance.importar_asistencias');
    })->name('asistencias.importar');

    Route::get('/cierre', function () {
        return view('grupos_finales.cierre');
    })->name('cierre');

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

    Route::get('/alumnos/info', function () {
        return view('alumnos.info');
    })->name('alumnos.info');

    Route::post('/alumnos/buscar', [AlumnoController::class, 'buscar'])->name('alumnos.buscar');

    Route::get('/alumnos/nuevo', function () {
        return view('alumnos.nuealum');
    })->name('alumnos.nuevo');

    // 2. Ruta POST para que el JavaScript envíe los datos a la BD
    Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');
    Route::get('/asistencia/paselista', function () {
        return view('asistencia.paselista');
    })->name('asistencia.paselista');

    Route::get('/asistencia/grupal', function () {
        return view('asistencia.grupal');
    })->name('asistencia.grupal');

    Route::get('/cierre', function () {
        return view('grupos_finales.cierre');
    })->name('cierre');
    Route::get('/usuarios/alta', [UsuarioController::class, 'create'])->name('usuarios.alta_usuarios');
    Route::post('/usuarios/alta', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.lista_usuarios');

    Route::prefix('calificaciones')->name('calificaciones.')->group(function () {

        Route::get('/captura', [CalificacionController::class, 'showCaptura'])->name('captura');
        Route::post('/upload', [CalificacionController::class, 'upload'])->name('upload');
        Route::get('/mostrar/{id_grupo?}', [CalificacionController::class, 'indexByGrupo'])->name('mostrar');
        Route::post('/update-batch', [CalificacionController::class, 'updateBatch'])->name('updateBatch');
    });

    Route::post('/calificaciones/guardar-tabla-directo', [CalificacionController::class, 'guardarTabla'])->name('calificaciones.guardarTablaDirecto');
    Route::get('/calificaciones/exportar/{id_grupo}', [CalificacionController::class, 'exportarGrupo'])->name('calificaciones.exportar');


    Route::get('/grupos/generados', function () {
        return view('groups.grupos_generados');
    })->name('grupos.generados');

    Route::get('/grupos/prope-creado', function () {
        return view('groups.crear_grupos_cursos.curso_prope_creado');
    })->name('curso_prope_creado');

    Route::get('/grupos/induc-creado', function () {
        return view('groups.crear_grupos_cursos.curso_induc_creado');
    })->name('curso_induc_creado');

    Route::get('/grupos/ver-lista', function () {
        return view('groups.crear_grupos_cursos.lista_grupo'); // Asegúrate de poner la ruta correcta a tu nuevo archivo
    })->name('lista_grupo');

});
