<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $userSearch = trim((string) $request->input('user', ''));
        $status = $request->input('status', 'all');
        $priority = $request->input('priority', 'all');
        $deletion = $request->input('deletion', 'active');
        $sort = $request->input('sort', 'newest');

        $query = Task::with('user');

        if ($deletion === 'trashed') {
            $query->onlyTrashed();
        } elseif ($deletion === 'all') {
            $query->withTrashed();
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($userSearch !== '') {
            $query->whereHas('user', function ($q) use ($userSearch) {
                $q->where('name', 'like', "%{$userSearch}%")
                    ->orWhere('email', 'like', "%{$userSearch}%");
            });
        }

        if ($status === 'active') {
            $query->where('completed', false);
        } elseif ($status === 'completed') {
            $query->where('completed', true);
        } elseif ($status === 'overdue') {
            $query->where('completed', false)
                ->whereNotNull('due_date')
                ->where('due_date', '<', now()->toDateString());
        }

        if (in_array($priority, ['low', 'medium', 'high'], true)) {
            $query->where('priority', $priority);
        }

        if ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'due_soonest') {
            $query->orderByRaw('due_date IS NULL, due_date ASC');
        } else {
            $query->latest();
        }

        $tasks = $query->paginate(15)->withQueryString();

        return view('admin.tasks.index', compact(
            'tasks', 'search', 'userSearch', 'status', 'priority', 'deletion', 'sort'
        ));
    }

    public function show(int $taskId): View
    {
        $task = Task::withTrashed()
            ->with(['user', 'comments.user', 'subtasks', 'attachments', 'labels'])
            ->findOrFail($taskId);

        return view('admin.tasks.show', compact('task'));
    }

    public function destroy(int $taskId): RedirectResponse
    {
        $task = Task::withTrashed()->findOrFail($taskId);
        $task->delete();

        ActivityLog::log('admin.task.delete', Task::class, $task->id, "Soft-deleted task '{$task->title}'");

        return back()->with('status', 'Task deleted.');
    }

    public function restore(int $taskId): RedirectResponse
    {
        $task = Task::onlyTrashed()->findOrFail($taskId);
        $task->restore();

        ActivityLog::log('admin.task.restore', Task::class, $task->id, "Restored task '{$task->title}'");

        return back()->with('status', 'Task restored.');
    }
}
