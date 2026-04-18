<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || ! $request->user()->isAdmin()) {
            if (! Auth::check()) {
                return redirect()->route('admin.login');
            }

            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
