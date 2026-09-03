<?php
namespace App\Http\Controllers;

use App\Models\ActivosModels;
use App\Models\AulasModels;
use App\Models\ReservasModels;
use App\Models\DetallesReservasModels;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
            foreach ($activosEncontrados as $activo) {
                $recursos->push([
                    'tipo_recurso_real' => 'activo',
                    'id' => $activo->act_id,
                    'nombre' => $activo->act_nombre ?? 'Sin nombre',
                    'serial' => $activo->act_serial ?? 'N/A',
                    'marca' => $activo->act_marca ?? 'N/A',
                    'estado' => $activo->act_estado_fisico ?? 'N/A'
                ]);
            }
        }

        if (!empty($idsAulas)) {
            $aulasEncontradas = AulasModels::whereIn('aula_id', $idsAulas)->get();
            foreach ($aulasEncontradas as $aula) {
                $recursos->push([
                    'tipo_recurso_real' => 'aula',
                    'id' => $aula->aula_id,
                    'nombre' => $aula->aula_nombre ?? 'Sin nombre',
                    'serial' => 'N/A',
                    'marca' => 'N/A',
                    'capacidad' => $aula->aula_capacidad ?? $aula->capacidad ?? 'No especificada', // <--- ¡Añadido aquí!
                    'estado' => $aula->aula_estado ?? 'Disponible'
                ]);
            }
        }

        return $recursos;
    }

    /**
     * Muestra el paso 1 (GET): Carga la vista con los recursos del carrito temporal del usuario.
     */
    public function paso1(Request $request)
    {
        $userId = Auth::id();
        $sessionKey = 'reserva_temp_' . $userId;
        $reservaTemp = session($sessionKey);

        if (!$reservaTemp || empty($reservaTemp['items'])) {
            return redirect()->route('dashboard.docente')
                ->with('error', 'No hay recursos seleccionados para iniciar una reserva.');
        }

        $separados = $this->extraerIdsSeguros($reservaTemp);
        $idsActivos = $separados['activos'];
        $idsAulas = $separados['aulas'];

        if (empty($idsActivos) && empty($idsAulas)) {
            return redirect()->route('dashboard.docente')
                ->with('error', 'No hay recursos válidos seleccionados.');
        }

        $recursos = $this->obtenerRecursosColeccion($idsActivos, $idsAulas);

        if ($recursos->isEmpty()) {
            return redirect()->route('dashboard.docente')
                ->with('error', 'Los recursos solicitados no existen o ya no se encuentran disponibles.');
        }

        $tipoRecurso = $reservaTemp['tipo'] ?? 'mixto';

        // Aseguramos explícitamente el primer recurso y convertimos a objeto por si viene como array
        $primerRecurso = (object) $recursos->first();

        // Parche seguro: Si es un aula y le falta la capacidad, la asignamos desde sus posibles variantes o un valor por defecto
        if (isset($primerRecurso->tipo_recurso_real) && $primerRecurso->tipo_recurso_real === 'aula') {
            if (!isset($primerRecurso->capacidad) || empty($primerRecurso->capacidad)) {
                $primerRecurso->capacidad = $primerRecurso->aula_capacidad ?? $primerRecurso->capacidad_maxima ?? '30';
            }
        }

        return view('reservas.crear.paso1', compact('recursos', 'tipoRecurso', 'primerRecurso'));
    }

    /**
     * Procesa el formulario del paso 1 (POST): Guarda los datos para la siguiente vista (paso 2).
     */
    public function postPaso1(Request $request)
    {
        if ($request->input('confirmacion_recurso') === 'no') {
            return redirect()->route('dashboard.docente')->with('info', 'Has cancelado la selección.');
        }

        $userId = Auth::id();
        $sessionKey = 'reserva_temp_' . $userId;
        $reservaTemp = session($sessionKey);

        if (!$reservaTemp) {
            return redirect()->route('dashboard.docente')->with('error', 'No hay recursos seleccionados.');
        }

        // Extraemos y consultamos reutilizando tus métodos privados
        $separados = $this->extraerIdsSeguros($reservaTemp);
        $idsActivos = $separados['activos'];
        $idsAulas = $separados['aulas'];

        $recursosObjetos = $this->obtenerRecursosColeccion($idsActivos, $idsAulas);
        $tipoRecurso = $reservaTemp['tipo'] ?? 'activo';

        // Guardamos todo en la sesión principal de la reserva para el paso 2
        $request->session()->put('reserva.ids_activos', $idsActivos);
        $request->session()->put('reserva.ids_aulas', $idsAulas);
        $request->session()->put('reserva.tipo_recurso', $tipoRecurso); 
        $request->session()->put('reserva.recursos_objetos', $recursosObjetos);

        return redirect()->route('reservas.paso2');
    }

    public function paso2(Request $request)
    {
        $userId = Auth::id();
        $sessionKey = 'reserva_temp_' . $userId;
        $reservaTemp = session($sessionKey);

        // Si no hay datos en la sesión temporal, redirigimos
        if (!$reservaTemp || empty($reservaTemp['items'])) {
            return redirect()->route('dashboard.docente')
                ->with('error', 'No hay recursos seleccionados para iniciar una reserva.');
        }

        $separados = $this->extraerIdsSeguros($reservaTemp);
        $idsActivos = $separados['activos'];
        $idsAulas = $separados['aulas'];
        $tipoRecurso = $reservaTemp['tipo'] ?? 'activo';

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

        // Aseguramos el primer recurso para que el componente no falle en el paso 2
        $primerItem = $recursos->first();
        $primerRecurso = $primerItem ? (object) $primerItem : null;

        return view('reservas.crear.paso2', compact('recursos', 'tipoRecurso', 'aulas', 'horasOcupadas', 'primerRecurso'));
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

        $userId = auth()->id();
        
        // Limpiamos las variables de sesión incluyendo la clave dinámica del carrito por usuario
        session()->forget([
            'reserva', 
            'reserva_temp', 
            'reserva_temp_' . $userId,
            'carrito',
            'cart'
        ]);

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

    public function notificaciones()
    {
        $usuario = auth()->user();

        $reservas = \App\Models\ReservasModels::where('usu_id', $usuario->usu_id)
            ->whereIn('res_estado_reserva', ['Aprobada', 'Rechazada'])
            ->latest('updated_at')
            ->get();

        $notificaciones = $reservas->map(function ($reserva) {
            $esAprobada = $reserva->res_estado_reserva === 'Aprobada';
            
            // Traemos todos los campos de los detalles, activos y aulas para evitar errores de columnas
            $detalles = \Illuminate\Support\Facades\DB::table('detalles_reservas')
                ->leftJoin('activos', 'detalles_reservas.act_id', '=', 'activos.act_id')
                ->leftJoin('aulas', 'detalles_reservas.aula_id', '=', 'aulas.aula_id') // Probando con aula_id por si acaso
                ->where('detalles_reservas.res_id', $reserva->res_id)
                ->select('detalles_reservas.*', 'activos.*', 'aulas.*')
                ->get();

            $nombresElementos = collect();

            foreach ($detalles as $det) {
                // Buscamos dinámicamente cualquier propiedad que parezca un nombre o título
                foreach ($det as $key => $value) {
                    if (!empty($value) && (str_contains($key, 'nombre') || str_contains($key, 'titulo') || str_contains($key, 'descripcion'))) {
                        // Evitamos agregar IDs o campos que no sean texto descriptivo
                        if (!str_contains($key, 'id')) {
                            $nombresElementos->push($value);
                        }
                    }
                }
            }

            $nombreElemento = $nombresElementos->isNotEmpty() 
                ? $nombresElementos->unique()->join(', ') 
                : 'la reserva #' . $reserva->res_id;

            return [
                'id' => $reserva->res_id,
                'titulo' => $esAprobada ? 'Reserva Aprobada' : 'Reserva Rechazada',
                'mensaje' => "Tu solicitud para " . $nombreElemento . " ha sido " . strtolower($reserva->res_estado_reserva),
                'tipo' => $esAprobada ? 'exito' : 'peligro',
                'icono' => $esAprobada ? 'fa-check-circle' : 'fa-times-circle',
                'fecha' => $reserva->updated_at ? $reserva->updated_at->diffForHumans() : '',
                'leida' => false,
            ];
        });

        return view('notificaciones.index', compact('notificaciones'));
    }
}