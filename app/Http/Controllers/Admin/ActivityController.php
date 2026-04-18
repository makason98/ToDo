<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $userSearch = trim((string) $request->input('user', ''));
        $action = $request->input('action', '');
        $subjectType = $request->input('subject_type', '');
        $from = $request->input('from', '');
        $to = $request->input('to', '');

        $query = ActivityLog::with('user');

        if ($search !== '') {
            $query->where('description', 'like', "%{$search}%");
        }
        if ($userSearch !== '') {
            $query->whereHas('user', function ($q) use ($userSearch) {
                $q->where('name', 'like', "%{$userSearch}%")
                    ->orWhere('email', 'like', "%{$userSearch}%");
            });
        }
        if ($action !== '') {
            $query->where('action', $action);
        }
        if ($subjectType !== '') {
            $query->where('subject_type', $subjectType);
        }
        if ($from !== '') {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to !== '') {
            $query->whereDate('created_at', '<=', $to);
        }

        $logs = $query->latest()->paginate(25)->withQueryString();
        $actions = ActivityLog::query()->select('action')->distinct()->orderBy('action')->pluck('action');
        $subjectTypes = ActivityLog::query()->select('subject_type')->distinct()->orderBy('subject_type')->pluck('subject_type');

        return view('admin.activity.index', compact(
            'logs', 'actions', 'subjectTypes', 'search', 'userSearch', 'action', 'subjectType', 'from', 'to'
        ));
    }
}
