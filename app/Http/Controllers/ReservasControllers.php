<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservasControllers extends Controller
{
    public function index(Request $request)
    {
        // Creamos una colección de aulas estáticas de prueba para que tu diseño funcione ya mismo
        $aulasColeccion = collect([
            (object)[
                'id' => 1,
                'nombre' => 'Aula 101',
                'bloque' => 'A',
                'piso' => '1',
                'capacidad' => 30,
                'estado' => 'Disponible'
            ],
            (object)[
                'id' => 2,
                'nombre' => 'Aula 102',
                'bloque' => 'B',
                'piso' => '2',
                'capacidad' => 27,
                'estado' => 'Ocupado'
            ],
            (object)[
                'id' => 4,
                'nombre' => 'Aula 201',
                'bloque' => 'B',
                'piso' => '2',
                'capacidad' => 35,
                'estado' => 'Disponible'
            ]
        ]);

        // --- LÓGICA DE FILTRADO (Simulación de Base de Datos) ---
        
        // Filtrar por Bloque
        if ($request->filled('bloque')) {
            $aulasColeccion = $aulasColeccion->where('bloque', $request->bloque);
        }

        // Filtrar por Piso
        if ($request->filled('piso')) {
            $aulasColeccion = $aulasColeccion->where('piso', $request->piso);
        }

        // Filtrar por Rango de Capacidad
        if ($request->filled('capacidad')) {
            if ($request->capacidad == '1-20') {
                $aulasColeccion = $aulasColeccion->whereBetween('capacidad', [1, 20]);
            } elseif ($request->capacidad == '21-40') {
                $aulasColeccion = $aulasColeccion->whereBetween('capacidad', [21, 40]);
            }
        }

        // Filtrar por Buscador de texto
        if ($request->filled('buscar')) {
            $busqueda = strtolower($request->buscar);
            $aulasColeccion = $aulasColeccion->filter(function($aula) use ($busqueda) {
                return str_contains(strtolower($aula->nombre), $busqueda);
            });
        }

        // Pasamos los resultados finales a la vista
        $aulas = $aulasColeccion;
        $totalAulas = $aulas->count();

        return view('reservas.index-reservas', compact('aulas', 'totalAulas'));
    }
}
