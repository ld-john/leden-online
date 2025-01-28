<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogViewerAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Replace this with your authorization logic
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Adjust this as per your application's needs
            return $next($request);
        }

        // Redirect or abort if the user is not authorized
        abort(403, 'Unauthorized access');
    }
}
