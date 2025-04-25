<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Contribution;
use Carbon\Carbon;
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

    /**
     * Calcula el ahorro estimado necesario por semana/mes para alcanzar la meta
     */
    public function getEstimatedSavings($goalId, $period = 'month')
    {
        $user = Auth::user();

        try {
            $goal = Goal::with(['contributions'])
                ->where('goal_id', $goalId)  // Cambiado de 'id' a 'goal_id'
                ->where('user_id', $user->user_id)  // Cambiado de 'id' a 'user_id'
                ->firstOrFail();

            // Resto del método permanece igual...
            if ($goal->goal_state !== 'pendiente') {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'estimated_savings' => 0,
                        'period' => $period,
                        'message' => 'La meta ya está ' . ($goal->goal_state === 'cumplido' ? 'cumplida' : 'cancelada')
                    ]
                ]);
            }

            $totalContributions = $goal->contributions->sum('amount');
            $remainingAmount = max($goal->target_amount - $totalContributions, 0);

            if (empty($goal->deadline_date)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'estimated_savings' => null,
                        'period' => $period,
                        'message' => 'No hay fecha límite definida para calcular el ahorro estimado'
                    ]
                ]);
            }

            $now = now();
            $deadline = Carbon::parse($goal->deadline_date);

            if ($now->greaterThan($deadline)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'estimated_savings' => $remainingAmount,
                        'period' => 'immediately',
                        'message' => 'La fecha límite ya pasó, necesitas ahorrar el total restante inmediatamente'
                    ]
                ]);
            }

            $periodsRemaining = $period === 'week'
                ? $now->diffInWeeks($deadline)
                : $now->diffInMonths($deadline);

            $periodsRemaining = $periodsRemaining ?: 1;

            return response()->json([
                'success' => true,
                'data' => [
                    'estimated_savings' => round($remainingAmount / $periodsRemaining, 2),
                    'period' => $period,
                    'periods_remaining' => $periodsRemaining,
                    'remaining_amount' => $remainingAmount,
                    'deadline_date' => $goal->deadline_date,
                    'current_amount' => $totalContributions,
                    'target_amount' => $goal->target_amount
                ]
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Meta no encontrada o no tienes permisos para acceder a ella'
            ], 404);
        }
    }

    /**
     * Verifica si la meta está en riesgo según el ritmo actual de ahorro
     */
    public function checkGoalRisk($goalId)
    {
        $user = Auth::user();

        $goal = Goal::with(['contributions'])
            ->where('user_id', $user->user_id)
            ->where('goal_id', $goalId)
            ->firstOrFail();

        // Si la meta ya está cumplida o cancelada
        if ($goal->goal_state !== 'pendiente') {
            return response()->json([
                'success' => true,
                'data' => [
                    'is_at_risk' => false,
                    'risk_level' => 'none',
                    'message' => 'La meta ya está ' . ($goal->goal_state === 'cumplido' ? 'cumplida' : 'cancelada')
                ]
            ]);
        }

        $totalContributions = $goal->contributions->sum('amount');
        $remainingAmount = max($goal->target_amount - $totalContributions, 0);

        // Si no hay contribuciones o no hay fecha límite
        if ($goal->contributions->isEmpty() || empty($goal->deadline_date)) {
            return response()->json([
                'success' => true,
                'data' => [
                    'is_at_risk' => false,
                    'risk_level' => 'undetermined',
                    'message' => 'No hay suficientes datos para determinar el riesgo'
                ]
            ]);
        }

        $now = Carbon::now();
        $deadline = Carbon::parse($goal->deadline_date);

        // Si la fecha límite ya pasó
        if ($now->greaterThan($deadline)) {
            return response()->json([
                'success' => true,
                'data' => [
                    'is_at_risk' => true,
                    'risk_level' => 'critical',
                    'message' => 'La fecha límite ya pasó y la meta no se ha cumplido'
                ]
            ]);
        }

        // Calcular tasa de ahorro actual
        $firstContributionDate = Carbon::parse($goal->contributions->min('contribution_date'));
        $daysActive = $now->diffInDays($firstContributionDate) ?: 1;
        $dailySavingsRate = $totalContributions / $daysActive;

        // Calcular días restantes y ahorro proyectado
        $daysRemaining = $now->diffInDays($deadline);
        $projectedSavings = $dailySavingsRate * $daysRemaining;

        // Calcular brecha
        $savingsGap = $remainingAmount - $projectedSavings;
        $gapPercentage = ($remainingAmount > 0) ? ($savingsGap / $remainingAmount) * 100 : 0;

        // Determinar nivel de riesgo
        $isAtRisk = $savingsGap > 0;
        $riskLevel = 'low';

        if ($gapPercentage > 25) {
            $riskLevel = 'moderate';
        }
        if ($gapPercentage > 50) {
            $riskLevel = 'high';
        }
        if ($gapPercentage > 75) {
            $riskLevel = 'critical';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'is_at_risk' => $isAtRisk,
                'risk_level' => $riskLevel,
                'gap_percentage' => round($gapPercentage, 2),
                'current_daily_rate' => round($dailySavingsRate, 2),
                'required_daily_rate' => round($remainingAmount / $daysRemaining, 2),
                'projected_savings' => round($projectedSavings, 2),
                'days_remaining' => $daysRemaining,
                'message' => $isAtRisk
                    ? "El ritmo actual de ahorro no es suficiente para alcanzar la meta (brecha del {$gapPercentage}%)"
                    : "Vas por buen camino para alcanzar la meta"
            ]
        ]);
    }

    /**
     * Obtiene un resumen completo con todos los indicadores para una meta
     */
    public function getGoalFullSummary($goalId)
    {
        $progress = $this->getGoalProgress($goalId)->getData();
        $monthlySavings = $this->getEstimatedSavings($goalId, 'month')->getData();
        $weeklySavings = $this->getEstimatedSavings($goalId, 'week')->getData();
        $riskAnalysis = $this->checkGoalRisk($goalId)->getData();

        return response()->json([
            'success' => true,
            'data' => [
                'progress' => $progress->data,
                'monthly_savings' => $monthlySavings->data,
                'weekly_savings' => $weeklySavings->data,
                'risk_analysis' => $riskAnalysis->data
            ]
        ]);
    }
}
