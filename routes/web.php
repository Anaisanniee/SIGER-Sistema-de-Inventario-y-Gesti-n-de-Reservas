<?php // 🚀 ESTA ETIQUETA ES OBLIGATORIA AQUÍ ARRIBA

use App\Http\Controllers\ActivosControllers;
use App\Http\Controllers\ReservasControllers;

// --- RUTA RAÍZ Y DASHBOARD ---
Route::get('/', function () { return view('dashboard.index-dashboard'); })->name('home');
Route::get('/dashboard', function () { return view('dashboard.index-dashboard'); })->name('dashboard');

// --- MÓDULO DE AULAS (RESERVAS) ---
Route::get('/reservas', [ReservasControllers::class, 'indexAulas'])->name('reservas.index');
Route::get('/aulas/crear', [ReservasControllers::class, 'createAula'])->name('aulas.create');
Route::post('/aulas/guardar', [ReservasControllers::class, 'storeAula'])->name('aulas.store');

// --- MÓDULO DE ACTIVOS (INVENTARIO) ---
Route::get('/activos/crear', [ActivosControllers::class, 'crear'])->name('activos.create');
Route::post('/activos/guardar', [ActivosControllers::class, 'storeActivo'])->name('activos.store');
Route::get('/prestamos/equipos', [ActivosControllers::class, 'prestamos'])->name('inventario.prestamos');
// Asegúrate de que no tenga variables como {id} para que sea frontend puro
Route::get('/activos/editar', [ActivosControllers::class, 'edit'])->name('activos.edit');
