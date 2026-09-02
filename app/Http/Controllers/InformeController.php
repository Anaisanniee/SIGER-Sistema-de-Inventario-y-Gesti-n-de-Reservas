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
        $reservas = $this->obtenerReservasFiltradas($request);
        $totalRegistros = $reservas->count();

        return view('informes.reservas', compact('reservas', 'totalRegistros'));
    }

    /**
     * Método dedicado a construir la consulta y aplicar los filtros.
     */
    private function obtenerReservasFiltradas(Request $request)
    {
        // Iniciamos la consulta cargando las relaciones necesarias
        $query = ReservasModels::with(['detalles.activo', 'detalles.aula', 'usuario']);

        $this->aplicarFiltroEstado($query, $request);
        $this->aplicarFiltroFechas($query, $request);

        // Obtenemos todos los registros ordenados del más reciente al más antiguo
        return $query->latest('res_id')->get();
    }

    /**
     * Aplica el filtro por estado de manera segura.
     */
    private function aplicarFiltroEstado($query, Request $request)
    {
        if ($request->filled('estado')) {
            $estado = strtolower(trim($request->estado));
            if ($estado !== 'todos') {
                $query->whereRaw("LOWER(TRIM(res_estado_reserva)) = ?", [$estado]);
            }
        }
    }

    /**
     * Aplica los filtros de fecha desde y fecha hasta basados en los detalles de reserva.
     */
    private function aplicarFiltroFechas($query, Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        // Si se seleccionó solo la "Fecha Inicio" pero NO la "Fecha Fin"
        if (!empty($fechaInicio) && empty($fechaFin)) {
            $query->whereHas('detalles', function($q) use ($fechaInicio) {
                $q->whereDate('det_re_fecha_ini', $fechaInicio);
            });
        }
        // Si se seleccionó solo la "Fecha Fin" pero NO la "Fecha Inicio"
        elseif (empty($fechaInicio) && !empty($fechaFin)) {
            $query->whereHas('detalles', function($q) use ($fechaFin) {
                $q->whereDate('det_re_fecha_fin', '<=', $fechaFin);
            });
        }
        // Si se seleccionaron AMBAS (es un rango completo)
        elseif (!empty($fechaInicio) && !empty($fechaFin)) {
            $query->whereHas('detalles', function($q) use ($fechaInicio, $fechaFin) {
                $q->whereDate('det_re_fecha_ini', '>=', $fechaInicio)
                  ->whereDate('det_re_fecha_fin', '<=', $fechaFin);
            });
        }
    }
}