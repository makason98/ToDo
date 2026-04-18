<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $userSearch = trim((string) $request->input('user', ''));
        $action = $request->input('action', '');
        $from = $request->input('from', '');
        $to = $request->input('to', '');

        $query = ActivityLog::with('user')->where('action', 'like', 'admin.%');

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
        if ($from !== '') {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to !== '') {
            $query->whereDate('created_at', '<=', $to);
        }

        $logs = $query->latest()->paginate(25)->withQueryString();

        $actions = ActivityLog::query()
            ->where('action', 'like', 'admin.%')
            ->select('action')->distinct()->orderBy('action')->pluck('action');

        return view('admin.audit.index', compact('logs', 'actions', 'search', 'userSearch', 'action', 'from', 'to'));
    }
}
