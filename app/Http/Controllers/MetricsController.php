<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MetricsController extends Controller
{
    /**
     * Obtiene el progreso de todas las metas del usuario autenticado
     */
    public function getGoalsProgress()
    {
        $user = Auth::user();

        $goals = Goal::with(['contributions'])
            ->where('user_id', $user->user_id)
            ->get();

        $progressData = $goals->map(function ($goal) {
            $totalContributions = $goal->contributions->sum('amount');
            $percentage = ($goal->target_amount > 0)
                ? ($totalContributions / $goal->target_amount) * 100
                : 0;

            // Aseguramos que el porcentaje no exceda 100%
            $progressPercentage = min(100, round($percentage, 2));

            return [
                'goal_id' => $goal->goal_id,
                'goal_name' => $goal->goal_name,
                'target_amount' => $goal->target_amount,
                'current_amount' => $totalContributions,
                'progress_percentage' => $progressPercentage,
                'remaining_amount' => max($goal->target_amount - $totalContributions, 0),
                'goal_state' => $goal->goal_state,
                'deadline_date' => $goal->deadline_date,
                'category_id' => $goal->category_id
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $progressData
        ]);
    }

    /**
     * Obtiene el progreso de una meta específica
     */
    public function getGoalProgress($goalId)
    {
        $user = Auth::user();

        $goal = Goal::with(['contributions'])
            ->where('user_id', $user->user_id)
            ->where('goal_id', $goalId)
            ->firstOrFail();

        $totalContributions = $goal->contributions->sum('amount');
        $percentage = ($goal->target_amount > 0)
            ? ($totalContributions / $goal->target_amount) * 100
            : 0;

        $progressPercentage = min(100, round($percentage, 2));

        $response = [
            'goal_id' => $goal->goal_id,
            'goal_name' => $goal->goal_name,
            'target_amount' => $goal->target_amount,
            'current_amount' => $totalContributions,
            'progress_percentage' => $progressPercentage,
            'remaining_amount' => max($goal->target_amount - $totalContributions, 0),
            'goal_state' => $goal->goal_state,
            'deadline_date' => $goal->deadline_date,
            'category_id' => $goal->category_id,
            'contributions_count' => $goal->contributions->count()
        ];

        return response()->json([
            'success' => true,
            'data' => $response
        ]);
    }

    /**
     * Obtiene métricas resumidas de todas las metas
     */
    public function getGoalsSummary()
    {
        $user = Auth::user();

        $goals = Goal::with(['contributions'])
            ->where('user_id', $user->user_id)
            ->get();

        $totalGoals = $goals->count();
        $completedGoals = $goals->where('goal_state', 'cumplido')->count();
        $pendingGoals = $goals->where('goal_state', 'pendiente')->count();
        $canceledGoals = $goals->where('goal_state', 'cancelado')->count();

        $totalTargetAmount = $goals->sum('target_amount');
        $totalContributions = $goals->sum(function ($goal) {
            return $goal->contributions->sum('amount');
        });

        $percentage = ($totalTargetAmount > 0)
            ? ($totalContributions / $totalTargetAmount) * 100
            : 0;

        $overallProgress = min(100, round($percentage, 2));

        return response()->json([
            'success' => true,
            'data' => [
                'total_goals' => $totalGoals,
                'completed_goals' => $completedGoals,
                'pending_goals' => $pendingGoals,
                'canceled_goals' => $canceledGoals,
                'total_target_amount' => $totalTargetAmount,
                'total_contributions' => $totalContributions,
                'overall_progress' => $overallProgress,
                'remaining_amount' => max($totalTargetAmount - $totalContributions, 0)
            ]
        ]);
    }
}
