<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivosControllers;

// Ruta para ver el listado
Route::get('/activos', [ActivosControllers::class, 'index'])->name('activos.index');

// Ruta para ver el formulario
Route::get('/activos/crear', [ActivosControllers::class, 'create'])->name('activos.create');

// Ruta para procesar el guardado
Route::post('/activos', [ActivosControllers::class, 'store'])->name('activos.store');