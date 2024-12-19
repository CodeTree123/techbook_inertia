<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // app/Http/Middleware/SessionTimeout.php

    public function handle($request, Closure $next)
    {
        if (auth()->check() && time() - session('last_activity') > config('session.lifetime') * 60) {
            auth()->logout();
            return redirect('/admin/logout')->with('message', 'Your session has expired. Please log in again.');
        }

        session(['last_activity' => time()]);

        return $next($request);
    }
}
