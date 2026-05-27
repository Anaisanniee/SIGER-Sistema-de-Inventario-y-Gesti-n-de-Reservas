<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pruebas-siger', function () {
    $recursos = [
        // 1. Un Activo con sus campos de Excel + precio simulado
        (object)[
            'act_id' => 1,
            'act_foto' => 'proyector.jpeg',
            'act_nombre' => 'Proyector Epson X41',
            'act_serial' => 'EPS-778899',
            'act_marca' => 'Epson',
            'act_estado_fisico' => 'Bueno',
            'act_reservable' => true,
            'act_fecha_ingreso' => '2025-03-20',
            'cate_id' => 3,
            'aula_nombre' => 'Aula 6.01', // Nombre resuelto de la ubicación
            'act_precio_actual' => '2450000'   // Traído de historial_precios
        ],

        // 2. Un Aula con sus campos de Excel
        (object)[
            'aula_id' => 10,
            'aula_foto' => '',
            'aula_nombre' => 'Laboratorio de Software 6.02',
            'aula_capacidad' => 30,
            'aula_estado' => 'Disponible',
            'aula_reservable' => true,
            'tip_aula_id' => 2
        ],

        // 3. Otro Activo
        (object)[
            'act_id' => 2,
            'act_foto' => 'portatil.jpg',
            'act_nombre' => 'Portátil HP ProBook',
            'act_serial' => 'HP-554433',
            'act_marca' => 'HP',
            'act_estado_fisico' => 'Regular',
            'act_reservable' => false,
            'act_fecha_ingreso' => '2024-09-12',
            'cate_id' => 1,
            'aula_nombre' => 'Sistemas Informáticos',
            'act_precio_actual' => '3800000'
        ],

        // 4. Otra Aula
        (object)[
            'aula_id' => 11,
            'aula_foto' =>'' ,
            'aula_nombre' => 'Auditorio Principal',
            'aula_capacidad' => 120,
            'aula_estado' => 'Mantenimiento',
            'aula_reservable' => false,
            'tip_aula_id' => 1
        ]
    ];

    return view('pruebas', compact('recursos'));
});

Route::get('/activos', function () {
     $recursos = [
        ['act_id' => 1, 'act_nombre' => 'Proyector Epson', 'act_serial' => '123456'],
        ['act_id' => 2, 'act_nombre' => 'Laptop Dell', 'act_serial' => '654321'],
        ['act_id' => 3, 'act_nombre' => 'Cámara Canon', 'act_serial' => '789012'],
    ];
    return view('activos.index-activos', ['activos' => $recursos]);
});

Route::get('/reservas', function () {
    return view('reservas.index-reservas');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/dashboard/docente', function () {
    return view('dashboard.docente');
});

Route::get('/dashboard/rector', function () {
    return view('dashboard.rector');
});

Route::get('/dashboard/admin', function () {
    return view('dashboard.secretario');
});

