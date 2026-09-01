<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReservasModels; // Asegúrate de importar tu modelo de reservas

class InformeController extends Controller
{
    /**
     * Muestra la vista del informe general de la institución y procesa los filtros.
     */
    public function index(Request $request)
    {
        // Iniciamos la consulta cargando las relaciones necesarias
        $query = ReservasModels::with(['detalles.activo', 'detalles.aula', 'usuario']);

        // Filtro por Estado
        if ($request->filled('estado')) {
            $query->where('res_estado_reserva', $request->estado);
        }

        // Filtro por Fecha Desde
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        // Filtro por Fecha Hasta
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // Obtenemos los registros paginados (o puedes usar get() si prefieres mostrarlos todos de una)
        $reservas = $query->latest()->paginate(10)->appends($request->query());
        
        $totalRegistros = $reservas->total();

        // Retornamos la vista (ajusta la ruta de la vista si es 'informes.reservas' o 'secretaria.informe')
        return view('informes.reservas', compact('reservas', 'totalRegistros'));
    }
}