<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivosControllers extends Controller
{
    public function prestamos(Request $request)
    {
        // 1. Datos base simulados
        $todosLosActivos = [
            [
                'nombre' => 'Computador Dell Inspiron',
                'codigo' => '#EQ-01',
                'detalles' => ['Windows 11', '16 GB RAM'],
                'estado' => 'Disponible',
                'icono' => 'fa-laptop'
            ],
            [
                'nombre' => 'Tablet Samsung Galaxy',
                'codigo' => '#EQ-02',
                'detalles' => ['Android 14', 'Tamaño 10.1"'],
                'estado' => 'Ocupado',
                'icono' => 'fa-tablet-alt'
            ],
            [
                'nombre' => 'Proyector Epson X39',
                'codigo' => '#EQ-03',
                'detalles' => ['3600 Lúmenes', 'HDMI'],
                'estado' => 'Disponible',
                'icono' => 'fa-video'
            ]
        ];

        // 2. Capturamos los filtros que vienen del formulario desplegable o buscador
        $buscar = $request->input('buscar');
        $tipo = $request->input('tipo');
        $disponibilidad = $request->input('disponibilidad');

        // 3. Procesamos los filtros de forma dinámica sobre el arreglo
        $activosFiltrados = array_filter($todosLosActivos, function($activo) use ($buscar, $tipo, $disponibilidad) {
            // Filtro de la barra de búsqueda (por Nombre o Código)
            if ($buscar && (stripos($activo['nombre'], $buscar) === false && stripos($activo['codigo'], $buscar) === false)) {
                return false;
            }
            // Filtro por Tipo de Equipo (compara con el icono)
            if ($tipo && $activo['icono'] !== $tipo) {
                return false;
            }
            // Filtro por Estado (Disponible u Ocupado)
            if ($disponibilidad && $activo['estado'] !== $disponibilidad) {
                return false;
            }
            return true;
        });

        // Pasamos los datos filtrados a la vista del inventario
        return view('inventario.prestamos', ['activos' => $activosFiltrados]);
    }
}