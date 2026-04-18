<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::where('user_id', auth()->id())
            ->latest()
            ->paginate(30);

        return view('activity.index', compact('logs'));
    }
}
