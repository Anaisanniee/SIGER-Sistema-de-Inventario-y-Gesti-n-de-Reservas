<?php

namespace App\Http\Controllers;
use App\Models\ActivosModels;
use App\Models\AulasModels;
use App\Models\ReservasModels;

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

        // Guardamos el ID del recurso y su tipo en la sesión temporalmente
        $request->session()->put('reserva.recurso_id', $id);
        // Puedes identificar si es activo o aula según tu lógica
        $request->session()->put('reserva.tipo_recurso', 'activo'); 

        // Redirigimos al Paso 2 (cuando lo tengan listo)
        return redirect()->route('reservas.paso2');
    }

    public function paso2(Request $request)
    {
        $recursoId = session('reserva.recurso_id');
        $tipoRecurso = session('reserva.tipo_recurso', 'activo');

        $recurso = null;
        if ($recursoId) {
            if ($tipoRecurso === 'aula') {
                $recurso = \App\Models\AulasModels::find($recursoId); 
            } else {
                $recurso = \App\Models\ActivosModels::find($recursoId); 
            }
        }

        // 1. Traemos todas las aulas registradas en el sistema
        $aulas = \App\Models\AulasModels::all();

        // 2. Las enviamos a la vista junto con lo que ya tenías
        return view('reservas.crear.paso2', compact('recurso', 'tipoRecurso', 'aulas'));
    }

    public function guardarPaso2(Request $request)
    {
        // Validaciones de seguridad en el backend
        $request->validate([
            'res_fecha_inicio' => 'required|date|after_or_equal:today',
            'res_fecha_fin'    => 'required|date|after_or_equal:res_fecha_inicio',
            'res_hora_inicio'  => 'required',
            'res_hora_fin'     => 'required|after:res_hora_inicio',
            'res_motivo'       => 'required|string',
        ], [
            'res_fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser una fecha pasada.',
            'res_fecha_fin.after_or_equal'    => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
            'res_hora_fin.after'              => 'La hora de fin debe ser posterior a la hora de inicio.',
        ]);

        // Combinamos la fecha con su respectiva hora para que quede un formato completo (Ej: 2026-07-26 08:00:00)
        $fechaInicioCompleta = $request->res_fecha_inicio . ' ' . $request->res_hora_inicio . ':00';
        $fechaFinCompleta    = $request->res_fecha_fin . ' ' . $request->res_hora_fin . ':00';

        // Guardamos en sesión ya unificado
        session([
            'reserva.res_fecha_inicio' => $fechaInicioCompleta,
            'reserva.res_fecha_fin'    => $fechaFinCompleta,
            'reserva.res_motivo'       => $request->res_motivo,
            'reserva.aula_uso'         => $request->input('aula_uso'),
        ]);

        return redirect()->route('reservas.paso3');
    }

    public function paso3(Request $request)
    {
        // Recuperamos todos los datos almacenados en la sesión de la reserva
        $datosReserva = session('reserva', []);
        
        // Aquí puedes cargar la vista del paso 3 pasándole los datos acumulados
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

        // 2. Guardamos la cabecera principal en la tabla 'reservas'
        $reserva = \App\Models\ReservasModels::create([
            'usu_id'             => auth()->id() ?? 1, // Si no hay sesión, usa el ID 1 por defecto para probar
            'res_estado_reserva' => 'Pendiente',
            'res_fecha_creacion' => now()->toDateString(),
            'res_motivo'         => $datosReserva['res_motivo'] ?? 'Sin motivo especificado',
        ]);

        // Lógica para resolver el ID del aula de forma segura usando 'aula_id'
        $aulaIngresada = $datosReserva['aula_uso'] ?? null;
        $aulaIdReal = 1; // Valor por defecto por seguridad

        if ($aulaIngresada) {
            $aulaObj = \App\Models\AulasModels::where('aula_id', $aulaIngresada)
                        ->orWhere('aula_nombre', $aulaIngresada)
                        ->first();
            if ($aulaObj) {
                $aulaIdReal = $aulaObj->aula_id; 
            }
        }

        // 3. Guardamos los datos específicos en la tabla 'detalles_reservas'
        \App\Models\DetallesReservasModels::create([
            'res_id'                  => $reserva->res_id,
            'act_id'                  => $datosReserva['recurso_id'] ?? 1,
            'det_re_fecha_ini'        => $datosReserva['res_fecha_inicio'] ?? now(), // Aquí ya vendrá con la hora unida
            'det_re_fecha_fin'        => $datosReserva['res_fecha_fin'] ?? now(),       // Aquí también
            'det_re_aula_destino_act' => $aulaIdReal,
            'aula_id'                 => 1,
        ]);

        // 4. Limpiamos la sesión de la reserva para liberar memoria
        session()->forget('reserva');

        // 5. Redirigimos al usuario al listado con un mensaje de éxito
        return redirect()->route('dashboard.docente')->with('success', '¡Reserva solicitada con éxito! Quedará pendiente de aprobación por el administrador.');
    }

    public function indexSecretaria()
    {
        // Obtenemos las reservas reales de la base de datos con sus relaciones
        $reservas = \App\Models\ReservasModels::with([
                        'usuario', 
                        'detalles.activo', // Relación con el recurso o activo (ajusta el nombre si es 'recurso' o 'act')
                        'detalles.aula'    // Relación con el aula
                    ])
                    ->orderBy('res_id', 'desc')
                    ->get();

        return view('reservas.index', compact('reservas'));
    }
}