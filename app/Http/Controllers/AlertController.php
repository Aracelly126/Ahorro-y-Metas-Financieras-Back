<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlertController extends Controller
{
    /**
     * Obtener todas las alertas de una meta específica
     */
    public function index($goalId)
    {
        // Obtener el usuario autenticado desde Sanctum
        $user = auth()->user();

        // Buscar la meta y verificar que exista
        $goal = Goal::find($goalId);

        if (!$goal) {
            return response()->json([
                'message' => 'Meta no encontrada'
            ], 404);
        }

        // Verificar que la meta pertenece al usuario autenticado
        if ($goal->user_id !== $user->user_id) {
            return response()->json([
                'message' => 'No tienes permisos para ver estas alertas'
            ], 403);
        }

        // Obtener las alertas ordenadas por fecha descendente
        $alerts = $goal->alerts()
                     ->orderBy('alert_date', 'desc')
                     ->get();

        return response()->json($alerts);
    }

    /**
     * Crear una nueva alerta para una meta
     */
    public function store(Request $request, $goalId)
    {
        $user = auth()->user();
        $goal = Goal::find($goalId);

        if (!$goal) {
            return response()->json(['message' => 'Meta no encontrada'], 404);
        }

        // Verificar que la meta pertenece al usuario
        if ($goal->user_id !== $user->user_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'alert_name' => 'required|string|max:100',
            'alert_message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $alert = $goal->alerts()->create([
            'alert_name' => $request->alert_name,
            'alert_message' => $request->alert_message,
            // alert_date se establece automáticamente por el useCurrent()
        ]);

        return response()->json([
            'message' => 'Alerta creada exitosamente',
            'data' => $alert
        ], 201);
    }

    /**
     * Mostrar una alerta específica
     */
    public function show($goalId, $id)
    {
        $user = auth()->user();

        $alert = Alert::where('goal_id', $goalId)
                    ->where('alert_id', $id)
                    ->first();

        if (!$alert) {
            return response()->json(['message' => 'Alerta no encontrada'], 404);
        }

        $goal = Goal::find($goalId);

        if ($goal->user_id !== $user->user_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($alert);
    }

    /**
     * Actualizar una alerta
     */
    public function update(Request $request, $goalId, $id)
    {
        $user = auth()->user();

        $alert = Alert::where('goal_id', $goalId)
                    ->where('alert_id', $id)
                    ->first();

        if (!$alert) {
            return response()->json(['message' => 'Alerta no encontrada'], 404);
        }

        $goal = Goal::find($goalId);

        if ($goal->user_id !== $user->user_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'alert_name' => 'sometimes|string|max:100',
            'alert_message' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $alert->update($validator->validated());

        return response()->json([
            'message' => 'Alerta actualizada exitosamente',
            'data' => $alert
        ]);
    }

    /**
     * Eliminar una alerta
     */
    public function destroy($goalId, $id)
    {
        $user = auth()->user();

        $alert = Alert::where('goal_id', $goalId)
                    ->where('alert_id', $id)
                    ->first();

        if (!$alert) {
            return response()->json(['message' => 'Alerta no encontrada'], 404);
        }

        $goal = Goal::find($goalId);

        if ($goal->user_id !== $user->user_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $alert->delete();

        return response()->json([
            'message' => 'Alerta eliminada exitosamente'
        ]);
    }
}
