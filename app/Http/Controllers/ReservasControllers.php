<?php

namespace App\Http\Controllers;

use App\Models\ActivosModels;
use App\Models\AulasModels;
use App\Models\ReservasModels;
use App\Models\DetallesReservasModels;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservasControllers extends Controller
{
    private function extraerIdsSeguros($reservaTemp)
    {
        $idsActivos = [];
        $idsAulas = [];

        // Si el carrito envió los items estructurados por tipo (lo ideal)
        if (isset($reservaTemp['items']) && is_array($reservaTemp['items'])) {
            foreach ($reservaTemp['items'] as $item) {
                if (isset($item['tipo']) && isset($item['id'])) {
                    if ($item['tipo'] === 'activo') {
                        $idsActivos[] = $item['id'];
                    } elseif ($item['tipo'] === 'aula') {
                        $idsAulas[] = $item['id'];
                    }
                }
            }
        } else {
            // Compatibilidad por si usa el formato antiguo de IDs planos
            $ids = $reservaTemp['ids'] ?? [];
            $idsActivos = $ids;
            $idsAulas = $ids; // Fallback por seguridad
        }

        return [
            'activos' => array_unique($idsActivos),
            'aulas' => array_unique($idsAulas)
        ];
    }

    private function obtenerRecursosColeccion($idsActivos, $idsAulas)
    {
        $recursos = collect();

        if (!empty($idsActivos)) {
            $activosEncontrados = ActivosModels::whereIn('act_id', $idsActivos)->get();
            foreach ($activosEncontrados as $item) {
                $item->tipo_recurso_real = 'activo';
                $recursos->push($item);
            }
        }

        if (!empty($idsAulas)) {
            $aulasEncontradas = AulasModels::whereIn('aula_id', $idsAulas)->get();
            foreach ($aulasEncontradas as $item) {
                $item->tipo_recurso_real = 'aula';
                $recursos->push($item);
            }
        }

        return $recursos;
    }

    public function paso1(Request $request)
    {
        $reservaTemp = session('reserva_temp');

        if (!$reservaTemp) {
            return redirect()->route('dashboard.docente')
                ->with('error', 'No hay recursos seleccionados para iniciar una reserva.');
        }

        $idsActivos = [];
        $idsAulas = [];

        // CASO 1: Si la sesión viene con la estructura nueva de 'items' (objetos con id y tipo)
        if (!empty($reservaTemp['items']) && is_array($reservaTemp['items'])) {
            foreach ($reservaTemp['items'] as $item) {
                // Soportamos tanto 'id' como claves alternativas por seguridad
                $idItem = $item['id'] ?? $item['act_id'] ?? $item['aula_id'] ?? null;
                $tipoItem = $item['tipo'] ?? null;

                if ($idItem) {
                    if ($tipoItem === 'activo') {
                        $idsActivos[] = $idItem;
                    } elseif ($tipoItem === 'aula') {
                        $idsAulas[] = $idItem;
                    } else {
                        // Si no especifica tipo, intentamos adivinar buscando en bases de datos o por defecto a activos
                        $idsActivos[] = $idItem;
                    }
                }
            }
        } 
        // CASO 2: Si la sesión viene con la estructura vieja de 'ids' planos
        elseif (!empty($reservaTemp['ids']) && is_array($reservaTemp['ids'])) {
            $idsActivos = $reservaTemp['ids'];
        }

        // Limpiamos duplicados de los arrays
        $idsActivos = array_unique($idsActivos);
        $idsAulas = array_unique($idsAulas);

        if (empty($idsActivos) && empty($idsAulas)) {
            return redirect()->route('dashboard.docente')
                ->with('error', 'No hay recursos válidos seleccionados.');
        }

        $recursos = collect();

        if (!empty($idsActivos)) {
            foreach (ActivosModels::whereIn('act_id', $idsActivos)->get() as $item) {
                $item->tipo_recurso_real = 'activo';
                if (!$recursos->contains('act_id', $item->act_id)) {
                    $recursos->push($item);
                }
            }
        }

        if (!empty($idsAulas)) {
            foreach (AulasModels::whereIn('aula_id', $idsAulas)->get() as $item) {
                $item->tipo_recurso_real = 'aula';
                if (!$recursos->contains('aula_id', $item->aula_id)) {
                    $recursos->push($item);
                }
            }
        }

        if ($recursos->isEmpty()) {
            return redirect()->route('dashboard.docente')
                ->with('error', 'Los recursos solicitados no existen o ya no se encuentran disponibles.');
        }

        $tipoRecurso = $reservaTemp['tipo'] ?? 'mixto';

        return view('reservas.crear.paso1', compact('recursos', 'tipoRecurso'));
    }

    public function postPaso1(Request $request)
    {
        if ($request->input('confirmacion_recurso') === 'no') {
            return redirect()->route('dashboard.docente')->with('info', 'Has cancelado la selección.');
        }

        $reservaTemp = session('reserva_temp');

        if (!$reservaTemp) {
            return redirect()->route('dashboard.docente')->with('error', 'No hay recursos seleccionados.');
        }

        $separados = $this->extraerIdsSeguros($reservaTemp);
        $recursosObjetos = $this->obtenerRecursosColeccion($separados['activos'], $separados['aulas']);
        $tipoRecurso = $reservaTemp['tipo'] ?? 'activo';

        // Guardamos los arreglos separados y objetos en la sesión principal de la reserva
        $request->session()->put('reserva.ids_activos', $separados['activos']);
        $request->session()->put('reserva.ids_aulas', $separados['aulas']);
        $request->session()->put('reserva.tipo_recurso', $tipoRecurso); 
        $request->session()->put('reserva.recursos_objetos', $recursosObjetos);

        return redirect()->route('reservas.paso2');
    }

    public function paso2(Request $request)
    {
        $idsActivos = session('reserva.ids_activos', []);
        $idsAulas = session('reserva.ids_aulas', []);
        $tipoRecurso = session('reserva.tipo_recurso', 'activo');

        $recursos = $this->obtenerRecursosColeccion($idsActivos, $idsAulas);
        $horasOcupadas = [];

        if ($recursos->isNotEmpty()) {
            $reservasExistentes = DetallesReservasModels::where(function($query) use ($idsActivos, $idsAulas) {
                    if (!empty($idsActivos)) {
                        $query->orWhereIn('act_id', $idsActivos);
                    }
                    if (!empty($idsAulas)) {
                        $query->orWhereIn('aula_id', $idsAulas);
                    }
                })
                ->whereHas('reserva', function($query) {
                    $query->whereIn('res_estado_reserva', ['Pendiente', 'Aprobada', 'pendiente', 'aprobada']);
                })
                ->get(['det_re_fecha_ini', 'det_re_fecha_fin']);

            foreach ($reservasExistentes as $res) {
                $horasOcupadas[] = [
                    'inicio' => $res->det_re_fecha_ini,
                    'fin' => $res->det_re_fecha_fin
                ];
            }
        }

        $aulas = AulasModels::all();

        return view('reservas.crear.paso2', compact('recursos', 'tipoRecurso', 'aulas', 'horasOcupadas'));
    }

    public function guardarPaso2(Request $request)
    {
        $reglasValidacion = [
            'res_fecha_inicio' => 'required|date',
            'res_fecha_fin'    => 'required|date|after_or_equal:res_fecha_inicio',
            'res_hora_inicio'  => 'required',
            'res_hora_fin'     => 'required',
            'res_motivo'       => 'required|string|max:255',
        ];

        $idsActivos = session('reserva.ids_activos', []);
        if (!empty($idsActivos)) {
            $reglasValidacion['aula_uso'] = 'required';
        }

        $request->validate($reglasValidacion);

        $horaInicioInput = $request->res_hora_inicio;
        $horaFinInput    = $request->res_hora_fin;

        $horaInicio = strlen($horaInicioInput) === 5 ? $horaInicioInput . ':00' : $horaInicioInput;
        $horaFin    = strlen($horaFinInput) === 5 ? $horaFinInput . ':00' : $horaFinInput;

        $fechaHoraInicio = $request->res_fecha_inicio . ' ' . $horaInicio;
        $fechaHoraFin    = $request->res_fecha_fin . ' ' . $horaFin;

        session([
            'reserva.res_fecha_inicio' => $fechaHoraInicio,
            'reserva.res_fecha_fin'    => $fechaHoraFin,
            'reserva.res_hora_inicio'  => $horaInicio,
            'reserva.res_hora_fin'     => $horaFin,
            'reserva.res_motivo'       => $request->res_motivo,
            'reserva.aula_uso'         => $request->aula_uso ?? null,
        ]);

        return redirect()->route('reservas.paso3');
    }

    public function paso3(Request $request)
    {
        $datosReserva = session('reserva', []);
        return view('reservas.crear.paso3', compact('datosReserva'));
    }

    public function guardarPaso3(Request $request)
    {
        $datosReserva = session('reserva', []);

        $idsActivos = $datosReserva['ids_activos'] ?? [];
        $idsAulas = $datosReserva['ids_aulas'] ?? [];

        if (empty($datosReserva) || (empty($idsActivos) && empty($idsAulas))) {
            return redirect()->route('dashboard.docente')->with('error', 'No hay datos de reserva en proceso. Por favor, comience de nuevo.');
        }

        $aulaIdReal = 1; 
        $aulaIngresada = $datosReserva['aula_uso'] ?? null;

        if (!empty($aulaIngresada)) {
            $aulaObj = AulasModels::where('aula_id', $aulaIngresada)
                        ->orWhere('aula_nombre', 'LIKE', '%' . trim($aulaIngresada) . '%')
                        ->first();

            if ($aulaObj) {
                $aulaIdReal = $aulaObj->aula_id;
            } elseif (is_numeric($aulaIngresada)) {
                $aulaIdReal = intval($aulaIngresada);
            }
        }

        $fechaInicio = $datosReserva['res_fecha_inicio'] ?? now();
        $fechaFin = $datosReserva['res_fecha_fin'] ?? now();

        if ($fechaInicio && !str_contains($fechaInicio, ' ')) {
            $horaIni = $datosReserva['res_hora_inicio'] ?? '08:00:00';
            $fechaInicio = trim($fechaInicio) . ' ' . (strlen($horaIni) === 5 ? $horaIni . ':00' : $horaIni);
        } elseif (strlen($fechaInicio) === 16) {
            $fechaInicio .= ':00';
        }

        if ($fechaFin && !str_contains($fechaFin, ' ')) {
            $horaFin = $datosReserva['res_hora_fin'] ?? '12:00:00';
            $fechaFin = trim($fechaFin) . ' ' . (strlen($horaFin) === 5 ? $horaFin . ':00' : $horaFin);
        } elseif (strlen($fechaFin) === 16) {
            $fechaFin .= ':00';
        }

        // Validación de cruce separada por tipo e ID exacto
        $conflicto = DetallesReservasModels::whereHas('reserva', function($query) {
                $query->whereIn('res_estado_reserva', ['Pendiente', 'Aprobada', 'pendiente', 'aprobada']);
            })
            ->where(function($query) use ($idsActivos, $idsAulas) {
                if (!empty($idsActivos)) {
                    $query->orWhereIn('act_id', $idsActivos);
                }
                if (!empty($idsAulas)) {
                    $query->orWhereIn('aula_id', $idsAulas);
                }
            })
            ->where(function($query) use ($fechaInicio, $fechaFin) {
                $query->where('det_re_fecha_ini', '<', $fechaFin)
                      ->where('det_re_fecha_fin', '>', $fechaInicio);
            })
            ->exists();

        if ($conflicto) {
            return redirect()->route('reservas.paso2')
                ->withErrors(['res_hora_inicio' => '¡Uno o más recursos seleccionados ya se encuentran reservados en ese intervalo de horario! Por favor seleccione otra hora.'])
                ->withInput();
        }

        $reserva = ReservasModels::create([
            'usu_id'             => auth()->id() ?? 1, 
            'res_estado_reserva' => 'Pendiente',
            'res_fecha_creacion' => now()->toDateString(),
            'res_motivo'         => $datosReserva['res_motivo'] ?? 'Sin motivo especificado',
        ]);

        // Registrar detalles independientes para activos
        foreach ($idsActivos as $idActivo) {
            DetallesReservasModels::create([
                'res_id'                    => $reserva->res_id,
                'act_id'                    => $idActivo, 
                'det_re_fecha_ini'          => $fechaInicio, 
                'det_re_fecha_fin'          => $fechaFin,      
                'det_re_aula_destino_act'   => $aulaIdReal, 
                'aula_id'                   => null,   
            ]);
        }

        // Registrar detalles independientes para aulas
        foreach ($idsAulas as $idAula) {
            DetallesReservasModels::create([
                'res_id'                    => $reserva->res_id,
                'act_id'                    => null, 
                'det_re_fecha_ini'          => $fechaInicio, 
                'det_re_fecha_fin'          => $fechaFin,      
                'det_re_aula_destino_act'   => null, 
                'aula_id'                   => $idAula,   
            ]);
        }

        session()->forget(['reserva', 'reserva_temp']);

        return redirect()->route('dashboard.docente')->with('success', '¡Solicitud múltiple de reserva procesada con éxito! Quedará pendiente de aprobación.');
    }

    public function indexSecretaria()
    {
        $reservas = ReservasModels::with([
                'usuario', 
                'detalles.activo', 
                'detalles.aula'    
            ])
            ->orderBy('res_id', 'desc')
            ->get();

        return view('reservas.index', compact('reservas'));
    }
}