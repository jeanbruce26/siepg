<?php

use Illuminate\Support\Facades\Route;

Route::get('/administrador', function () {
    return view('layouts.modulo-administrador');
});

Route::get('/usuario', function () {
    return view('layouts.modulo-usuario');
});
