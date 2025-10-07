<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventBackHistory
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate'); // [web:5]
        $response->headers->set('Pragma', 'no-cache'); // [web:5]
        $response->headers->set('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT'); // [web:7]

        return $response;
    }
}
