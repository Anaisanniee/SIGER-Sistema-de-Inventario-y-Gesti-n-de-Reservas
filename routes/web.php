<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

// Ruta para ver la lista de personal del colegio
Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');

// Ruta para procesar el registro de un nuevo docente/administrativo
Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');