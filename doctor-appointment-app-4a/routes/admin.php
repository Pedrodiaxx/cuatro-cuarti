<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Hola desde admin');
});