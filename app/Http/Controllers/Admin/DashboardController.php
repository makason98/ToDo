<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = now()->toDateString();
        $weekAgo = now()->subDays(7)->startOfDay();
        $monthStart = now()->startOfMonth();

        $totalUsers = User::count();
        $totalAdmins = User::whereNotNull('admin_role')->count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $unverifiedUsers = $totalUsers - $verifiedUsers;
        $newUsersToday = User::whereDate('created_at', $today)->count();
        $newUsersThisWeek = User::where('created_at', '>=', $weekAgo)->count();
        $newUsersThisMonth = User::where('created_at', '>=', $monthStart)->count();

        $totalTasks = Task::count();
        $completedTasks = Task::where('completed', true)->count();
        $activeTasks = Task::where('completed', false)->count();
        $overdueTasks = Task::where('completed', false)
            ->whereNotNull('due_date')
            ->where('due_date', '<', $today)->count();
        $tasksCreatedToday = Task::whereDate('created_at', $today)->count();
        $completedToday = Task::where('completed', true)
            ->whereDate('completed_at', $today)->count();

        $completionRate = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100, 1)
            : 0;

        $signupsByDay = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->toDateString();
            return [
                'label' => now()->subDays($daysAgo)->format('M d'),
                'count' => User::whereDate('created_at', $date)->count(),
            ];
        });
        $maxSignups = max($signupsByDay->max('count'), 1);

        $topUsers = User::withCount('tasks')
            ->orderByDesc('tasks_count')
            ->take(5)
            ->get();

        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalAdmins', 'verifiedUsers', 'unverifiedUsers',
            'newUsersToday', 'newUsersThisWeek', 'newUsersThisMonth',
            'totalTasks', 'completedTasks', 'activeTasks', 'overdueTasks',
            'tasksCreatedToday', 'completedToday', 'completionRate',
            'signupsByDay', 'maxSignups', 'topUsers', 'recentUsers'
        ));
    }
}
