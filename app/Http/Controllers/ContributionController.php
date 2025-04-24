<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $goalId)
    {
        $goal = Goal::findOrFail($goalId);

        // Verificar que la meta pertenece al usuario
        if ($goal->user_id !== $request->user()->user_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $contributions = $goal->contributions;
        return response()->json($contributions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $goalId)
    {
        $goal = Goal::findOrFail($goalId);

        if ($goal->user_id !== $request->user()->user_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'remaining_amount' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $contribution = $goal->contributions()->create([
            'amount' => $request->amount,
            'remaining_amount' => $request->remaining_amount ?? ($goal->target_amount - ($goal->total_contributions + $request->amount))
        ]);

        return response()->json([
            'message' => 'Aporte creado exitosamente',
            'data' => $contribution
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $goalId, $id)
    {
        $contribution = Contribution::where('goal_id', $goalId)
            ->where('contribution_id', $id)
            ->firstOrFail();

        $goal = Goal::findOrFail($goalId);

        if ($goal->user_id !== $request->user()->user_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($contribution);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $goalId, $id)
    {
        $contribution = Contribution::where('goal_id', $goalId)
            ->where('contribution_id', $id)
            ->firstOrFail();

        $goal = Goal::findOrFail($goalId);

        if ($goal->user_id !== $request->user()->user_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'sometimes|numeric|min:0.01',
            'remaining_amount' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $contribution->update($validator->validated());

        return response()->json([
            'message' => 'Aporte actualizado exitosamente',
            'data' => $contribution
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $goalId, $id)
    {
        $contribution = Contribution::where('goal_id', $goalId)
            ->where('contribution_id', $id)
            ->firstOrFail();

        $goal = Goal::findOrFail($goalId);

        if ($goal->user_id !== $request->user()->user_id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $contribution->delete();

        return response()->json([
            'message' => 'Aporte eliminado exitosamente'
        ]);
    }
}
