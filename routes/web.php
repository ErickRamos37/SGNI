<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/asistencia', function () {
    return view('Asistencia.Asistencia');
});
