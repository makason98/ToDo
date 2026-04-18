<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $today = now()->toDateString();

        $activeTasksCount = $user->tasks()->where('completed', false)->count();
        $overdueCount = $user->tasks()->where('completed', false)
            ->whereNotNull('due_date')
            ->where('due_date', '<', $today)->count();

        $completedToday = $user->tasks()->where('completed', true)
            ->whereDate('completed_at', $today)->count();
        $completedThisWeek = $user->tasks()->where('completed', true)
            ->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $completedThisMonth = $user->tasks()->where('completed', true)
            ->whereMonth('completed_at', now()->month)
            ->whereYear('completed_at', now()->year)->count();

        $streak = $this->calculateStreak($user);

        $recentCompleted = $user->tasks()->where('completed', true)
            ->latest('completed_at')->take(10)->get()
            ->map(fn($t) => ['type' => 'completed', 'task' => $t, 'date' => $t->completed_at]);
        $recentCreated = $user->tasks()->latest()->take(10)->get()
            ->map(fn($t) => ['type' => 'created', 'task' => $t, 'date' => $t->created_at]);
        $recentActivity = $recentCompleted->concat($recentCreated)
            ->sortByDesc('date')->take(10)->values();

        $upcomingTasks = $user->tasks()->where('completed', false)
            ->whereNotNull('due_date')->orderBy('due_date')->take(5)->get();

        return view('dashboard', compact(
            'activeTasksCount', 'overdueCount',
            'completedToday', 'completedThisWeek', 'completedThisMonth',
            'streak', 'recentActivity', 'upcomingTasks'
        ));
    }

    private function calculateStreak($user): int
    {
        $dates = $user->tasks()->where('completed', true)
            ->whereNotNull('completed_at')
            ->selectRaw('DATE(completed_at) as date')
            ->groupBy('date')
            ->orderByDesc('date')
            ->pluck('date');

        if ($dates->isEmpty()) {
            return 0;
        }

        $streak = 0;
        $checkDate = now()->toDateString();

        if ($dates->first() !== $checkDate) {
            $checkDate = now()->subDay()->toDateString();
            if ($dates->first() !== $checkDate) {
                return 0;
            }
        }

        foreach ($dates as $date) {
            if ($date === $checkDate) {
                $streak++;
                $checkDate = Carbon::parse($checkDate)->subDay()->toDateString();
            } else {
                break;
            }
        }

        return $streak;
    }
}
