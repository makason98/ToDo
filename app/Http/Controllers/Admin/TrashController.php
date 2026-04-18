<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrashController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->input('type', 'tasks');

        $tasks = Task::onlyTrashed()
            ->with('user')
            ->latest('deleted_at')
            ->paginate(15, ['*'], 'tpage')
            ->withQueryString();

        $comments = Comment::onlyTrashed()
            ->with(['user', 'task'])
            ->latest('deleted_at')
            ->paginate(15, ['*'], 'cpage')
            ->withQueryString();

        $counts = [
            'tasks' => Task::onlyTrashed()->count(),
            'comments' => Comment::onlyTrashed()->count(),
        ];

        return view('admin.trash.index', compact('type', 'tasks', 'comments', 'counts'));
    }

    public function restoreTask(int $id): RedirectResponse
    {
        $task = Task::onlyTrashed()->findOrFail($id);
        $task->restore();

        ActivityLog::log('admin.task.restore', Task::class, $task->id, "Restored task '{$task->title}' from trash");

        return back()->with('status', 'Task restored.');
    }

    public function purgeTask(int $id): RedirectResponse
    {
        $task = Task::onlyTrashed()->findOrFail($id);
        $title = $task->title;
        $task->forceDelete();

        ActivityLog::log('admin.task.purge', Task::class, $id, "Permanently deleted task '{$title}'");

        return back()->with('status', 'Task permanently deleted.');
    }

    public function restoreComment(int $id): RedirectResponse
    {
        $comment = Comment::onlyTrashed()->findOrFail($id);
        $comment->restore();

        ActivityLog::log('admin.comment.restore', Comment::class, $comment->id, 'Restored a comment from trash');

        return back()->with('status', 'Comment restored.');
    }

    public function purgeComment(int $id): RedirectResponse
    {
        $comment = Comment::onlyTrashed()->findOrFail($id);
        $comment->forceDelete();

        ActivityLog::log('admin.comment.purge', Comment::class, $id, 'Permanently deleted a comment');

        return back()->with('status', 'Comment permanently deleted.');
    }

    public function emptyTrash(Request $request): RedirectResponse
    {
        $type = $request->input('type', 'tasks');

        if ($type === 'tasks') {
            $count = Task::onlyTrashed()->count();
            Task::onlyTrashed()->forceDelete();
            ActivityLog::log('admin.trash.empty_tasks', Task::class, null, "Emptied trash: {$count} task(s) permanently deleted");
            $msg = "Emptied: {$count} task(s) permanently deleted.";
        } else {
            $count = Comment::onlyTrashed()->count();
            Comment::onlyTrashed()->forceDelete();
            ActivityLog::log('admin.trash.empty_comments', Comment::class, null, "Emptied trash: {$count} comment(s) permanently deleted");
            $msg = "Emptied: {$count} comment(s) permanently deleted.";
        }

        return back()->with('status', $msg);
    }
}
