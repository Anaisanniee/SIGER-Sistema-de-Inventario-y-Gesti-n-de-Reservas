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
    public function paso1(Request $request, $id = null)
    {
        // 1. Validar que exista un ID
        if (!$id) {
            return redirect()->route('dashboard.docente')
                ->with('error', 'Debes seleccionar un recurso válido para iniciar una reserva.');
        }

        // 2. Validar que el tipo sea estrictamente 'activo' o 'aula'
        $tipoRecurso = strtolower($request->query('tipo', 'activo'));
        $tiposPermitidos = ['activo', 'aula'];

        if (!in_array($tipoRecurso, $tiposPermitidos)) {
            return redirect()->route('dashboard.docente')
                ->with('error', 'El tipo de recurso especificado no es válido.');
        }

        // 3. Búsqueda segura según el tipo especificado
        if ($tipoRecurso === 'aula') {
            $recurso = AulasModels::find($id);
        } else {
            $recurso = ActivosModels::find($id);
            
            // Si no lo encuentra en activos, busca en aulas por respaldo
            if (!$recurso) {
                $recurso = AulasModels::find($id);
                $tipoRecurso = 'aula';
            }
        }

        // 4. Validar que el recurso realmente exista en la base de datos
        if (!$recurso) {
            return redirect()->route('dashboard.docente')
                ->with('error', 'El recurso solicitado no existe o ya no se encuentra disponible.');
        }

        return view('reservas.crear.paso1', compact('recurso', 'tipoRecurso'));
    }

    public function postPaso1(Request $request, $id)
    {
        // Verificamos si el usuario confirmó que sí es el recurso
        if ($request->input('confirmacion_recurso') === 'no') {
            return redirect()->route('dashboard.docente')->with('info', 'Has cancelado la selección.');
        }

        // Capturamos el tipo de recurso que viene del formulario, o lo determinamos buscando en la BD si no viene
        $tipoRecurso = $request->input('tipo_recurso');
        
        if (!$tipoRecurso) {
            // Verificamos si existe en aulas, de lo contrario es activo
            $tipoRecurso = AulasModels::where('aula_id', $id)->exists() ? 'aula' : 'activo';
        }

        // Guardamos el ID del recurso, su tipo real y el objeto en la sesión
        $recursoObjeto = ($tipoRecurso === 'aula') ? AulasModels::find($id) : ActivosModels::find($id);

        $request->session()->put('reserva.recurso_id', $id);
        $request->session()->put('reserva.tipo_recurso', $tipoRecurso); 
        $request->session()->put('reserva.recurso_objeto', $recursoObjeto);

        // Redirigimos al Paso 2
        return redirect()->route('reservas.paso2');
    }

    public function paso2(Request $request)
    {
        $recursoId = session('reserva.recurso_id');
        $tipoRecurso = session('reserva.tipo_recurso', 'activo');

        $recurso = null;
        $horasOcupadas = [];

        if ($recursoId) {
            if ($tipoRecurso === 'aula') {
                $recurso = \App\Models\AulasModels::find($recursoId); 
            } else {
                $recurso = \App\Models\ActivosModels::find($recursoId); 
            }

            // Consultar los rangos de horas ya reservados para bloquearlos
            $campoFiltro = ($tipoRecurso === 'aula') ? 'aula_id' : 'act_id';
            
            $reservasExistentes = \App\Models\DetallesReservasModels::where($campoFiltro, $recursoId)
                ->whereHas('reserva', function($query) {
                    $query->whereIn('res_estado_reserva', ['Pendiente', 'Aprobada', 'pendiente', 'aprobada']);
                })
                ->get(['det_re_fecha_ini', 'det_re_fecha_fin']);

            // Formateamos los rangos para usarlos fácilmente en JavaScript o validación extra
            foreach ($reservasExistentes as $res) {
                $horasOcupadas[] = [
                    'inicio' => $res->det_re_fecha_ini,
                    'fin' => $res->det_re_fecha_fin
                ];
            }
        }

        $aulas = \App\Models\AulasModels::all();

        return view('reservas.crear.paso2', compact('recurso', 'tipoRecurso', 'aulas', 'horasOcupadas'));
    }

    public function guardarPaso2(Request $request)
    {
        // Obtener el recurso actual de la sesión para saber su tipo
        $recurso = session('reserva.recurso_objeto', null);
        $tipoRecurso = session('reserva.tipo_recurso');
        
        if (!$tipoRecurso && $recurso) {
            $tipoRecurso = isset($recurso->aula_nombre) ? 'aula' : 'activo';
        }

        // 1. Validar los campos de la vista
        $reglasValidacion = [
            'res_fecha_inicio' => 'required|date',
            'res_fecha_fin'    => 'required|date|after_or_equal:res_fecha_inicio',
            'res_hora_inicio'  => 'required',
            'res_hora_fin'     => 'required',
            'res_motivo'       => 'required|string|max:255',
        ];

        // CORRECCIÓN: Solo exigimos 'aula_uso' si el tipo de recurso NO es un aula
        if ($tipoRecurso !== 'aula') {
            $reglasValidacion['aula_uso'] = 'required';
        }

        $request->validate($reglasValidacion);

        // 2. Procesar y formatear horas asegurando los segundos (:00)
        $horaInicioInput = $request->res_hora_inicio;
        $horaFinInput    = $request->res_hora_fin;

        $horaInicio = strlen($horaInicioInput) === 5 ? $horaInicioInput . ':00' : $horaInicioInput;
        $horaFin    = strlen($horaFinInput) === 5 ? $horaFinInput . ':00' : $horaFinInput;

        // 3. Unir correctamente la fecha con su respectiva hora de inicio y fin
        $fechaHoraInicio = $request->res_fecha_inicio . ' ' . $horaInicio;
        $fechaHoraFin    = $request->res_fecha_fin . ' ' . $horaFin;

        // 4. Guardar en la sesión tanto las fechas completas como las horas individuales por respaldo
        session([
            'reserva.res_fecha_inicio' => $fechaHoraInicio,
            'reserva.res_fecha_fin'    => $fechaHoraFin,
            'reserva.res_hora_inicio'  => $horaInicio,
            'reserva.res_hora_fin'     => $horaFin,
            'reserva.res_motivo'       => $request->res_motivo,
            'reserva.aula_uso'         => $request->aula_uso ?? null,
        ]);

        // Redireccionar al siguiente paso (Paso 3)
        return redirect()->route('reservas.paso3');
    }

    public function paso3(Request $request)
    {
        // Recuperamos todos los datos almacenados en la sesión de la reserva
        $datosReserva = session('reserva', []);
        
        return view('reservas.crear.paso3', compact('datosReserva'));
    }

    public function guardarPaso3(Request $request)
    {
        // 1. Recuperamos todos los datos almacenados en la sesión de la reserva
        $datosReserva = session('reserva', []);

        // Verificamos por seguridad que existan datos en la sesión
        if (empty($datosReserva)) {
            return redirect()->route('dashboard.docente')->with('error', 'No hay datos de reserva en proceso. Por favor, comience de nuevo.');
        }

        // Determinamos el tipo de recurso de forma segura desde la sesión
        $tipoRecurso = $datosReserva['tipo_recurso'] ?? 'activo';
        if (!isset($datosReserva['tipo_recurso']) && isset($datosReserva['recurso_objeto'])) {
            $tipoRecurso = isset($datosReserva['recurso_objeto']->aula_nombre) ? 'aula' : 'activo';
        }

        // Rescatamos de forma robusta y específica el ID real según el tipo de recurso
        if ($tipoRecurso === 'aula') {
            $recursoIdReal = $datosReserva['recurso_id'] 
                ?? ($datosReserva['recurso_objeto']->aula_id ?? null)
                ?? ($datosReserva['recurso_objeto']->id ?? 1);
        } else {
            $recursoIdReal = $datosReserva['recurso_id'] 
                ?? ($datosReserva['recurso_objeto']->act_id ?? null)
                ?? ($datosReserva['recurso_objeto']->id ?? 1);
        }

        $aulaIdReal = 1; // Valor por defecto por seguridad

        // 2. Lógica para rescatar el aula elegida en el formulario del Paso 2
        if ($tipoRecurso === 'activo') {
            $aulaIngresada = $datosReserva['aula_uso'] ?? null;

            if (!empty($aulaIngresada)) {
                // Buscamos usando únicamente las columnas reales de tu tabla de aulas
                $aulaObj = \App\Models\AulasModels::where('aula_id', $aulaIngresada)
                            ->orWhere('aula_nombre', 'LIKE', '%' . trim($aulaIngresada) . '%')
                            ->first();

                if ($aulaObj) {
                    $aulaIdReal = $aulaObj->aula_id;
                } elseif (is_numeric($aulaIngresada)) {
                    $aulaIdReal = intval($aulaIngresada);
                }
            }
        } else {
            // Si es reserva directa de aula, el aula de destino es el mismo recurso seleccionado
            $aulaIdReal = $recursoIdReal;
        }

        $fechaInicio = $datosReserva['res_fecha_inicio'] ?? now();
        $fechaFin = $datosReserva['res_fecha_fin'] ?? now();

        // Normalización y control para Fecha Inicio
        if ($fechaInicio && !str_contains($fechaInicio, ' ')) {
            $horaIni = $datosReserva['res_hora_inicio'] ?? '08:00:00';
            $fechaInicio = trim($fechaInicio) . ' ' . (strlen($horaIni) === 5 ? $horaIni . ':00' : $horaIni);
        } elseif (strlen($fechaInicio) === 16) {
            $fechaInicio .= ':00';
        }

        // Normalización y control para Fecha Fin
        if ($fechaFin && !str_contains($fechaFin, ' ')) {
            $horaFin = $datosReserva['res_hora_fin'] ?? '12:00:00';
            $fechaFin = trim($fechaFin) . ' ' . (strlen($horaFin) === 5 ? $horaFin . ':00' : $horaFin);
        } elseif (strlen($fechaFin) === 16) {
            $fechaFin .= ':00';
        }

        // === 3. VALIDACIÓN DE CRUCE DE HORARIOS ===
        $conflicto = \App\Models\DetallesReservasModels::whereHas('reserva', function($query) {
                $query->whereIn('res_estado_reserva', ['Pendiente', 'Aprobada', 'pendiente', 'aprobada']);
            })
            ->where(function($query) use ($tipoRecurso, $recursoIdReal) {
                if ($tipoRecurso === 'activo') {
                    $query->where('act_id', $recursoIdReal);
                } else {
                    $query->where('aula_id', $recursoIdReal);
                }
            })
            ->where(function($query) use ($fechaInicio, $fechaFin) {
                $query->where('det_re_fecha_ini', '<', $fechaFin)
                      ->where('det_re_fecha_fin', '>', $fechaInicio);
            })
            ->exists();

        if ($conflicto) {
            return redirect()->route('reservas.paso2')
                ->withErrors(['res_hora_inicio' => '¡El recurso o aula ya se encuentra reservado en ese intervalo de horario! Por favor seleccione otra hora.'])
                ->withInput();
        }
        // ==========================================

        // 4. Guardamos la cabecera principal en la tabla 'reservas'
        $reserva = \App\Models\ReservasModels::create([
            'usu_id'             => auth()->id() ?? 1, 
            'res_estado_reserva' => 'Pendiente',
            'res_fecha_creacion' => now()->toDateString(),
            'res_motivo'         => $datosReserva['res_motivo'] ?? 'Sin motivo especificado',
        ]);

        $activoPorDefecto = \App\Models\ActivosModels::value('act_id') ?? 1;

        // 5. Guardamos los datos específicos en la tabla 'detalles_reservas' (Ya preparado para campos NULL)
        \App\Models\DetallesReservasModels::create([
            'res_id'                    => $reserva->res_id,
            'act_id'                    => ($tipoRecurso === 'activo') ? $recursoIdReal : null, // Si es aula, guardará NULL
            'det_re_fecha_ini'          => $fechaInicio, 
            'det_re_fecha_fin'          => $fechaFin,      
            'det_re_aula_destino_act'   => ($tipoRecurso === 'activo') ? $aulaIdReal : null, 
            'aula_id'                   => ($tipoRecurso === 'aula') ? $recursoIdReal : null,   // Si es activo, guardará NULL
        ]);

        // 6. Limpiamos la sesión de la reserva para liberar memoria
        session()->forget('reserva');

        // 7. Redirigimos al usuario al listado con un mensaje de éxito
        return redirect()->route('dashboard.docente')->with('success', '¡Reserva solicitada con éxito! Quedará pendiente de aprobación por el administrador.');
    }

    public function indexSecretaria()
    {
        // Obtenemos las reservas reales de la base de datos con sus relaciones
        $reservas = \App\Models\ReservasModels::with([
                    'usuario', 
                    'detalles.activo', 
                    'detalles.aula'    
                ])
                ->orderBy('res_id', 'desc')
                ->get();

        return view('reservas.index', compact('reservas'));
    }
}