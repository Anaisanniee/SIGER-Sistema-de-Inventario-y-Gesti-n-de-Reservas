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
        // 1. Consultamos los Activos incluyendo el historial de precios completo
        $activos = ActivosModels::withTrashed()
            ->with([
                'aula' => fn($q) => $q->withTrashed(), 
                'categoria', 
                'precioActual',
                'historialPrecios' => fn($q) => $q->orderBy('his_pre_fecha_cambio', 'desc')
            ])
            ->get()
            ->map(function ($activo) {
                
                $valorPrecio = $activo->precioActual ? $activo->precioActual->his_pre_valor : 0;
                $nombreActivo = $activo->act_nombre ?? 'Sin nombre';
                if ($activo->trashed()) {
                    $nombreActivo .= ' (Fuera de servicio)';
                }

                // Convertimos el activo a un arreglo pero aseguramos conservar el ID y la relación
                $arrayActivo = $activo->toArray();
                
                $arrayActivo['nombre_activo'] = $nombreActivo;
                $arrayActivo['serial'] = $activo->act_serial ?? 'N/A';
                $arrayActivo['ubicacion'] = optional($activo->aula)->aula_nombre ?? 'Sede Principal';
                $arrayActivo['marca'] = $activo->act_marca ?? 'N/A';
                $arrayActivo['categoria'] = optional($activo->categoria)->cate_nombre ?? 'General';
                $arrayActivo['estado'] = $activo->trashed() ? 'Eliminado / Papelera' : ($activo->act_estado_fisico ?? 'No registrado');
                $arrayActivo['anio_adquisicion'] = $activo->act_fecha_ingreso ? Carbon::parse($activo->act_fecha_ingreso)->format('Y') : 'N/A';
                $arrayActivo['his_pre_valor'] = $valorPrecio > 0 ? '$ ' . number_format($valorPrecio, 2, ',', '.') : 'N/A';
                
                // Aseguramos que el ID esté disponible para los modales y botones
                $arrayActivo['id'] = $activo->act_id ?? $activo->id;

                return $arrayActivo;
            });

        // 2. Consultamos y transformamos las Aulas
        $aulas = AulasModels::withTrashed()->get()->map(function ($aula) {
            $tipoAulaNombre = 'General';
            if ($aula->tip_aula_id ?? null) {
                $tipoRecord = \DB::table('tipos_aulas')->where('tip_aula_id', $aula->tip_aula_id)->first();
                if ($tipoRecord) {
                    $tipoAulaNombre = $tipoRecord->tip_aula_nombre ?? 'General';
                }
            }

            $nombreAula = $aula->aula_nombre ?? 'Sin nombre';
            if ($aula->trashed()) {
                $nombreAula .= ' (Fuera de servicio)';
            }

            return [
                'nombre_aula'          => $nombreAula,
                'tip_aula_id'          => $tipoAulaNombre, 
                'capacidad'            => ($aula->aula_capacidad ?? 0) . ' personas',
                'reservable'           => ($aula->aula_reservable == 1) ? 'Sí' : 'No',
                'estado'               => $aula->trashed() ? 'Eliminada / Papelera' : ($aula->aula_estado ?? 'Disponible'),
                'ultimo_mantenimiento' => $aula->updated_at ? Carbon::parse($aula->updated_at)->format('d/m/Y') : 'N/A'
            ];
        });

        return view('informes.inventario', compact('activos', 'aulas'));
    }

    public function exportarExcel($tipo)
    {
        $nombreArchivo = 'informe_' . $tipo . '_' . date('Y-m-d') . '.csv';
        
        if ($tipo === 'activos') {
            // Añadimos withTrashed() para incluir los activos eliminados y la relación con el aula eliminada también
            $datos = \App\Models\ActivosModels::withTrashed()
                ->with(['aula' => fn($q) => $q->withTrashed(), 'categoria'])
                ->get()
                ->map(function ($activo) {
                    
                    // Buscamos el precio más reciente
                    $historialPrecio = DB::table('historial_precios')
                        ->where('act_id', $activo->act_id)
                        ->orderBy('his_pre_id', 'desc')
                        ->first();
                    
                    $valorPrecio = $historialPrecio ? ($historialPrecio->his_pre_valor ?? 0) : 0;
                    
                    $nombreActivo = $activo->act_nombre ?? 'Sin nombre';
                    if ($activo->trashed()) {
                        $nombreActivo .= ' (Fuera de servicio)';
                    }

                    return [
                        'Nombre del activo'    => $nombreActivo,
                        'Serial'               => $activo->act_serial ?? 'N/A',
                        'Ubicación'            => optional($activo->aula)->aula_nombre ?? 'Sede Principal',
                        'Marca'                => $activo->act_marca ?? 'N/A',
                        'Categoría'            => optional($activo->categoria)->cate_nombre ?? 'General',
                        'Estado'               => $activo->trashed() ? 'Eliminado / Papelera' : ($activo->act_estado_fisico ?? 'No registrado'),
                        'Año de adquisición'   => $activo->act_fecha_ingreso ? Carbon::parse($activo->act_fecha_ingreso)->format('Y') : 'N/A',
                        'Precio'               => $valorPrecio > 0 ? '$ ' . number_format($valorPrecio, 2, ',', '.') : 'N/A'
                    ];
                });
        } else {
            // Añadimos withTrashed() para incluir las aulas eliminadas
            $aulasTest = \App\Models\AulasModels::withTrashed()->get();
            
            $datos = $aulasTest->map(function ($aula) {
                $tipoAulaNombre = 'General';
                if (!empty($aula->tip_aula_id)) {
                    $tipoRecord = DB::table('tipos_aulas')->where('tip_aula_id', $aula->tip_aula_id)->first();
                    if ($tipoRecord) {
                        $tipoAulaNombre = $tipoRecord->tip_aula_nombre ?? 'General';
                    }
                }

                $nombreAula = $aula->aula_nombre ?? 'Sin nombre';
                if ($aula->trashed()) {
                    $nombreAula .= ' (Fuera de servicio)';
                }

                return [
                    'Nombre del aula'      => $nombreAula,
                    'Tipo de aula'         => $tipoAulaNombre,
                    'Capacidad'            => ($aula->aula_capacidad ?? 0) . ' personas',
                    'Reservable'           => ($aula->aula_reservable == 1) ? 'Sí' : 'No',
                    'Estado'               => $aula->trashed() ? 'Eliminada / Papelera' : ($aula->aula_estado ?? 'Disponible'),
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
        $query = ReservasModels::with(['detalles.activo', 'detalles.aula', 'usuario']);

        $usuario = auth()->user();
        $rol = strtolower($usuario->role->name ?? '');

        // 🎯 VERIFICACIÓN INTELIGENTE:
        // Si la petición viene de la sección "Mis Reservas" (ya sea por la URL o por el nombre de ruta),
        // filtramos ESTRICTAMENTE por el ID del usuario actual, sin importar si es Rector o Docente.
        if ($request->routeIs('mis.reservas') || str_contains(url()->previous(), 'mis-reservas')) {
            $query->where('usu_id', $usuario->usu_id);
        } 
        // Si está en el módulo general de informes, aplicamos la regla de roles:
        elseif (in_array($rol, ['docente'])) {
            $query->where('usu_id', $usuario->usu_id);
        }
        // Nota: Si es Rector/Secretaria en el informe general, no entra aquí y descarga todo.

        $this->aplicarFiltroEstado($query, $request);
        $this->aplicarFiltroFechas($query, $request);

        return $query->latest('res_id')->get();
    }

    public function misReservas(Request $request)
    {
        $usuario = auth()->user();
        $rol = strtolower($usuario->role->name ?? '');

        // Consulta filtrada estrictamente por el ID del usuario actual (con withTrashed para capturar recursos eliminados)
        $query = ReservasModels::with([
                'detalles.activo' => fn($q) => $q->withTrashed(), 
                'detalles.aula' => fn($q) => $q->withTrashed(), 
                'usuario'
            ])
            ->where('usu_id', $usuario->usu_id);

        $this->aplicarFiltroEstado($query, $request);
        $this->aplicarFiltroFechas($query, $request);

        $reservas = $query->latest('res_id')->get();
        $totalRegistros = $reservas->count();

        // 🔀 Definimos la ruta de retorno de forma inteligente según el rol
        if (in_array($rol, ['rector', 'rectora'])) {
            $rutaRegresar = route('dashboard.rectora'); 
        } elseif (in_array($rol, ['docente'])) {
            $rutaRegresar = route('dashboard.docente'); 
        } else {
            $rutaRegresar = route('dashboard.secretaria'); 
        }

        return view('informes.reservas', compact('reservas', 'totalRegistros', 'rutaRegresar'));
    }

    public function exportarMisReservas(Request $request)
    {
        $usuario = auth()->user();

        // 1. Consultamos las reservas filtradas ESTRICTAMENTE por el ID del usuario actual
        $query = ReservasModels::with([
            'detalles.activo' => fn($q) => $q->withTrashed(), 
            'detalles.aula' => fn($q) => $q->withTrashed(), 
            'usuario'
        ])->where('usu_id', $usuario->usu_id);

        // Aplicamos los mismos filtros de estado y fechas si el usuario los usó en la vista
        $this->aplicarFiltroEstado($query, $request);
        $this->aplicarFiltroFechas($query, $request);

        $reservas = $query->latest('res_id')->get();
        $nombreArchivo = 'mis_reservas_' . date('Y-m-d_H-i-s') . '.csv';

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

            // Encabezados del CSV
            fputcsv($file, ['ID Reserva', 'Solicitante', 'Estado', 'Recurso / Elemento', 'Fecha Inicio', 'Hora Inicio', 'Fecha Fin', 'Hora Fin', 'Ubicación'], ';');

            foreach ($reservas as $reserva) {
                $detalles = $reserva->detalles;
                $primerDetalle = $detalles->first();
                $esMultiple = $detalles->count() > 1;

                if ($esMultiple) {
                    $nombreRecurso = 'Reserva Múltiple (' . $detalles->count() . ' elementos)';
                } else {
                    $activoAsociado = optional($primerDetalle)->activo;
                    $aulaAsociada = optional($primerDetalle)->aula;

                    if (!$aulaAsociada && $primerDetalle && ($primerDetalle->aula_id ?? $primerDetalle->det_re_aula_destino_act ?? null)) {
                        $aId = $primerDetalle->aula_id ?? $primerDetalle->det_re_aula_destino_act;
                        $aulaAsociada = \App\Models\AulasModels::withTrashed()->find($aId);
                    }

                    if ($activoAsociado) {
                        $nombreRecurso = !empty($activoAsociado->deleted_at) ? 'Activo fuera de servicio' : ($activoAsociado->act_nombre ?? 'Activo sin nombre');
                    } elseif ($aulaAsociada) {
                        $nombreRecurso = !empty($aulaAsociada->deleted_at) ? 'Aula fuera de servicio' : ($aulaAsociada->aula_nombre ?? $aulaAsociada->nombre ?? 'Aula Asignada');
                    } else {
                        $nombreRecurso = 'Recurso General';
                    }
                }

                $nombreUsuario = trim((optional($reserva->usuario)->USU_PRIMER_NOMBRE ?? '') . ' ' . (optional($reserva->usuario)->USU_PRIMER_APELLIDO ?? ''));

                $rawFechaIni = optional($primerDetalle)->det_re_fecha_ini ?? $reserva->created_at;
                if ($rawFechaIni) {
                    $carbonIni = \Carbon\Carbon::parse($rawFechaIni);
                    $fechaInicio = $carbonIni->format('Y-m-d');
                    $horaInicio = $carbonIni->format('h:i A');
                } else {
                    $fechaInicio = 'N/A';
                    $horaInicio = 'N/A';
                }

                $rawFechaFin = optional($primerDetalle)->det_re_fecha_fin;
                if ($rawFechaFin) {
                    $carbonFin = \Carbon\Carbon::parse($rawFechaFin);
                    $fechaFin = $carbonFin->format('Y-m-d');
                    $horaFin = $carbonFin->format('h:i A');
                } else {
                    $fechaFin = 'N/A';
                    $horaFin = 'N/A';
                }

                $ubicacionExport = 'Sede Principal';
                if ($primerDetalle) {
                    if (isset($primerDetalle->aula) && $primerDetalle->aula) {
                        $ubicacionExport = !empty($primerDetalle->aula->deleted_at) ? 'Aula fuera de servicio' : ($primerDetalle->aula->aula_nombre ?? 'Aula Asignada');
                    } elseif (optional($primerDetalle->activo)->act_ubicacion) {
                        $ubicacionExport = $primerDetalle->activo->act_ubicacion;
                    } else {
                        $aulaId = $primerDetalle->det_re_aula_destino_act ?? $primerDetalle->aula_id;
                        if ($aulaId) {
                            $aulaRecord = \App\Models\AulasModels::withTrashed()->find($aulaId);
                            if ($aulaRecord) {
                                $ubicacionExport = !empty($aulaRecord->deleted_at) ? 'Aula fuera de servicio' : ($aulaRecord->aula_nombre ?? ('Aula #' . $aulaId));
                            }
                        }
                    }
                }

                fputcsv($file, [
                    $reserva->res_id ?? $reserva->id,
                    $nombreUsuario ?: 'Solicitante no asignado',
                    ucfirst($reserva->res_estado_reserva ?? $reserva->estado ?? 'Pendiente'),
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

    public function exportarReservas(Request $request)
    {
        // Pasamos el $request para que los filtros de la interfaz se apliquen también al exportar
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
                $detalles = $reserva->detalles;
                $primerDetalle = $detalles->first();
                $esMultiple = $detalles->count() > 1;

                if ($esMultiple) {
                    $nombreRecurso = 'Reserva Múltiple (' . $detalles->count() . ' elementos)';
                } else {
                    $activoAsociado = optional($primerDetalle)->activo;
                    $aulaAsociada = optional($primerDetalle)->aula;

                    if (!$aulaAsociada && $primerDetalle && ($primerDetalle->aula_id ?? $primerDetalle->det_re_aula_destino_act ?? null)) {
                        $aId = $primerDetalle->aula_id ?? $primerDetalle->det_re_aula_destino_act;
                        $aulaAsociada = \App\Models\AulasModels::withTrashed()->find($aId);
                    }

                    if ($activoAsociado) {
                        if (!empty($activoAsociado->deleted_at)) {
                            $nombreRecurso = 'Activo fuera de servicio';
                        } else {
                            $nombreRecurso = $activoAsociado->act_nombre ?? 'Activo sin nombre';
                        }
                    } elseif ($aulaAsociada) {
                        if (!empty($aulaAsociada->deleted_at)) {
                            $nombreRecurso = 'Aula fuera de servicio';
                        } else {
                            $nombreRecurso = $aulaAsociada->aula_nombre ?? $aulaAsociada->nombre ?? 'Aula Asignada';
                        }
                    } else {
                        $nombreRecurso = 'Recurso General';
                    }
                }

                $nombreUsuario = trim((optional($reserva->usuario)->USU_PRIMER_NOMBRE ?? '') . ' ' . (optional($reserva->usuario)->USU_PRIMER_APELLIDO ?? ''));

                $rawFechaIni = optional($primerDetalle)->det_re_fecha_ini ?? $reserva->created_at;
                if ($rawFechaIni) {
                    $carbonIni = \Carbon\Carbon::parse($rawFechaIni);
                    $fechaInicio = $carbonIni->format('Y-m-d');
                    $horaInicio = $carbonIni->format('h:i A');
                } else {
                    $fechaInicio = 'N/A';
                    $horaInicio = 'N/A';
                }

                $rawFechaFin = optional($primerDetalle)->det_re_fecha_fin;
                if ($rawFechaFin) {
                    $carbonFin = \Carbon\Carbon::parse($rawFechaFin);
                    $fechaFin = $carbonFin->format('Y-m-d');
                    $horaFin = $carbonFin->format('h:i A');
                } else {
                    $fechaFin = 'N/A';
                    $horaFin = 'N/A';
                }

                // Ubicación con validación de borrado suave
                $ubicacionExport = 'Sede Principal';
                if ($primerDetalle) {
                    if (isset($primerDetalle->aula) && $primerDetalle->aula) {
                        $ubicacionExport = !empty($primerDetalle->aula->deleted_at) ? 'Aula fuera de servicio' : ($primerDetalle->aula->aula_nombre ?? 'Aula Asignada');
                    } elseif (optional($primerDetalle->activo)->act_ubicacion) {
                        $ubicacionExport = $primerDetalle->activo->act_ubicacion;
                    } else {
                        $aulaId = $primerDetalle->det_re_aula_destino_act ?? $primerDetalle->aula_id;
                        if ($aulaId) {
                            $aulaRecord = \App\Models\AulasModels::withTrashed()->find($aulaId);
                            if ($aulaRecord) {
                                $ubicacionExport = !empty($aulaRecord->deleted_at) ? 'Aula fuera de servicio' : ($aulaRecord->aula_nombre ?? ('Aula #' . $aulaId));
                            }
                        }
                    }
                }

                fputcsv($file, [
                    $reserva->res_id ?? $reserva->id,
                    $nombreUsuario ?: 'Solicitante no asignado',
                    ucfirst($reserva->res_estado_reserva ?? $reserva->estado ?? 'Pendiente'),
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

    public function obtenerHistorialPrecios($id)
    {
        // Buscamos el activo (ajusta 'ActivosModel' si tu modelo tiene otro nombre)
        $activo = \App\Models\ActivosModel::find($id);

        if (!$activo) {
            return response()->json(['error' => 'Activo no encontrado'], 404);
        }

        // Consultamos el historial usando la tabla y columnas reales de tu base de datos
        $historial = \DB::table('historial_precios')
                        ->where('act_id', $id)
                        ->orderBy('his_pre_fecha_cambio', 'desc')
                        ->get();

        return response()->json([
            'activo' => [
                'nombre' => $activo->act_nombre ?? 'Activo',
                'serial' => $activo->act_serial ?? 'N/A'
            ],
            'historial' => $historial
        ]);
    }
}