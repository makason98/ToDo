<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $userSearch = trim((string) $request->input('user', ''));
        $deletion = $request->input('deletion', 'active');

        $query = Comment::with(['user', 'task']);

        if ($deletion === 'trashed') {
            $query->onlyTrashed();
        } elseif ($deletion === 'all') {
            $query->withTrashed();
        }

        if ($search !== '') {
            $query->where('body', 'like', "%{$search}%");
        }

        if ($userSearch !== '') {
            $query->whereHas('user', function ($q) use ($userSearch) {
                $q->where('name', 'like', "%{$userSearch}%")
                    ->orWhere('email', 'like', "%{$userSearch}%");
            });
        }

        $comments = $query->latest()->paginate(20)->withQueryString();

        return view('admin.comments.index', compact('comments', 'search', 'userSearch', 'deletion'));
    }

    public function destroy(int $commentId): RedirectResponse
    {
        $comment = Comment::withTrashed()->findOrFail($commentId);
        $comment->delete();

        ActivityLog::log('admin.comment.delete', Comment::class, $comment->id, 'Soft-deleted a comment');

        return back()->with('status', 'Comment deleted.');
    }

    public function restore(int $commentId): RedirectResponse
    {
        $comment = Comment::onlyTrashed()->findOrFail($commentId);
        $comment->restore();

        ActivityLog::log('admin.comment.restore', Comment::class, $comment->id, 'Restored a comment');

        return back()->with('status', 'Comment restored.');
    }
}
