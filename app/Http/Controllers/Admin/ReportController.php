<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $defaultFrom = now()->subDays(29)->startOfDay();
        $defaultTo = now()->endOfDay();

        $from = $request->filled('from')
            ? Carbon::parse($request->input('from'))->startOfDay()
            : $defaultFrom;
        $to = $request->filled('to')
            ? Carbon::parse($request->input('to'))->endOfDay()
            : $defaultTo;

        if ($from->gt($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        $days = $this->dailySeries($from, $to);

        $signups = $this->dailyCount(User::query(), 'created_at', $days);
        $tasksCreated = $this->dailyCount(Task::withTrashed(), 'created_at', $days);
        $tasksCompleted = $this->dailyCount(
            Task::withTrashed()->where('completed', true)->whereNotNull('completed_at'),
            'completed_at',
            $days
        );

        $maxSignups = max(max($signups), 1);
        $maxCreated = max(max($tasksCreated), 1);
        $maxCompleted = max(max($tasksCompleted), 1);

        $totalSignups = array_sum($signups);
        $totalCreated = array_sum($tasksCreated);
        $totalCompleted = array_sum($tasksCompleted);

        $tasksInRange = Task::withTrashed()->whereBetween('created_at', [$from, $to])->count();
        $tasksCompletedInRange = Task::withTrashed()->where('completed', true)
            ->whereBetween('completed_at', [$from, $to])->count();
        $completionRate = $tasksInRange > 0
            ? round(($tasksCompletedInRange / $tasksInRange) * 100, 1)
            : 0;

        $topLabels = Label::withCount(['tasks' => function ($q) use ($from, $to) {
            $q->whereBetween('tasks.created_at', [$from, $to]);
        }])
            ->orderByDesc('tasks_count')
            ->take(5)
            ->get();

        $topUsers = User::withCount(['tasks' => function ($q) use ($from, $to) {
            $q->whereBetween('created_at', [$from, $to]);
        }])
            ->orderByDesc('tasks_count')
            ->take(10)
            ->get();

        $hourlyDistribution = Task::withTrashed()
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->pluck('count', 'hour')->all();
        $hourly = [];
        for ($h = 0; $h < 24; $h++) {
            $hourly[$h] = $hourlyDistribution[$h] ?? 0;
        }
        $maxHourly = max(max($hourly), 1);

        return view('admin.reports.index', compact(
            'from', 'to', 'days', 'signups', 'tasksCreated', 'tasksCompleted',
            'maxSignups', 'maxCreated', 'maxCompleted',
            'totalSignups', 'totalCreated', 'totalCompleted', 'completionRate',
            'topLabels', 'topUsers', 'hourly', 'maxHourly'
        ));
    }

    /**
     * @return array<int, string> day labels (Y-m-d)
     */
    private function dailySeries(Carbon $from, Carbon $to): array
    {
        $days = [];
        $cursor = $from->copy()->startOfDay();
        $end = $to->copy()->startOfDay();
        while ($cursor->lte($end)) {
            $days[] = $cursor->toDateString();
            $cursor->addDay();
        }
        return $days;
    }

    /**
     * @param array<int, string> $days
     * @return array<int, int>
     */
    private function dailyCount($query, string $column, array $days): array
    {
        $rows = $query
            ->selectRaw("DATE({$column}) as d, COUNT(*) as c")
            ->whereBetween($column, [reset($days) . ' 00:00:00', end($days) . ' 23:59:59'])
            ->groupBy('d')
            ->pluck('c', 'd')->all();

        return array_map(fn ($d) => (int) ($rows[$d] ?? 0), $days);
    }
}
