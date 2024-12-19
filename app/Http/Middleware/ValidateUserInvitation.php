<?php

namespace App\Http\Middleware;

use App\Models\UserInvitation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateUserInvitation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->input('access_token');
        if (!$token) {
            return redirect()->route('user.verify.view')
                ->withErrors(['access_token' => 'Token is required.']);
        }
        $invitation = UserInvitation::where('token', $token)
            ->where('used', false)
            ->where('expire_at', '>', now())
            ->first();

        if (!$invitation) {
            return redirect()->route('user.verify.view')
                ->withErrors(['access_token' => 'Invalid or expired token.']);
        }

        $request->merge(['user_invitation' => $invitation]);
        return $next($request);
    }
}
