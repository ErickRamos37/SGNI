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
use App\Http\Controllers\GrupoFinalController;

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

        // Creación y Gestión de Grupos
        Route::get('/crear_grupo', function () { return view('groups.crear_grupos_cursos.crear_grupo'); })->name('crear_grupo');
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

        // Alumnos (Altas, Edición e Importación)
        Route::get('/alumnos/nuevo', [AlumnoController::class, 'create'])->name('alumnos.nuevo');
        Route::post('/alumnos', [AlumnoController::class, 'store'])->name('alumnos.store');
        Route::get('/alumnos/{alumno}/editar', [AlumnoController::class, 'edit'])->name('alumnos.edit');
        Route::put('/alumnos/{alumno}', [AlumnoController::class, 'update'])->name('alumnos.update');
        
        // Importación de excel para la creación de los grupos
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

        Route::prefix('grupos-finales')->name('grupos_finales.')->group(function () {
            // 1. Formulario de criterios -> URL final: /grupos-finales/configurar | Nombre: grupos_finales.criterios
            Route::get('configurar', [GrupoFinalController::class, 'configurar'])
                ->name('criterios');

            // 2. Nueva vista para subir el Excel
            Route::get('subir-excel', [GrupoFinalController::class, 'mostrarSubirExcel'])
                ->name('subir_excel');

            // 3. Procesar algoritmo -> URL final: /grupos-finales/generar | Nombre: grupos_finales.generar
            Route::post('generar', [GrupoFinalController::class, 'generarDistribucion'])
                ->name('generar');

            // 3. Tabla de resultados -> URL final: /grupos-finales/lista | Nombre: grupos_finales.lista
            Route::get('lista', [GrupoFinalController::class, 'gruposFinales'])
                ->name('lista');

            // 4. Modo lectura -> URL final: /grupos-finales/modo-lectura | Nombre: grupos_finales.modo_lectura
            Route::get('modo-lectura', function () {
                return view('grupos_finales.modo_lectura');
            })->name('modo_lectura');

            Route::get('{id}/lista', [GrupoFinalController::class, 'verListaGrupo'])
                ->name('lista_grupo_final');
        });

        // Vista para ver la lista en pantalla
        Route::get('/grupos-finales/lista/{id}', [GrupoFinalController::class, 'verListaGrupo'])->name('grupos_finales.lista_grupo_final');

        // Acción para descargar el CSV
        Route::get('/grupos-finales/descargar/{id}', [GrupoFinalController::class, 'descargarLista'])->name('grupos_finales.descargar_lista');
    });

    // ------------------------------------------------------
    // B. COMPARTIDO: ADMINISTRADOR Y DOCENTE
    // ------------------------------------------------------
    Route::middleware(['rol:Administrador,Docente'])->group(function () {

        // Visualización de Listas
        Route::get('/grupos/{id_grupo}/ver-lista', [GrupoController::class, 'showListaGrupo'])->name('lista_grupo');
        Route::get('/grupos/{id_grupo}/descargar-lista', [GrupoController::class, 'descargarLista'])->name('grupos.descargar_lista');

        // Asistencias
        Route::post('/asistencias/guardar-masivo', [AsistenciaController::class, 'guardarMasivo'])->name('asistencias.guardarMasivo');
        Route::get('/asistencia/paselista', [AsistenciaController::class, 'paselista'])->name('asistencia.paselista');
        Route::get('/asistencia/grupal', [AsistenciaController::class, 'grupal'])->name('asistencia.grupal');

        // Calificaciones (Agrupadas correctamente bajo un solo prefijo)
        // Calificaciones
        Route::prefix('calificaciones')->name('calificaciones.')->group(function () {
            Route::get('/captura', [CalificacionController::class, 'showCaptura'])->name('captura');
            Route::post('/upload', [CalificacionController::class, 'upload'])->name('upload');
            Route::get('/mostrar/{id_grupo?}', [CalificacionController::class, 'indexByGrupo'])->name('mostrar');
            Route::post('/update-batch', [CalificacionController::class, 'updateBatch'])->name('updateBatch');
            Route::post('/guardar-tabla-directo', [CalificacionController::class, 'guardarTabla'])->name('guardarTablaDirecto');
            Route::get('/exportar/{id_grupo}', [CalificacionController::class, 'exportarGrupo'])->name('exportar');
            Route::get('/descargar-formato-base', [CalificacionController::class, 'descargarFormatoBase'])->name('descargarFormatoBase');
            Route::get('/data/{id_grupo}', [CalificacionController::class, 'getAlumnosData'])->name('data');
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
