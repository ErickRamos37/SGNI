<?php

use Illuminate\Support\Facades\Route;
// ESTA LÍNEA ES VITAL:
use App\Http\Controllers\AlumnoController; 

Route::get('/', function () {
    return view('welcome');
});

// ASEGÚRATE DE QUE ESTA LÍNEA ESTÉ ESCRITA ASÍ:
Route::get('/vista_alumno', [AlumnoController::class, 'create']);
