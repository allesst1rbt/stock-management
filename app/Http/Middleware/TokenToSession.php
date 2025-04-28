<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenToSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (! $token) {
            return redirect('/login');
        }


        $user = Auth::guard('api')->user();

        if (! $user) {
            return redirect('/login');
        }

        Auth::login($user);

        return $next($request);
    }
}
