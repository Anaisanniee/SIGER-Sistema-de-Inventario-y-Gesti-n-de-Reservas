<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ReservasModels; // Asegúrate de importar tu modelo de reservas
use App\Models\AulasModels;
use App\Models\ActivosModels;
use App\Models\HistorialPreciosModels;
use App\Models\TiposAulasModels;
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

    public function inventario()
    {
        // 1. Consultamos y transformamos los Activos incluyendo la relación del precio actual
        $activos = ActivosModels::with(['aula', 'categoria', 'precioActual'])->get()->map(function ($activo) {
            
            // Obtenemos el valor directamente de la relación que ya definiste en el modelo
            $valorPrecio = $activo->precioActual ? $activo->precioActual->his_pre_valor : 0;

            return [
                'nombre_activo'    => $activo->act_nombre ?? 'Sin nombre',
                'serial'           => $activo->act_serial ?? 'N/A',
                'ubicacion'        => optional($activo->aula)->aula_nombre ?? 'Sede Principal',
                'marca'            => $activo->act_marca ?? 'N/A',
                'categoria'        => optional($activo->categoria)->cate_nombre ?? 'General',
                'estado'           => $activo->act_estado_fisico ?? 'No registrado',
                'anio_adquisicion' => $activo->act_fecha_ingreso ? Carbon::parse($activo->act_fecha_ingreso)->format('Y') : 'N/A',
                'his_pre_valor'    => $valorPrecio > 0 ? '$ ' . number_format($valorPrecio, 2, ',', '.') : 'N/A'
            ];
        });

        // 2. Consultamos y transformamos las Aulas
        // 2. Consultamos y transformamos las Aulas
        $aulas = AulasModels::all()->map(function ($aula) {
            
            // Buscamos el tipo de aula usando la columna correcta 'tip_aula_id' y 'tip_aula_nombre'
            $tipoAulaNombre = 'General';
            if ($aula->tip_aula_id ?? null) {
                $tipoRecord = \DB::table('tipos_aulas')->where('tip_aula_id', $aula->tip_aula_id)->first();
                if ($tipoRecord) {
                    $tipoAulaNombre = $tipoRecord->tip_aula_nombre ?? 'General';
                }
            }

            return [
                'nombre_aula'          => $aula->aula_nombre ?? 'Sin nombre',
                'tip_aula_id'          => $tipoAulaNombre, // <--- Cambiado de 'tipo_aula' a 'tip_aula_id' para que coincida con el Blade
                'capacidad'            => ($aula->aula_capacidad ?? 0) . ' personas',
                'reservable'           => ($aula->aula_reservable == 1) ? 'Sí' : 'No',
                'estado'               => $aula->aula_estado ?? 'Disponible',
                'ultimo_mantenimiento' => $aula->updated_at ? Carbon::parse($aula->updated_at)->format('d/m/Y') : 'N/A'
            ];
        });

        return view('informes.inventario', compact('activos', 'aulas'));
    }

    public function exportarExcel($tipo)
    {
        $nombreArchivo = 'informe_' . $tipo . '_' . date('Y-m-d') . '.csv';
        
        if ($tipo === 'activos') {
            $datos = \App\Models\ActivosModels::with(['aula', 'categoria'])->get()->map(function ($activo) {
                // Buscamos el precio más reciente usando la misma lógica que ya funciona en tu vista
                $historialPrecio = DB::table('historial_precios')
                    ->where('act_id', $activo->act_id)
                    ->orderBy('his_pre_id', 'desc')
                    ->first();
                
                $valorPrecio = $historialPrecio ? ($historialPrecio->his_pre_valor ?? 0) : 0;

                return [
                    'Nombre del activo'    => $activo->act_nombre ?? 'Sin nombre',
                    'Serial'               => $activo->act_serial ?? 'N/A',
                    'Ubicación'            => optional($activo->aula)->aula_nombre ?? 'Sede Principal',
                    'Marca'                => $activo->act_marca ?? 'N/A',
                    'Categoría'            => optional($activo->categoria)->cate_nombre ?? 'General',
                    'Estado'               => $activo->act_estado_fisico ?? 'No registrado',
                    'Año de adquisición'   => $activo->act_fecha_ingreso ? Carbon::parse($activo->act_fecha_ingreso)->format('Y') : 'N/A',
                    'Precio'               => $valorPrecio > 0 ? '$ ' . number_format($valorPrecio, 2, ',', '.') : 'N/A'
                ];
            });
        } else {
            // PRUEBA DE DIAGNÓSTICO TEMPORAL PARA AULAS
            $aulasTest = \App\Models\AulasModels::all();
            
            // Si quieres ver qué datos trae antes de exportar, descomenta la siguiente línea:
            // dd($aulasTest);

            $datos = $aulasTest->map(function ($aula) {
                $tipoAulaNombre = 'General';
                if (!empty($aula->tip_aula_id)) {
                    $tipoRecord = DB::table('tipos_aulas')->where('tip_aula_id', $aula->tip_aula_id)->first();
                    if ($tipoRecord) {
                        $tipoAulaNombre = $tipoRecord->tip_aula_nombre ?? 'General';
                    }
                }

                return [
                    'Nombre del aula'      => $aula->aula_nombre ?? 'Sin nombre',
                    'Tipo de aula'         => $tipoAulaNombre,
                    'Capacidad'            => ($aula->aula_capacidad ?? 0) . ' personas',
                    'Reservable'           => ($aula->aula_reservable == 1) ? 'Sí' : 'No',
                    'Estado'               => $aula->aula_estado ?? 'Disponible',
                    'Último Mantenimiento' => $aula->updated_at ? Carbon::parse($aula->updated_at)->format('d/m/Y') : 'N/A'
                ];
            });
        }

        // Generamos la descarga en formato CSV compatible con Excel y codificación UTF-8
        $callback = function() use ($datos) {
            $file = fopen('php://output', 'w');
            
            // Escribir BOM UTF-8 para que Excel reconozca tildes y caracteres especiales
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            if ($datos->isNotEmpty()) {
                // Escribir las cabeceras de la tabla
                fputcsv($file, array_keys($datos->first()), ";");
                
                // Escribir cada una de las filas de datos
                foreach ($datos as $row) {
                    fputcsv($file, $row, ";");
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-Type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"$nombreArchivo\"",
        ]);
    }

    /**
     * Método dedicado a construir la consulta y aplicar los filtros.
     */
    private function obtenerReservasFiltradas(Request $request)
    {
        // Iniciamos la consulta cargando las relaciones necesarias
        $query = ReservasModels::with(['detalles.activo', 'detalles.aula', 'usuario']);

        // 🛡️ RESTRICCIÓN POR ROL: 
        // Únicamente si es Docente (o un rol que no sea directivo/secretaría), filtramos sus propias reservas.
        // La Rectora, Rector y Secretaría verán todo el consolidado institucional.
        $usuario = auth()->user();
        $rol = strtolower($usuario->role->name ?? '');

        if (in_array($rol, ['docente'])) { // Puedes agregar más roles aquí si los hay (ej: 'estudiante')
            $query->where('usu_id', $usuario->usu_id);
        }

        $this->aplicarFiltroEstado($query, $request);
        $this->aplicarFiltroFechas($query, $request);

        // Obtenemos todos los registros ordenados del más reciente al más antiguo
        return $query->latest('res_id')->get();
    }

    public function misReservas(Request $request)
    {
        $usuario = auth()->user();
        $rol = strtolower($usuario->role->name ?? '');

        // Consulta filtrada estrictamente por el ID del usuario actual
        $query = ReservasModels::with(['detalles.activo', 'detalles.aula', 'usuario'])
            ->where('usu_id', $usuario->usu_id);

        $this->aplicarFiltroEstado($query, $request);
        $this->aplicarFiltroFechas($query, $request);

        $reservas = $query->latest('res_id')->get();
        $totalRegistros = $reservas->count();

        // 🔀 Definimos la ruta de retorno de forma inteligente según el rol
        if (in_array($rol, ['rector', 'rectora'])) {
            $rutaRegresar = route('dashboard.rectora'); // O la ruta principal de la rectora
        } elseif (in_array($rol, ['docente'])) {
            $rutaRegresar = route('dashboard.docente'); // O la ruta principal del docente
        } else {
            $rutaRegresar = route('dashboard.secretaria'); // Por defecto para secretaría
        }

        return view('informes.reservas', compact('reservas', 'totalRegistros', 'rutaRegresar'));
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
                $esMultiple = $reserva->detalles->count() > 1;

                if ($esMultiple) {
                    $nombreRecurso = 'Reserva Múltiple (' . $reserva->detalles->count() . ' elementos)';
                } else {
                    $activoAsociado = optional($primerDetalle)->activo;
                    $aulaAsociada = optional($primerDetalle)->aula;

                    // Si la relación no viene cargada pero existe el ID en el detalle, lo buscamos de forma segura
                    if (!$aulaAsociada && $primerDetalle && ($primerDetalle->aula_id ?? $primerDetalle->det_re_aula_destino_act ?? null)) {
                        $aId = $primerDetalle->aula_id ?? $primerDetalle->det_re_aula_destino_act;
                        $aulaAsociada = \DB::table('aulas')->where('aula_id', $aId)->first();
                    }

                    if ($activoAsociado && !empty($activoAsociado->act_nombre)) {
                        $nombreRecurso = $activoAsociado->act_nombre;
                    } elseif ($aulaAsociada) {
                        $nombreRecurso = $aulaAsociada->aula_nombre ?? $aulaAsociada->nombre ?? 'Aula Asignada';
                    } else {
                        $nombreRecurso = 'Recurso General';
                    }
                }

                $nombreUsuario = trim((optional($reserva->usuario)->USU_PRIMER_NOMBRE ?? '') . ' ' . (optional($reserva->usuario)->USU_PRIMER_APELLIDO ?? ''));

                // Fecha y Hora de Inicio (con formato AM/PM)
                $rawFechaIni = optional($primerDetalle)->det_re_fecha_ini ?? $reserva->created_at;
                if ($rawFechaIni) {
                    $carbonIni = \Carbon\Carbon::parse($rawFechaIni);
                    $fechaInicio = $carbonIni->format('Y-m-d');
                    $horaInicio = $carbonIni->format('h:i A');
                } else {
                    $fechaInicio = 'N/A';
                    $horaInicio = 'N/A';
                }

                // Fecha y Hora de Fin (con formato AM/PM)
                $rawFechaFin = optional($primerDetalle)->det_re_fecha_fin;
                if ($rawFechaFin) {
                    $carbonFin = \Carbon\Carbon::parse($rawFechaFin);
                    $fechaFin = $carbonFin->format('Y-m-d');
                    $horaFin = $carbonFin->format('h:i A');
                } else {
                    $fechaFin = 'N/A';
                    $horaFin = 'N/A';
                }

                // Ubicación segura también para el Excel
                $ubicacionExport = 'Sede Principal';
                if ($primerDetalle) {
                    if (isset($primerDetalle->aula) && $primerDetalle->aula) {
                        $ubicacionExport = $primerDetalle->aula->aula_nombre ?? 'Aula Asignada';
                    } elseif (optional($primerDetalle->activo)->act_ubicacion) {
                        $ubicacionExport = $primerDetalle->activo->act_ubicacion;
                    } else {
                        $aulaId = $primerDetalle->det_re_aula_destino_act ?? $primerDetalle->aula_id;
                        if ($aulaId) {
                            $aulaRecord = \DB::table('aulas')->where('aula_id', $aulaId)->first();
                            if ($aulaRecord) {
                                $ubicacionExport = $aulaRecord->aula_nombre ?? ('Aula #' . $aulaId);
                            }
                        }
                    }
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
                    $ubicacionExport
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}