<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('admin')->user();

        if (!$user || in_array($user->role_id, [1, 2])) {
            return redirect()->route('admin.dashboardNew')->withErrors('Access Denied!');
        }

        return $next($request);
    }
}

