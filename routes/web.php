<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\CierreController;
use App\Http\Controllers\SeguimientoController;

// ==========================================================
// 1. RUTAS PÚBLICAS Y DE AUTENTICACIÓN
// ==========================================================
Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('/auth/error', function () {
    return view('auth.error_institucional');
})->name('auth.error');
Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');


// ==========================================================
// 2. RUTAS PROTEGIDAS (Requieren sesión iniciada)
// ==========================================================
Route::middleware(['auth'])->group(function () {

    // ------------------------------------------------------
    // A. EXCLUSIVO ADMINISTRADOR
    // ------------------------------------------------------
    Route::middleware(['rol:Administrador'])->group(function () {

        // Personal
        Route::resource('usuarios', UsuarioController::class)->except(['show']);

        // Creación y gestión de grupos
        Route::post('/grupos/crear', [GrupoController::class, 'store'])->name('grupos.store');
        Route::post('/grupos/guardar-profesores', [GrupoController::class, 'guardarProfesores'])->name('grupos.guardar_profesores');
        Route::get('/grupos/generados', function () { return view('groups.grupos_generados'); })->name('grupos.generados');
        Route::get('/crear_grupo', function () { return view('groups.crear_grupos_cursos.crear_grupo'); })->name('crear_grupo');

        // Curso propedéutico
        Route::post('/grupos/{id_grupo}/cambiar-estado', [GrupoController::class, 'cambiarModoEstado'])->name('grupos.cambiar_estado');

        // Cursos Propedéutico e Inducción
        Route::get('/curso_prope', [GrupoController::class, 'showCursoPrope'])->name('curso_prope');
        Route::get('/grupos/prope-creado', [GrupoController::class, 'showPropeCreado'])->name('curso_prope_creado');

        // Curso inducción
        Route::get('/curso_induc', [GrupoController::class, 'showCursoInduc'])->name('curso_induc');
        Route::post('/grupos-induc/store', [GrupoController::class, 'storeInduc'])->name('grupos_induc.store');
        Route::get('/grupos/induc-creado', [GrupoController::class, 'showInducCreado'])->name('curso_induc_creado');

        // Importación de Excel de alumnos
        Route::get('/grupos/importar', function () { return view('groups.importar_alumnos'); })->name('grupos.importar');
        Route::post('/grupos/importar', [AlumnoController::class, 'importar'])->name('alumnos.importar.post');

        // Alta manual de alumnos
        Route::get('/alumnos/nuevo', [AlumnoController::class, 'create'])->name('alumnos.nuevo');
        Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');

        // ── NUEVA: grupos disponibles por carrera (debe ir ANTES de /{alumno}/editar) ──
        Route::get('/alumnos/grupos-por-carrera/{id_carrera}', [AlumnoController::class, 'gruposPorCarrera'])
            ->name('alumnos.grupos_por_carrera');

        // Edición de alumnos
        Route::get('/alumnos/{alumno}/editar', [AlumnoController::class, 'edit'])->name('alumnos.edit');
        Route::put('/alumnos/{alumno}', [AlumnoController::class, 'update'])->name('alumnos.update');

        // Cierres y grupos finales
        Route::get('/cierre', function () { return view('grupos_finales.cierre'); })->name('cierre');
        Route::get('/grupos_final/criterios', function () { return view('grupos_final.criterios'); })->name('grupos_final.criterios');
        Route::get('/grupos_final/grupos_finales', function () { return view('grupos_final.grupos_finales'); })->name('grupos_final.grupos_finales');
    });

    // ------------------------------------------------------
    // B. COMPARTIDO: ADMINISTRADOR Y DOCENTE
    // ------------------------------------------------------
    Route::middleware(['rol:Administrador,Docente'])->group(function () {

        // Listas de grupos
        Route::get('/grupos/{id_grupo}/ver-lista', [GrupoController::class, 'showListaGrupo'])->name('lista_grupo');
        Route::get('/grupos/{id_grupo}/descargar-lista', [GrupoController::class, 'descargarLista'])->name('grupos.descargar_lista');

        // Asistencias
        Route::get('/asistencias/importar', function () { return view('attendance.importar_asistencias'); })->name('asistencias.importar');
        Route::post('/asistencias/procesar', [AsistenciaController::class, 'procesar'])->name('asistencias.procesar');
        Route::post('/asistencias/guardar-masivo', [AsistenciaController::class, 'guardarMasivo'])->name('asistencias.guardarMasivo');
        Route::get('/asistencia/paselista', [AsistenciaController::class, 'paselista'])->name('asistencia.paselista');
        Route::get('/asistencia/grupal', [AsistenciaController::class, 'grupal'])->name('asistencia.grupal');

        // Calificaciones
        Route::prefix('calificaciones')->name('calificaciones.')->group(function () {
            Route::get('/captura', [CalificacionController::class, 'showCaptura'])->name('captura');
            Route::post('/upload', [CalificacionController::class, 'upload'])->name('upload');
            Route::get('/mostrar/{id_grupo?}', [CalificacionController::class, 'indexByGrupo'])->name('mostrar');
            Route::post('/update-batch', [CalificacionController::class, 'updateBatch'])->name('updateBatch');
        });
        Route::post('/calificaciones/guardar-tabla-directo', [CalificacionController::class, 'guardarTabla'])->name('calificaciones.guardarTablaDirecto');
        Route::get('/calificaciones/exportar/{id_grupo}', [CalificacionController::class, 'exportarGrupo'])->name('calificaciones.exportar');
        Route::get('/calificaciones/descargar-formato-base', [CalificacionController::class, 'descargarFormatoBase'])->name('calificaciones.descargarFormatoBase');
        Route::get('/calificaciones/data/{id_grupo}', [CalificacionController::class, 'getAlumnosData'])->name('calificaciones.data');
    });

    // ------------------------------------------------------
    // C. COMPARTIDO: ADMINISTRADOR Y PSICOPEDAGÓGICO
    // ------------------------------------------------------
    Route::middleware(['rol:Administrador,Psicopedagogico'])->group(function () {
        Route::get('/alumnos/info', function () { return view('alumnos.info'); })->name('alumnos.info');
        Route::post('/alumnos/buscar', [AlumnoController::class, 'buscar'])->name('alumnos.buscar');
        Route::get('/psicologo', [SeguimientoController::class, 'index'])->name('psicologo');
        Route::get('/seguimiento/datos', [SeguimientoController::class, 'getDatosSeguimiento'])->name('seguimiento.datos');
    });

});