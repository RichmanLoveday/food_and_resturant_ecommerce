<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class PreventAdminDeleteEditMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Assumes the admin ID is passed in the route as {id}
        $adminId = $request->route('id');

        if ((int) $adminId === 1) {
            throw ValidationException::withMessages([
                'error' => 'You cannot delete or edit the super admin.'
            ]);
        }

        return $next($request);
    }
}
