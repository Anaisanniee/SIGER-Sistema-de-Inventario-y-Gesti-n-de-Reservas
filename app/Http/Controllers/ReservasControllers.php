<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservasControllers extends Controller
{
    public function index(Request $request)
    {
        // Colección estática de prueba para las aulas
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

        // --- LÓGICA DE FILTRADO ---
        if ($request->filled('bloque')) {
            $aulasColeccion = $aulasColeccion->where('bloque', $request->bloque);
        }

        if ($request->filled('piso')) {
            $aulasColeccion = $aulasColeccion->where('piso', $request->piso);
        }

        if ($request->filled('capacidad')) {
            if ($request->capacidad == '1-20') {
                $aulasColeccion = $aulasColeccion->whereBetween('capacidad', [1, 20]);
            } elseif ($request->capacidad == '21-40') {
                $aulasColeccion = $aulasColeccion->whereBetween('capacidad', [21, 40]);
            }
        }

        if ($request->filled('buscar')) {
            $busqueda = strtolower($request->buscar);
            $aulasColeccion = $aulasColeccion->filter(function($aula) use ($busqueda) {
                return str_contains(strtolower($aula->nombre), $busqueda);
            });
        }

        // --- CONFIGURACIÓN DINÁMICA DEL FILTRO PARA ESTE MÓDULO ---
        $filtroConfig = [
            [
                'name' => 'bloque',
                'label' => 'Bloque',
                'placeholder' => 'Todos los bloques',
                'opciones' => [
                    'A' => 'Bloque A',
                    'B' => 'Bloque B',
                    'C' => 'Bloque C',
                ]
            ],
            [
                'name' => 'piso',
                'label' => 'Piso',
                'placeholder' => 'Todos los pisos',
                'opciones' => [
                    '1' => 'Piso 1',
                    '2' => 'Piso 2',
                    '3' => 'Piso 3',
                ]
            ],
            [
                'name' => 'capacidad',
                'label' => 'Capacidad',
                'placeholder' => 'Cualquier capacidad',
                'opciones' => [
                    '1-20'  => '1 a 20 personas',
                    '21-40' => '21 a 40 personas',
                ]
            ]
        ];

        $aulas = $aulasColeccion;
        $totalAulas = $aulas->count();

        return view('reservas.index-reservas', compact('aulas', 'totalAulas', 'filtroConfig'));
    }
    // Muestra el formulario para crear un aula
    public function create()
    {
        return view('reservas.crear');
    }

    // Procesa el formulario de aulas
    public function store(Request $request)
    {
        return redirect('/reservas')->with('success', 'Aula registrada exitosamente.');
    }

    // Muestra el formulario para crear un activo
    public function createActivo()
    {
        return view('inventario.crear');
    }

    // Procesa el formulario de activos
    public function storeActivo(Request $request)
    {
        return redirect('/dashboard')->with('success', 'Activo registrado exitosamente.');
    }
}