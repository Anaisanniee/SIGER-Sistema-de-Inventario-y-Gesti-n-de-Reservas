<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservasControllers extends Controller
{
    // Vista del catálogo de aulas
    public function indexAulas() {
        
        // 1. Configuración dinámica para los filtros del sidebar
        $filtroConfig = [
            [
                'name' => 'bloque',
                'label' => 'Bloque',
                'type' => 'select',
                'options' => ['Bloque A', 'Bloque B', 'Bloque C']
            ],
            [
                'name' => 'capacidad',
                'label' => 'Capacidad Mínima',
                'type' => 'number',
                'placeholder' => 'Ej. 20'
            ],
            [
                'name' => 'estado',
                'label' => 'Estado',
                'type' => 'select',
                'options' => ['Disponible', 'Mantenimiento', 'Reservada']
            ]
        ];

        // 2. 🚀 Agregamos la propiedad 'piso' para solucionar el error de la línea 47
        $aulas = [
            (object) [
                'id' => 1,
                'nombre' => 'Aula 101',
                'bloque' => 'A',
                'piso' => '1', // <-- Agregado
                'capacidad' => 35,
                'estado' => 'Disponible',
                'codigo' => 'AUL-101'
            ],
            (object) [
                'id' => 2,
                'nombre' => 'Laboratorio de Sistemas',
                'bloque' => 'B',
                'piso' => '2', // <-- Agregado
                'capacidad' => 25,
                'estado' => 'Disponible',
                'codigo' => 'LAB-SIS'
            ],
            (object) [
                'id' => 3,
                'nombre' => 'Auditorio Principal',
                'bloque' => 'C',
                'piso' => '1', // <-- Agregado
                'capacidad' => 120,
                'estado' => 'Ocupado',
                'codigo' => 'AUD-PRI'
            ]
        ];

        // 3. Variable por si tu vista cuenta el total en los textos superiores
        $totalAulas = count($aulas);

        // Enviamos las variables dinámicas completas a la vista
        return view('reservas.index-reservas', compact('filtroConfig', 'aulas', 'totalAulas')); 
    }

    // Muestra el formulario de registro de aulas
    public function createAula() {
        return view('reservas.crear'); 
    }

    // Procesa el formulario de aulas
    public function storeAula(Request $request) {
        return back();
    }
}