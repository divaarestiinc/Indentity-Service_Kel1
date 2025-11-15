<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if ($request->auth['role'] !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak, hanya admin yang diperbolehkan.'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
