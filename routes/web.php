<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivosControllers;

// Ruta para ver el listado
Route::get('/activos', [ActivosControllers::class, 'index'])->name('activos.index');

// Ruta para ver el formulario
Route::get('/activos/crear', [ActivosControllers::class, 'create'])->name('activos.create');

// Ruta para procesar el guardado
Route::post('/activos', [ActivosControllers::class, 'store'])->name('activos.store');
// Ruta para mostrar el formulario de edición
Route::get('/activos/{id}/editar', [ActivosControllers::class, 'edit'])->name('activos.edit');

// Ruta para procesar la actualización (fíjate que es PUT o PATCH)
Route::put('/activos/{id}', [ActivosControllers::class, 'update'])->name('activos.update');
Route::delete('/activos/{id}', [ActivosControllers::class, 'destroy'])->name('activos.destroy');