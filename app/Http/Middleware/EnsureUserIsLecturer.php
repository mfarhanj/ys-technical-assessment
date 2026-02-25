<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsLecturer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isLecturer()) {
            abort(403, 'Access denied. Lecturer role required.');
        }
        return $next($request);
    }
}
