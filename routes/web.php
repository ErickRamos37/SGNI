<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Middleware\CheckRole; // Importa el Middleware
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\GrupoController;
use App\Models\Grupo;
//use App\Http\Middleware\ValidarSesionGoogle;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\CierreController;
use App\Http\Controllers\SeguimientoController;

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
        // Rutas index, create, store, edit, update, show y destroy del Usuario
        Route::resource('usuarios', UsuarioController::class)->except(['show']);
    });


    Route::get('/asistencias/importar', function () {
        return view('attendance.importar_asistencias');
    })->name('asistencias.importar');
    // RUTAS DE ASISTENCIA
    Route::post('/asistencias/procesar', [AsistenciaController::class, 'procesar'])->name('asistencias.procesar');
    Route::post('/asistencias/guardar-masivo', [AsistenciaController::class, 'guardarMasivo'])->name('asistencias.guardarMasivo');

    Route::get('/cierre', function () {
        return view('grupos_finales.cierre');
    })->name('cierre');

    // Importación de excel para la creación de los grupos
    Route::get('/grupos/importar', function () {
        return view('groups.importar_alumnos');
    })->name('grupos.importar');

    Route::post('/grupos/crear', [GrupoController::class, 'store'])->name('grupos.store');

    Route::get('/psicologo', [SeguimientoController::class, 'index'])->name('psicologo');



    Route::get('/curso_prope', [GrupoController::class, 'showCursoPrope'])->name('curso_prope');
    Route::post('/grupos/guardar-profesores', [GrupoController::class, 'guardarProfesores'])->name('grupos.guardar_profesores');

    Route::get('/curso_induc', [GrupoController::class, 'showCursoInduc'])->name('curso_induc');
    Route::post('/grupos-induc/store', [GrupoController::class, 'storeInduc'])->name('grupos_induc.store');

    Route::get('/grupos/induc-creado', [GrupoController::class, 'showInducCreado'])->name('curso_induc_creado');

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
    Route::get('/asistencia/paselista', [AsistenciaController::class, 'paselista'])->name('asistencia.paselista');

    Route::get('/asistencia/grupal', [AsistenciaController::class, 'grupal'])->name('asistencia.grupal');

    Route::prefix('calificaciones')->name('calificaciones.')->group(function () {

        Route::get('/captura', [CalificacionController::class, 'showCaptura'])->name('captura');
        Route::post('/upload', [CalificacionController::class, 'upload'])->name('upload');
        Route::get('/mostrar/{id_grupo?}', [CalificacionController::class, 'indexByGrupo'])->name('mostrar');
        Route::post('/update-batch', [CalificacionController::class, 'updateBatch'])->name('updateBatch');
    });

    // Rutas para las vistas relacionadas con la catura y muestra de calificaciones de los examenes propedeuticos.
    Route::post('/calificaciones/guardar-tabla-directo', [CalificacionController::class, 'guardarTabla'])->name('calificaciones.guardarTablaDirecto');
    Route::get('/calificaciones/exportar/{id_grupo}', [CalificacionController::class, 'exportarGrupo'])->name('calificaciones.exportar');
    Route::get('/calificaciones/descargar-formato-base', [CalificacionController::class, 'descargarFormatoBase'])->name('calificaciones.descargarFormatoBase');
    Route::get('/calificaciones/data/{id_grupo}', [CalificacionController::class, 'getAlumnosData'])->name('calificaciones.data');

    Route::get('/grupos/generados', function () {
        return view('groups.grupos_generados');
    })->name('grupos.generados');

    Route::get('/grupos/{id_grupo}/descargar-lista', [GrupoController::class, 'descargarLista'])->name('grupos.descargar_lista');

    Route::get('/grupos/prope-creado', [GrupoController::class, 'showPropeCreado'])->name('curso_prope_creado');

    Route::get('/grupos/{id_grupo}/ver-lista', [GrupoController::class, 'showListaGrupo'])->name('lista_grupo');

    // Rutas para las vistas relacionadas a los grupos de primer semestre
    Route::get('/grupos_finales/criterios', [App\Http\Controllers\GrupoFinalController::class, 'configurar'])->name('grupos_finales.criterios');

    // Ruta para visualizar la tabla de grupos generados - Conectada al Controlador
    Route::get('/grupos_finales/grupos_finales', [App\Http\Controllers\GrupoFinalController::class, 'gruposFinales'])->name('grupos_finales.grupos_finales');

    // Ruta para el modo lectura
    Route::get('/grupos_finales/modo_lectura', function () {
        return view('grupos_finales.modo_lectura');
    })->name('grupos_finales.modo_lectura');
});
