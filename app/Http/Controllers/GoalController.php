<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class GoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $goals = $request->user()
            ->goals()
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($goals);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'goal_name' => 'required|string|max:100',
            'target_amount' => 'required|numeric|min:0.01',
            'deadline_date' => 'nullable|date',
            'goal_state' => 'sometimes|in:pendiente,cumplido,cancelado',
            'category_id' => 'nullable|exists:categories,category_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Establecer estado por defecto si no se proporciona
        $validatedData = $validator->validated();
        if (!isset($validatedData['goal_state'])) {
            $validatedData['goal_state'] = 'pendiente';
        }

        $goal = $request->user()->goals()->create($validatedData);

        return response()->json([
            'message' => 'Meta creada exitosamente',
            'data' => $goal
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $goal = Goal::with(['category', 'contributions', 'alerts'])
            ->findOrFail($id);

        if ($goal->user_id !== $request->user()->user_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($goal);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $goal = Goal::findOrFail($id);

        if ($goal->user_id !== $request->user()->user_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'goal_name' => 'sometimes|string|max:100',
            'target_amount' => 'sometimes|numeric|min:0.01',
            'deadline_date' => 'nullable|date',
            'goal_state' => 'sometimes|in:pendiente,cumplido,cancelado',
            'category_id' => 'nullable|exists:categories,category_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $goal->update($validator->validated());

        // Si se marca como cumplido, verificar si se alcanzó el monto objetivo
        if ($request->has('goal_state') && $request->goal_state === 'cumplido') {
            $totalContributions = $goal->contributions()->sum('amount');
            if ($totalContributions < $goal->target_amount) {
                return response()->json([
                    'message' => 'No se puede marcar como cumplido: el total de aportes no alcanza el monto objetivo',
                    'data' => $goal
                ], 422);
            }
        }

        return response()->json([
            'message' => 'Meta actualizada exitosamente',
            'data' => $goal->fresh() // Devuelve los datos actualizados
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $goal = Goal::findOrFail($id);

        if ($goal->user_id !== $request->user()->user_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $goal->delete();

        return response()->json([
            'message' => 'Meta eliminada exitosamente'
        ]);
    }
}
