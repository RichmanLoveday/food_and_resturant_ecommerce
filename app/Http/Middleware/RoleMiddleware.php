<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        //? check if currently authenticated user role is same with role
        if ($request->user()->role === $role) {
            return $next($request);
        }

        //? redirect to dashboard if not same
        return to_route('dashboard');
    }
}
