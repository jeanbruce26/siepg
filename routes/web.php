<?php

use Illuminate\Support\Facades\Route;

Route::get('/administrador', function () {
    return view('layouts.modulo-administrador');
});

Route::get('/inscripcion', function () {
    return view('layouts.modulo-inscripcion');
});
