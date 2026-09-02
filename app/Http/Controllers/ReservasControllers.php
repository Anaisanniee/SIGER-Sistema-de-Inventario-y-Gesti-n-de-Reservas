<?php
namespace App\Http\Controllers;

use App\Models\ActivosModels;
use App\Models\AulasModels;
use App\Models\ReservasModels;
use App\Models\DetallesReservasModels;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReservasControllers extends Controller
{
    private function extraerIdsSeguros($reservaTemp)
    {
        $idsActivos = [];
        $idsAulas = [];

        // Si el carrito envió los items estructurados por tipo
        if (isset($reservaTemp['items']) && is_array($reservaTemp['items'])) {
            foreach ($reservaTemp['items'] as $item) {
                $tipoItem = $item['tipo'] ?? null;
                
                if ($tipoItem === 'aula') {
                    $idItem = $item['aula_id'] ?? $item['id'] ?? null;
                    if ($idItem !== null) {
                        $idsAulas[] = $idItem;
                    }
                } else {
                    $idItem = $item['act_id'] ?? $item['id'] ?? null;
                    if ($idItem !== null) {
                        $idsActivos[] = $idItem;
                    }
                }
            }
        } else {
            // Compatibilidad por si usa el formato antiguo de IDs planos
            $ids = $reservaTemp['ids'] ?? [];
            $idsActivos = $ids;
        }

        return [
            'activos' => array_unique(array_filter($idsActivos)),
            'aulas' => array_unique(array_filter($idsAulas))
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

        // 1. Extraemos los IDs de forma robusta soportando tanto 'id' como 'aula_id' o 'act_id'
        if (!empty($reservaTemp['items']) && is_array($reservaTemp['items'])) {
            foreach ($reservaTemp['items'] as $item) {
                $tipoItem = $item['tipo'] ?? null;
                
                if ($tipoItem === 'aula') {
                    $idItem = $item['aula_id'] ?? $item['id'] ?? null;
                    if ($idItem !== null) {
                        $idsAulas[] = $idItem;
                    }
                } else {
                    $idItem = $item['act_id'] ?? $item['id'] ?? null;
                    if ($idItem !== null) {
                        $idsActivos[] = $idItem;
                    }
                }
            }
        } elseif (!empty($reservaTemp['ids']) && is_array($reservaTemp['ids'])) {
            $idsActivos = $reservaTemp['ids'];
        }

        $idsActivos = array_unique($idsActivos);
        $idsAulas = array_unique($idsAulas);

        if (empty($idsActivos) && empty($idsAulas)) {
            return redirect()->route('dashboard.docente')
                ->with('error', 'No hay recursos válidos seleccionados.');
        }

        $recursos = collect();

        // 2. Consultamos Activos de forma segura
        if (!empty($idsActivos)) {
            $activosEncontrados = ActivosModels::whereIn('act_id', $idsActivos)->get();
            foreach ($activosEncontrados as $item) {
                $item->tipo_recurso_real = 'activo';
                $recursos->push($item);
            }
        }

        // 3. Consultamos Aulas usando explícitamente 'aula_id' (la llave primaria de tu modelo)
        if (!empty($idsAulas)) {
            $aulasEncontradas = AulasModels::whereIn('aula_id', $idsAulas)->get();
            foreach ($aulasEncontradas as $item) {
                $item->tipo_recurso_real = 'aula';
                $recursos->push($item);
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

        // --- SOLUCIÓN: Obtener el nombre real del aula usando 'aula_id' ---
        $nombreAulaUso = null;
        if ($request->filled('aula_uso')) {
            $aulaInput = $request->aula_uso;
            if (is_numeric($aulaInput)) {
                $aulaObj = \App\Models\AulasModels::where('aula_id', $aulaInput)->first();
                $nombreAulaUso = $aulaObj ? $aulaObj->aula_nombre : $aulaInput;
            } else {
                $nombreAulaUso = $aulaInput;
            }
        }
        // -----------------------------------------------------------------

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

        $aulaExplícitaEnCarrito = false;

        if (empty($idsAulas)) {
            if (isset($datosReserva['recursos_objetos'])) {
                foreach ($datosReserva['recursos_objetos'] as $rec) {
                    $recObj = (object)$rec;
                    if (isset($recObj->tipo_recurso_real) && $recObj->tipo_recurso_real === 'aula') {
                        $idsAulas[] = $recObj->aula_id ?? $recObj->id;
                        $aulaExplícitaEnCarrito = true;
                    }
                }
            }
        } else {
            $aulaExplícitaEnCarrito = true;
        }

        $idsAulas = array_unique(array_filter($idsAulas));

        $aulaIdReal = null;
        $aulaIngresada = $datosReserva['aula_uso'] ?? null;
        
        if (!empty($aulaIngresada)) {
            if (is_numeric($aulaIngresada)) {
                $aulaObj = AulasModels::where('aula_id', $aulaIngresada)->first();
                if ($aulaObj) {
                    $aulaIdReal = $aulaObj->aula_id;
                }
            } else {
                $aulaObj = AulasModels::where('aula_nombre', 'LIKE', '%' . trim($aulaIngresada) . '%')->first();
                if ($aulaObj) {
                    $aulaIdReal = $aulaObj->aula_id;
                }
            }
        }

        if (!$aulaIdReal && !empty($idsAulas)) {
            $aulaIdReal = $idsAulas[0];
        }

        if (!$aulaIdReal) {
            $primeraAula = AulasModels::first();
            $aulaIdReal = $primeraAula ? $primeraAula->aula_id : 3;
        }

        $user = auth()->user();
        $rolUser = strtolower($user->rol ?? $user->role ?? $user->tipo_usuario ?? '');
        
        $dashboardRoute = 'dashboard.docente';
        if (str_contains($rolUser, 'rector')) {
            $dashboardRoute = \Route::has('dashboard.rectora') ? 'dashboard.rectora' : 'dashboard.docente';
        }

        if (empty($datosReserva) || (empty($idsActivos) && empty($idsAulas) && empty($aulaIdReal))) {
            return redirect()->route($dashboardRoute)->with('error', 'No hay datos de reserva en proceso. Por favor, comience de nuevo.');
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

        $recursosOcupadosNombres = [];

        // 1. Revisar conflictos en activos
        if (!empty($idsActivos)) {
            $conflictosActivos = DetallesReservasModels::whereHas('reserva', function($query) {
                    $query->whereIn('res_estado_reserva', ['Pendiente', 'Aprobada', 'pendiente', 'aprobada']);
                })
                ->whereIn('act_id', $idsActivos)
                ->where('det_re_fecha_ini', '<', $fechaFin)
                ->where('det_re_fecha_fin', '>', $fechaInicio)
                ->with('activo')
                ->get();

            foreach ($conflictosActivos as $detalle) {
                if ($detalle->activo) {
                    $recursosOcupadosNombres[] = $detalle->activo->act_nombre;
                }
            }
        }

        // 2. Revisar conflictos en aulas
        if (!empty($idsAulas) && $aulaExplícitaEnCarrito) {
            $conflictosAulas = DetallesReservasModels::whereHas('reserva', function($query) {
                    $query->whereIn('res_estado_reserva', ['Pendiente', 'Aprobada', 'pendiente', 'aprobada']);
                })
                ->where(function($q) use ($idsAulas) {
                    $q->whereIn('aula_id', $idsAulas)
                    ->orWhereIn('det_re_aula_destino_act', $idsAulas);
                })
                ->where('det_re_fecha_ini', '<', $fechaFin)
                ->where('det_re_fecha_fin', '>', $fechaInicio)
                ->get();

            foreach ($conflictosAulas as $detalle) {
                $idAulaEncontrada = $detalle->aula_id ?? $detalle->det_re_aula_destino_act;
                if ($idAulaEncontrada) {
                    $aulaObj = AulasModels::where('aula_id', $idAulaEncontrada)->first();
                    if ($aulaObj) {
                        $recursosOcupadosNombres[] = $aulaObj->aula_nombre;
                    }
                }
            }
        }

        if (!empty($recursosOcupadosNombres)) {
            $listaNombres = implode(', ', array_unique($recursosOcupadosNombres));

            return redirect()->route('reservas.paso2')
                ->withErrors([
                    'res_hora_inicio' => "⚠️ Los siguientes recursos o aulas ya se encuentran reservados en ese horario: {$listaNombres}. Por favor seleccione otra hora."
                ])
                ->withInput();
        }

        $reserva = ReservasModels::create([
            'usu_id'             => auth()->id(),      
            'res_estado_reserva' => 'Pendiente',
            'res_fecha_creacion' => now()->toDateString(),
            'res_motivo'         => $datosReserva['res_motivo'] ?? 'Sin motivo especificado',
        ]);

        $aulaDestinoFinal = $aulaIdReal;

        // 1. Guardar activos (siempre con su aula de destino, pero con aula_id en NULL para no fusionar líneas)
        foreach ($idsActivos as $idActivo) {
            DetallesReservasModels::create([
                'res_id'                    => $reserva->res_id,
                'act_id'                    => $idActivo, 
                'det_re_fecha_ini'          => $fechaInicio, 
                'det_re_fecha_fin'          => $fechaFin,      
                'det_re_aula_destino_act'   => $aulaDestinoFinal, 
                'aula_id'                   => null,   
            ]);
        }

        // 2. Guardar aulas independientes o de reserva mixta en una línea aparte
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

        // Respaldo por si fue mixta pero el ID venía suelto en aula_uso y no en idsAulas directamente
        if (empty($idsAulas) && $aulaExplícitaEnCarrito && $aulaIdReal && !empty($idsActivos)) {
            DetallesReservasModels::create([
                'res_id'                    => $reserva->res_id,
                'act_id'                    => null, 
                'det_re_fecha_ini'          => $fechaInicio, 
                'det_re_fecha_fin'          => $fechaFin,      
                'det_re_aula_destino_act'   => null, 
                'aula_id'                   => $aulaIdReal, 
            ]);
        }

        session()->forget(['reserva', 'reserva_temp']);

        return redirect()->route($dashboardRoute)->with('success', '¡Solicitud de reserva procesada con éxito! Quedará pendiente de aprobación.');
    }

    public function indexSecretaria()
    {
        // Traemos todas las reservas de forma general para el calendario y pestañas de aprobadas/rechazadas
        $reservas = ReservasModels::with(['usuario', 'detalles.activo', 'detalles.aula'])->orderBy('res_id', 'desc')->get();

        // OBTENEMOS ESTRICTAMENTE LAS 10 MÁS RECIENTES QUE ESTÉN PENDIENTES
        $pendientes = ReservasModels::with(['usuario', 'detalles.activo', 'detalles.aula'])
            ->whereRaw("LOWER(TRIM(res_estado_reserva)) = 'pendiente'")
            ->orderBy('res_id', 'desc')
            ->take(6) // <-- AQUÍ ESTÁ EL LÍMITE DE 10
            ->get();

        $aprobadas  = $reservas->filter(fn($r) => in_array(strtolower(trim($r->res_estado_reserva ?? '')), ['aprobada', 'aprobado']));
        $rechazadas = $reservas->filter(fn($r) => in_array(strtolower(trim($r->res_estado_reserva ?? '')), ['rechazada', 'rechazado']));

        return view('reservas.index', compact('reservas', 'pendientes', 'aprobadas', 'rechazadas'));
    }

    public function aprobar($id)
    {
        // Cargamos la reserva con sus detalles para verificar fecha y hora exacta
        $reserva = ReservasModels::with('detalles')->findOrFail($id);
        
        // Obtenemos la fecha y hora de fin (o de inicio) del detalle o de la reserva principal
        $fechaHoraFin = optional($reserva->detalles->first())->det_re_fecha_fin 
                        ?? optional($reserva->detalles->first())->det_re_fecha_ini 
                        ?? ($reserva->res_fecha_fin ?? $reserva->res_fecha_inicio);

        // Validamos si la fecha y hora ya pasaron respecto al momento actual (now)
        if ($fechaHoraFin && Carbon::parse($fechaHoraFin)->lt(Carbon::now()) && strtolower($reserva->res_estado_reserva ?? 'pendiente') === 'pendiente') {
            return redirect()->back()->with('error', 'No se puede aceptar una reserva cuyo horario ya ha finalizado o transcurrido.');
        }

        $reserva->res_estado_reserva = 'Aprobada'; 
        $reserva->save();

        return redirect()->back()->with('success', 'Reserva aprobada exitosamente.');
    }

    public function rechazar($id)
    {
        $reserva = ReservasModels::findOrFail($id);
        $reserva->res_estado_reserva = 'Rechazada'; // Ajusta al texto que uses (ej: 'Rechazada')
        $reserva->save();

        return redirect()->back()->with('success', 'Reserva rechazada.');
    }

    public function revertir($id)
    {
        $reserva = ReservasModels::findOrFail($id);
        $reserva->res_estado_reserva = 'Pendiente';
        $reserva->save();

        return redirect()->back();
    }
}