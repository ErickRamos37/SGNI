<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas del referentes al inicio de sesion
Route::get('/login', function () {
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


// Ruta básica para el dashboard
Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
})->name('dashboard');

Route::get('/grupos/importar', function () {
    return view('groups.importar_alumnos');
})->name('grupos.importar');

Route::get('/asistencias/importar', function () {
    return view('attendance.importar_asistencias');
})->name('asistencias.importar');
