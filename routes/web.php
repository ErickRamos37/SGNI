<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// 'login' (es un estándar en Laravel)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Ruta básica para el dashboard
Route::get('/dashboard', function () {
    return view('welcome'); // Por ahora lo mandamos a welcome, luego se debera actualizar por la vista real
})->name('dashboard');

Route::get('/grupos/importar', function () {
    return view('groups.importar_alumnos');
})->name('grupos.importar');

Route::get('/asistencias/importar', function () {
    return view('attendance.importar_asistencias');
})->name('asistencias.importar');