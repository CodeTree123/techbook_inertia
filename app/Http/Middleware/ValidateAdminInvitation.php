<?php

namespace App\Http\Middleware;

use App\Models\AdminInvitation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateAdminInvitation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->input('reg_token');
        if (!$token) {
            return redirect()->route('admin.verify.view')
                ->withErrors(['reg_token' => 'Token is required.']);
        }
        $invitation = AdminInvitation::where('token', $token)
            ->where('used', false)
            ->where('expire_at', '>', now())
            ->first();

        if (!$invitation) {
            return redirect()->route('admin.verify.view')
                ->withErrors(['reg_token' => 'Invalid or expired token.']);
        }

        $request->merge(['admin_invitation' => $invitation]);

        return $next($request);
    }
}
