<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    // Check if the user is trying to access admin routes
    if ($request->is('admin/*')) {
        // Check if the user is authenticated and is an admin
        if (auth()->user()->isAdmin()) {
            return $next($request);
        }

        // Redirect or handle unauthorized access for admin routes
        abort(403, 'Unauthorized action for admin route.');
    }

    // Allow access to non-admin routes without checking for admin status
    return $next($request);
    }
}
