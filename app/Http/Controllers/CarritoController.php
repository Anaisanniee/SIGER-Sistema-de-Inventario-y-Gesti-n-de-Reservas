<?php

namespace App\Http\Controllers;
use App\Models\ActivosModels;
use App\Models\AulasModels;

use Illuminate\Http\Request;

class CarritoController extends Controller
{
    /**
     * Recibe los IDs seleccionados desde el componente del carrito y los guarda en sesión.
     */
    public function guardarSeleccionTemporal(Request $request)
    {
        $items = $request->input('items', []);

        if (empty($items)) {
            return response()->json(['success' => false, 'message' => 'No hay items seleccionados.']);
        }

        // Guardamos directamente los items estructurados por el carrito
        session([
            'reserva_temp' => [
                'items' => $items,
                'tipo' => count($items) > 1 ? 'mixto' : ($items[0]['tipo'] ?? 'activo')
            ]
        ]);

        return response()->json(['success' => true]);
    }

    public function paso1(Request $request)
    {
        $reservaTemp = session('reserva_temp');

        if (!$reservaTemp || empty($reservaTemp['items'])) {
            return redirect()->route('dashboard.docente')
                ->with('error', 'No hay recursos seleccionados para iniciar una reserva.');
        }

        $idsActivos = [];
        $idsAulas = [];

        // Extraemos los IDs separándolos estrictamente por su tipo para evitar cruces
        foreach ($reservaTemp['items'] as $item) {
            if (isset($item['tipo']) && isset($item['id'])) {
                if ($item['tipo'] === 'activo') {
                    $idsActivos[] = $item['id'];
                } elseif ($item['tipo'] === 'aula') {
                    $idsAulas[] = $item['id'];
                }
            }
        }

        $recursos = collect();

        // Consultas independientes y seguras
        if (!empty($idsActivos)) {
            $activosEncontrados = ActivosModels::whereIn('act_id', array_unique($idsActivos))->get();
            foreach ($activosEncontrados as $item) {
                $item->tipo_recurso_real = 'activo';
                $recursos->push($item);
            }
        }

        if (!empty($idsAulas)) {
            $aulasEncontradas = AulasModels::whereIn('aula_id', array_unique($idsAulas))->get();
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
}
