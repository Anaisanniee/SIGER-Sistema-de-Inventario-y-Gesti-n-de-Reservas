<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\ReservasControllers;

Route::get('/reservas', [ReservasControllers::class, 'index']);

Route::get('/dashboard', function () {
    return view('dashboard.index-dashboard');
});