<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReservasModels; // Asegúrate de importar tu modelo de reservas
use Carbon\Carbon;

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

    public function exportar(Request $request)
    {
        $reservas = $this->obtenerReservasFiltradas($request);
        $nombreArchivo = 'informe_reservas_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$nombreArchivo",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($reservas) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

            // Encabezados
            fputcsv($file, ['ID Reserva', 'Solicitante', 'Estado', 'Recurso / Elemento', 'Fecha Inicio', 'Hora Inicio', 'Fecha Fin', 'Hora Fin', 'Ubicación'], ';');

            foreach ($reservas as $reserva) {
                $primerDetalle = $reserva->detalles->first();
                $nombreRecurso = $reserva->detalles->count() > 1 
                    ? 'Reserva Múltiple (' . $reserva->detalles->count() . ' elementos)' 
                    : (optional($primerDetalle->activo)->act_nombre ?? optional($primerDetalle->aula)->aula_nombre ?? 'Recurso');

                $nombreUsuario = trim((optional($reserva->usuario)->USU_PRIMER_NOMBRE ?? '') . ' ' . (optional($reserva->usuario)->USU_PRIMER_APELLIDO ?? ''));

                // Fecha y Hora de Inicio (con formato AM/PM)
                $rawFechaIni = optional($primerDetalle)->det_re_fecha_ini ?? $reserva->created_at;
                if ($rawFechaIni) {
                    $carbonIni = \Carbon\Carbon::parse($rawFechaIni);
                    $fechaInicio = $carbonIni->format('Y-m-d');
                    $horaInicio = $carbonIni->format('h:i A'); // <-- Aquí se aplica el formato de 12 horas con AM/PM
                } else {
                    $fechaInicio = 'N/A';
                    $horaInicio = 'N/A';
                }

                // Fecha y Hora de Fin (con formato AM/PM)
                $rawFechaFin = optional($primerDetalle)->det_re_fecha_fin;
                if ($rawFechaFin) {
                    $carbonFin = \Carbon\Carbon::parse($rawFechaFin);
                    $fechaFin = $carbonFin->format('Y-m-d');
                    $horaFin = $carbonFin->format('h:i A'); // <-- Aquí también
                } else {
                    $fechaFin = 'N/A';
                    $horaFin = 'N/A';
                }

                fputcsv($file, [
                    $reserva->res_id ?? $reserva->id,
                    $nombreUsuario ?: 'Solicitante no asignado',
                    ucfirst($reserva->res_estado_reserva ?? 'Pendiente'),
                    $nombreRecurso,
                    $fechaInicio,
                    $horaInicio,
                    $fechaFin,
                    $horaFin,
                    optional($primerDetalle)->aula->aula_nombre ?? optional($primerDetalle->activo)->act_ubicacion ?? 'Sede Principal'
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}